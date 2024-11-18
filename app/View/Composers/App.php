<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class App extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        '*',
    ];

    /**
     * Data to be passed to view before rendering.
     *
     * @return array
     */
    public function with()
    {
        return [
            'siteName' => $this->siteName(),
            'propertyTypes' => $this->propertyTypes(),            
        ];
    }

    /**
     * Returns the site name.
     *
     * @return string
     */
    public function siteName()
    {
        return get_bloginfo('name', 'display');
    }

    /**
     * Returns the Property Types.
     *
     * @return array
     */
    
    public function propertyTypes()
    {
        $property_types = get_terms( array(
        'taxonomy'   => 'property-type',
        'hide_empty' => true,
        ) );

        foreach ( $property_types as $property_type ) {
            $property_type->featured = get_field('homepage_highlight', 'term_'.$property_type->term_id);
            $property_type->image = get_field('property_type_photo', 'term_'.$property_type->term_id);
        }

        return array (
            'all_types' => $property_types,
            'featured_property_types' => array_filter($property_types, function($property_type) {
                return $property_type->featured;
            }),

        );
    }
}
