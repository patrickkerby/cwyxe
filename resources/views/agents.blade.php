{{--
  Template Name: Our People
--}}

@extends('layouts.app')

@section('content')
  @include('partials.page-header')
  <section class="agents-grid">
    @foreach ($agents as $agent)
      <div class="agent card">
        <div class="headshot">
          <a href="{{ $agent['link'] }}"><img src="{{ $agent['headshot']['url'] }}" alt="{{ $agent['headshot']['alt'] }}"></a>
        </div>
        <div class="contact-details">
          <h3><a href="{{ $agent['link'] }}">{{ $agent['name'] }}</a></h3>
          @if (@$agent['contact_details']['title'])
            <h4>{{ @$agent['contact_details']['title'] }}</h4>
          @endif
          <ul>
            @if(@$agent['contact_details']['office_phone'] )              
              <li><span>Office</span> {{ @$agent['contact_details']['office_phone'] }}</li>
            @endif
            @if(@$agent['contact_details']['mobile_phone'] )              
              <li><span>Mobile</span> {{ @$agent['contact_details']['mobile_phone'] }}</li>
            @endif
            @if (@$agent['contact_details']['email'])
              <li><a class="email" href="mailto:{{ @$agent['contact_details']['email'] }}">{{ @$agent['contact_details']['email'] }}</a></li>                
            @endif
            <li><a class="emphasized-link" href="">Download vCard</a></li>
          </ul>
        </div>
      </div>
    @endforeach
  </section>  
  @include('partials.cta')
@endsection

