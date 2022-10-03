/**
 * reCAPTCHA Stuff
 */
var captchaShort;
var captchaContact;
var captchaSignin;
var captchaSignup;
var captchaForgotpassword;
var captchaShortlink;
var invisibleCaptchaShort;
var invisibleCaptchaContact;
var invisibleCaptchaSignin;
var invisibleCaptchaSignup;
var invisibleCaptchaForgotpassword;
var invisibleCaptchaShortlink;

window.onload = function() {

  if (app_vars['enable_captcha'] !== 'yes') {
    return true;
  }

  if (app_vars['captcha_type'] === 'securimage') {
    if (app_vars['user_id'] === null &&
        app_vars['captcha_short_anonymous'] === '1' &&
        $('#captchaShort').length) {

      $.ajax({
        type: 'GET',
        url: app_vars.current_url + 'securimage/render/captchaShort',
        success: function(data, textStatus) {
          $('#captchaShort').html(data);
        },
        error: function() {
          console.log('Securimage can not be loaded');
        },
      });

    }

    if (app_vars['captcha_contact'] === 'yes' && $('#captchaContact').length) {

      $.ajax({
        type: 'GET',
        url: app_vars.current_url + 'securimage/render/captchaContact',
        success: function(data, textStatus) {
          $('#captchaContact').html(data);
        },
        error: function() {
          console.log('Securimage can not be loaded');
        },
      });

    }

    if (app_vars['captcha_signin'] === 'yes' && $('#captchaSignin').length) {

      $.ajax({
        type: 'GET',
        url: app_vars.current_url + 'securimage/render/captchaSignin',
        success: function(data, textStatus) {
          $('#captchaSignin').html(data);
        },
        error: function() {
          console.log('Securimage can not be loaded');
        },
      });

    }

    if (app_vars['captcha_signup'] === 'yes' && $('#captchaSignup').length) {

      $.ajax({
        type: 'GET',
        url: app_vars.current_url + 'securimage/render/captchaSignup',
        success: function(data, textStatus) {
          $('#captchaSignup').html(data);
        },
        error: function() {
          console.log('Securimage can not be loaded');
        },
      });

    }

    if (app_vars['captcha_forgot_password'] === 'yes' &&
        $('#captchaForgotpassword').length) {

      $.ajax({
        type: 'GET',
        url: app_vars.current_url + 'securimage/render/captchaForgotpassword',
        success: function(data, textStatus) {
          $('#captchaForgotpassword').html(data);
        },
        error: function() {
          console.log('Securimage can not be loaded');
        },
      });

    }

    if (app_vars['captcha_shortlink'] === 'yes' &&
        $('#captchaShortlink').length) {

      $.ajax({
        type: 'GET',
        url: app_vars.current_url + 'securimage/render/captchaShortlink',
        success: function(data, textStatus) {
          $('#captchaShortlink').html(data);
        },
        error: function() {
          console.log('Securimage can not be loaded');
        },
      });

    }
  }

  if (app_vars['captcha_type'] === 'solvemedia') {
    if (app_vars['user_id'] === null &&
        app_vars['captcha_short_anonymous'] === '1' &&
        $('#captchaShort').length) {
      captchaShort = ACPuzzle.create(
          app_vars['solvemedia_challenge_key'],
          'captchaShort',
          {multi: true, id: 'captchaShort'}
      );
    }

    if (app_vars['captcha_contact'] === 'yes' && $('#captchaContact').length) {
      captchaContact = ACPuzzle.create(
          app_vars['solvemedia_challenge_key'],
          'captchaContact',
          {multi: true, id: 'captchaContact'}
      );
    }

    if (app_vars['captcha_signin'] === 'yes' && $('#captchaSignin').length) {
      captchaSignin = ACPuzzle.create(
          app_vars['solvemedia_challenge_key'],
          'captchaSignin',
          {multi: true, id: 'captchaSignin'}
      );
    }

    if (app_vars['captcha_signup'] === 'yes' && $('#captchaSignup').length) {
      captchaSignup = ACPuzzle.create(
          app_vars['solvemedia_challenge_key'],
          'captchaSignup',
          {multi: true, id: 'captchaSignup'}
      );
    }

    if (app_vars['captcha_forgot_password'] === 'yes' &&
        $('#captchaForgotpassword').length) {
      captchaForgotpassword = ACPuzzle.create(
          app_vars['solvemedia_challenge_key'],
          'captchaForgotpassword',
          {multi: true, id: 'captchaForgotpassword'}
      );
    }

    if (app_vars['captcha_shortlink'] === 'yes' &&
        $('#captchaShortlink').length) {
      captchaShortlink = ACPuzzle.create(
          app_vars['solvemedia_challenge_key'],
          'captchaShortlink',
          {multi: true, id: 'captchaShortlink'}
      );
    }
  }

};

