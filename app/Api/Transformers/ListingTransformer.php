<?php

namespace App\Api\Transformers;

use App\Api\Config\ListingFieldMap;
use App\Api\Support\AddressParser;
use App\Api\Support\DateTimeHelper;
use WP_Post;

/**
 * Maps a property post into a flat, stable external API payload.
 * Never exposes raw WP_Post or unprocessed ACF structures.
 */
class ListingTransformer
{
    private ListingFieldMap $config;

    /** @var array<int, array<string, mixed>> */
    private array $acfCache = [];

    public function __construct(?ListingFieldMap $config = null)
    {
        $this->config = $config ?? new ListingFieldMap();
    }

    /**
     * @return array<string, mixed>
     */
    public function transform(WP_Post $post): array
    {
        $output = [];

        foreach ($this->config->fields() as $externalField => $source) {
            $value = $this->resolveField($post, $externalField, $source);
            $output[$externalField] = $this->normalizeValue($externalField, $value);
        }

        return $output;
    }

    /**
     * @param string|array<string, mixed> $source
     */
    private function resolveField(WP_Post $post, string $externalField, $source)
    {
        if (is_string($source)) {
            return $this->getAcfValue($post->ID, $source);
        }

        if (! is_array($source)) {
            return null;
        }

        if (isset($source['resolver'])) {
            return $this->callResolver($post, $source);
        }

        if (isset($source['group'], $source['field'])) {
            return $this->getGroupSubField($post->ID, (string) $source['group'], (string) $source['field']);
        }

        return null;
    }

    /**
     * @param array<string, mixed> $source
     */
    private function callResolver(WP_Post $post, array $source)
    {
        $resolver = (string) ($source['resolver'] ?? '');

        switch ($resolver) {
            case 'propertyDetail':
                return $this->propertyDetail($post, (string) ($source['field'] ?? ''));
            case 'additionalDetail':
                return $this->additionalDetail($post, $source['match'] ?? '');
            case 'dimension':
                return $this->dimension($post, (string) ($source['field'] ?? ''));
            case 'empty':
                return $this->config->defaultScalar();
        }

        if ($resolver !== '' && method_exists($this, $resolver)) {
            return $this->{$resolver}($post);
        }

        return null;
    }

    private function getAcfValue(int $postId, string $fieldName)
    {
        if (! function_exists('get_field')) {
            return null;
        }

        if (! isset($this->acfCache[$postId])) {
            $this->acfCache[$postId] = [];
        }

        if (! array_key_exists($fieldName, $this->acfCache[$postId])) {
            $this->acfCache[$postId][$fieldName] = get_field($fieldName, $postId);
        }

        return $this->acfCache[$postId][$fieldName];
    }

