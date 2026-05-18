<?php

namespace App\Providers;

use App\Api\Routes\ListingRoutes;
use Roots\Acorn\Sage\SageServiceProvider;

class ThemeServiceProvider extends SageServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        parent::register();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        $this->registerListingApi();
    }

    /**
     * Client-facing listings REST API (Airtable sync).
     */
    protected function registerListingApi(): void
    {
        add_action('rest_api_init', static function () {
            (new ListingRoutes())->register();
        });
    }
}