var onloadRecaptchaCallback = function() {

  if (app_vars['enable_captcha'] !== 'yes') {
    return true;
  }

  if (app_vars['captcha_type'] === 'recaptcha') {
    if (app_vars['user_id'] === null &&
        app_vars['captcha_short_anonymous'] === '1' &&
        $('#captchaShort').length) {
      $('#shorten .btn-captcha').attr('disabled', 'disabled');
      captchaShort = grecaptcha.render('captchaShort', {
        'sitekey': app_vars['reCAPTCHA_site_key'],
        'callback': function(response) {
          $('#shorten .btn-captcha').removeAttr('disabled');
        },
      });
    }

    if (app_vars['captcha_contact'] === 'yes' && $('#captchaContact').length) {
      $('#contact-form .btn-captcha').attr('disabled', 'disabled');
      captchaContact = grecaptcha.render('captchaContact', {
        'sitekey': app_vars['reCAPTCHA_site_key'],
        'callback': function(response) {
          $('#contact-form .btn-captcha').removeAttr('disabled');
        },
      });
    }

    if (app_vars['captcha_signin'] === 'yes' && $('#captchaSignin').length) {
      $('#signin-form .btn-captcha').attr('disabled', 'disabled');
      captchaSignin = grecaptcha.render('captchaSignin', {
        'sitekey': app_vars['reCAPTCHA_site_key'],
        'callback': function(response) {
          $('#signin-form .btn-captcha').removeAttr('disabled');
        },
      });
    }

    if (app_vars['captcha_signup'] === 'yes' && $('#captchaSignup').length) {
      $('#signup-form .btn-captcha').attr('disabled', 'disabled');
      captchaSignup = grecaptcha.render('captchaSignup', {
        'sitekey': app_vars['reCAPTCHA_site_key'],
        'callback': function(response) {
          $('#signup-form .btn-captcha').removeAttr('disabled');
        },
      });
    }

    if (app_vars['captcha_forgot_password'] === 'yes' &&
        $('#captchaForgotpassword').length) {
      $('#forgotpassword-form .btn-captcha').attr('disabled', 'disabled');
      captchaForgotpassword = grecaptcha.render('captchaForgotpassword', {
        'sitekey': app_vars['reCAPTCHA_site_key'],
        'callback': function(response) {
          $('#forgotpassword-form .btn-captcha').removeAttr('disabled');
        },
      });
    }

    if (app_vars['captcha_shortlink'] === 'yes' &&
        $('#captchaShortlink').length) {
      $('#link-view .btn-captcha').attr('disabled', 'disabled');
      captchaShortlink = grecaptcha.render('captchaShortlink', {
        'sitekey': app_vars['reCAPTCHA_site_key'],
        'callback': function(response) {
          $('#link-view .btn-captcha').removeAttr('disabled');
        },
      });
    }
  }

  if (app_vars['captcha_type'] === 'invisible-recaptcha') {
    if (app_vars['user_id'] === null &&
        app_vars['captcha_short_anonymous'] === '1' && $(
            '#captchaShort').length) {
      invisibleCaptchaShort = grecaptcha.render('captchaShort', {
        'sitekey': app_vars['invisible_reCAPTCHA_site_key'],
        'size': 'invisible',
        'callback': function(response) {
          if (grecaptcha.getResponse(invisibleCaptchaShort)) {
            $('#shorten').addClass('captcha-done').submit();
          }
        },
      });

      $('#shorten').submit(function(event) {
        if (!grecaptcha.getResponse(invisibleCaptchaShort)) {
          event.preventDefault(); //prevent form submit before captcha is completed
          grecaptcha.execute(invisibleCaptchaShort);
        }
      });
    }

    if (app_vars['captcha_contact'] === 'yes' && $('#captchaContact').length) {
      invisibleCaptchaContact = grecaptcha.render('captchaContact', {
        'sitekey': app_vars['invisible_reCAPTCHA_site_key'],
        'size': 'invisible',
        'callback': function(response) {
          if (grecaptcha.getResponse(invisibleCaptchaContact)) {
            $('#contact-form').addClass('captcha-done').submit();
          }
        },
      });

      $('#contact-form').submit(function(event) {
        if (!grecaptcha.getResponse(invisibleCaptchaContact)) {
          event.preventDefault(); //prevent form submit before captcha is completed
          grecaptcha.execute(invisibleCaptchaContact);
        }
      });
    }

    if (app_vars['captcha_signin'] === 'yes' && $('#captchaSignin').length) {
      invisibleCaptchaSignin = grecaptcha.render('captchaSignin', {
        'sitekey': app_vars['invisible_reCAPTCHA_site_key'],
        'size': 'invisible',
        'callback': function(response) {
          $('#signin-form').submit();
        },
      });

      $('#signin-form').submit(function(event) {
        if (!grecaptcha.getResponse(invisibleCaptchaSignin)) {
          event.preventDefault(); //prevent form submit before captcha is completed
          grecaptcha.execute(invisibleCaptchaSignin);
        }
      });
    }

    if (app_vars['captcha_signup'] === 'yes' && $('#captchaSignup').length) {
      invisibleCaptchaSignup = grecaptcha.render('captchaSignup', {
        'sitekey': app_vars['invisible_reCAPTCHA_site_key'],
        'size': 'invisible',
        'callback': function(response) {
          $('#signup-form').submit();
        },
      });

      $('#signup-form').submit(function(event) {
        if (!grecaptcha.getResponse(invisibleCaptchaSignup)) {
          event.preventDefault(); //prevent form submit before captcha is completed
          grecaptcha.execute(invisibleCaptchaSignup);
        }
      });
    }

    if (app_vars['captcha_forgot_password'] === 'yes' &&
        $('#captchaForgotpassword').length) {
      invisibleCaptchaForgotpassword = grecaptcha.render(
          'captchaForgotpassword', {
            'sitekey': app_vars['invisible_reCAPTCHA_site_key'],
            'size': 'invisible',
            'callback': function(response) {
              $('#forgotpassword-form').submit();
            },
          });

      $('#forgotpassword-form').submit(function(event) {
        if (!grecaptcha.getResponse(invisibleCaptchaForgotpassword)) {
          event.preventDefault(); //prevent form submit before captcha is completed
          grecaptcha.execute(invisibleCaptchaForgotpassword);
        }
      });
    }

    if (app_vars['captcha_shortlink'] === 'yes' &&
        $('#captchaShortlink').length) {
      invisibleCaptchaShortlink = grecaptcha.render('captchaShortlink', {
        'sitekey': app_vars['invisible_reCAPTCHA_site_key'],
        'size': 'invisible',
        'callback': function(response) {
          $('#link-view').submit();
        },
      });

      $('#link-view').submit(function(event) {
        if (!grecaptcha.getResponse(invisibleCaptchaShortlink)) {
          event.preventDefault(); //prevent form submit before captcha is completed
          grecaptcha.execute(invisibleCaptchaShortlink);
        }
      });
    }
  }

};

