<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class Properties extends Composer
{

    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        // 'partials.content-single-property',
        'properties',
        'partials.content-single-agent',
    ];

  public function propertiesLoop()
  {
    $properties = get_posts([
        'post_type' => 'property',
        'posts_per_page'=>'-1',  
        'orderby' => 'date',
        'order' => 'ASC'
    ]);
    
    return array_map(function ($post) {
        $status_terms = get_the_terms( $post->ID, 'property-status' );
        $status_color = get_field('property_status_colour', 'term_'.$status_terms[0]->term_id);
        $general_settings = get_field('general_settings', $post->ID);
        $rates = get_field('rates', $post->ID);
        $amount = $rates['amount'];
        $price_str = preg_replace('/(\d)(?=(?:\d{3})+$)/', '$1,', $amount);


        return [
            'name' => get_the_title($post->ID),
            'slug' => $post->post_name,
            'link' => get_permalink($post->ID),
            'property_type' => get_the_terms( $post->ID, 'property-type' ),
            'property_status' => $status_terms,
            'property_status_color' => $status_color,
            'availability' => $general_settings['availability'],
            'address' => get_field('address', $post->ID),
            'price' => $price_str,
            'primary_image' => get_field('primary_image', $post->ID),
            'agents' => get_field('agent', $post->ID)
          ];
    }, $properties);
  }

  public function with()
    {
        return [
            'properties' => $this->propertiesLoop()
        ];
    }
}