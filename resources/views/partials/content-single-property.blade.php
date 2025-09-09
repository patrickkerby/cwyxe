@php
  $agents = get_field('agent');
  $status_terms = get_the_terms( $post->ID, 'property-status' );
  $availability_condition_single = get_the_terms( $post->ID, 'availability-condition' );
  $property_type = get_the_terms( $post->ID, 'property-type' );
  $featured_meta = get_field('highlighted_property_details' );
  $dimensions_meta = array("building_size", "lot_size", "area_size", "max_contiguous", "min_divisible");
  $details_meta = array("property_id", "year_built", "building_class", "space_type", "property_use_type", "zoning", "occupancy", "construction_status");
  $rate_meta = array("amount", "rate_type");
  $dimensions = get_fields('dimensions_section');
  $additional_images = get_field('additional_images');
  $property_type_slugs = [];

  if ($property_type) {
    foreach ($property_type as $item) {
      $property_type_slugs[] = $item->slug;
    }
    // $property_type_slugs = implode(',', $property_type_slugs);
  }

  $args = [
    "post_type"      => 'property',
    "posts_per_page" => 6,
    "orderby"        => ["title" => "ASC"],
    "post__not_in"   => [$post->ID],
    "tax_query" => array(
        array(
            'taxonomy' => 'property-type', // Replace with your custom taxonomy slug
            'field'    => 'slug',               // Can be 'slug', 'term_id', or 'name'
            'terms'    => $property_type_slugs, // Replace with your desired term slugs or IDs
            'operator' => 'IN',                 // Use 'IN' for matching any of the terms, 'AND' for all terms, 'NOT IN' for excluding terms
        ),
      ),
  ];
  $related_properties = new WP_Query( $args );

@endphp

