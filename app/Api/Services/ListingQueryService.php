<?php

namespace App\Api\Services;

use App\Api\Config\ListingFieldMap;
use App\Api\Support\DateTimeHelper;
use WP_Post;
use WP_Query;

/**
 * Builds efficient WP_Query instances for listing endpoints.
 */
class ListingQueryService
{
    private ListingFieldMap $config;

    public function __construct(?ListingFieldMap $config = null)
    {
        $this->config = $config ?? new ListingFieldMap();
    }

    /**
     * @return array{posts: WP_Post[], total: int, page: int, per_page: int}
     */
    public function fetchListings(array $args = []): array
    {
        $page     = max(1, (int) ($args['page'] ?? 1));
        $perPage  = $this->resolvePerPage($args['per_page'] ?? null);
        $modifiedAfter = $args['modified_after'] ?? null;
        $includeInactive = filter_var($args['include_inactive'] ?? false, FILTER_VALIDATE_BOOLEAN);

        $queryArgs = [
            'post_type'              => $this->config->postType(),
            'post_status'            => $this->resolvePostStatuses($modifiedAfter, $includeInactive),
            'posts_per_page'         => $perPage,
            'paged'                  => $page,
            'orderby'                => 'modified',
            'order'                  => 'DESC',
            'no_found_rows'          => false, // TODO: set true when pagination meta is not needed
            'update_post_meta_cache' => true,
            'update_post_term_cache' => true,
            'ignore_sticky_posts'    => true,
        ];

        if ($modifiedAfter) {
            $mysqlGmt = DateTimeHelper::parseIso8601ToMysqlGmt($modifiedAfter);

            if ($mysqlGmt === null) {
                return [
                    'posts'    => [],
                    'total'    => 0,
                    'page'     => $page,
                    'per_page' => $perPage,
                    'error'    => 'invalid_modified_after',
                ];
            }

            $queryArgs['date_query'] = [
                [
                    'column'    => 'post_modified_gmt',
                    'after'     => $mysqlGmt,
                    'inclusive' => false,
                ],
            ];
        }

        // TODO: apply transient/cache key based on query args hash
        $query = new WP_Query($queryArgs);

        return [
            'posts'    => $query->posts,
            'total'    => (int) $query->found_posts,
            'page'     => $page,
            'per_page' => $perPage,
        ];
    }

    public function findById(int $id, bool $includeInactive = false): ?WP_Post
    {
        $post = get_post($id);

        if (! $post instanceof WP_Post) {
            return null;
        }

        if ($post->post_type !== $this->config->postType()) {
            return null;
        }

        $allowedStatuses = $this->resolvePostStatuses(null, $includeInactive);

        if (! in_array($post->post_status, $allowedStatuses, true)) {
            return null;
        }

        return $post;
    }

    /**
     * Published-only by default. Incremental sync includes inactive statuses so
     * Incremental sync can pick up unpublished listings when they change.
     *
     * @return string[]
     */
    private function resolvePostStatuses(?string $modifiedAfter, bool $includeInactive): array
    {
        if ($includeInactive || $modifiedAfter) {
            return ['publish', 'draft', 'pending', 'private', 'future'];
        }

        return ['publish'];
    }

    private function resolvePerPage($perPage): int
    {
        // TODO: read max per_page from filter or env
        $max     = 100;
        $default = 50;

        if ($perPage === null || $perPage === '') {
            return $default;
        }

        $perPage = (int) $perPage;

        if ($perPage < 1) {
            return $default;
        }

        return min($perPage, $max);
    }
}
