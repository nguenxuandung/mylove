if (typeof WOW === 'function') {
  // data-wow-duration="1s" data-wow-delay="0s" data-wow-offset="100"  data-wow-iteration="1"
  var wow = new WOW(
      {
        boxClass: 'wow', // animated element css class (default is wow)
        animateClass: 'animated', // animation css class (default is animated)
        offset: 150, // distance to the element when triggering the animation (default is 0)
        mobile: false, // trigger animations on mobile devices (default is true)
        live: true // act on asynchronously loaded content (default is true)
      }
  );
  wow.init();
}

// Offset for Main Navigation
$('#mainNav.navbar-fixed-top').affix({
  offset: {
    top: 100,
  },
});

function fixHeight()
{
  var headerHeight = $('#mainNav').outerHeight();
  $('#frame').attr('height', (($(window).height() - 0) - headerHeight) + 'px');
}

$(window).resize(function() {
  fixHeight();
}).resize();

$(document).ready(function() {
  $('.testimonials').owlCarousel({
    items: 1,
    margin: 10,
    loop: true,
  });

  $('.advertising-rates > ul.nav-tabs li:first-child').addClass('active');
  $('.payout-rates > ul.nav-tabs li:first-child').addClass('active');

  $('.advertising-rates > div.tab-content div.tab-pane:first-child').
      addClass('active');
  $('.payout-rates > div.tab-content div.tab-pane:first-child').
      addClass('active');

});

/**
 * Contact Form
 */
$('#contact-form').submit(function(e) {

  e.preventDefault();

  if (app_vars['captcha_type'] === 'invisible-recaptcha') {
    if (app_vars['enable_captcha'] === 'yes' &&
        app_vars['captcha_contact'] === 'yes' && $('#captchaContact').length) {
      if (!$(this).hasClass('captcha-done')) {
        return false;
      }
    }
  }

  var contactForm = $(this);
  var contactFormHTML = $(this).html();
  var submitButton = contactForm.find('button');
  var submitButtonHTML = submitButton.html();

  $.ajax({
    dataType: 'json', // The type of data that you're expecting back from the server.
    type: 'POST', // he HTTP method to use for the request
    url: contactForm.attr('action'), // A string containing the URL to which the request is sent.
    data: contactForm.serialize(), // Data to be sent to the server.
    cache: false,
    beforeSend: function(xhr) {
      submitButton.attr('disabled', true).
          html('<i class="fa fa-spinner fa-spin"></i>');
      //homeForm.slideUp();
      $('<div class="loader"></div>').insertAfter(contactForm);

    },
    success: function(result, status, xhr) {
      //console.log( result );
      if (result.status === 'success') {
        contactForm.slideUp();
        var success_message = '<div class="alert alert-success" role="alert">' +
            result.message + '</div>';
        $('#contact .contact-result').html(success_message).slideDown();
      } else {
        contactForm.slideUp();
        var success_message = '<div class="alert alert-danger" role="alert"><b>Error!</b> ' +
            result.message + '</div>';
        $('#contact .contact-result').html(success_message).slideDown();
      }

    },
    error: function(xhr, status, error) {
      //console.table( xhr );
      alert('An error occured: ' + xhr.status + ' ' + xhr.statusText);
    },
    complete: function(xhr, status) {
      $('#contact .loader').remove();
    },
  });

});

/**
 * Popup for share icons
 */
$('a.popup').on('click', function(e) {
  e.preventDefault();
  var width = 575,
      height = 400,
      left = ($(window).width() - width) / 2,
      top = ($(window).height() - height) / 2,
      url = this.href,
      opts = 'status=1' + ',width=' + width + ',height=' + height + ',top=' +
          top + ',left=' + left;

  window.open(url, 'share', opts);
});
