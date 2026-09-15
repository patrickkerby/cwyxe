<?php

namespace App\Support;

/**
 * Office-specific ACF options with Saskatoon production fallbacks.
 * Empty options keep the live site unchanged until values are saved in WP Admin.
 */
class SiteOptions
{
    public const DEFAULT_LEGAL_NAME = 'Cushman & Wakefield Saskatoon, Ltd.';

    public const DEFAULT_NEWSLETTER_ORG = 'Cushman & Wakefield Saskatoon';

    public const DEFAULT_MARKETBEAT_HEADING = 'Saskatoon Marketbeat Reports';

    public const DEFAULT_LOCAL_RESEARCH_CATEGORY = 'saskatchewan-research';

    public const DEFAULT_NATIONAL_RESEARCH_CATEGORY = 'canadian-research';

    public const DEFAULT_LISTING_ALERT_FORM_ID = 27307;

    /**
     * @return array{
     *     logo_url: string,
     *     legal_name: string,
     *     newsletter_org: string,
     *     marketbeat_heading: string,
     *     local_research_category: string,
     *     national_research_category: string,
     *     listing_alert_form_id: int
     * }
     */
    public static function all(): array
    {
        return [
            'logo_url' => self::logoUrl(),
            'legal_name' => self::string('office_legal_name', self::DEFAULT_LEGAL_NAME),
            'newsletter_org' => self::string('newsletter_org_name', self::DEFAULT_NEWSLETTER_ORG),
            'marketbeat_heading' => self::string('marketbeat_local_heading', self::DEFAULT_MARKETBEAT_HEADING),
            'local_research_category' => self::string('local_research_category', self::DEFAULT_LOCAL_RESEARCH_CATEGORY),
            'national_research_category' => self::string('national_research_category', self::DEFAULT_NATIONAL_RESEARCH_CATEGORY),
            'listing_alert_form_id' => self::int('listing_alert_form_id', self::DEFAULT_LISTING_ALERT_FORM_ID),
        ];
    }

    public static function logoUrl(): string
    {
        $logo = function_exists('get_field') ? get_field('header_logo', 'option') : null;

        if (is_array($logo) && ! empty($logo['url'])) {
            return (string) $logo['url'];
        }

        if (is_string($logo) && $logo !== '') {
            return $logo;
        }

        return '';
    }

    private static function string(string $field, string $fallback): string
    {
        if (! function_exists('get_field')) {
            return $fallback;
        }

        $value = get_field($field, 'option');

        if ($value === null || $value === false || $value === '') {
            return $fallback;
        }

        return trim((string) $value);
    }

    private static function int(string $field, int $fallback): int
    {
        if (! function_exists('get_field')) {
            return $fallback;
        }

        $value = get_field($field, 'option');

        if ($value === null || $value === false || $value === '') {
            return $fallback;
        }

        $int = (int) $value;

        return $int > 0 ? $int : $fallback;
    }
}
