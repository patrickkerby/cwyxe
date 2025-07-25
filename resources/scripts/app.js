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

    $('.insights-mega').hover(function() {
      $(this).addClass('is-active');
      $('.insights-mega-content').addClass('is-active');
    });

    $('.insights-mega-content').mouseleave(function() {
      $('.insights-mega-content').removeClass('is-active');
      $('.insights-mega').removeClass('is-active');
    });

    $('.reports-mega').hover(function() {
      $(this).toggleClass('is-active');
      $('.reports-mega-content').addClass('is-active');
    });

    $('.reports-mega').mouseleave(function() {
      $('.reports-mega-content').removeClass('is-active');
      $('.reports-mega').removeClass('is-active');
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
