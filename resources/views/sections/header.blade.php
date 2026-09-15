<section class="banner-container">
  <header class="banner">
    <a class="brand" href="{{ home_url('/') }}">
      @if(!empty($office['logo_url']))
        <img alt="{{ $siteName }}" src="{{ $office['logo_url'] }}">
      @else
        <img alt="{{ $siteName }}" src="@asset('images/cw-saskatoon-logo-small.png')">
      @endif
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

<div class="insights-mega-content insights-mega mega mega-content">
  <span class="close-arrow"></span>
    <div>
      <h3>Latest Insights</h3>
      <ul class="mega-list">
        @foreach (get_posts(['post_type' => 'post', 'tag' => 'insights', 'posts_per_page' => 2]) as $post)
          <li>
            <a href="{{ get_permalink($post) }}">
              {{ get_the_title($post) }}
            </a>
            <span class="post-date">{{ get_the_date('', $post) }}</span>
          </li>
        @endforeach
      </ul>
      <a href="/insights/?_insights_types=insights" class="menu-cta">Access all the latest Insights</a>
    </div>
    
    <div>
      <h3>Latest News</h3>
      <ul class="mega-list">
        @foreach (get_posts(['post_type' => 'post', 'tag' => 'news', 'posts_per_page' => 2]) as $post)
          <li>
            <a href="{{ get_permalink($post) }}">
              {{ get_the_title($post) }}
            </a>
            <span class="post-date">{{ get_the_date('', $post) }}</span>
          </li>
        @endforeach
      </ul>
      <a href="/insights/?_insights_types=news" class="menu-cta">Access all latest News</a>
    </div>
</div>
<div class="reports-mega-content reports-mega mega mega-content">
  <span class="close-arrow"></span>
  <div>
    <h3>{{ $office['marketbeat_heading'] }}</h3>
    <ul class="mega-list">
      @foreach (get_posts(['post_type' => 'post', 'category_name' => $office['local_research_category'], 'posts_per_page' => 2]) as $post)
        <li>
          <a href="{{ get_permalink($post) }}">
            {{ get_the_title($post) }}
          </a>
          <span class="post-date">{{ get_the_date('', $post) }}</span>
        </li>
      @endforeach
    </ul>
    <a href="/insights/?_insights_topics={{ $office['local_research_category'] }}" class="menu-cta">See Archive</a>
  </div>
  <div>
    <h3>National Market Reports</h3>
    <ul class="mega-list">
      @foreach (get_posts(['post_type' => 'post', 'category_name' => $office['national_research_category'], 'posts_per_page' => 2]) as $post)
        <li>
          <a href="{{ get_permalink($post) }}">
            {{ get_the_title($post) }}
          </a>
          <span class="post-date">{{ get_the_date('', $post) }}</span>
        </li>
      @endforeach
    </ul>
    <a href="/insights/?_insights_topics={{ $office['national_research_category'] }}" class="menu-cta">See Archive</a>
  </div>
</div>

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
        @if(is_single())
          <span class="meta">@include('partials.entry-meta')</span>
        @endif
      </div>
      @hasfield('background_image')
        <img src="@field('background_image', 'url')" alt="">
      @endfield    
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