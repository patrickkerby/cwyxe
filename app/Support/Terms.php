<?php

namespace App\Support;

use WP_Term;

/**
 * Safe term lookups for empty sites (missing taxonomies, no terms assigned).
 */
class Terms
{
    /**
     * @return WP_Term[]
     */
    public static function forPost(int $postId, string $taxonomy): array
    {
        $terms = get_the_terms($postId, $taxonomy);

        if (! is_array($terms)) {
            return [];
        }

        return array_values(array_filter($terms, static fn ($term) => $term instanceof WP_Term));
    }

    /**
     * @return WP_Term[]
     */
    public static function all(string $taxonomy, bool $hideEmpty = true): array
    {
        $terms = get_terms([
            'taxonomy' => $taxonomy,
            'hide_empty' => $hideEmpty,
        ]);

        if (is_wp_error($terms) || ! is_array($terms)) {
            return [];
        }

        return array_values(array_filter($terms, static fn ($term) => $term instanceof WP_Term));
    }
}
