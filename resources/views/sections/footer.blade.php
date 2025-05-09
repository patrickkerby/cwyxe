<footer class="global-footer">
  <div>
    <p>@option('footer') </p>
    <a class="icon" href="mailto: @option('primary_email')"><img src="@asset('images/icons/email.svg')" /></a>
    <a class="icon" target="_blank" href="@option('linked_in')"><img src="@asset('images/icons/linkedin.svg')" /></a>
  </div>
  <div class="footer-menu">
    <nav>
      <h5>Links</h5>
      @if (has_nav_menu('footer_navigation1'))
        <nav class="nav-footer" aria-label="{{ wp_get_nav_menu_name('footer_navigation1') }}">
          {!! wp_nav_menu(['theme_location' => 'footer_navigation1', 'menu_class' => 'nav', 'echo' => false]) !!}
        </nav>
      @endif
    </nav>
    <nav>
      <h5>Our Services</h5>
      @if (has_nav_menu('footer_navigation2'))
        <nav class="nav-footer" aria-label="{{ wp_get_nav_menu_name('footer_navigation2') }}">
          {!! wp_nav_menu(['theme_location' => 'footer_navigation2', 'menu_class' => 'nav', 'echo' => false]) !!}
        </nav>
      @endif
    </nav>
  </div>
  <span class="copyright">
    <p>Copyright ⓒ {{ date('Y') }} Cushman & Wakefield Saskatoon, Ltd. | Independently Owned and Operated | All rights reserved</p>
    <p><a href="/privacy-policy">Privacy Policy</a> | <a href="/sitemap_index.xml">Site Map</a></p>
  </span>
</footer>
