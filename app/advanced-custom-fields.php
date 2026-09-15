<?php

/**
 * ACF office settings (shared across regional sites).
 * Existing Options fields (footer, email, office address) stay in WP Admin as-is.
 */

namespace App;

add_action('acf/init', function () {
    if (! function_exists('acf_add_options_page') || ! function_exists('acf_add_local_field_group')) {
        return;
    }

    acf_add_options_page([
        'page_title' => 'Office Settings',
        'menu_title' => 'Office Settings',
        'menu_slug'  => 'cw-office-settings',
        'capability' => 'edit_theme_options',
        'redirect'   => false,
        'icon_url'   => 'dashicons-building',
        'position'   => 59,
    ]);

    acf_add_local_field_group([
        'key' => 'group_cw_office_settings',
        'title' => 'Office Settings',
        'fields' => [
            [
                'key' => 'field_cw_header_logo',
                'label' => 'Header logo',
                'name' => 'header_logo',
                'type' => 'image',
                'instructions' => 'Leave empty to use the theme default logo.',
                'return_format' => 'array',
                'preview_size' => 'medium',
                'library' => 'all',
            ],
            [
                'key' => 'field_cw_office_legal_name',
                'label' => 'Legal / copyright name',
                'name' => 'office_legal_name',
                'type' => 'text',
                'instructions' => 'Shown in the footer. Example: Cushman & Wakefield Regina, Ltd.',
                'placeholder' => 'Cushman & Wakefield Saskatoon, Ltd.',
            ],
            [
                'key' => 'field_cw_newsletter_org_name',
                'label' => 'Newsletter organization name',
                'name' => 'newsletter_org_name',
                'type' => 'text',
                'instructions' => 'Used in opt-in copy. Example: Cushman & Wakefield Regina.',
                'placeholder' => 'Cushman & Wakefield Saskatoon',
            ],
            [
                'key' => 'field_cw_marketbeat_local_heading',
                'label' => 'Local Marketbeat heading',
                'name' => 'marketbeat_local_heading',
                'type' => 'text',
                'instructions' => 'Mega menu heading for local reports.',
                'placeholder' => 'Saskatoon Marketbeat Reports',
            ],
            [
                'key' => 'field_cw_local_research_category',
                'label' => 'Local research category slug',
                'name' => 'local_research_category',
                'type' => 'text',
                'instructions' => 'WP category slug for local Marketbeat posts. Saskatoon: saskatchewan-research',
                'placeholder' => 'saskatchewan-research',
            ],
            [
                'key' => 'field_cw_national_research_category',
                'label' => 'National research category slug',
                'name' => 'national_research_category',
                'type' => 'text',
                'instructions' => 'WP category slug for national reports. Default: canadian-research',
                'placeholder' => 'canadian-research',
            ],
            [
                'key' => 'field_cw_listing_alert_form_id',
                'label' => 'Listing alert Mailchimp form ID',
                'name' => 'listing_alert_form_id',
                'type' => 'number',
                'instructions' => 'MC4WP form post ID. Saskatoon production is 27307. Set Regina’s form ID here.',
                'placeholder' => '27307',
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'options_page',
                    'operator' => '==',
                    'value' => 'cw-office-settings',
                ],
            ],
        ],
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'active' => true,
    ]);
});
