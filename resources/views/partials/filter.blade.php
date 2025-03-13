@if(is_page('property-search'))
    <div class="card">
        <h5>Property Status</h5>
        {!! facetwp_display( 'facet', 'status' ) !!}
    </div>
    <div class="card">
        <h5>Property Type</h5>
        {!! facetwp_display( 'facet', 'property_type' ) !!}
    </div>
    <div class="card">
        <h5>Available Space Unit</h5>
        {!! facetwp_display( 'facet', 'available_space_postfix' ) !!}
    </div>
    <div class="card">
        <h5>Available Space</h5>
        {!! facetwp_display( 'facet', 'available_space_filter' ) !!}
    </div>
    <div class="card">
        <h5>Broker</h5>
        {!! facetwp_display( 'facet', 'broker' ) !!}
    </div>
    {!! facetwp_display( 'facet', 'reset' ) !!}
            
    <div style="display: none;">
        {!! facetwp_display( 'facet', 'keyword_homepage' ) !!}
        {!! facetwp_display( 'facet', 'city_town' ) !!}
        {!! facetwp_display( 'facet', 'property_status_homepage' ) !!}
        {!! facetwp_display( 'facet', 'property_type_homepage' ) !!}
        {!! facetwp_display( 'facet', 'min_area_homepage' ) !!}
    </div>

@endif

@if(is_page('insights'))
    <div class="card">
        <h5>Topics</h5>
        {!! facetwp_display( 'facet', 'insights_topics' ) !!}
    </div>
    <div class="card">
        <h5>Types</h5>
        {!! facetwp_display( 'facet', 'insights_types' ) !!}
    </div>
    {!! facetwp_display( 'facet', 'reset' ) !!}

@endif