var onloadHCaptchaCallback = function() {

    if (app_vars['enable_captcha'] !== 'yes') {
        return true;
    }

    if (app_vars['captcha_type'] === 'hcaptcha_checkbox') {
        if (app_vars['user_id'] === null &&
            app_vars['captcha_short_anonymous'] === '1' &&
            $('#captchaShort').length) {
            $('#shorten .btn-captcha').attr('disabled', 'disabled');
            captchaShort = hcaptcha.render('captchaShort', {
                'sitekey': app_vars['hcaptcha_checkbox_site_key'],
                'callback': function(response) {
                    $('#shorten .btn-captcha').removeAttr('disabled');
                },
            });
        }

        if (app_vars['captcha_contact'] === 'yes' && $('#captchaContact').length) {
            $('#contact-form .btn-captcha').attr('disabled', 'disabled');
            captchaContact = hcaptcha.render('captchaContact', {
                'sitekey': app_vars['hcaptcha_checkbox_site_key'],
                'callback': function(response) {
                    $('#contact-form .btn-captcha').removeAttr('disabled');
                },
            });
        }

        if (app_vars['captcha_signin'] === 'yes' && $('#captchaSignin').length) {
            $('#signin-form .btn-captcha').attr('disabled', 'disabled');
            captchaSignin = hcaptcha.render('captchaSignin', {
                'sitekey': app_vars['hcaptcha_checkbox_site_key'],
                'callback': function(response) {
                    $('#signin-form .btn-captcha').removeAttr('disabled');
                },
            });
        }

        if (app_vars['captcha_signup'] === 'yes' && $('#captchaSignup').length) {
            $('#signup-form .btn-captcha').attr('disabled', 'disabled');
            captchaSignup = hcaptcha.render('captchaSignup', {
                'sitekey': app_vars['hcaptcha_checkbox_site_key'],
                'callback': function(response) {
                    $('#signup-form .btn-captcha').removeAttr('disabled');
                },
            });
        }

        if (app_vars['captcha_forgot_password'] === 'yes' &&
            $('#captchaForgotpassword').length) {
            $('#forgotpassword-form .btn-captcha').attr('disabled', 'disabled');
            captchaForgotpassword = hcaptcha.render('captchaForgotpassword', {
                'sitekey': app_vars['hcaptcha_checkbox_site_key'],
                'callback': function(response) {
                    $('#forgotpassword-form .btn-captcha').removeAttr('disabled');
                },
            });
        }

        if (app_vars['captcha_shortlink'] === 'yes' &&
            $('#captchaShortlink').length) {
            $('#link-view .btn-captcha').attr('disabled', 'disabled');
            captchaShortlink = hcaptcha.render('captchaShortlink', {
                'sitekey': app_vars['hcaptcha_checkbox_site_key'],
                'callback': function(response) {
                    $('#link-view .btn-captcha').removeAttr('disabled');
                },
            });
        }
    }

};

