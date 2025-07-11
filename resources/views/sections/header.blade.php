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

<div class="insights-mega-content insights-mega mega mega-content">
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
  <div>
    <h3>Saskatoon Marketbeat Reports</h3>
    <ul class="mega-list">
      @foreach (get_posts(['post_type' => 'post', 'category_name' => 'saskatchewan-research', 'posts_per_page' => 2]) as $post)
        <li>
          <a href="{{ get_permalink($post) }}">
            {{ get_the_title($post) }}
          </a>
          <span class="post-date">{{ get_the_date('', $post) }}</span>
        </li>
      @endforeach
    </ul>
    <a href="/insights/?_insights_topics=saskatchewan-research" class="menu-cta">See Archive</a>
  </div>
  <div>
    <h3>National Market Reports</h3>
    <ul class="mega-list">
      @foreach (get_posts(['post_type' => 'post', 'category_name' => 'canadian-research', 'posts_per_page' => 2]) as $post)
        <li>
          <a href="{{ get_permalink($post) }}">
            {{ get_the_title($post) }}
          </a>
          <span class="post-date">{{ get_the_date('', $post) }}</span>
        </li>
      @endforeach
    </ul>
    <a href="/insights/?_insights_topics=canadian-research" class="menu-cta">See Archive</a>
  </div>
  <div class="cta">
    <h3>Access our full Saskatoon<br> MartketBeat Reports</h3>
    <p>Go beyond the free summaries to get annual access to our full market research <br>
      <strong>Get all 12 reports for $850/year</strong>
    </p>
    <div class="buttons">
      <a href="/subscribe" class="button">Subscribe Now</a>
      {{-- @shortcode('[swpm_payment_button id="27292" class="button" button_text="Pay Now"]') --}}
      <a href="/insights/pro-marketbeat-reports/">Learn More</a>
    </div>
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