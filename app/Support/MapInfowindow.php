<?php

namespace App\Support;

use WP_Post;

/**
 * FacetWP map marker infowindow markup, shared by every office using this theme.
 */
class MapInfowindow
{
    public static function render(int $postId): string
    {
        $post = get_post($postId);

        if (! $post instanceof WP_Post) {
            return '';
        }

        return \Roots\view('partials.map-infowindow', self::data($post))->render();
    }

    /**
     * @return array{
     *     link: string,
     *     title: string,
     *     address: string,
     *     image_url: string,
     *     image_alt: string,
     *     status_line: string,
     *     price: string
     * }
     */
    public static function data(WP_Post $post): array
    {
        $postId = (int) $post->ID;
        $general = get_field('general_settings', $postId) ?: [];
        $rates = get_field('rates', $postId) ?: [];
        $image = get_field('primary_image', $postId);
        $conditions = Terms::forPost($postId, 'availability-condition');
        $types = Terms::forPost($postId, 'property-type');

        $hidePrice = $conditions !== [] && in_array($conditions[0]->slug, ['leased', 'sold'], true);
        $availability = AvailabilityFormatter::format($general['availability'] ?? '');
        $typeNames = array_values(array_map(static fn ($term) => $term->name, $types));
        $typeLabel = implode(' / ', $typeNames);
        $statusParts = [];

        if ($availability !== '') {
            $statusParts[] = 'For '.$availability;
        }

        if ($typeLabel !== '') {
            $statusParts[] = $typeLabel;
        }

        return [
            'link' => (string) get_permalink($postId),
            'title' => get_the_title($postId),
            'address' => (string) (get_field('address', $postId) ?: ''),
            'image_url' => is_array($image) ? (string) ($image['url'] ?? '') : '',
            'image_alt' => is_array($image) ? (string) ($image['alt'] ?? get_the_title($postId)) : '',
            'status_line' => implode(' • ', $statusParts),
            'price' => $hidePrice ? '' : self::formatPrice(is_array($rates) ? $rates : []),
        ];
    }

    private static function formatPrice(array $rates): string
    {
        $amount = $rates['amount'] ?? '';

        if ($amount === '' || $amount === null) {
            return '';
        }

        $formatted = preg_replace('/(\d)(?=(?:\d{3})+$)/', '$1,', (string) $amount);
        $rateType = trim((string) ($rates['rate_type'] ?? ''));
        $postfix = trim((string) ($rates['rate_postfix'] ?? ''));
        $negotiable = ! empty($rates['negotiable']) ? '(negotiable)' : '';

        $parts = [];

        if ($rateType !== '') {
            $parts[] = 'For '.$rateType.':';
        }

        $parts[] = '$'.$formatted;

        if ($postfix !== '') {
            $parts[] = $postfix;
        }

        if ($negotiable !== '') {
            $parts[] = $negotiable;
        }

        return implode(' ', $parts);
    }
}
