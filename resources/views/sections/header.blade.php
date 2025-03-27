<section class="banner-container">
  <header class="banner">
    <a class="brand" href="{{ home_url('/') }}">
      <img alt="{{ $siteName }}" src="@asset('images/cw-saskatoon-logo-small.png')">
    </a>

 {{-- Mobile Navigation --}}
 <nav class="nav-mobile">
  <input type="checkbox" id="side-menu-input"/>
  <label class="hamb" for="side-menu-input">
    <span class="hamb-line"></span>
    <span class="nav-title">Menu</span>
  </label>
  <nav role="navigation">
    @if (has_nav_menu('primary_navigation'))
      {!! wp_nav_menu(['theme_location' => 'primary_navigation', 'menu_class' => 'nav']) !!}
    @endif
  </nav>
</nav>

{{-- Desktop Navigation --}}
<nav class="nav-desktop" role="navigation">
    @if (has_nav_menu('primary_navigation'))
      <nav class="nav-primary nav-desktop" aria-label="{{ wp_get_nav_menu_name('primary_navigation') }}">
        {!! wp_nav_menu(['theme_location' => 'primary_navigation', 'menu_class' => 'nav', 'echo' => false]) !!}
      </nav>
    @endif
  </header>
</section>

@if(is_singular('agent') || is_singular('property'))
<div class="breadcrumb">
  <x-breadcrumb />
</div>
@endif

@unless(is_singular('agent') || is_singular('property') || is_page('property-search'))
  <section class="page-header @if(!get_field('background_image')) no-bg @endif">
      <div class="breadcrumb">
        <x-breadcrumb />
      </div>
      <div class="header-content">
        @hasfield('eyebrow_headline')
          <span class="eyebrow">@field('eyebrow_headline')</span>
        @endfield
        @hasfield('primary_headline')
          <h1>
              @field('primary_headline')
          </h1>
          @else 
          <h1>
            @title
          </h1>
          @endfield
        @hasfield('secondary_headline')
          <h2>
            @field('secondary_headline')
          </h2>
          @endfield
        @hasfield('primary_link')
          <a href="@field('primary_link')" class="button">Learn More</a>
        @endfield
        @hasfield('description')
          <p class="description">@field('description')</p>
        @endfield
      </div>
      @hasfield('background_image')
        <img src="@field('background_image', 'url')" alt="">
      @endfield    
      @if(is_single())
        <span class="meta">@include('partials.entry-meta')</span>
      @endif
  </section>
@endunless

@if(is_page('property-search'))
<section class="page-header @if(!get_field('background_image')) @endif">
  <div class="header-content">
    <div class="breadcrumb">
      <x-breadcrumb />
    </div>
    <h1>Properties Search</h1>
    <div class="search card">
      {!! facetwp_display( 'facet', 'search' ) !!}
    </div>
  </div>
  @hasfield('background_image')
    <img src="@field('background_image', 'url')" alt="">
  @endfield
</section>
@endif