<article @php post_class('h-entry') @endphp>

  <section class="property-header">
    
    @if($status_terms)
    <span class="pills">
      @foreach ($status_terms as $status)
        @php
          $status_color = get_field('property_status_colour', 'term_'.$status->term_id);
        @endphp
        <span class="property_type status" style="background-color:{{$status_color}}">{{ $status->name }}</span>
      @endforeach
      </span> 
    @endif
   
    <span class="availability">
      @group('general_settings')
        For @sub('availability') • 
      @endgroup
      @foreach ($property_type as $type)      
        {{ $type->name }}@if (!$loop->last), @endif
      @endforeach
    </span>
    <h1>{!! $title !!}</h1>
    @hasfield('address')
      <h2>@field('address')</h2>
    @endfield
  </section>
  <section class="container">
    <div class="content">   
      <div class="gallery">        

        @if ($availability_condition_single)
          @php
            $char_count = mb_strlen($availability_condition_single[0]->name, "UTF-8");

            if ($char_count < 10) {
              $status_size = 'status-small';      
            } else {
              $status_size = 'status-large';
            }        
          @endphp
          <span class="status_banner {{ $status_size }}">{{ $availability_condition_single[0]->name }}</span>
        @endif
        
        <div class="siema images">
          @hasfield('primary_image')   
            <img src="@field('primary_image', 'url')" class="siema-slide" alt="@field('primary_image', 'alt')">        
          @endfield
          @foreach ($additional_images as $image)
            <img src="{{ $image['url'] }}" class="siema-slide" alt="{{ $image['alt'] }}">
          @endforeach      
        </div>
        <button class="prev arrow">Prev</button>
        <button class="next arrow right">Next</button>
      </div>

      @if($featured_meta)
        <div class="property-meta-featured">
          @foreach ($featured_meta as $meta)
            @set($value, $meta['value'])
            @set($icon_field, $value . '_icon')
      
            @options('property_details_display_icons')
              @set($icon, get_sub_field($icon_field))
              @if ( 'dashicons' == $icon['type'] )
                @set($icon_value, $icon['value'])
                @set($background_img, '')
              @elseif ( 'media_library' == $icon['type'] )
                @set($icon_value, '')
                @set($background_img, $icon['value']['url'])
              @endif
            @endoptions

            @unless(!empty($property['availability_condition']) && $availability_condition_single[0]->slug == 'leased' && $meta['label'] == 'Amount' || !empty($property['availability_condition']) && $availability_condition_single[0]->slug == 'sold' && $meta['label'] == 'Amount')             
              <div class="{{ $value }} {{ $icon_value }}" style="background-image:url({{ $background_img }});">
                @if(in_array($value, $dimensions_meta))
                  @set($group, 'dimensions_section')
                @elseif(in_array($value, $details_meta))
                  @set($group, 'property_details')
                @elseif(in_array($value, $rate_meta))
                  @set($group, 'rates')
                @endif
                @group($group)
                  @hassub($value)
                    <span class="label">{{ $meta['label'] }}</span>
                    @if($meta['label'] == 'Amount')       
                      @php
                          $price = get_sub_field($value);
                          $price_str = preg_replace('/(\d)(?=(?:\d{3})+$)/', '$1,', $price);
                      @endphp
                      <span class="value">${{ $price_str }}</span>
                      @elseif($group == 'dimensions_section')
                      @set($postfix, $value.'_postfix')
                      <span class="value">&plusmn; @sub($value) @sub($postfix)</span>
                      @else
                      <span class="value">@sub($value)</span>
                    @endif
                  @endsub
                @endgroup
              </div>
            @endunless
          @endforeach
        </div>     
      @endif

      @hasfield('marketing_package')
        <a class="download" target="_blank" href="@field('marketing_package')">Marketing Package (PDF)</a>
      @endfield
      @hasfield('overview')
        <div class="overview">
          <h4>Overview</h4>
          @field('overview')
        </div>
      @endfield
      @hasfields('key_features')
        <div class="key_features">
          <h4>Key Features</h4>
          @fields('key_features')
            <strong>@sub('feature_title')</strong>
            <p>@sub('key_feature_content')</p>
          @endfields
        </div>
      @endhasfields
      
      {{-- Get all the property details fields from the group and display them --}}
      {{-- This uses the get_sub_field_object so that we can add in the label dynamically --}}
      <div class="details">
        <h4>Details</h4>
        @hasfields('property_details')
          @set($details_group, get_field('property_details'))
          @foreach ($details_group as $key => $value)
            @group('property_details')
              @set($label, get_sub_field_object($key)['label'])
              @hassub($key)
                <div class="detail {{ $key }}">        
                  <strong>{{ $label }}</strong>
                  <p>@sub($key)</p>
                </div>
              @endsub
            @endgroup 
          @endforeach
        @endhasfields

      {{-- Get all the dimensions details fields from the group and display them. --}}
      {{-- TODO future improvement is to populate the dimensions_meta array dynamically --}}

        @hasfields('dimensions_section')
            @group('dimensions_section')
              @foreach($dimensions_meta as $dimension)
              @hassub($dimension)
                  @set($label, get_sub_field_object($dimension)['label'])
                  @set($value, get_sub_field_object($dimension)['value'])

                  @php 
                  $value = (float) $value; @endphp
                    <div class="detail">        
                      <strong>{{ $label }}</strong>
                      <p>&plusmn; {{ number_format($value, 2, '.') }} @sub($dimension.'_postfix')</p>
                    </div>
                @endsub
              @endforeach
            @endgroup 
        @endhasfields

        {{-- Add any rate related data to this list --}}
        @hasfields('rates')
          @group('rates')            
            @hassub('rate_type')
              <div class="detail">        
                <strong>Rate Type</strong>
                <p>@sub('rate_type')</p>
              </div>
            @endsub
            @if(get_sub_field('negotiable') == TRUE)
              @set($negotiable, '(negotiable)')
            @else
              @set($negotiable, '')
            @endif
                
            @unless(!empty($property['availability_condition']) && $availability_condition_single[0]->slug == 'leased' || !empty($property['availability_condition']) && $availability_condition_single[0]->slug == 'sold')
              <div class="detail">
                @php 
                  $price = get_sub_field('amount');
                  $price_str = preg_replace('/(\d)(?=(?:\d{3})+$)/', '$1,', $price); 
                @endphp
                <strong>Price</strong>
                <p>@hassub('amount')${{ $price_str }} @sub('rate_postfix')@endsub {{ $negotiable }}</p>
              </div>
            @endunless
          @endgroup 
        @endhasfields

        {{-- now add any custom additional fields --}}
        @group('additional_details')
            @hasfields('details')
              @fields('details')
                <div class="detail">
                  <strong>@sub('detail_title')</strong>
                  <p>@sub('detail_info')</p>
                </div>
              @endfields
            @endhasfields
        @endgroup
      </div>
      
      <div class="map">
        <h4>Location</h4>
        {!! facetwp_display( 'facet', 'single_map' ) !!}
        {!! facetwp_display( 'template', 'single_map' ) !!}
      </div> 

    </div>
    <div class="sidebar">
      @if($agents)
        @foreach ($agents as $agent)
          @php
            $agent_id = $agent->ID;
            $headshot = get_field('headshot', $agent_id);
          @endphp
          <div class="agent @if($loop->count > 1) additional-agent @endif">
            <div class="headshot">
                <img src="{{ $headshot['url'] }}" alt="">
            </div>
            <div class="contact-details">
              <h4>@title($agent_id)</h4>          
              <ul>
                @group('contact_details', $agent_id)
                  @php
                      $details = get_field('contact_details', $agent_id);
                      $fname = $details['first_name'];
                      $lname = $details['last_name'];
                      $name = $fname . '-' . $lname;
                      $vcard_filename = strtolower($name);
                  @endphp
                  @hassub('office_phone')
                    <li><span>Office:</span> @sub('office_phone')</li>
                  @endsub
                  @hassub('mobile_phone')
                    <li><span>Mobile:</span> @sub('mobile_phone')</li>
                  @endsub
                  @hassub('email')
                    <li><a href="mailto:@sub('email')">@if($loop->count == 1) @sub('email') @else Email Agent @endif</a></li>
                  @endsub
                @endgroup
                <li><a class="emphasized-link @if($loop->count == 1) btn-hollow @endif" href="/app/uploads/vcards/{{$vcard_filename}}.vcf"">Download vCard</a></li>
                @if($loop->count == 1)<li><a class="emphasized-link" href="@permalink($agent_id)">View My listings</a></li>@endif 
              </ul>
            </div>
          </div>
          @endforeach
        @endif
      <div class="listing-alerts form">        
        <x-listing-alert />
      </div>
    </div>
  </section>
  <section class="related-properties">
    <h4>Similar Properties</h4>
    <div class="properties-grid">
      @php 
        $count = 0;
        if ( $related_properties->have_posts() ) :
          while ( $related_properties->have_posts() ) :
            $related_properties->the_post(); 
            $status_terms = get_the_terms( $related_properties->post->ID, 'property-status' );
            $availability_condition = get_the_terms( $related_properties->post->ID, 'availability-condition' );
            if ( $availability_condition ) {
              continue;
            }
            $count++;
            @endphp
            
            @include('partials.property-card')
            
            @php
            if ($count >= 3) break;
          endwhile;
        else : @endphp
        <p><?php  _e( 'Sorry, no posts matched your criteria.' ); ?></p>
      @php endif;

      wp_reset_postdata(); @endphp      
    </div>
  </section>

</article>


  