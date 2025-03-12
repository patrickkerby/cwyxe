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

add_filter( 'acf/fields/google_map/api', function($api) {
    $api['key'] = 'AIzaSyB4tACHjkBLlcqFPGeMycOSvDadNWVurS0';
    return $api;
} );

add_filter( 'facetwp_map_init_args', function ( $args ) {
 
  $args['init']['zoomControl']       = true; // +- zoom control
  $args['init']['mapTypeControl']    = false; // roadmap / satellite toggle
  $args['init']['streetViewControl'] = false; // street view / yellow man icon
  $args['init']['fullscreenControl'] = true; // full screen icon
  $args['init']['mapId'] = '98064b9348db619a'; // map style id
  $args['init']['styles'] = ''; // map style array
  
  /** this overwrites all 4 lines above and will disable ALL of the default ui icons instead of the individual icons above */
  // $args['init']['disableDefaultUI']  = true; // disable the default ui
  
  return $args;
  
} );

add_filter( 'facetwp_map_init_args', function( $args ) {
  if ( isset( $args['config']['cluster'] ) ) {
    $args['config']['cluster']['zoomOnClick'] = true; // default: false
  }
  return $args;
} );

add_filter( 'facetwp_map_init_args', function ( $args ) {

  if ( wp_is_mobile() ) {
    $args['init']['gestureHandling'] = 'cooperative'; // Default: 'auto', other options: 'cooperative', 'greedy', 'none'a
  }
  else {
    $args['init']['gestureHandling'] = 'greedy'; // Default: 'auto', other options: 'cooperative', 'greedy', 'none'a
  }

  // $args['init']['gestureHandling'] = 'auto'; // Default: 'auto', other options: 'cooperative', 'greedy', 'none'a

  return $args;
} );

add_action('facetwp_scripts', function () {
  ?>
  <!-- <script>
    (function($) {
      FWP.hooks.addAction('facetwp/reset', function() {
        $.each(FWP.facet_type, function(type, name) {
          if ('map' === type) {
            var $button = $('.facetwp-map-filtering');
            $button.text(FWP_JSON['map']['filterText']);
            FWP_MAP.is_filtering = false;
            $button.toggleClass('enabled');
          }
        });
      });
    })(fUtil);
  </script> -->
  <?php
}, 100);

add_action('facetwp_scripts', function () {
 ?>
    <!-- <script>
      (function($) {
        document.addEventListener('facetwp-loaded', function() {
          if ('undefined' === typeof FWP_MAP) {
            return;
          }
          var filterButton = $(".facetwp-map-filtering");
          if (!filterButton.hasClass('enabled') && 'undefined' == typeof FWP_MAP.enableFiltering) {
            filterButton.text(FWP_JSON['map']['resetText']);
            FWP_MAP.is_filtering = true;
            filterButton.addClass('enabled');
            FWP_MAP.enableFiltering = true;
          }
        });
      })(fUtil);
    </script> -->
  <?php
  }, 100);



  // Custom map icons
  add_filter( 'facetwp_map_marker_args', function( $args, $post_id ) {
    $args['icon'] = [
      'url' => get_stylesheet_directory_uri() . '/resources/images/map-icons/CWS-map-icon-single.svg',
      'scaledSize' => [
        'width' => 24,
        'height' => 32
      ]
    ];
    return $args;
  }, 10, 2 );

  // Add new icon when marker is clicked
  add_action( 'facetwp_scripts', function() {
    ?>
    <script>
      (function($) {
        if ('object' !== typeof FWP) {
          return;
        }
   
        // Change URL to the default marker icon
        let icon_default = {
          url: '/app/themes/cwyxe/resources/images/map-icons/CWS-map-icon-single.svg',
          scaledSize: {
            width: 24,
            height: 32
          }
        };
   
        // Change URL to the alternative, 'active' icon
        let icon_active = {
          url: '/app/themes/cwyxe/resources/images/map-icons/CWS-map-icon-selected.svg',
          scaledSize: {
            width: 24,
            height: 32
          }
        };
   
        $(function() {
          FWP.hooks.addAction('facetwp_map/marker/click', function(marker) {
   
            // Check if another marker is already active. If so set to default icon
            if (window.marker_post_id) {
              let post_id = window.marker_post_id;
              let marker = FWP_MAP.get_post_markers(post_id)[0];
              marker.setIcon(icon_default); // or use marker.setIcon(null); for the default pin
            }
            // Set the clicked marker to have the 'active' icon
            window.marker_post_id = marker.post_id;
            marker.setIcon(icon_active);
          });
   
          // When an infoWindow is closed revert its marker to the default icon
          google.maps.event.addListener(FWP_MAP.infoWindow, 'closeclick', function() {
            let post_id = window.marker_post_id;
            let marker = FWP_MAP.get_post_markers(post_id)[0];
            marker.setIcon(icon_default); // or use marker.setIcon(null); for the default pin
          });
   
          // When the map is clicked anywhere, revert the active marker to the default icon
          google.maps.event.addListener(FWP_MAP.map, 'click', function(event) {
            if (window.marker_post_id) {
              let post_id = window.marker_post_id;
              let marker = FWP_MAP.get_post_markers(post_id)[0];
              marker.setIcon(icon_default); // or use marker.setIcon(null); for the default pin
            }
          });
        });
      })(jQuery);
    </script>
    <?php
  }, 100 );

  add_filter( 'facetwp_map_init_args', function( $args ) {
    if ( isset( $args['config']['cluster'] ) ) {
      $args['config']['cluster']['cssClass'] = 'my-cluster-class'; 
    }
    return $args;
  } );

  // Preselect availability facet when you load the property search page
  add_filter( 'facetwp_preload_url_vars', function( $url_vars ) {
    if ( 'property-search' == FWP()->helper->get_uri() ) {
      if ( empty( $url_vars['availability'] ) ) { 
        $url_vars['availability'] = [ 'lease' ];
      }
    }
    return $url_vars;
  } );

// Callback function to insert 'styleselect' into the $buttons array
function my_mce_buttons_2( $buttons ) {
	array_unshift( $buttons, 'styleselect' );
	return $buttons;
}
// Register our callback to the appropriate filter
add_filter( 'mce_buttons_2', 'App\my_mce_buttons_2' );

// Callback function to filter the MCE settings
function my_mce_before_init_insert_formats( $init_array ) {  
	// Define the style_formats array
	$style_formats = array(  
		// Each array child is a format with it's own settings
    array(  
			'title' => 'Intro Text',  
			'selector' => 'p',  
			'classes' => 'intro-text',
		)
	);  
	// Insert the array, JSON ENCODED, into 'style_formats'
	$init_array['style_formats'] = wp_json_encode( $style_formats );  
	
	return $init_array;  
  
} 
// Attach callback to 'tiny_mce_before_init' 
add_filter( 'tiny_mce_before_init', 'App\my_mce_before_init_insert_formats' );  