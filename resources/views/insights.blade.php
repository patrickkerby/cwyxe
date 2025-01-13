{{--
  Template Name: Insights
--}}

@extends('layouts.app')

@section('content')
    <section class="insights-container" id="listings">

        <div class="grid-head">
            <h2>Most Recent</h2>
        </div>
        <section class="insights-grid">

            @query([
                'post_type' => 'post',
                "facetwp"   => true 
            ])
            @posts
                @php
                    // Will return `false` if no primary term has been set
                    if (function_exists('yoast_get_primary_term_id')) {
                        $primary_term_id = yoast_get_primary_term_id('category');
                    } else {
                        $primary_term_id = null;
                    }

                    if ( $primary_term_id ) {
                        /** @var WP_Term $primary_term */
                        $primary_term = get_term( $primary_term_id );
                    }
                    else {
                        $primary_term = false;
                    }
                @endphp

                <div class="@if($loop->iteration > 2)wide @endif insight card">
                    @set($cat, get_the_category($post->id))                
                    <a href="@permalink">
                        @thumbnail('full')
                    </a>
                    <div class="content">  
                        <span class="meta">
                            @if($primary_term)
                                <a href="?_insights_topics={{ $primary_term->slug }}">{{ $primary_term->name }}</a>
                            @else
                                <a href="?_insights_topics={{ $cat[0]->slug }}">@category</a>
                            @endif
                        </span>
                        <a href="@permalink">
                            <h4>@title</h4>
                            @if($loop->iteration < 3) <time class="dt-published" datetime="{{ get_post_time('c', true) }}">{{ get_the_date() }}</time> @endif
                            @if($loop->iteration < 3) <p>@excerpt</p> @endif
                            @if($loop->iteration < 3) <a href="@permalink" class="excerpt">Read More</a> @endif
                            @if($loop->iteration > 2) <span class="time"><time class="dt-published" datetime="{{ get_post_time('c', true) }}">{{ get_the_date() }}</time> by @author </span>@endif
                        </a>
                    </div>
                </div>
            @endposts
        </section>
        {!! facetwp_display( 'facet', 'paging' ) !!}
    </section>
    <section class="sidebar filter">
        @include('partials.filter')
    </section> 

@endsection

@section('sidebar')
  @include('sections.sidebar')
@endsection