/**
 * Load recaptcha/invisible-recaptcha captcha script
 */
if (['recaptcha', 'invisible-recaptcha'].indexOf(app_vars.captcha_type) !== -1) {
    let recaptcha_script = document.createElement('script');
    recaptcha_script.src = 'https://www.recaptcha.net/recaptcha/api.js?onload=onloadRecaptchaCallback&render=explicit';
    recaptcha_script.async = true;
    recaptcha_script.defer = true;
    document.body.appendChild(recaptcha_script);
}

/**
 * Load hCaptcha script
 */
if (app_vars.captcha_type === 'hcaptcha_checkbox') {
    let hcaptcha_script = document.createElement('script');
    hcaptcha_script.src = 'https://hcaptcha.com/1/api.js?onload=onloadHCaptchaCallback&render=explicit';
    hcaptcha_script.async = true;
    hcaptcha_script.defer = true;
    document.body.appendChild(hcaptcha_script);
}

/**
 * Load SolveMedia captcha script
 */
if (app_vars.captcha_type === 'solvemedia') {
    let script_solvemedia = document.createElement('script');
    script_solvemedia.type = 'text/javascript';

    if (location.protocol === 'https:') {
        script_solvemedia.src = 'https://api-secure.solvemedia.com/papi/challenge.ajax';
    } else {
        script_solvemedia.src = 'http://api.solvemedia.com/papi/challenge.ajax';
    }
    document.body.appendChild(script_solvemedia);
}

/**
 * Ads JS
 */
function setCookie(cname, cvalue, exdays)
{
  var d = new Date();
  d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
  var expires = 'expires=' + d.toUTCString();
  document.cookie = cname + '=' + cvalue + ';' + expires + ';path=/';
}

function getCookie(cname)
{
  var name = cname + '=';
  var decodedCookie = decodeURIComponent(document.cookie);
  var ca = decodedCookie.split(';');
  for (var i = 0; i < ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) === ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) === 0) {
      return c.substring(name.length, c.length);
    }
  }
  return '';
}

