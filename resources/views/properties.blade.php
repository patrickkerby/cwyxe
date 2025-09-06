{{--
  Template Name: Property Search
--}}
@php
    $args = [
        "post_type"      => 'property',
        "posts_per_page" => 10,
        "orderby"        => ["title" => "ASC"],        
        "facetwp"        => true,

    ];
    // Run the query
    $properties_loop = new WP_Query( $args );

    // $featured_array = [];
    // foreach( $properties_loop->posts as $post) {
    //     $general_settings = get_field('general_settings', $post->ID);
    //     $featured = $general_settings['featured_property'] ?? false;
        
    //     if ( $featured ) {
    //         $featured_array[] = $post;
    //     }
    // }
    // print("<pre>".print_r($properties_loop,true)."</pre>");


@endphp
@extends('layouts.app')
@section('content')


    @include('partials.page-header')
    
    <section class="properties-container" id="listings">
        {{-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB4tACHjkBLlcqFPGeMycOSvDadNWVurS0&callback=Function.prototype&loading=async"></script> --}}
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

