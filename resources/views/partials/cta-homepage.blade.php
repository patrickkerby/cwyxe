@hasfields('homepage_ctas')
    @php
        $ctas = get_field('homepage_ctas') ?: [];
        if (! is_array($ctas)) {
            $ctas = [];
        }
    @endphp

    @if($count == 'first')
        @foreach($ctas as $cta)
            @if($loop->iteration == 1 || $loop->iteration == 2)
                <section class="cta">
                    <div class="content">
                        <h3>{{ $cta['cta_title'] }}</h3>
                        <p>{{ $cta['cta_text'] }}</p>
        @if(!empty($cta['cta_button_link']['url']))
                        <a href="{{ $cta['cta_button_link']['url'] }}" class="button">{{ $cta['cta_button_link']['title'] ?? '' }}</a>
                        @endif
                        @if($cta['related_post_label'])
                            <div class="content related_post">
                                <p>{{ $cta['related_post_label'] }}</p>
                                @php
                                    $related = $cta['related_post'] ?? null;
                                    $url = $related ? get_permalink( $related->ID ) : '';
                                @endphp
                                @if($related)
                                    <a href="{{ $url }}" class="button">{{ $related->post_title }}</a>
                                @endif
                            </div>
                        @endif
                    </div>
                    @if(!empty($cta['cta_image']['url']))
                    <img src="{{ $cta['cta_image']['url'] }}" alt="{{ $cta['cta_image']['alt'] ?? '' }}">
                    @endif
                </section>
            @endif
        @endforeach

    @elseif($count == 'second')
        @foreach($ctas as $cta)
            @if($loop->iteration == 3)
            <section class="cta">
                <div class="content">
                    <h3>{{ $cta['cta_title'] }}</h3>
                    <p>{{ $cta['cta_text'] }}</p>
                    @if(!empty($cta['cta_button_link']['url']))
                    <a href="{{ $cta['cta_button_link']['url'] }}" class="button">{{ $cta['cta_button_link']['title'] ?? '' }}</a>
                    @endif 
                    @if($cta['related_post_label'])
                        <div class="content related_post">
                            <p>{{ $cta['related_post_label'] }}</p>
                            @php
                                $related = $cta['related_post'] ?? null;
                                $url = $related ? get_permalink( $related->ID ) : '';
                            @endphp
                            @if($related)
                                <a href="{{ $url }}" class="button">{{ $related->post_title }}</a>
                            @endif
                        </div>
                    @endif
                </div>
                @if(!empty($cta['cta_image']['url']))
                <img src="{{ $cta['cta_image']['url'] }}" alt="{{ $cta['cta_image']['alt'] ?? '' }}">
                @endif
            </section>
            @endif
        @endforeach
    @endif

@endhasfields
