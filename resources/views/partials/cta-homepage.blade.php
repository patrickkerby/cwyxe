@hasfields('homepage_ctas')
    @php
        $ctas = get_field('homepage_ctas');
    @endphp

    @if($count == 'first')
        @foreach($ctas as $cta)
            @if($loop->iteration == 1 || $loop->iteration == 2)
                <section class="cta">
                    <div class="content">
                        <h3>{{ $cta['cta_title'] }}</h3>
                        <p>{{ $cta['cta_text'] }}</p>
                    </div>
                    <a href="{{ $cta['cta_button_link']['url'] }}" class="button">{{ $cta['cta_button_link']['title'] }}</a>      
                    @if($cta['related_post_label'])
                        <div class="content related_post">
                            <p>{{ $cta['related_post_label'] }}</p>
                            @php
                                $post = get_post( get_sub_field('related_post') );
                                $title = $post->post_title;
                                $url = get_permalink( $post->ID );
                            @endphp
                            @if($post)
                                <a href="{{ $url }}" class="button">{{ $title }}</a>
                            @endif
                        </div>
                    @endif
                    <img src="{{ $cta['cta_image']['url'] }}" alt="{{ $cta['cta_image']['alt'] }}">
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
                    </div>
                    <a href="{{ $cta['cta_button_link']['url'] }}" class="button">{{ $cta['cta_button_link']['title'] }}</a>      
                    @if($cta['related_post_label'])
                        <div class="content related_post">
                            <p>{{ $cta['related_post_label'] }}</p>
                            @php
                                $post = get_post( get_sub_field('related_post') );
                                $title = $post->post_title;
                                $url = get_permalink( $post->ID );
                            @endphp
                            @if($post)
                                <a href="{{ $url }}" class="button">{{ $title }}</a>
                            @endif
                        </div>
                    @endif
                    <img src="{{ $cta['cta_image']['url'] }}" alt="{{ $cta['cta_image']['alt'] }}">
                </section>
            @endif
        @endforeach
    @endif

@endhasfields
