<?php

namespace App\View\Components;

use Roots\Acorn\View\Component;
use Log1x\Crumb\Facades\Crumb;

class ListingAlert extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * The listing items.
     *
     * @return string
     */
    public function listingAlert()
    {
        return $test;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return $this->view('components.listingalert');
    }
}