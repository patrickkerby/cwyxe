@php
  $border = get_sub_field('following-border');
@endphp

<section class="flexible-content {{ $border ? 'border-follow' : '' }}">
  <div class="full-width">
    @if( get_row_layout() == 'flexible-photo' )
        <img src="@sub('full_width_photo')" />
    @endif
  </div>
</section>
