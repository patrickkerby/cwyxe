{{--
  Template Name: Home Page
--}}

@extends('layouts.app')
    @php
      
  @endphp
@section('content')
  @while(have_posts()) @php(the_post())

    <section class="listings-search">
        <div class="listings-heading">
            <h4>Find Your Property</h4>
        </div>
        <div class="facet">
            <span>Keyword</span>
            {!! facetwp_display( 'facet', 'keyword_homepage' ) !!}
        </div>
        {{-- {!! facetwp_display( 'facet', 'city_town' ) !!} --}}
        <div class="facet">
            <span>Property Status</span>
            {!! facetwp_display( 'facet', 'property_status_homepage' ) !!}
        </div>
        <div class="facet">
            <span>Property Type</span>
            {!! facetwp_display( 'facet', 'property_type_homepage' ) !!}
        </div>
        <div class="facet">
            <span>Min / Max Area</span>
            
            {!! facetwp_display( 'facet', 'min_max_fields' ) !!}
            {!! facetwp_display( 'facet', 'min_max_area_homepage' ) !!}
        </div>

        <div style="display: none;">
            {!! facetwp_display( 'facet', 'search' ) !!}
            {!! facetwp_display( 'facet', 'status' ) !!}
            {!! facetwp_display( 'facet', 'property_type' ) !!}
            {!! facetwp_display( 'facet', 'min_max_area' ) !!}
        </div>

        <div style="display:none"> {!! facetwp_display( 'template', 'home_page_listings_search' ) !!}</div>
        {{-- <button class="fwp-submit" data-href="/property-search/">Map View</button> --}}
        <div class="submit-buttons">
            <button id="listings-search" class="fwp-submit button" data-href="/property-search/">Search</button>
            {{-- <button class="fwp-submit button btn-hollow" data-href="/property-search/">Map View</button> --}}

        </div>
    </section>
    {{-- <section class="featured-property-types">
        <h4>Listing by Property Type</h4>
        <div class="property-type-grid">
            @foreach($propertyTypes['featured_property_types'] as $featured_property_type) 
                <div class="property-type-feature">
                    <a href="/property-search/?_property_type={{ $featured_property_type->slug }}">
                        <h3>{{ $featured_property_type->name }}</h3>
                        <img src="{{ $featured_property_type->image['url'] }}" alt="{{ $featured_property_type->image['alt'] }}">
                    </a>
                </div>
            @endforeach
            <div>
            <form action="" class="">
                <h4>Sign up for listing alerts</h4>
                <label>Name</label>
                <input type="text" placeholder="John Smith">
                <label>Email</label>
                <input type="text" placeholder="john@example.com">
                <button class="button">Submit</button>
            </form>
            </div>
        </div>
    </section> --}}
    @include('partials.cta-homepage', ['count' => 'first'])
    <section class="insights">
        <h4>Insights</h4>
        <div class="insights-grid">
            @foreach($featuredInsights as $insight)
                <div class="insight card">
                    <img src="{{ $insight['thumbnail'] }}" alt="{{ $insight['name'] }}">
                    <div class="content">
                        <span class="meta"><time class="dt-published">{{ $insight['date'] }}</time> in {!! $insight['terms'] !!}</span>
                        <h4>{{ $insight['name'] }}</h4>
                        <p>{!! $insight['excerpt'] !!}</p>
                        <a href="{{ $insight['link'] }}" class="excerpt">Read More</a>                        
                    </div>
                </div>
                @if ($loop->iteration == 3)
                    @break
                @endif
            @endforeach
        </div>
        <a href="/insights" class="excerpt fullwidth">Read More Insights</a>
    </section>
    <section class="statistics">
        @include('partials.cta-stats')
    </section>
    @include('partials.cta-homepage', ['count' => 'second'])
    <section class="featured-properties">
        <h4>Featured Properties</h4>
        <div class="properties-grid">
            @foreach ($featuredProperties as $property)
                @if($property)
                    @include('partials.property-card')
                @endif
                @if ($loop->iteration == 3 )
                    @break
                @endif
            @endforeach
        </div>
    </section>
    <section class="listing-alerts">
        <form action="" class="">
            <h4>Sign up for listing alerts</h4>
            <label>Name</label>
            <input type="text" placeholder="John Smith">
            <label>Email</label>
            <input type="text" placeholder="john@example.com">
            <div class="form-row">
                <input id="subscribe" class="cws-form-checkbox" name="subscribe" type="checkbox" value="yes" />
                <label class="cws-form-checkbox-label" for="subscribe">Yes, I would like to receive property listings, news, and market intelligence from Cushman &amp; Wakefield Saskatoon.</label>
            </div>
            <button class="button">Sign up</button>
        </form>
    </section>
  @endwhile
@endsection
