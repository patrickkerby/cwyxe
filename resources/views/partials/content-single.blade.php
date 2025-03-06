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

  <form id="request_form" action="https://hook.us1.make.com/p0r1wtndfmnd8qkkin7eo4l545qng2bc" method="POST">
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


