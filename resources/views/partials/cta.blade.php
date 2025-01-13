@hasfield('cta_title')
<section class="cta">
  <div class="content">
    <h3>@field('cta_title')</h3>
      @hasfield('cta_text')
        <p>@field('cta_text')</p>
      @endfield
    </div>
    @hasfield('cta_button_link')
      <a href="@field('cta_button_link', 'url')" class="button">@field('cta_button_link', 'title')</a>      
    @endfield
    @hasfield('cta_image')
      <img src="@field('cta_image', 'url')" alt="@field('cta_image', 'alt')">
    @endfield
</section>
@endfield