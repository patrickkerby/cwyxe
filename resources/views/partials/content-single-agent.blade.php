@set($fname, get_field('contact_details')['first_name'])
@set($lname, get_field('contact_details')['last_name'])
@set($name, $fname . '-' . $lname)

@php
  $vcard_filename = strtolower($name);

  $agents = get_field('agent');

    $args = [
      "post_type"      => 'property',
      "posts_per_page" => 6,
      "orderby"        => ["title" => "ASC"],        
      "facetwp"        => true,
      'meta_query'  => array(
        array(
            'key' => 'agent',
            'value' => $post->ID,
            'compare' => 'LIKE',
        ),
    ),
  ];       
  $properties_loop = new WP_Query( $args );
@endphp

<article @php post_class('h-entry') @endphp>

  <div class="content">
    <h1>{!! $title !!}</h1>    
    @group('contact_details')
      @hassub('title')
        <h2>@sub('title')</h2>
      @endsub
    @endgroup

    @include('partials.broker-attributes')
  
    <div class="horizontal-tabs">
      <nav class="tabs">
        <ul>
          @hasfield('overview')<li class="is-active"><a href="#tab-overview">Overview</a></li>@endfield
          @hasfield('services')<li><a href="#tab-services">Services</a></li>@endfield
          @hasfield('awards')<li><a href="#tab-awards">Awards</a></li>@endfield
          @hasfield('clients')<li><a href="#tab-clients">Clients</a></li>@endfield
          @hasfield('affiliations')<li><a href="#tab-affiliations">Affiliations</a></li>@endfield
        </ul>
      </nav>
      <div class="tab-content-container"> 
        @hasfield('overview')
          <section class="tab-content is-active" id="tab-overview">@field('overview')</section>
        @endfield
        @hasfield('services')
          <section class="tab-content" id="tab-services">@field('services')</section>
        @endfield
        @hasfield('awards')
          <section class="tab-content" id="tab-awards">@field('awards')</section>
        @endfield
        @hasfield('clients')
          <section class="tab-content" id="tab-clients">@field('clients')</section>
        @endfield
        @hasfield('affiliations')
          <section class="tab-content" id="tab-affiliations">@field('affiliations')</section>
        @endfield  
      </div>   
    </div>
  </div>
  <div class="sidebar">
    <div class="headshot">
      <img src="@field('headshot', 'url')" alt="@field('headshot', 'alt')">
    </div>
    <div class="contact-details">
      <h1>{!! $title !!}</h1>
      @hasfield('title')
        <h2>@field('title')</h2>
      @endfield
      <ul>
        @group('contact_details')
          @hassub('office_phone')
            <li><span>Office</span> @sub('office_phone')</li>
          @endsub
          @hassub('mobile_phone')
            <li><span>Mobile</span> @sub('mobile_phone')</li>
          @endsub
        @endgroup
          <li><a class="emphasized-link" href="/app/uploads/vcards/{{$vcard_filename}}.vcf">Download vCard</a></li>
          <li><a href="#listings">View My listings</a></li>
      </ul>
    </div>
  </div>
</article>

<section class="container centered" id="listings">
  <h4>My Listings</h4>
  <div class="properties-grid">
    {{-- @foreach ($properties_loop as $property)
      @foreach ($property['agents'] as $agent)
        @if($agent->ID == $post->ID)
          @include('partials.property-card')
        @endif
      @endforeach
    @endforeach --}}

    @php if ( $properties_loop->have_posts() ) :
      while ( $properties_loop->have_posts() ) :
        $properties_loop->the_post(); 
        $status_terms = get_the_terms( $properties_loop->post->ID, 'property-status' );
    @endphp
@include('partials.property-card')

        {{-- @foreach ($agents as $agent)
          @if($agent->ID == $post->ID)            
          @else  
            @php
                continue;
            @endphp
          @endif
        @endforeach --}}

      @php endwhile;
      else : @endphp
        <p><?php  _e( 'Sorry, no posts matched your criteria.' ); ?></p>
      @php endif;
    wp_reset_postdata(); @endphp
  </div>
  {!! facetwp_display( 'facet', 'paging' ) !!}

</section>