var go_popup = $('#go-popup');
if (go_popup.length) {
  $(document).one('click.adLinkFly.goPopupClick', function(e) {
    go_popup.submit();
  });

  go_popup.one('submit.adLinkFly.goPopupSubmit', function(e) {
    //var window_height = $(window).height()-150;
    //var window_width = $(window).width()-150;
    var window_height = screen.height - 150;
    var window_width = screen.width - 150;

    var window_left = Number((screen.width / 2) - (window_width / 2));
    var window_top = Number((screen.height / 2) - (window_height / 2));

    var w = window.open('about:blank', 'Popup_Window',
        'toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,' +
        'width=' + window_width + ',height=' + window_height + ',left = ' +
        window_left + ',top = ' + window_top + '');
    this.target = 'Popup_Window';

  });
}

document.cookie = 'ab=0; path=/';

function checkAdblockUser()
{
  //alert('Begin adblock check');
  if (getCookie('ab') === '1') {
    //alert('No adblock check');
    return;
  }
  document.cookie = 'ab=2; path=/';

  var adBlock = $('#ad-banner');

  if (adBlock.filter(':visible').length === 0 ||
      adBlock.filter(':hidden').length > 0 ||
      adBlock.height() === 0) {
    document.cookie = 'ab=1; path=/';
    /**
     * Force disable adblocker
     */
    if (app_vars['force_disable_adblock'] === '1') {
      var adblock_message = '<div class="alert alert-danger" style="display: inline-block;">' +
          app_vars['please_disable_adblock'] + '</div>';

      $('#link-view').replaceWith(adblock_message);
      $('.banner-page a.get-link').replaceWith(adblock_message);
      $('.interstitial-page div.skip-ad').replaceWith(adblock_message);
    }
  }
  //alert('End adblock check');
}

function checkAdsbypasserUser()
{
  //alert('Begin Adsbypasser check');
  if (getCookie('ab') === '1') {
    //alert('No Adsbypasser check');
    return;
  }
  var ads_bypassers = ['AdsBypasser', 'SafeBrowse'];
  var word = document.title.split(' ').splice(-1)[0];
  document.cookie = 'ab=2; path=/';
  if (ads_bypassers.indexOf(word) >= 0) {
    document.cookie = 'ab=1; path=/';
  }
  //alert('End Adsbypasser check');
}

function checkPrivateMode()
{
  if (typeof Promise === 'function') {
    new Promise(function(resolve) {
      var db,
          on = function() { resolve(true); },
          off = function() { resolve(false); },
          tryls = function tryls() {
            try {
              localStorage.length
                  ? off()
                  : (localStorage.x = 1, localStorage.removeItem('x'), off());
            } catch (e) {
              // Safari only enables cookie in private mode
              // if cookie is disabled then all client side storage is disabled
              // if all client side storage is disabled, then there is no point
              // in using private mode
              navigator.cookieEnabled ? on() : off();
            }
          };

      // Blink (chrome & opera)
      window.webkitRequestFileSystem
          ? webkitRequestFileSystem(0, 0, off, on)
          // FF
          : 'MozAppearance' in document.documentElement.style
          ? (db = indexedDB.open(
              'test'), db.onerror = on, db.onsuccess = off)
          // Safari
          : /constructor/i.test(window.HTMLElement)
              ? tryls()
              // IE10+ & edge
              : !window.indexedDB &&
              (window.PointerEvent || window.MSPointerEvent)
                  ? on()
                  // Rest
                  : off();
    }).then(function(isPrivateMode) {
      //alert('Begin Promise check');
      if (getCookie('ab') === '1') {
        //alert('No Promise check');
        return;
      }
      document.cookie = 'ab=2; path=/';
      if (isPrivateMode) {
        document.cookie = 'ab=1; path=/';
      }
      //alert('End Promise check');
    });
  }
}

var body = $('body');
var ad_type = '';
if (body.hasClass('banner-page')) {
  ad_type = 'banner';
} else {
  if (body.hasClass('interstitial-page')) {
    ad_type = 'interstitial';
  }
}

var counter_start_object = document;
if (app_vars['counter_start'] === 'load') {
  counter_start_object = window;
}

