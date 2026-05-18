<?php

namespace App\Api\Controllers;

use App\Api\Services\ListingQueryService;
use App\Api\Transformers\ListingTransformer;
use WP_REST_Request;
use WP_REST_Response;

/**
 * REST controller for read-only listing endpoints.
 */
class ListingController
{
    private ListingQueryService $queryService;

    private ListingTransformer $transformer;

    public function __construct(
        ?ListingQueryService $queryService = null,
        ?ListingTransformer $transformer = null
    ) {
        $this->queryService = $queryService ?? new ListingQueryService();
        $this->transformer  = $transformer ?? new ListingTransformer();
    }

    /**
     * GET /wp-json/client/v1/listings
     */
    public function index(WP_REST_Request $request): WP_REST_Response
    {
        $modifiedAfter   = $request->get_param('modified_after');
        $includeInactive = $request->get_param('include_inactive');
        $page            = $request->get_param('page');
        $perPage         = $request->get_param('per_page');

        $result = $this->queryService->fetchListings([
            'modified_after'   => $modifiedAfter,
            'include_inactive' => $includeInactive,
            'page'             => $page,
            'per_page'         => $perPage,
        ]);

        if (isset($result['error']) && $result['error'] === 'invalid_modified_after') {
            return new WP_REST_Response([
                'code'    => 'invalid_modified_after',
                'message' => 'modified_after must be a valid ISO 8601 timestamp.',
            ], 400);
        }

        $listings = array_map(
            fn ($post) => $this->transformer->transform($post),
            $result['posts']
        );

        $total     = $result['total'];
        $perPage   = $result['per_page'];
        $page      = $result['page'];
        $totalPages = $perPage > 0 ? (int) ceil($total / $perPage) : 0;

        return new WP_REST_Response([
            'listings' => $listings,
            'meta'     => [
                'total'          => $total,
                'page'           => $page,
                'per_page'       => $perPage,
                'total_pages'    => $totalPages,
                'modified_after' => $modifiedAfter ?: null,
                // TODO: expose rate-limit headers / remaining quota
            ],
        ], 200);
    }

    /**
     * GET /wp-json/client/v1/listings/{id}
     */
    public function show(WP_REST_Request $request): WP_REST_Response
    {
        $id = (int) $request->get_param('id');

        if ($id < 1) {
            return new WP_REST_Response([
                'code'    => 'invalid_id',
                'message' => 'Listing ID must be a positive integer.',
            ], 400);
        }

        $includeInactive = filter_var(
            $request->get_param('include_inactive'),
            FILTER_VALIDATE_BOOLEAN
        );

        $post = $this->queryService->findById($id, $includeInactive);

        if (! $post) {
            return new WP_REST_Response([
                'code'    => 'listing_not_found',
                'message' => 'Listing not found.',
            ], 404);
        }

        return new WP_REST_Response([
            'listing' => $this->transformer->transform($post),
        ], 200);
    }
}
