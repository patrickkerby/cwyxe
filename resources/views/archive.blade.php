<?php
/*
Template Name: Archives
*/
?>

@extends('layouts.app')

@section('content')

  @if (! have_posts())
    <x-alert type="warning">
      {!! __('Sorry, no results were found.', 'sage') !!}
    </x-alert>

    {!! get_search_form(false) !!}
  @endif

  @while(have_posts()) @php(the_post())
    @includeFirst(['partials.content-' . get_post_type(), 'partials.content'])
  @endwhile

  {!! get_the_posts_navigation() !!}
  <section class="sidebar filter">
      <div class="card">
          <h5>Topics</h5>
          {!! facetwp_display( 'facet', 'insights_topics' ) !!}
      </div>
      <div class="card">
          <h5>Types</h5>
          {!! facetwp_display( 'facet', 'insights_types' ) !!}
      </div>
  </section> 
@endsection

@section('sidebar')
  @include('sections.sidebar')
@endsection