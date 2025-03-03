@php
  $content = get_sub_field('content');
  $border = get_sub_field('following-border');
@endphp

<section class="flexible-content {{ $border ? 'border-follow' : '' }} @hassub('vertical_padding')extrapadding-@sub('vertical_padding') @endsub">
  <div class="wysiwyg columns-@sub('columns')">    
    {!! $content !!}
  </div>
</section>