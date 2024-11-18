<?php

/**
 * Theme filters.
 */

namespace App;

/**
 * Add "… Continued" to the excerpt.
 *
 * @return string
 */
add_filter('excerpt_more', function () {
    return sprintf(' &hellip; <a href="%s">%s</a>', get_permalink(), __('Continued', 'sage'));
});

add_filter( 'acf/fields/google_map/api', function($api) {
    $api['key'] = 'AIzaSyB4tACHjkBLlcqFPGeMycOSvDadNWVurS0';
    return $api;
} );

add_filter( 'facetwp_map_init_args', function ( $args ) {
 
  // $args['init']['zoomControl']       = false; // +- zoom control
  $args['init']['mapTypeControl']    = false; // roadmap / satellite toggle
  $args['init']['streetViewControl'] = false; // street view / yellow man icon
  $args['init']['fullscreenControl'] = false; // full screen icon
  
  /** this overwrites all 4 lines above and will disable ALL of the default ui icons instead of the individual icons above */
  $args['init']['disableDefaultUI']  = true; // disable the default ui
  
  return $args;
  
} );

add_filter( 'facetwp_map_init_args', function( $args ) {
  if ( isset( $args['config']['cluster'] ) ) {
    $args['config']['cluster']['zoomOnClick'] = true; // default: false
  }
  return $args;
} );


// This block helps sync the facets on the homepage with the ones on the search page
add_action( 'facetwp_scripts', function() {
  ?>
  <script>
    document.addEventListener('facetwp-refresh', function() {
      if (null !== FWP.active_facet) {
        //Status
        if ( 'status' == fUtil(FWP.active_facet.nodes[0]).attr('data-name' ) ) {
          FWP.facets['property_status_homepage'] = FWP.facets['status'];
        } else if ( 'property_status_homepage' == fUtil(FWP.active_facet.nodes[0]).attr('data-name' ) ) {
          FWP.facets['status'] = FWP.facets['property_status_homepage'];
        }
        //Property Type
        if ( 'property_type' == fUtil(FWP.active_facet.nodes[0]).attr('data-name' ) ) {
          FWP.facets['property_type_homepage'] = FWP.facets['property_type'];
        } else if ( 'property_type_homepage' == fUtil(FWP.active_facet.nodes[0]).attr('data-name' ) ) {
          FWP.facets['property_type'] = FWP.facets['property_type_homepage'];
        }
        //Keyword
        if ( 'search' == fUtil(FWP.active_facet.nodes[0]).attr('data-name' ) ) {
          FWP.facets['keyword_homepage'] = FWP.facets['search'];
        } else if ( 'keyword_homepage' == fUtil(FWP.active_facet.nodes[0]).attr('data-name' ) ) {
          FWP.facets['search'] = FWP.facets['keyword_homepage'];
        }
        //Min Max Area
        if ( 'min_max_area' == fUtil(FWP.active_facet.nodes[0]).attr('data-name' ) ) {
          FWP.facets['min_area_homepage'] = FWP.facets['min_max_area'];
        } else if ( 'min_area_homepage' == fUtil(FWP.active_facet.nodes[0]).attr('data-name' ) ) {
          FWP.facets['min_max_area'] = FWP.facets['min_area_homepage'];
        }
      }
    });
  </script>
  <?php
}, 100 );


// Function to change "posts" to "Insights" in the admin side menu
add_action( 'admin_menu', function() {
  global $menu;
  global $submenu;
  $menu[5][0] = 'Insights';
  $submenu['edit.php'][5][0] = 'Insights Articles';
  $submenu['edit.php'][10][0] = 'Add Insights Article';
  $submenu['edit.php'][16][0] = 'Tags';
  echo '';
});

// Function to change post object labels to "Insights"
add_action( 'init', function() {
  global $wp_post_types;
  $labels = &$wp_post_types['post']->labels;
  $labels->name = 'Insights Articles';
  $labels->singular_name = 'Insights Article';
  $labels->add_new = 'Add Insights Article';
  $labels->add_new_item = 'Add Insights Article';
  $labels->edit_item = 'Edit Insights Article';
  $labels->new_item = 'Insights Article';
  $labels->view_item = 'View Insights Article';
  $labels->search_items = 'Search Insights Articles';
  $labels->not_found = 'No Insights Articles found';
  $labels->not_found_in_trash = 'No Insights Articles found in Trash';
});


add_filter('excerpt_length', function ($length) {
  return 20; // Set excerpt length to 20 words
});

add_filter('excerpt_more', function () {
  return '...';
});

