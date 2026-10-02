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

// Smooth-scroll to the listings after FacetWP pagination
add_action( 'facetwp_scripts', function() {
  ?>
  <script>
    (function() {
      var shouldScrollToListings = false;

      document.addEventListener('click', function(event) {
        if (event.target.closest('.facetwp-page[data-page]')) {
          shouldScrollToListings = true;
        }
      });

      document.addEventListener('facetwp-loaded', function() {
        if (!shouldScrollToListings) {
          return;
        }

        shouldScrollToListings = false;

        var listings = document.querySelector('.properties-grid, .insights-grid');
        if (!listings) {
          return;
        }

        var offset = 24;
        var header = document.querySelector('header.banner');
        if (header) {
          var position = window.getComputedStyle(header).position;
          if (position === 'fixed' || position === 'sticky') {
            offset += header.getBoundingClientRect().height;
          }
        }

        var adminBar = document.getElementById('wpadminbar');
        if (adminBar) {
          offset += adminBar.getBoundingClientRect().height;
        }

        var top = listings.getBoundingClientRect().top + window.pageYOffset - offset;
        window.scrollTo({ top: Math.max(0, top), behavior: 'smooth' });
      });
    })();
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
    $api['key'] = env('GOOGLEAPI');
    
    return $api;
} );

add_filter( 'facetwp_map_init_args', function ( $args ) {
 
  $args['init']['zoomControl']       = true; // +- zoom control
  $args['init']['mapTypeControl']    = false; // roadmap / satellite toggle
  $args['init']['streetViewControl'] = false; // street view / yellow man icon
  $args['init']['fullscreenControl'] = true; // full screen icon
  $args['init']['mapId'] = '98064b9348db619a'; // map style id
  $args['init']['styles'] = ''; // map style array
  $args['init']['clickableIcons']    = false; // disable clicking on POIs (street names, neighborhoods, etc.)
  
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
   
        let icon_default = {
          url: {!! json_encode(get_stylesheet_directory_uri() . '/resources/images/map-icons/CWS-map-icon-single.svg') !!},
          scaledSize: {
            width: 24,
            height: 32
          }
        };

        let icon_active = {
          url: {!! json_encode(get_stylesheet_directory_uri() . '/resources/images/map-icons/CWS-map-icon-selected.svg') !!},
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

add_filter('facetwp_facets', function ($facets) {
    foreach ($facets as &$facet) {
        if (($facet['type'] ?? '') === 'dropdown') {
            $facet['ui_type'] = 'fselect';
            $facet['multiple'] = $facet['multiple'] ?? 'no';
        }
    }
    unset($facet);

    return $facets;
});

add_action('facetwp_scripts', function () {
    ?>
    <script>
      if (typeof FWP !== 'undefined' && FWP.hooks) {
        FWP.hooks.addFilter('facetwp/set_options/fselect', function(opts) {
          opts.showSearch = false;
          return opts;
        });
      }
    </script>
    <?php
}, 20);

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
		),
    array(  
			'title' => 'Teal Heading 3',  
			'selector' => 'h3',  
			'classes' => 'teal',
		)
	);  
	// Insert the array, JSON ENCODED, into 'style_formats'
	$init_array['style_formats'] = wp_json_encode( $style_formats );  
	
	return $init_array;  
  
} 
// Attach callback to 'tiny_mce_before_init' 
add_filter( 'tiny_mce_before_init', 'App\my_mce_before_init_insert_formats' );  

add_action( 'wp_head', function() {
  ?>
    <script>
      (function($) {
        $(function() {
          if ('object' != typeof FWP) return;
          FWP.hooks.addFilter('facetwp/flyout/facets', function(facets) {
            return ['availability', 'status', 'property_type', 'available_space_unit', 'min_max_area', 'broker' ,'reset']; /* Choose which facets to display in the flyout, and/or change the facet display order */
          });
        });
      })(jQuery);
    </script>
  <?php
}, 100 );


add_filter( 'enter_title_here', function( $input, $post ) {
    if ( 'property' === $post->post_type ) {
        return __( 'Property Listing Title', 'your_textdomain' );
    }
    return $input;
},
10, 2 );

// Only use with the "Info window ajax loading" setting disabled
add_filter( 'facetwp_map_marker_args', function( $args, $post_id ) {
  if ( empty( $args['infoWindowContent'] ) ) {
    $args['clickable'] = false;
    $args['pinOptions']['pinClass'] = 'unclickable'; // Set a custom pin class
  }
  return $args;
}, 10, 2 );
 
// Set a 'default' cursor when the marker is unclickable
add_action( 'wp_head', function () {
  ?>
  <style>
    .unclickable {
      cursor: default; /* set default cursor */
    }
  </style>
  <?php
}, 100 );

