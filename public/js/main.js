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

  /* -- MODAL: VIEW DETAILS -- */
  var products = {
    earbuds : {
      title    : 'Wireless Earbuds',
      image    : 'asset/images/earbuds.jpg',
      qty      : '1,000 pcs',
      cost     : '?500',
      total    : '?5,00,000',
      import   : '25 Days',
      profit   : '?50',
      payment  : 'Weekly',
      investor : '1 Person',
      funded   : 64,
      invested : '?3,20,000',
      remaining: '?1,80,000',
      profitPer: 50
    },
    watch   : {
      title    : 'Smart Watch Series 8',
      image    : 'asset/images/smartwatch.jpg',
      qty      : '500 pcs',
      cost     : '?1,200',
      total    : '?6,00,000',
      import   : '28 Days',
      profit   : '?120',
      payment  : 'Weekly',
      investor : '1 Person',
      funded   : 45,
      invested : '?2,70,000',
      remaining: '?3,30,000',
      profitPer: 120
    },
    blender : {
      title    : 'Portable Blender',
      image    : 'asset/images/blender.jpg',
      qty      : '800 pcs',
      cost     : '?650',
      total    : '?5,20,000',
      import   : '25 Days',
      profit   : '?65',
      payment  : 'Weekly',
      investor : '1 Person',
      funded   : 30,
      invested : '?1,56,000',
      remaining: '?3,64,000',
      profitPer: 65
    }
  };

  /* Open modal */
  $(document).on('click', '.btn-invest-action', function () {
    var key  = $(this).data('product');
    var p    = products[key];
    if (!p) return;

    $('#modalProductImg').attr('src', p.image).attr('alt', p.title);
    $('#modalProductTitle').text(p.title);
    $('#modalQty').text(p.qty);
    $('#modalCost').text(p.cost);
    $('#modalTotal').text(p.total);
    $('#modalImport').text(p.import);
    $('#modalProfit').text(p.profit);
    $('#modalPayment').text(p.payment);
    $('#modalFunded').text(p.funded + '% Funded');
    $('#modalProgressBar').css('width', p.funded + '%').attr('aria-valuenow', p.funded);
    $('#modalInvested').text(p.invested);
    $('#modalRemaining').text(p.remaining);
    $('#investAmountInput').val('').trigger('input');
    $('#investAmountInput').data('profitper', p.profitPer);

    var modal = new bootstrap.Modal(document.getElementById('investModal'));
    modal.show();
  });

  /* Profit preview on input */
  $(document).on('input', '#investAmountInput', function () {
    var val  = parseInt($(this).val()) || 0;
    var ppp  = parseInt($(this).data('profitper')) || 0;
    var qty  = val > 0 ? Math.floor(val / ppp) : 0;
    var earn = qty * ppp;
    $('#profitPreviewText').text('?' + earn.toLocaleString() + ' estimated weekly profit');
  });

  /* Confirm invest */
  $(document).on('click', '#confirmInvestBtn', function () {
    var val = parseInt($('#investAmountInput').val()) || 0;
    if (val < 100) {
      if (typeof toastr !== 'undefined') {
        toastr.warning('Minimum investment is ?100!', 'Heads up');
      }
      return;
    }
    bootstrap.Modal.getInstance(document.getElementById('investModal')).hide();
    if (typeof toastr !== 'undefined') {
      toastr.success('Investment of ?' + val.toLocaleString() + ' submitted successfully!', 'Investment Placed');
    }
  });

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

  /* -- WELCOME TOAST -- */
  setTimeout(function () {
    if (typeof toastr !== 'undefined') {
      toastr.info('New investment opportunities are live today!', 'InvestHub');
    }
  }, 1500);

  /* -- ACTIVE NAV LINK -- */
  $('.navbar-nav .nav-link').on('click', function () {
    $('.navbar-nav .nav-link').removeClass('active');
    $(this).addClass('active');
  });



});
