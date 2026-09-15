<?php

namespace App\View\Composers;

use App\Support\AvailabilityFormatter;
use App\Support\Terms;
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
        $status_terms = Terms::forPost($post->ID, 'property-status');
        $status_color = '#000';
        if ($status_terms !== []) {
            $status_color = get_field('property_status_colour', 'term_' . $status_terms[0]->term_id) ?: '#000';
        }

        $general_settings = get_field('general_settings', $post->ID) ?: [];
        $rates = get_field('rates', $post->ID) ?: [];
        $amount = $rates['amount'] ?? '';
        $price_str = $amount !== '' && $amount !== null
            ? preg_replace('/(\d)(?=(?:\d{3})+$)/', '$1,', (string) $amount)
            : '';

        $primary_image = get_field('primary_image', $post->ID);
        if (! is_array($primary_image)) {
            $primary_image = null;
        }

        return [
            'name' => get_the_title($post->ID),
            'slug' => $post->post_name,
            'link' => get_permalink($post->ID),
            'property_type' => Terms::forPost($post->ID, 'property-type'),
            'property_status' => $status_terms,
            'property_status_color' => $status_color,
            'availability' => AvailabilityFormatter::format($general_settings['availability'] ?? ''),
            'availability_condition' => Terms::forPost($post->ID, 'availability-condition'),
            'featured' => $general_settings['featured_property'] ?? false,
            'address' => get_field('address', $post->ID) ?: '',
            'price' => $price_str,
            'primary_image' => $primary_image,
            'agents' => get_field('agent', $post->ID) ?: [],
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