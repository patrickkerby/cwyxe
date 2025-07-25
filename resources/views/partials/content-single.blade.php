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
      {{-- <form id="request_form" action="https://hook.us1.make.com/yyb8lpbnyq952cmwmf0ial4019bivyjh" method="POST">
        <label for="name">Name</label>
        <input id="name" name="name" required="" type="text" />
        <label for="company">Company</label>
        <input id="company" name="company" required="" type="text" />
        <label for="email">Email</label>
        <input id="email" name="email" required="" type="email" />
        <input id="field_53fdd05" name="page" type="hidden" />
        <div class="form-row">
          <input id="subscribe" name="subscribe" type="checkbox" value="yes" />
          <label for="subscribe">Yes, I would like to receive property listings, news, and market intelligence from Cushman &amp; Wakefield Saskatoon.</label>
        </div>
        <button type="submit">Download Summary Report</button>
      </form> --}}
      <div class="lead-gen-form">
        <div class="form-content">
          <h5>Download a printable copy of this summary</h5>
          {!! do_shortcode('[hf_form slug="lead-generator"]') !!}
        </div>
        <div class="cta">
          <h3>Access our full Saskatoon<br> MartketBeat Reports</h3>
          <p>Go beyond the free summaries to get annual access to our full market research <br>
            <strong>Get all 12 reports for $850/year</strong>
          </p>
          <div class="buttons">
            {{-- <a href="/subscribe" class="button">Subscribe Now</a> --}}
            @shortcode('[swpm_payment_button id="27292" class="button" button_text="Subscribe Now"]')
            <a href="/insights/pro-marketbeat-reports/">Learn More</a>
          </div>
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



