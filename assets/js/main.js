// ==================================================
// Project Name   :  Cafenod - HTML5 Template
// File           :  CSS Base
// Version        :  1.0.0
// Last change    :  27 March 2021
// Author         :  Xpressrow
// ==================================================

(function($) {
  "use strict";

  // back to top - start
  // --------------------------------------------------
  $(window).scroll(function() {
    if ($(this).scrollTop() > 200) {
      $('.backtotop:hidden').stop(true, true).fadeIn();
    } else {
      $('.backtotop').stop(true, true).fadeOut();
    }
  });
  $(function() {
    $(".scroll").on('click', function() {
      $("html,body").animate({scrollTop: 0}, "slow");
      return false
    });
  });
  // back to top - end
  // --------------------------------------------------

  // preloader - start
  // --------------------------------------------------
  $(window).on('load', function(){
    $('#preloader').fadeOut('slow',function(){$(this).remove();});
  });
  // preloader - end
  // --------------------------------------------------

  // sticky header - start
  // --------------------------------------------------
  $(window).on('scroll', function () {
    if ($(this).scrollTop() > 120) {
      $('.header_section').addClass("sticky")
    } else {
      $('.header_section').removeClass("sticky")
    }
  });
  // sticky header - end
  // --------------------------------------------------

  // main search btn - start
  // --------------------------------------------------
  $('.main_search_btn').on('click', function() {
    $('.main_search_btn > i').toggleClass('fa-times');
  });
  // main search btn - end
  // --------------------------------------------------

  // menu button - start
  // --------------------------------------------------
  $(document).ready(function () {
    $('.close_btn, .cart_sidebar_overlay').on('click', function () {
      $('.cart_sidebar').removeClass('active');
      $('.cart_sidebar_overlay').removeClass('active');
    });

    $('.cart_btn').on('click', function () {
      $('.cart_sidebar').addClass('active');
      $('.cart_sidebar_overlay').addClass('active');
    });
  });
  // menu button - end
  // --------------------------------------------------

  // wow js - start
  // --------------------------------------------------
  var wow = new WOW({
    animateClass: 'animated',
    offset: 100,
    mobile: true,
    duration: 1000,
  });
  wow.init();
  // wow js - end
  // --------------------------------------------------

  // popup images & videos - start
  // --------------------------------------------------
  $('.popup_video').magnificPopup({
    type: 'iframe',
    preloader: false,
    removalDelay: 160,
    mainClass: 'mfp-fade',
    fixedContentPos: false
  });

  $('.zoom-gallery').magnificPopup({
    delegate: '.popup_image',
    type: 'image',
    closeOnContentClick: false,
    closeBtnInside: false,
    mainClass: 'mfp-with-zoom mfp-img-mobile',
    gallery: {
      enabled: true
    },
    zoom: {
      enabled: true,
      duration: 300,
      opener: function(element) {
        return element.find('img');
      }
    }
    
  });
  // popup images & videos - end
  // --------------------------------------------------

  // main slider - start
  // --------------------------------------------------
  $('.main_slider').slick({
    dots: true,
    fade: true,
    arrows: true,
    infinite: true,
    autoplay: true,
    slidesToShow: 1,
    autoplaySpeed: 6000,
    prevArrow: ".main_left_arrow",
    nextArrow: ".main_right_arrow"
  });

  $('.main_slider').on('init', function (e, slick) {
    var $firstAnimatingElements = $('div.slider_item:first-child').find('[data-animation]');
    doAnimations($firstAnimatingElements);
  });
  $('.main_slider').on('beforeChange', function (e, slick, currentSlide, nextSlide) {
    var $animatingElements = $('div.slider_item[data-slick-index="' + nextSlide + '"]').find('[data-animation]');
    doAnimations($animatingElements);
  });
  var slideCount = null;

  $('.main_slider').on('init', function (event, slick) {
    slideCount = slick.slideCount;
    setSlideCount();
    setCurrentSlideNumber(slick.currentSlide);
  });
  $('.main_slider').on('beforeChange', function (event, slick, currentSlide, nextSlide) {
    setCurrentSlideNumber(nextSlide);
  });

  function setSlideCount() {
    var $el = $('.slide_count_wrap').find('.total');
    if (slideCount < 10) {
      $el.text('0' + slideCount);
    } else {
      $el.text(slideCount);
    }
  }

  function setCurrentSlideNumber(currentSlide) {
    var $el = $('.slide_count_wrap').find('.current');
    $el.text(currentSlide + 1);
  }

  function doAnimations(elements) {
    var animationEndEvents = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
    elements.each(function () {
      var $this = $(this);
      var $animationDelay = $this.data('delay');
      var $animationType = 'animated ' + $this.data('animation');
      $this.css({
        'animation-delay': $animationDelay,
        '-webkit-animation-delay': $animationDelay
      });
      $this.addClass($animationType).one(animationEndEvents, function () {
        $this.removeClass($animationType);
      });
    });
  }

  var $timer = 6000;
  function progressBar() {
    $(".slick-progress").find("span").removeAttr("style");
    $(".slick-progress").find("span").removeClass("active");
    setTimeout(function () {
      $(".slick-progress").find("span").css("transition-duration", $timer / 1000 + "s").addClass("active");
    }, 100);
  }

  progressBar();
  $('.main_slider').on("beforeChange", function (e, slick) {
    progressBar();
  });
  // main slider - end
  // --------------------------------------------------

  // isotope filter - start
  // --------------------------------------------------
  var $grid = $(".grid").isotope({
    itemSelector: ".element-item",
    layoutMode: "fitRows"
  });

  var filterFns = {

    numberGreaterThan50: function () {
      var number = $(this).find(".number").text();
      return parseInt(number, 10) > 50;
    },

    ium: function () {
      var name = $(this).find(".name").text();
      return name.match(/ium$/);
    }
  };

  $(".filters-button-group").on("click", "button", function () {
    var filterValue = $(this).attr("data-filter");

    filterValue = filterFns[filterValue] || filterValue;
    $grid.isotope({ filter: filterValue });
  });

  $(".filters-button-group").each(function (i, buttonGroup) {
    var $buttonGroup = $(buttonGroup);
    $buttonGroup.on("click", "button", function () {
      $buttonGroup.find(".active").removeClass("active");
      $(this).addClass("active");
    });
  });
  // isotope filter - end
  // --------------------------------------------------

  // testimonial slider - start
  // --------------------------------------------------
  $('.testimonial_slider').slick({
    dots: false,
    speed: 1000,
    arrows: false,
    autoplay: true,
    slidesToShow: 1,
    slidesToScroll: 1,
    autoplaySpeed: 4000,
  });
  // testimonial slider - end
  // --------------------------------------------------

  // google map - start
  // --------------------------------------------------
  if ( $('#mapBox').length ){
    var $lat = $('#mapBox').data('lat');
    var $lon = $('#mapBox').data('lon');
    var $zoom = $('#mapBox').data('zoom');
    var $marker = $('#mapBox').data('marker');
    var $info = $('#mapBox').data('info');
    var $markerLat = $('#mapBox').data('mlat');
    var $markerLon = $('#mapBox').data('mlon');
    var map = new GMaps({
      el: '#mapBox',
      lat: $lat,
      lng: $lon,
      scrollwheel: false,
      scaleControl: true,
      streetViewControl: false,
      panControl: true,
      disableDoubleClickZoom: true,
      mapTypeControl: false,
      zoom: $zoom,
    });
    map.addMarker({
      lat: $markerLat,
      lng: $markerLon,
      icon: $marker,    
      infoWindow: {
        content: $info
      }
    })
  }
  // google map - end
  // --------------------------------------------------


  // price range - start
  // --------------------------------------------------
  if($("#slider-range").length){
    $( "#slider-range" ).slider({
      range: true,
      min: 0,
      max: 10000,
      values: [ 0, 4000.00 ],
      slide: function( event, ui ) {
        $( "#amount" ).val( "$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ] );
      }
    });
    $( "#amount" ).val( "$" + $( "#slider-range" ).slider( "values", 0 ) +
      " - $" + $( "#slider-range" ).slider( "values", 1 ) );
  }

  $('.ar_top').on('click', function () {
    var getID = $(this).next().attr('id');
    var result = document.getElementById(getID);
    var qty = result.value;
    $('.proceed_to_checkout .update-cart').removeAttr('disabled');
    if( !isNaN( qty ) ) {
      result.value++;
    }else{
      return false;
    }
  });
  // price range - end
  // --------------------------------------------------

  // price range - start
  // --------------------------------------------------
  $('.details_image_carousel').slick({
    dots: false,
    arrows: false,
    slidesToShow: 1,
    slidesToScroll: 1,
    asNavFor: '.details_image_carousel_nav'
  });
  $('.details_image_carousel_nav').slick({
    dots: false,
    arrows: false,
    vertical: true,
    slidesToShow: 5,
    slidesToScroll: 1,
    focusOnSelect: true,
    verticalSwiping: true,
    asNavFor: '.details_image_carousel',
    responsive: [
    {
      breakpoint: 992,
      settings: {
        slidesToShow: 5,
      }
    },
    {
      breakpoint: 768,
      settings: {
        slidesToShow: 5,
      }
    },
    {
      breakpoint: 580,
      settings: {
        slidesToShow: 5,
      }
    },
    {
      breakpoint: 380,
      settings: {
        slidesToShow: 5,
      }
    }
    ]
  });
  // price range - end
  // --------------------------------------------------

  // quantity - start
  // --------------------------------------------------
  (function() {
    window.inputNumber = function(el) {
      var min = el.attr("min") || false;
      var max = el.attr("max") || false;

      var els = {};

      els.dec = el.prev();
      els.inc = el.next();

      el.each(function() {
        init($(this));
      });

      function init(el) {
        els.dec.on("click", decrement);
        els.inc.on("click", increment);

        function decrement() {
          var value = el[0].value;
          value--;
          if (!min || value >= min) {
            el[0].value = value;
          }
        }

        function increment() {
          var value = el[0].value;
          value++;
          if (!max || value <= max) {
            el[0].value = value++;
          }
        }
      }
    };
  })();
  inputNumber($(".input_number"));
  // quantity - end
  // --------------------------------------------------

})(jQuery);