    private function getGroupSubField(int $postId, string $group, string $field)
    {
        $groupData = $this->getAcfValue($postId, $group);

        if (! is_array($groupData) || ! array_key_exists($field, $groupData)) {
            return null;
        }

        return $groupData[$field];
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    private function normalizeValue(string $externalField, $value)
    {
        if ($this->config->isArrayField($externalField)) {
            return $this->normalizeArray($value);
        }

        if (is_array($value)) {
            // Flatten simple ACF image arrays to URL when encountered unexpectedly
            if (isset($value['url'])) {
                return (string) $value['url'];
            }

            return $this->config->defaultScalar();
        }

        if ($value === null || $value === false) {
            return $this->config->defaultScalar();
        }

        if (is_bool($value)) {
            return $value;
        }

        if (is_numeric($value)) {
            return (string) $value;
        }

        return sanitize_text_field((string) $value);
    }

    /**
     * @param mixed $value
     * @return array<int, string>
     */
    private function normalizeArray($value): array
    {
        if (! is_array($value) || $value === []) {
            return [];
        }

        // List of strings
        if ($this->isListOfScalars($value)) {
            return array_values(array_map('strval', $value));
        }

        // ACF repeater (key_features, etc.)
        $features = [];

        foreach ($value as $row) {
            if (! is_array($row)) {
                continue;
            }

            $title   = $row['feature_title'] ?? $row['title'] ?? '';
            $content = $row['key_feature_content'] ?? $row['content'] ?? '';

            if ($title && $content) {
                $features[] = sanitize_text_field($title . ': ' . $content);
            } elseif ($title) {
                $features[] = sanitize_text_field((string) $title);
            } elseif ($content) {
                $features[] = sanitize_text_field((string) $content);
            }
        }

        return $features;
    }

    private function isListOfScalars(array $value): bool
    {
        if ($value === []) {
            return true;
        }

        if (array_keys($value) !== range(0, count($value) - 1)) {
            return false;
        }

        foreach ($value as $item) {
            if (is_array($item) || is_object($item)) {
                return false;
            }
        }

        return true;
    }

    // -------------------------------------------------------------------------
    // Resolvers (decoupled from ACF field names in the external schema)
    // -------------------------------------------------------------------------

    private function postId(WP_Post $post): string
    {
        return (string) $post->ID;
    }

    private function permalink(WP_Post $post): string
    {
        return (string) get_permalink($post);
    }

    private function primaryImageUrl(WP_Post $post): string
    {
        $image = $this->getAcfValue($post->ID, 'primary_image');

        if (is_array($image) && ! empty($image['url'])) {
            return esc_url_raw((string) $image['url']);
        }

        if (is_string($image) && $image !== '') {
            return esc_url_raw($image);
        }

        return $this->config->defaultScalar();
    }

    private function latitude(WP_Post $post): string
    {
        return $this->mapCoordinate($post, 'lat');
    }

    private function longitude(WP_Post $post): string
    {
        return $this->mapCoordinate($post, 'lng');
    }

    private function mapCoordinate(WP_Post $post, string $key): string
    {
        $map = $this->getAcfValue($post->ID, 'map');

        if (is_array($map) && isset($map[$key]) && $map[$key] !== '') {
            return (string) $map[$key];
        }

        return $this->config->defaultScalar();
    }

    private function propertyType(WP_Post $post): string
    {
        $terms = get_the_terms($post->ID, 'property-type');

        if (! is_array($terms) || is_wp_error($terms)) {
            return $this->config->defaultScalar();
        }

        $names = array_map(static fn ($term) => $term->name, $terms);

        return implode(', ', $names);
    }

    private function marketingPackageName(WP_Post $post): string
    {
        $link = $this->getAcfValue($post->ID, 'marketing_package');

        return $link ? 'Marketing Package' : $this->config->defaultScalar();
    }

    private function propertyFeatures(WP_Post $post): array
    {
        $features = $this->getAcfValue($post->ID, 'key_features');

        return $this->normalizeArray($features);
    }

    private function primaryAgent(WP_Post $post): ?\WP_Post
    {
        $agents = $this->getAcfValue($post->ID, 'agent');

        if (! is_array($agents) || $agents === []) {
            return null;
        }

        $first = $agents[0];

        if ($first instanceof \WP_Post) {
            return $first;
        }

        if (is_numeric($first)) {
            $agent = get_post((int) $first);

            return $agent instanceof \WP_Post ? $agent : null;
        }

        return null;
    }

    private function contactName(WP_Post $post): string
    {
        $agent = $this->primaryAgent($post);

        if (! $agent) {
            return $this->config->defaultScalar();
        }

        $details = $this->getAcfValue($agent->ID, 'contact_details');

        if (is_array($details)) {
            $first = trim((string) ($details['first_name'] ?? ''));
            $last  = trim((string) ($details['last_name'] ?? ''));
            $name  = trim($first . ' ' . $last);

            if ($name !== '') {
                return sanitize_text_field($name);
            }
        }

        return sanitize_text_field(get_the_title($agent));
    }

    private function contactPhone(WP_Post $post): string
    {
        return $this->contactDetail($post, ['mobile_phone', 'office_phone']);
    }

    private function contactEmail(WP_Post $post): string
    {
        $email = $this->contactDetail($post, ['email']);

        return $email ? sanitize_email($email) : $this->config->defaultScalar();
    }

    private function contactCompany(WP_Post $post): string
    {
        $agent = $this->primaryAgent($post);

        if (! $agent) {
            return $this->config->defaultScalar();
        }

        $company = $this->getAcfValue($agent->ID, 'company');

        return $company ? sanitize_text_field((string) $company) : $this->config->defaultScalar();
    }

    /**
     * @param string[] $keys
     */
    private function contactDetail(WP_Post $post, array $keys): string
    {
        $agent = $this->primaryAgent($post);

        if (! $agent) {
            return $this->config->defaultScalar();
        }

        $details = $this->getAcfValue($agent->ID, 'contact_details');

        if (! is_array($details)) {
            return $this->config->defaultScalar();
        }

        foreach ($keys as $key) {
            if (! empty($details[$key])) {
                return sanitize_text_field((string) $details[$key]);
            }
        }

        return $this->config->defaultScalar();
    }

    private function createdAt(WP_Post $post): string
    {
        return DateTimeHelper::toIso8601Utc($post->post_date_gmt);
    }

    private function updatedAt(WP_Post $post): string
    {
        return DateTimeHelper::toIso8601Utc($post->post_modified_gmt);
    }

    private function daysOnMarket(WP_Post $post): string
    {
        $listingDateIso = $this->listingDate($post);

        try {
            if ($listingDateIso !== '') {
                $start = new \DateTimeImmutable($listingDateIso, new \DateTimeZone('UTC'));
            } else {
                $start = new \DateTimeImmutable($post->post_date_gmt, new \DateTimeZone('UTC'));
            }

            $now  = new \DateTimeImmutable('now', new \DateTimeZone('UTC'));
            $days = (int) $start->diff($now)->days;

            return (string) $days;
        } catch (\Exception $e) {
            return $this->config->defaultScalar();
        }
    }

    private function source(WP_Post $post): string
    {
        return 'wordpress';
    }

    private function listingDate(WP_Post $post): string
    {
        $value = $this->getGroupSubField($post->ID, 'property_details', 'listing_date');

        return DateTimeHelper::acfDateToIso8601Utc($value);
    }

    /**
     * @return array<string, mixed>
     */
    private function mapField(WP_Post $post): array
    {
        $map = $this->getAcfValue($post->ID, 'map');

        return is_array($map) ? $map : [];
    }

    /**
     * @return array{city: string, province: string, postal_code: string}
     */
    private function parsedAddress(WP_Post $post): array
    {
        $fromMap = AddressParser::fromMapField($this->mapField($post));

        if ($fromMap['city'] !== '' || $fromMap['province'] !== '' || $fromMap['postal_code'] !== '') {
            return $fromMap;
        }

        $displayAddress = (string) $this->getAcfValue($post->ID, 'address');

        return AddressParser::parse($displayAddress);
    }

    private function addressCity(WP_Post $post): string
    {
        return $this->parsedAddress($post)['city'];
    }

    private function addressProvince(WP_Post $post): string
    {
        return $this->parsedAddress($post)['province'];
    }

    private function addressPostalCode(WP_Post $post): string
    {
        return $this->parsedAddress($post)['postal_code'];
    }

    private function propertyDetail(WP_Post $post, string $field): string
    {
        if ($field === '') {
            return $this->config->defaultScalar();
        }

        $value = $this->getGroupSubField($post->ID, 'property_details', $field);

        return $value !== null && $value !== '' && $value !== false
            ? sanitize_text_field((string) $value)
            : $this->config->defaultScalar();
    }

    /**
     * @param string|string[] $match Titles to match (case-insensitive substring) in additional_details.details
     */
    private function additionalDetail(WP_Post $post, $match): string
    {
        $needles = is_array($match) ? $match : [$match];
        $needles = array_filter(array_map('strtolower', array_map('strval', $needles)));

        if ($needles === []) {
            return $this->config->defaultScalar();
        }

        foreach ($this->additionalDetailRows($post) as $row) {
            $title = strtolower(trim((string) ($row['detail_title'] ?? '')));

            foreach ($needles as $needle) {
                if ($needle !== '' && strpos($title, $needle) !== false) {
                    $info = trim((string) ($row['detail_info'] ?? ''));

                    return $info !== '' ? sanitize_text_field($info) : $this->config->defaultScalar();
                }
            }
        }

        return $this->config->defaultScalar();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function additionalDetailRows(WP_Post $post): array
    {
        $group = $this->getAcfValue($post->ID, 'additional_details');

        if (! is_array($group) || empty($group['details']) || ! is_array($group['details'])) {
            return [];
        }

        return $group['details'];
    }

    private function dimension(WP_Post $post, string $field): string
    {
        if ($field === '') {
            return $this->config->defaultScalar();
        }

        $value = $this->getGroupSubField($post->ID, 'dimensions_section', $field);

        if ($value === null || $value === '' || $value === false) {
            return $this->config->defaultScalar();
        }

        $formatted = is_numeric($value)
            ? number_format((float) $value, 2, '.', '')
            : sanitize_text_field((string) $value);

        $postfix = $this->getGroupSubField($post->ID, 'dimensions_section', $field . '_postfix');

        if ($postfix) {
            return trim($formatted . ' ' . sanitize_text_field((string) $postfix));
        }

        return $formatted;
    }

    private function notes(WP_Post $post): string
    {
        $lines = [];

        foreach ($this->additionalDetailRows($post) as $row) {
            $title = trim((string) ($row['detail_title'] ?? ''));
            $info  = trim((string) ($row['detail_info'] ?? ''));

            if ($title === '' && $info === '') {
                continue;
            }

            $lines[] = $title !== '' ? "{$title}: {$info}" : $info;
        }

        return $lines !== [] ? implode("\n", $lines) : $this->config->defaultScalar();
    }
}
