@php
  $brokerAttributes = \App\Support\Terms::forPost($post->ID, 'broker-attribute');
@endphp

@if($brokerAttributes)
<ul class="broker-attributes pills">
  @foreach ($brokerAttributes as $item)
    @set($color, get_field('colour', $item))
    
    <style>
      ul.pills li.{{ $item->slug }} a:hover { background: {{ $color }} !important; }
    </style>
    <li class="{{ $item->slug }}">
      <a style="color: {{ $color }}; border-color: {{ $color }};" href="#">{!! $item->name !!}</a>
    </li>
  @endforeach
</ul>
@endif
