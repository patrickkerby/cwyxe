@php
  $content = get_sub_field('content');
@endphp

<section class="row content-row justify-content-center">
  <div class="col-9 col-sm-8">
   <h1>test</h1>
    {!! $content !!}
  </div>
</section>