$(counter_start_object).
    on(app_vars['counter_start'] + '.adLinkFly.checkAdblockers', function(e) {
      checkAdsbypasserUser();

      window.setTimeout(function() {
        checkAdblockUser();
      }, 1000);
    });

$(counter_start_object).
    on(app_vars['counter_start'] + '.adLinkFly.counter', function(e) {
      if (ad_type === 'banner') {
        var timer = $('#timer');

        window.setTimeout(function() {
          var time = app_vars['counter_value'] * 1000,
              delta = 1000,
              tid;

          tid = setInterval(function() {
            if (window.blurred) {
              return;
            }
            time -= delta;
            timer.text(time / 1000);
            if (time <= 0) {
              clearInterval(tid);

              $('#go-link').addClass('go-link');
              $('#go-link.go-link').submit();
            }
          }, delta);
        }, 500);

        window.onblur = function() {
          window.blurred = true;
        };
        window.onfocus = function() {
          window.blurred = false;
        };
      }

      if (ad_type === 'interstitial') {
        var skip_ad = $('.skip-ad');
        var counter = $('.skip-ad .counter');

        window.setTimeout(function() {
          var time = app_vars['counter_value'] * 1000,
              delta = 1000,
              tid;

          tid = setInterval(function() {
            time -= delta;
            counter.text((time / 1000) + ' s');
            if (time <= 0) {
              skip_ad.html(
                  '<a href="" class="btn" onclick="javascript: return false;">' +
                  app_vars['skip_ad'] + '</a>');
              clearInterval(tid);
              $('#go-link').addClass('go-link');
              $('#go-link.go-link').submit();
            }
          }, delta);
        }, 500);
      }

    });

checkPrivateMode();

/**
 * Report invalid link
 */
$('#go-link').one('submit.adLinkFly.counterSubmit', function(e) {
  e.preventDefault();
  var goForm = $(this);

  if (!goForm.hasClass('go-link')) {
    return;
  }

  var submitButton = goForm.find('button');

  $.ajax({
    dataType: 'json', // The type of data that you're expecting back from the server.
    type: 'POST', // he HTTP method to use for the request
    url: goForm.attr('action'),
    data: goForm.serialize(), // Data to be sent to the server.
    beforeSend: function(xhr) {
      if (ad_type === 'banner') {
        submitButton.attr('disabled', 'disabled');
        $('a.get-link').text(app_vars['getting_link']);
      }
      if (ad_type === 'interstitial') {
        submitButton.attr('disabled', 'disabled');
      }
    },
    success: function(result, status, xhr) {
      if (result.url) {
        if (ad_type === 'banner') {
          $('a.get-link').
              attr('href', result.url).
              removeClass('disabled').
              text(app_vars['get_link']);
        }
        if (ad_type === 'interstitial') {
          $('.skip-ad a').attr('href', result.url).removeAttr('onclick');
        }
      } else {
        alert(result.message);
      }
    },
    error: function(xhr, status, error) {
      console.log('An error occured: ' + xhr.status + ' ' + xhr.statusText);
    },
    complete: function(xhr, status) {

    },
  });
});

$('body').
    one('focus.adLinkFly.displayShortenCaptcha', '#shorten input#url',
        function(e) {
          $('#shorten .form-group.captcha').slideDown('slow');
        });

$(document).ready(function() {

  var url_href = window.location.href;
  if (url_href.substr(-1) === '#') {
    history.pushState('', document.title,
        url_href.substr(0, url_href.length - 1));
  }

  var url = window.location.href;
  $('.sidebar-menu a').filter(function() {
    return this.href === url;
    //} ).closest( 'li' ).addClass( 'active' );
  }).parents('.sidebar-menu li').addClass('active');

  function fixHeight()
  {
    var headerHeight = $('header.main-header').outerHeight();
    $('#frame').
        attr('height', (($(window).height() - 0) - headerHeight) + 'px');
  }

  $(window).resize(function() {
    fixHeight();
  }).resize();

  function populate_visitors_price()
  {
    /**
     * Calculate visitors
     */
        // http://stackoverflow.com/a/3087027
    var visitors = 0;
    $('input[id^=campaign-items-][id$=-purchase]').each(function(index, item) {
      var val = $(item).val();
      visitors += val * 1000;
    });
    $('#total-visitors').text(visitors);

    /**
     * Calculate price
     */
    var price = 0;
    $('input[id^=campaign-items-][id$=-purchase]').each(function(index, item) {
      var val = $(item).data('advertiser_price');
      price += val * $(item).val();

    });
    $('#total-price').
        text(price.toFixed(2).toLocaleString(app_vars['language']));
  }

  populate_visitors_price();

  $('#campaign-create').change(function() {
    populate_visitors_price();
  });

  function shortenButton()
  {
    var short_box = $('.box-short');
    var short_button = $('button.shorten-button');
    if (jQuery(window).width() <= 767) {
      short_box.css('display', 'block');
      short_button.css('display', 'none');
    } else {
      short_box.css('display', 'none');
      short_button.css('display', 'block');
    }
  }

  $(window).resize(function() {
    shortenButton();
  }).resize();

  $('button.shorten-button').click(function(e) {
    e.preventDefault();
    $('.box-short').toggle('fast');
  });

});

