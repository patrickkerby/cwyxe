@hasfields('homepage_ctas')
    @fields('homepage_ctas')
    <section class="cta">
        <div class="content">
            <h3>@sub('cta_title') - {{ $count }}</h3>
            @hassub('cta_text')
                <p>@sub('cta_text')</p>
            @endsub
        </div>
        @hassub('cta_button_link')
        <a href="@sub('cta_button_link', 'url')" class="button">@sub('cta_button_link', 'title')</a>      
        @endsub
        @hassub('cta_image')
        <img src="@sub('cta_image', 'url')" alt="@sub('cta_image', 'alt')">
        @endsub
    </section>
    @endfields
@endhasfields