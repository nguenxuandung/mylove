(function($) {
    'use strict'; // Start of use strict

// data-wow-duration="1s" data-wow-delay="0s" data-wow-offset="100"  data-wow-iteration="1"
    var wow = new WOW(
        {
            boxClass: 'wow', // animated element css class (default is wow)
            animateClass: 'animated', // animation css class (default is animated)
            offset: 150, // distance to the element when triggering the animation (default is 0)
            mobile: false, // trigger animations on mobile devices (default is true)
            live: true, // act on asynchronously loaded content (default is true)
        }
    );
    wow.init();

    // jQuery for page scrolling feature - requires jQuery Easing plugin
    $('a.page-scroll').on('click', function(event) {
        var $anchor = $(this);
        $('html, body').stop().animate({
            scrollTop: ($($anchor.attr('href')).offset().top - 50),
        }, 1250, 'easeInOutExpo');
        event.preventDefault();
    });

    // Highlight the top nav as scrolling occurs
    /*
     $( 'body' ).scrollspy( {
     target: '.navbar-fixed-top',
     offset: 51
     } );
     */

    // Closes the Responsive Menu on Menu Item Click
    $('.navbar-collapse ul li a:not(.dropdown-toggle)').click(function() {
        $('.navbar-toggle:visible').click();
    });

    // Offset for Main Navigation
    $('#mainNav').affix({
        offset: {
            top: 100,
        },
    });

    $(document).ready(function() {
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
                app_vars['captcha_contact'] === 'yes' &&
                $('#captchaContact').length) {
                if (!$(this).hasClass('captcha-done')) {
                    return false;
                }
            }
        }

        var contactForm = $(this);
        var contactFormHTML = $(this).html();
        var submitButton = contactForm.find('button');
        var submitButtonHTML = submitButton.html();

        //console.log( homeForm.serialize() );

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
                    $('#contact .contact-result').
                        html(success_message).
                        slideDown();
                } else {
                    contactForm.slideUp();
                    var success_message = '<div class="alert alert-danger" role="alert"><b>Error!</b> ' +
                        result.message + '</div>';
                    $('#contact .contact-result').
                        html(success_message).
                        slideDown();
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
            opts = 'status=1' + ',width=' + width + ',height=' + height +
                ',top=' +
                top + ',left=' + left;

        window.open(url, 'share', opts);
    });

})(jQuery); // End of use strict