/**
 * Bootstrap 3: Keep selected tab on page refresh
 */
// store the currently selected tab in the localStorage
$('#form-settings a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
  var id = $(e.target).attr('href').substr(1);
  localStorage.setItem('settings_selectedTab', id);
});

// on load of the page: switch to the currently selected tab
var selectedTab = localStorage.getItem('settings_selectedTab');

if ($('#form-settings').length && selectedTab !== null) {
  $('#form-settings a[data-toggle="tab"][href="#' + selectedTab + '"]').
      tab('show');
} else {
  $('#form-settings a[data-toggle="tab"]:first').tab('show');
}

/**
 *  Member Area Shorten
 */
$('.shorten-member #shorten').
    on('submit.adLinkFly.memberShortLinkForm', function(e) {
      e.preventDefault();
      var shortenForm = $(this);
      var shortenContainer = shortenForm.closest('.box-short');
      var submitButton = shortenForm.find('button.btn-submit');
      var submitButtoHTML = submitButton.html();

      $.ajax({
        dataType: 'json', // The type of data that you're expecting back from the server.
        type: 'POST', // he HTTP method to use for the request
        url: shortenForm.attr('action'),
        data: shortenForm.serialize(), // Data to be sent to the server.
        beforeSend: function(xhr) {

          submitButton.attr('disabled', 'disabled').
              html('<i class="fa fa-spinner fa-spin"></i>');
          $('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>').
              insertAfter(
                  shortenContainer.find('.box-body'));

        },
        success: function(result, status, xhr) {

          if (result.url) {
            var short_url_html = '<div class="form-group"><div class="input-group"><input class="form-control input-lg" ' +
                'value="' + result.url +
                '" readonly onfocus="javascript:this.select()" ><div class="input-group-addon copy-it" ' +
                'data-clipboard-text="' + result.url +
                '" data-toggle="tooltip" data-placement="left" title="' +
                app_vars['copy'] +
                '"><i class="fa fa-clone"></i></div></div></div>';
            $('.shorten.add-link-result').html(short_url_html).slideDown();
            $('[data-toggle="tooltip"]').tooltip();
          } else {
            var success_message = '<div class="form-group"><p></p><div class="alert alert-danger" role="alert">' +
                result.message + '</div></div>';
            $('.shorten.add-link-result').html(success_message).slideDown();
            //alert( result.message );
          }

        },
        error: function(xhr, status, error) {

          alert('An error occured: ' + xhr.status + ' ' + xhr.statusText);

        },
        complete: function(xhr, status) {

          shortenContainer.find('.overlay').remove();
          submitButton.removeAttr('disabled').html(submitButtoHTML);

        },
      });
    });

/**
 * Home Page Shorten
 */
