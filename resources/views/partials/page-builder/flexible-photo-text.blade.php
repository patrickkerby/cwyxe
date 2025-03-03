@php
  $border = get_sub_field('following-border');
@endphp

<section class="flexible-content {{ $border ? 'border-follow' : '' }}">
    <div class="photo-text @sub('photo_alignment')">
      @if( get_row_layout() == 'flexible-photo-text' )
        <img src="@sub('image', 'url')" alt="@sub('image', 'alt')">
        <div class="inner-content">
            @group('feature-content')
                <h3>@sub('feature_title')</h3>
                <p>@sub('feature_text')</p>
                @hassub('button')
                    <a href="@sub('button', 'url')" class="button">@sub('button_text')</a>
                @endsub
            @endgroup
        </div>
      @endif
    </div>
  </section>
  