@if(is_page('property-search') || is_singular('agent') || is_singular('property'))
    <div class="property card">
        <div class="image">
            <img src="@field('primary_image', 'url')" alt="@field('primary_image', 'alt')">
        </div>
        <div class="content">    
            @foreach ($status_terms as $status)
                @php
                    $status_color = get_field('property_status_colour', 'term_'.$status->term_id);
                    $map = get_field('map');
                @endphp
                <span class="property_type status" style="background-color:{{$status_color}}">{{ $status->name }}</span>
            @endforeach
            <span class="availability">For @field('availability') • @term('property-type')</span>                    
            <h3><a href="@permalink">@title</a></h3>
            <p>@field('address')</p>            

            @hasfields('rates')
                @group('rates')                        
                    @if(get_sub_field('negotiable') == TRUE)
                        @set($negotiable, '(negotiable)')
                    @else
                        @set($negotiable, '')
                    @endif
                    <span class="price">For @sub('rate_type'): @hassub('amount')$@sub('amount') @sub('rate_postfix')@endsub {{ $negotiable }}</span>
                @endgroup 
            @endhasfields


        </div>
    </div>
@else
    <div class="property card">
        <div class="image">
            <img src="{{ $property['primary_image']['url']}}" alt="{{ $property['primary_image']['url']}}">
        </div>
        <div class="content">
            @foreach($property['property_status'] as $status)
                <span class="property_type status" style="background-color:{{ $property['property_status_color'] }}">{{ $status->name }}</span>
            @endforeach
            <span class="availability">
                For {{$property['availability']}} • 
                @foreach ($property['property_type'] as $type)
                    {{$type->name}}
                @endforeach
            </span>
            <h3><a href="{{ $property['link']}}">{{ $property['name']}}</a></h3>
            <p>{{ $property['address']}}</p>
            <span class="price">Lease price: ${{ $property['price']}}</span>
        </div>
    </div>
@endif