$('.shorten #shorten').on('submit.adLinkFly.homeShortLinkForm', function(e) {
  e.preventDefault();
  if (app_vars['user_id'] === null &&
      app_vars['home_shortening_register'] === 'yes') {
    window.location.href = app_vars['base_url'] + 'auth/signup';
    return;
  }

  if (app_vars['captcha_type'] === 'invisible-recaptcha') {
    if (app_vars['enable_captcha'] === 'yes' &&
        app_vars['captcha_short_anonymous'] === '1' &&
        $('#captchaContact').length) {
      if (!$(this).hasClass('captcha-done')) {
        return false;
      }
    }
  }

  var shortenForm = $(this);
  var submitButton = shortenForm.find('button');
  var submitButtoHTML = submitButton.html();

  $.ajax({
    dataType: 'json', // The type of data that you're expecting back from the server.
    type: 'POST', // he HTTP method to use for the request
    url: shortenForm.attr('action'),
    data: shortenForm.serialize(), // Data to be sent to the server.
    beforeSend: function(xhr) {
      submitButton.attr('disabled', 'disabled');
      $('<div class="shorten loader"></div>').insertAfter(shortenForm);
    },
    success: function(result, status, xhr) {
      //console.log( result );
      if (result.url) {
        shortenForm.slideUp();
        var short_url_html = '<div class="form-group"><div class="input-group"><input class="form-control input-lg" value="' +
            result.url +
            '" readonly onfocus="javascript:this.select()" ><div class="input-group-addon copy-it" ' +
            'data-clipboard-text="' + result.url +
            '" data-toggle="tooltip" data-placement="bottom" title="' +
            app_vars['copy'] +
            '"><i class="fa fa-clone"></i></div><div class="input-group-addon reshort" data-toggle="tooltip" ' +
            'data-placement="bottom" title="Reshort"><i class="fa fa-refresh"></i></div></div></div>';
        $('.shorten.add-link-result').html(short_url_html).slideDown();
      } else {
        shortenForm.slideUp();
        var success_message = '<div class="form-group"><div class="input-group"><input class="form-control input-lg" ' +
            'value="' + result.message +
            '" readonly ><div class="input-group-addon reshort" data-toggle="tooltip" ' +
            'data-placement="bottom" title="Reshort"><i class="fa fa-refresh"></i></div></div></div>';
        $('.shorten.add-link-result').html(success_message).slideDown();

        // securimage captcha
        if (typeof window.captchaShort_captcha_img_audioObj !==
            'undefined') {
          captchaShort_captcha_img_audioObj.refresh();
        }
        document.getElementById(
            'captchaShort_captcha_img').src = app_vars.current_url +
            'securimage/show?namespace=captchaShort&' + Math.random();
        // securimage captcha

      }
    },
    error: function(xhr, status, error) {
      alert('An error occured: ' + xhr.status + ' ' + xhr.statusText);
    },
    complete: function(xhr, status) {
      $('[data-toggle="tooltip"]').tooltip();
      submitButton.removeAttr('disabled');
      $('.shorten.loader').remove();
      shortenForm[0].reset();
      try {
        grecaptcha.reset(captchaShort);
      } catch (e) {
      }
      try {
        ACPuzzle.reload('captchaShort');
      } catch (e) {
      }
    },
  });
});

$('header.shorten').on('click', '.reshort', function(e) {
  $('.shorten.add-link-result').html('').slideUp();
  $('.shorten #shorten').slideDown();
});

// Tooltip

$('[data-toggle="tooltip"]').tooltip();

// Clipboard

var clipboard = new ClipboardJS('.copy-it');

clipboard.on('success', function(e) {
  setTooltip(e.trigger, app_vars['copied']);
});

function setTooltip(btn, message)
{
  $(btn).attr('data-original-title', message).tooltip('show');
}

function cookie_accept()
{
  var cookie_html = '<div id="cookie-pop">' +
      '<div class="container-fluid">' +
      '<div class="col-xs-9">' +
      '<div class="cookie-message">' + app_vars['cookie_message'] + '</div>' +
      '</div>' +
      '<div class="col-xs-3">' +
      '<div class="cookie-confirm">' +
      '<button id="got-cookie" class="btn btn-default" type="submit">' +
      app_vars['cookie_button'] + '</button>' +
      '</div>' +
      '</div>' +
      '</div>' +
      '</div>';

  $('body').append(cookie_html);
}

if (app_vars['cookie_notification_bar']) {
  if (getCookie('cookieLaw') === '') {
    cookie_accept();

    $('#cookie-pop').show();

    $('#got-cookie').click(function() {
      setCookie('cookieLaw', 'got_it', 365);
      $('#cookie-pop').remove();
    });
  }
}
