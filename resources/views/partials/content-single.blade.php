@php
  $lead_gen = get_field('lead_gen_form');
  $protect = get_field('prevent_text_selection');
  if ($protect) {
    $protect = 'noselect';
  } else {
    $protect = '';
  }
  $test = "test";
@endphp

<article @php post_class('h-entry') @endphp>
  <section class="{{$protect}}">    
    @php

      the_content();

      // This loop requires a /partials template that is named exactly the same as the layout title in ACF flexible content page builder
      $id = get_the_ID();
      if ( have_rows( 'page_builder', $id ) ) :
        // loop through the selected ACF layouts and display the matching partial
        while ( have_rows( 'page_builder', $id ) ) : the_row();
          $layout = get_row_layout();
          @endphp
            @include( "partials.page-builder.{$layout}")
          @php
        endwhile;
      elseif ( get_the_content() ) :
      endif;
    @endphp      

    @if($lead_gen)
      <div class="lead-gen-form">
        <div class="form-content">
          <h5>Download a printable copy of this summary</h5>
          {!! do_shortcode('[hf_form slug="lead-generator"]') !!}
        </div>
      </div>


      <script>
        function getLastPartOfCurrentUrl() { 
          // Get the current page URL
          const url = window.location.href;
          // Remove query parameters and fragment identifier
          const cleanedUrl = url.split('?')[0].split('#')[0];
          // Get the last part of the URL path
          const parts = cleanedUrl.split('/');
          const lastPart = parts.pop() || parts.pop(); // Handle potential trailing slash
          return lastPart;
        }
        document.addEventListener('DOMContentLoaded', function() {
          const hiddenField = document.getElementById('field_53fdd05');
            if (hiddenField) {
                hiddenField.value = getLastPartOfCurrentUrl();
            }
        });
      </script>
    @endif


    <a class="back" href="/insights"><- Back to Insights</a>
  </section>
</article>



