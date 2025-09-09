@if(is_page('property-search') || is_singular('agent') || is_singular('property'))
     <div class="property card @group('general_settings')@hassub('featured_property')featured @endsub @endgroup">
        <div class="image">
            @if($availability_condition)
                @php
                    $char_count = mb_strlen($availability_condition[0]->name, "UTF-8");

                    if ($char_count < 10) {
                        $status_size = 'status-small';      
                    } else {
                        $status_size = 'status-large';
                    }
                @endphp
                <span class="status_banner {{ $status_size }}">{{ $availability_condition[0]->name }}</span>
            @endif

            @hasfield('primary_image')                
            <a href="@permalink"><img src="@field('primary_image', 'url')" alt="@field('primary_image', 'alt')"></a>
            @endfield
        </div>
        <div class="content">    
            @if($status_terms)            
                <div class="pills">
                @foreach ($status_terms as $status)
                    @php
                        $status_color = get_field('property_status_colour', 'term_'.$status->term_id);
                        $map = get_field('map');
                    @endphp
                    <span class="property_type status" style="background-color:{{$status_color}}">{{ $status->name }}</span>
                @endforeach
                </div>
            @endif
            <span class="availability">For @group('general_settings') @sub('availability')@endgroup • @term('property-type')</span>                    
            <h3><a href="@permalink">@title</a></h3>
            <p>@field('address')</p>            
            
            
                @hasfields('rates')
                    @group('rates')                        
                        @if(get_sub_field('negotiable') == TRUE)
                            @set($negotiable, '(negotiable)')
                        @else
                            @set($negotiable, '')
                        @endif
                        @php
                            $price = get_sub_field('amount');
                            $price_str = preg_replace('/(\d)(?=(?:\d{3})+$)/', '$1,', $price);    
                        @endphp
                        @if($availability_condition && ($availability_condition[0]->slug == 'leased' || $availability_condition[0]->slug == 'sold'))
                            {{-- Do nothing --}}
                        @else
                            <span class="price">For @sub('rate_type'): @hassub('amount')${{ $price_str }} @sub('rate_postfix')@endsub {{ $negotiable }}</span>
                        @endif
                    @endgroup 
                @endhasfields
        </div>
    </div>
@else
    <div class="property card">
        @if($property['availability_condition'])
                @php
                    $char_count = mb_strlen($property['availability_condition'][0]->name, "UTF-8");

                    if ($char_count < 10) {
                        $status_size = 'status-small';
                    } else {
                        $status_size = 'status-large';
                    }
                @endphp
                <span class="status_banner {{ $status_size }}">{{ $property['availability_condition'][0]->name }}</span>
            @endif
        <div class="image">
            @if($property['primary_image'])
            <a href="{{ $property['link']}}"><img src="{{ $property['primary_image']['url']}}" alt="{{ $property['primary_image']['url']}}"></a>
            @endif
        </div>
        <div class="content">
            <span class="pills">
            @foreach($property['property_status'] as $status)
                <span class="property_type status" style="background-color:{{ $property['property_status_color'] }}">{{ $status->name }}</span>
            @endforeach
            </span>
            <span class="availability">
                @if($property['availability'])
                    For {{$property['availability']}} • 
                @endif 
                @foreach ($property['property_type'] as $type)
                    @if(!$loop->first)                        
                        /
                    @endif
                {{$type->name}}
                @endforeach
            </span>
            <h3><a href="{{ $property['link']}}">{{ $property['name']}}</a></h3>
            <p>{{ $property['address']}}</p>
            @unless(!empty($property['availability_condition']) && $property['availability_condition'][0]->slug == 'leased' || !empty($property['availability_condition']) && $property['availability_condition'][0]->slug == 'sold')
                <span class="price">Lease price: ${{ $property['price']}}</span>
            @endunless
        </div>
    </div>
@endif