add_action( 'facetwp_scripts', function() {
  ?>
  <script>
    (function($) {
      $(function() {
 
        if ('object' != typeof FWP) return;
 
        // Define a custom renderer function
        var customrenderer = {
 
          render: function({count, position}, stats, map) {
 
            // Create cluster element with CSS class for styling
            const clusterElement = document.createElement('div');
            clusterElement.className = 'my-cluster-class';
            clusterElement.textContent = count;
 
            // Set cluster title text, visible on hover
            const title = `Cluster of ${count} markers`;
 
            // Adjust zIndex to be above other markers
            const zIndex = 1000000 + count;
 
            const clusterOptions = {
              map,
              position,
              zIndex,
              title,
              content: clusterElement,
            };
 
            return new google.maps.marker.AdvancedMarkerElement(clusterOptions);
          }
 
        };
 
        FWP.hooks.addFilter('facetwp_map/clusterer', function(clusterargs, clusterconfig) {
          clusterargs['renderer'] = customrenderer;
          return clusterargs;
        });
 
      });
    })(fUtil);
  </script>
  <?php
}, 100 );

// Keep matching featured properties at the top of FacetWP listing results.
add_filter( 'facetwp_filtered_query_args', function( $query_args ) {
    $post_type = $query_args['post_type'] ?? '';
    $is_property_query = $post_type === 'property'
        || ( is_array( $post_type ) && in_array( 'property', $post_type, true ) );

    if ( ! $is_property_query ) {
        return $query_args;
    }

    $post_ids = FWP()->filtered_post_ids ?? [];
    if ( empty( $post_ids ) ) {
        return $query_args;
    }

    $featured_ids = get_posts( [
        'post_type'              => 'property',
        'post_status'            => 'publish',
        'post__in'               => $post_ids,
        'posts_per_page'         => -1,
        'fields'                 => 'ids',
        'no_found_rows'          => true,
        'suppress_filters'       => true,
        'facetwp'                => false,
        'update_post_meta_cache' => false,
        'update_post_term_cache' => false,
        'meta_query'             => [
            [
                'key'     => 'general_settings_featured_property',
                'value'   => '1',
                'compare' => '=',
            ],
        ],
    ] );

    if ( empty( $featured_ids ) ) {
        return $query_args;
    }

    $featured_lookup = array_flip( array_map( 'intval', $featured_ids ) );
    $featured = [];
    $regular = [];

    foreach ( $post_ids as $post_id ) {
        $post_id = (int) $post_id;
        if ( isset( $featured_lookup[ $post_id ] ) ) {
            $featured[] = $post_id;
        } else {
            $regular[] = $post_id;
        }
    }

    $query_args['post__in'] = array_merge( $featured, $regular );
    $query_args['orderby'] = 'post__in';
    unset( $query_args['order'] );

    return $query_args;
}, 20, 1 );

// Apply the sorted FacetWP query on the initial property listing pageload.
add_filter( 'facetwp_preload_force_query', function( $force, $query ) {
    $post_type = $query->get( 'post_type' );
    $is_property_query = $post_type === 'property'
        || ( is_array( $post_type ) && in_array( 'property', $post_type, true ) );

    return $is_property_query ? true : $force;
}, 10, 2 );

/**
* Use ACF 'primary_image' field as the Yoast OG image for property listings.
*/
add_filter( 'wpseo_opengraph_image', function( $image ) {
  if ( is_singular( 'property' ) ) {
    $primary_image = get_field( 'primary_image' );
  
    if ( ! empty( $primary_image['url'] ) ) {
      return $primary_image['url'];
    }
  }
  return $image;
}, 10, 1 );

/**
 * MarketBeat lead-gen: tag submissions with the office (Saskatoon or Regina).
 * The Make webhook URL is unchanged and not managed here.
 */
add_filter('hf_form_markup', function ($markup, $form) {
    if (! is_object($form) || ($form->slug ?? '') !== 'lead-generator') {
        return $markup;
    }

    $site = esc_attr(\App\Support\SiteOptions::officeIdentifier());
    $input = sprintf('<input type="hidden" name="site" id="lead-gen-site" value="%s" />', $site);

    if (preg_match('/<input[^>]*name=["\']site["\'][^>]*>/i', $markup)) {
        return preg_replace('/<input[^>]*name=["\']site["\'][^>]*>/i', $input, $markup, 1);
    }

    return $input . "\n" . $markup;
}, 10, 2);

add_action('hf_process_form', function ($form, $submission) {
    if (! is_object($form) || ($form->slug ?? '') !== 'lead-generator' || ! is_object($submission)) {
        return;
    }

    $submission->data['site'] = \App\Support\SiteOptions::officeIdentifier();
}, 10, 2);