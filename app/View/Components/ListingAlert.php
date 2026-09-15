<?php

namespace App\View\Components;

use App\Support\SiteOptions;
use Roots\Acorn\View\Component;

class ListingAlert extends Component
{
    public int $formId;

    public function __construct()
    {
        $this->formId = SiteOptions::all()['listing_alert_form_id'];
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        if (! function_exists('do_shortcode') || $this->formId < 1) {
            return '';
        }

        return $this->view('components.listingalert');
    }
}
