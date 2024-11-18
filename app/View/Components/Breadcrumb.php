<?php

namespace App\View\Components;

use Roots\Acorn\View\Component;
use Log1x\Crumb\Facades\Crumb;

class Breadcrumb extends Component
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
     * The breadcrumb items.
     *
     * @return string
     */
    public function items()
    {
        return Crumb::build()->toArray();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return $this->view('components.breadcrumb');
    }
}