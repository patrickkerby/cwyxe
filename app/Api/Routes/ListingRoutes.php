<?php

namespace App\Api\Routes;

use App\Api\Controllers\ListingController;

/**
 * Registers client-facing listing REST routes.
 */
class ListingRoutes
{
    public const NAMESPACE = 'client/v1';

    private ListingController $controller;

    public function __construct(?ListingController $controller = null)
    {
        $this->controller = $controller ?? new ListingController();
    }

    public function register(): void
    {
        register_rest_route(self::NAMESPACE, '/listings', [
            [
                'methods'             => \WP_REST_Server::READABLE,
                'callback'            => [$this->controller, 'index'],
                'permission_callback' => [$this, 'canRead'],
                'args'                => $this->collectionArgs(),
            ],
        ]);

        register_rest_route(self::NAMESPACE, '/listings/(?P<id>\d+)', [
            [
                'methods'             => \WP_REST_Server::READABLE,
                'callback'            => [$this->controller, 'show'],
                'permission_callback' => [$this, 'canRead'],
                'args'                => $this->singleArgs(),
            ],
        ]);
    }

    /**
     * Public read-only endpoint. Replace with auth/rate-limit as needed.
     *
     * @see https://developer.wordpress.org/rest-api/extending-the-rest-api/routes-and-endpoints/
     */
    public function canRead(): bool
    {
        // TODO: API key, OAuth, or IP allowlist for production hardening
        return true;
    }

    private function collectionArgs(): array
    {
        return [
            'modified_after' => [
                'description'       => 'ISO 8601 UTC timestamp. Returns listings modified after this time.',
                'type'              => 'string',
                'required'          => false,
                'sanitize_callback' => 'sanitize_text_field',
            ],
            'include_inactive' => [
                'description' => 'When true, includes unpublished listings in the response.',
                'type'        => 'boolean',
                'required'    => false,
                'default'     => false,
            ],
            'page' => [
                'description' => 'Page number for pagination.',
                'type'        => 'integer',
                'required'    => false,
                'default'     => 1,
                'minimum'     => 1,
            ],
            'per_page' => [
                'description' => 'Results per page (max 100).',
                'type'        => 'integer',
                'required'    => false,
                'default'     => 50,
                'minimum'     => 1,
                'maximum'     => 100,
            ],
        ];
    }

    private function singleArgs(): array
    {
        return [
            'id' => [
                'description' => 'Listing post ID.',
                'type'        => 'integer',
                'required'    => true,
            ],
            'include_inactive' => [
                'description' => 'When true, allows fetching unpublished listings.',
                'type'        => 'boolean',
                'required'    => false,
                'default'     => false,
            ],
        ];
    }
}
