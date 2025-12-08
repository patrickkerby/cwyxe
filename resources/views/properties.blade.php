{{--
  Template Name: Property Search
--}}
@php
    // First, get all posts matching FacetWP filters (without pagination)
    $all_args = [
        "post_type"      => 'property',
        "posts_per_page" => -1, // Get all posts
        "facetwp"        => true,
    ];
    $all_properties_query = new WP_Query( $all_args );
    
    // Sort posts with multi-level sorting:
    // 1. Featured first
    // 2. Then by menu_order (page attributes order)
    // 3. Then by post date
    $posts_to_sort = $all_properties_query->posts;
    usort($posts_to_sort, function($a, $b) {
        // Get featured status
        $general_settings_a = get_field('general_settings', $a->ID);
        $general_settings_b = get_field('general_settings', $b->ID);
        $featured_a = $general_settings_a['featured_property'] ?? false;
        $featured_b = $general_settings_b['featured_property'] ?? false;
        
        // Priority 1: Featured first
        if ($featured_a && !$featured_b) {
            return -1; // $a comes first
        }
        if (!$featured_a && $featured_b) {
            return 1; // $b comes first
        }
        
        // Priority 2: menu_order (if both have same featured status)
        $menu_order_a = $a->menu_order ?? 0;
        $menu_order_b = $b->menu_order ?? 0;
        if ($menu_order_a != $menu_order_b) {
            return $menu_order_a <=> $menu_order_b; // ASC order
        }
        
        // Priority 3: Post date (if menu_order is the same)
        return strtotime($a->post_date) <=> strtotime($b->post_date); // ASC order
    });
    
    // Extract sorted post IDs
    $sorted_post_ids = array_map(function($post) {
        return $post->ID;
    }, $posts_to_sort);
    
    // Now create the final query with sorted post IDs and let FacetWP handle pagination
    $args = [
        "post_type"      => 'property',
        "posts_per_page" => 10,
        "post__in"       => $sorted_post_ids,
        "orderby"        => "post__in", // Maintain the order of post__in array
        "facetwp"        => true,
    ];
    // Run the query - FacetWP will handle pagination
    $properties_loop = new WP_Query( $args );

@endphp
@extends('layouts.app')
@section('content')


    @include('partials.page-header')
    
    <section class="properties-container" id="listings">
        <div class="grid-head">
            <h2>Property Results</h2>
            {{-- {!! facetwp_display( 'facet', 'sort_listings' ) !!} --}}
        </div>
        <section class="map">
            <div class="search-type display-mobile">
                {!! facetwp_display( 'facet', 'availability' ) !!}
            </div>
            <button class="button facetwp-flyout-open">Show filters</button>
            {!! facetwp_display( 'facet', 'map' ) !!}
        </section>        
        <section class="properties-grid">
            @php if ( $properties_loop->have_posts() ) :
                while ( $properties_loop->have_posts() ) :
                    $properties_loop->the_post(); 
                    $status_terms = get_the_terms( $properties_loop->post->ID, 'property-status' );                    
                    $availability_condition = get_the_terms( $properties_loop->post->ID, 'availability-condition' );
                    if (!$availability_condition) {
                        $availability_condition = [];
                    }
                    @endphp
                    @include('partials.property-card')
                @php endwhile;
                else : @endphp
                    <p><?php  _e( 'Sorry, no posts matched your criteria.' ); ?></p>
            @php endif;

            wp_reset_postdata(); @endphp
        </section>
        {!! facetwp_display( 'facet', 'paging' ) !!}
    </section>
    <section class="sidebar filter">
        @include('partials.filter')
    </section>

@endsection

