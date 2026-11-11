<div class="infowindow">
  @if($image_url)
    <a href="{{ $link }}">
      <img src="{{ $image_url }}" alt="{{ $image_alt }}">
    </a>
  @endif

  @if($status_line)
    <span class="availability">{{ $status_line }}</span>
  @endif

  <h3>
    <a href="{{ $link }}" title="{{ $title }}">{{ $title }}</a>
  </h3>

  @if($address)
    <p>{{ $address }}</p>
  @endif

  @if($price)
    <span class="price">{{ $price }}</span>
  @endif
</div>
