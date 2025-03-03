@php
  $content = get_sub_field('content');
  $border = get_sub_field('following-border');
@endphp

<section class="flexible-content {{ $border ? 'border-follow' : '' }} @hassub('vertical_padding')extrapadding-@sub('vertical_padding') @endsub">
  <div class="wysiwyg v2">
    @hassub('content_1col')
      <div class="columns-1">
        @sub('content_1col')
      </div>
    @endsub
    @hassub('content_2col_a')
      <div class="columns-2">
        @sub('content_2col_a')
      </div>
      <div class="columns-2">
        @sub('content_2col_b')
      </div>
    @endsub
    @hassub('content_3col_a')
      <div class="columns-3">
        @sub('content_3col_a')
      </div>
      <div class="columns-3">
        @sub('content_3col_b')
      </div>
      <div class="columns-3">
        @sub('content_3col_c')
      </div>
    @endsub
  </div>
</section>