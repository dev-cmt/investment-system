/**
 * InvestHub - Main JavaScript
 * Uses: jQuery, AOS, Swiper, Toastr, GLightbox
 */

$(document).ready(function () {

  /* -- PAGE LOADER -- */
  setTimeout(function () {
    $('#pageLoader').addClass('hide');
  }, 900);

  /* -- AOS INIT -- */
  if (typeof AOS !== 'undefined') {
    AOS.init({
      duration : 700,
      easing   : 'ease-out-cubic',
      once     : true,
      offset   : 60
    });
  }

  /* -- SWIPER INIT -- */
  if (typeof Swiper !== 'undefined') {
    new Swiper('.swiper-opportunity', {
      slidesPerView  : 1,
      spaceBetween   : 20,
      loop           : true,
      autoplay       : { delay: 4000, disableOnInteraction: false },
      pagination     : { el: '.swiper-pagination', clickable: true },
      breakpoints    : {
        576 : { slidesPerView: 1.2 },
        768 : { slidesPerView: 2   },
        992 : { slidesPerView: 3   }
      }
    });
  }

  /* -- GLIGHTBOX -- */
  if (typeof GLightbox !== 'undefined') {
    GLightbox({
      selector   : '.glightbox',
      touchNavigation: true,
      loop       : true,
      zoomable   : true
    });
  }

  /* -- NAVBAR SCROLL EFFECT -- */
  $(window).on('scroll', function () {
    if ($(this).scrollTop() > 50) {
      $('#mainNavbar').addClass('scrolled');
      $('#scrollTopBtn').addClass('show');
    } else {
      $('#mainNavbar').removeClass('scrolled');
      $('#scrollTopBtn').removeClass('show');
    }

    /* Animate progress bars when in view */
    animateProgressBars();
  });

  /* -- SCROLL TO TOP -- */
  $('#scrollTopBtn').on('click', function () {
    $('html, body').animate({ scrollTop: 0 }, 500, 'swing');
  });

  /* -- PROGRESS BAR ANIMATION -- */
  function animateProgressBars () {
    $('.progress-bar[data-width]').each(function () {
      var $bar  = $(this);
      var pos   = $bar.offset().top;
      var winH  = $(window).height() + $(window).scrollTop();
      if (winH > pos && !$bar.hasClass('animated')) {
        $bar.addClass('animated');
        $bar.css('width', $bar.data('width') + '%');
      }
    });
  }
  /* Trigger on load too */
  animateProgressBars();

  /* -- COUNTER ANIMATION -- */
  function animateCounters () {
    $('.counter[data-target]').each(function () {
      var $el  = $(this);
      var pos  = $el.offset().top;
      var winH = $(window).height() + $(window).scrollTop();
      if (winH > pos && !$el.hasClass('counted')) {
        $el.addClass('counted');
        var target = parseInt($el.data('target'));
        $({ val: 0 }).animate({ val: target }, {
          duration : 1500,
          easing   : 'swing',
          step     : function () { $el.text(Math.ceil(this.val).toLocaleString()); },
          complete : function () { $el.text(target.toLocaleString()); }
        });
      }
    });
  }
  $(window).on('scroll', animateCounters);
  animateCounters();

  /* -- TOASTR CONFIG -- */
  if (typeof toastr !== 'undefined') {
    toastr.options = {
      closeButton      : true,
      progressBar      : true,
      positionClass    : 'toast-top-right',
      timeOut          : 4000,
      extendedTimeOut  : 1000,
      showEasing       : 'swing',
      hideEasing       : 'linear',
      showMethod       : 'fadeIn',
      hideMethod       : 'fadeOut'
    };
  }

  /* -- ACTIVE NAV LINK -- */
  $('.navbar-nav .nav-link').on('click', function () {
    $('.navbar-nav .nav-link').removeClass('active');
    $(this).addClass('active');
  });

});
