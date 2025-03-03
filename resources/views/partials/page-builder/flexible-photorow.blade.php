<section class="flexible-content @hassub('vertical_padding')extrapadding-@sub('vertical_padding') @endsub">
  <div class="photo-row @foreach(get_sub_field('photos') as $photo)@set($count, $loop->count)@endforeach count-{{ $count }}">
    @if( get_row_layout() == 'flexible-photorow' )
        @foreach(get_sub_field('photos') as $photo)
            <img src="{{ $photo['url'] }}" alt="{{ $photo['alt'] }}">
        @endforeach
    @endif
  </div>
</section>
