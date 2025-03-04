@php
  $lead_gen = get_field('lead_gen_form');
@endphp

<article @php(post_class('h-entry'))>
  <section>    
    @php(the_content())
    <a class="btn-hollow grey back" href="/insights"><- Back to Insights</a>
  </section>
</article>

@if($lead_gen)

  <form class="cws-form" id="request_form" action="https://hook.us1.make.com/p0r1wtndfmnd8qkkin7eo4l545qng2bc" method="POST">
    <label class="cws-form-label" for="name">Name</label>
    <input id="name" class="cws-form-input" name="name" required="" type="text" />
    <label class="cws-form-label" for="company">Company</label>
    <input id="company" class="cws-form-input" name="company" required="" type="text" />
    <label class="cws-form-label" for="email">Email</label>
    <input id="email" class="cws-form-input" name="email" required="" type="email" />
    <input id="field_53fdd05" name="page" type="hidden" />
  <div class="form-row">
    <input id="subscribe" class="cws-form-checkbox" name="subscribe" type="checkbox" value="yes" />
    <label class="cws-form-checkbox-label" for="subscribe">Yes, I would like to receive property listings, news, and market intelligence from Cushman &amp; Wakefield Saskatoon.</label>
  </div>
  <button class="cws-form-button button" type="submit">Download Summary Report</button>
  </form>

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
        const hiddenField = document.getElementById('form-field-page_field');
        if (hiddenField) {
            hiddenField.value = getLastPartOfCurrentUrl();
        }
    });

  </script>
@endif


