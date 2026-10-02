import domReady from '@roots/sage/client/dom-ready';
import Siema from 'siema';

/**
 * Application entrypoint
 */
domReady(async () => {
  
  document.body.classList.add = "visible";
  document.body.classList.remove = "hidden";


  const tabs = document.querySelectorAll(".horizontal-tabs .tabs li");
  const sections = document.querySelectorAll(".horizontal-tabs .tab-content");

  tabs.forEach(tab => {
    tab.addEventListener("click", e => {
      e.preventDefault();
      removeActiveTab();
      addActiveTab(tab);
    });
  })

  const removeActiveTab = () => {
    tabs.forEach(tab => {
      tab.classList.remove("is-active");
    });
    sections.forEach(section => {
      section.classList.remove("is-active");
    });
  }

  const addActiveTab = tab => {
    tab.classList.add("is-active");
    const href = tab.querySelector("a").getAttribute("href");
    const matchingSection = document.querySelector(href);
    matchingSection.classList.add("is-active");
  }

  const enhanceSelects = () => {
    if (typeof window.fSelect !== 'function') {
      return;
    }

    document.querySelectorAll('select:not([multiple]):not(.fs-hidden):not(.facetwp-dropdown)').forEach((el) => {
      if (el.fselect || el._rel || el.closest('.fs-wrap') || !el.offsetParent) {
        return;
      }

      window.fSelect(el, { showSearch: false });
    });
  };

  const startEnhance = (attempt = 0) => {
    if (typeof window.fSelect === 'function') {
      enhanceSelects();
      return;
    }

    if (attempt < 20) {
      setTimeout(() => startEnhance(attempt + 1), 50);
    }
  };

  startEnhance();
  document.addEventListener('facetwp-loaded', enhanceSelects);

  (function($) {
    $(document).on('facetwp-loaded', function() {
      if ( ! FWP.loaded ) { // Run on the initial page load only
        if ( '' != FWP.buildQueryString() ) { // Run only when there are facet selections in the URL

          // Do something. 
          // For example a scroll to the top of the results listing:
          $('html, body').animate({
            scrollTop: $('.properties-container').offset().top
          }, 500);
        }
      }
    });

      // JavaScript to be fired on all pages
    $('.hamb').click(function() {
      $(this).toggleClass('is-active');
      $('.nav-mobile').toggleClass('is-active');
      $('body').toggleClass('is-active');
    });

    const closeMega = (name) => {
      $('.' + name).removeClass('is-active');
      $('.' + name + '-content').removeClass('is-active');
    };

    const bindDesktopMegaHover = (name) => {
      const $trigger = $('.nav-desktop .' + name).not('.mega-content');
      const $panel = $('.' + name + '-content');

      if (!$trigger.length || !$panel.length) {
        return;
      }

      let closeTimer;

      const cancelClose = () => window.clearTimeout(closeTimer);
      const scheduleClose = () => {
        cancelClose();
        closeTimer = window.setTimeout(() => closeMega(name), 100);
      };

      $trigger.on('mouseenter', function() {
        cancelClose();
        if (name !== 'insights-mega') {
          closeMega('insights-mega');
        }
        if (name !== 'reports-mega') {
          closeMega('reports-mega');
        }
        $trigger.addClass('is-active');
        $panel.addClass('is-active');
      });

      $trigger.on('mouseleave', function(event) {
        if ($panel.is(event.relatedTarget) || $panel.has(event.relatedTarget).length) {
          return;
        }
        scheduleClose();
      });

      $panel.on('mouseenter', cancelClose);
      $panel.on('mouseleave', function(event) {
        if ($trigger.is(event.relatedTarget) || $trigger.has(event.relatedTarget).length) {
          return;
        }
        scheduleClose();
      });
    };

    bindDesktopMegaHover('insights-mega');
    bindDesktopMegaHover('reports-mega');

    $('.nav-desktop .nav > li').on('mouseenter', function() {
      if (!$(this).hasClass('insights-mega')) {
        closeMega('insights-mega');
      }
      if (!$(this).hasClass('reports-mega')) {
        closeMega('reports-mega');
      }
    });

    $('.nav-mobile .insights-mega').click(function() {
      $(this).toggleClass('is-active');
      $('.insights-mega-content').addClass('is-active');
    });

   $('.nav-mobile .reports-mega').click(function() {
      $(this).toggleClass('is-active');
      $('.reports-mega-content').addClass('is-active');
    });

    $('.nav-mobile .menu-item-has-children').click(function() {
      $(this).toggleClass('is-active');
    });

    $('.close-arrow').click(function() {
      $('.reports-mega-content').removeClass('is-active');
      $('.reports-mega').removeClass('is-active');
      $('.insights-mega-content').removeClass('is-active');
      $('.insights-mega').removeClass('is-active');
    });

    // Add class to body when scrolling to add white background
    var targetDiv = $('body');

    $(window).scroll(function() {

         var windowpos = $(window).scrollTop();

   // change amount here to choose distance from top to add class
         if( windowpos >= 1 ) {
              targetDiv.addClass('scrolling-active');
         } else {
              targetDiv.removeClass('scrolling-active');
         }

    });

    // Listing carousel, using Siema library (https://pawelgrzybek.github.io/siema/#installation)
  const mySiema = new Siema();
  const prev = document.querySelector('.prev');
  const next = document.querySelector('.next');
  
  prev.addEventListener('click', () => mySiema.prev(1));
  next.addEventListener('click', () => mySiema.next(1));
  
  var slide = document.querySelector('.siema .siema-slide');
if(slide) document.querySelector('.siema').style.maxWidth = slide.clientWidth + 'px';

  })(jQuery);

  

});

/**
 * @see {@link https://webpack.js.org/api/hot-module-replacement/}
 */
import.meta.webpackHot?.accept(console.error);
