<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class Insights extends Composer
{

    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        'homepage',
        'insights'
    ];

    public function featuredInsights()
    {
        $insights = get_posts([
            'post_type' => 'post',
            'posts_per_page'=>'3',  
            'orderby' => 'date',
            'order' => 'DESC'
        ]);
        
        return array_map(function ($post) {
            return [
                'name' => get_the_title($post->ID),
                'link' => get_permalink($post->ID),
                'thumbnail' => get_the_post_thumbnail_url($post->ID),
                'excerpt' => get_the_excerpt($post->ID),
                'terms' => get_the_term_list($post->ID, 'category', '', ', ')
            ];         
        }, $insights);
    }

  public function with()
    {
        $primary_term_id = yoast_get_primary_term_id( 'category' );

        return [
            'featuredInsights' => $this->featuredInsights()
        ];
    }

    
}