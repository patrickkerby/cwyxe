<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class Agents extends Composer
{
  public function agentsLoop()
  {
      $agents = get_posts([
          'post_type' => 'agent',
          'posts_per_page'=>'-1',  
          'orderby' => 'date',
          'order' => 'ASC'      
      ]);
  
    return array_map(function ($post) {        
    
        return [
            'name' => get_the_title($post->ID),
            'slug' => $post->post_name,
            'link' => get_permalink($post->ID),
            'title' => get_field('title', $post),
            'headshot' => get_field('headshot', $post),
            'broker_attributes' => get_the_terms( $post->ID, 'broker-attribute' ),
            'contact_details' => get_field('contact_details', $post)
          ];
    }, $agents);
  }

  public function with()
    {
        return [
            'agents' => $this->agentsLoop()
        ];
    }

}