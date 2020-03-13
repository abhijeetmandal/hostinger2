/**
 * easyModal.js v1.3.2
 * A minimal jQuery modal that works with your CSS.
 * Author: Flavius Matis - http://flaviusmatis.github.com/
 * URL: https://github.com/flaviusmatis/easyModal.js
 *
 * Copyright 2012, Flavius Matis
 * Released under the MIT license.
 * http://flaviusmatis.github.com/license.html
 */

/* jslint browser: true */
/* global jQuery */


(function ($, sr) {
  // debouncing function from John Hann
  // http://unscriptable.com/index.php/2009/03/20/debouncing-javascript-methods/
  var debounce = function (func, threshold, execAsap) {
    var timeout;

    return function debounced() {
      var obj = this; var
        args = arguments;
      function delayed() {
        if (!execAsap) func.apply(obj, args);
        timeout = null;
      }

      if (timeout) clearTimeout(timeout);
      else if (execAsap) func.apply(obj, args);

      timeout = setTimeout(delayed, threshold || 100);
    };
  };
  // smartModalResize
  jQuery.fn[sr] = function (fn) { return fn ? this.bind('resize', debounce(fn)) : this.trigger(sr); };
}(jQuery, 'smartModalResize'));

(function ($) {
  'use strict';

  var methods = {
    init: function (options) {
      var defaults = {
        top: 'auto',
        left: 'auto',
        autoOpen: false,
        overlayOpacity: 0.5,
        overlayColor: '#000',
        overlayClose: true,
        overlayParent: 'body',
        closeOnEscape: true,
        closeButtonClass: '.close',
        transitionIn: '',
        transitionOut: '',
        onOpen: false,
        onClose: false,
        zIndex: function () {
          return (function (value) {
            return value === -Infinity ? 0 : value + 1;
          }(Math.max.apply(Math, $.makeArray($('*').map(function () {
            return $(this).css('z-index');
          }).filter(function () {
            return $.isNumeric(this);
          }).map(function () {
            return parseInt(this, 10);
          })))));
        },
        updateZIndexOnOpen: true,
        hasVariableWidth: false
      };

      options = $.extend(defaults, options);

      return this.each(function () {
        var o = options;


        var $overlay = $('<div class="lean-overlay"></div>');


        var $modal = $(this);

        $overlay.css({
          display: 'none',
          position: 'fixed',
          // When updateZIndexOnOpen is set to true, we avoid computing the z-index on initialization,
          // because the value would be replaced when opening the modal.
          'z-index': (o.updateZIndexOnOpen ? 0 : o.zIndex()),
          top: 0,
          left: 0,
          height: '100%',
          width: '100%',
          background: o.overlayColor,
          opacity: o.overlayOpacity,
          overflow: 'auto'
        }).appendTo(o.overlayParent);

        $modal.css({
          display: 'none',
          position: 'fixed',
          // When updateZIndexOnOpen is set to true, we avoid computing the z-index on initialization,
          // because the value would be replaced when opening the modal.
          'z-index': (o.updateZIndexOnOpen ? 0 : o.zIndex() + 1),
          left: parseInt(o.left, 10) > -1 ? o.left + 'px' : 50 + '%',
          top: parseInt(o.top, 10) > -1 ? o.top + 'px' : 50 + '%'
        });

        $modal.bind('openModal', function () {
          var overlayZ = o.updateZIndexOnOpen ? o.zIndex() : parseInt($overlay.css('z-index'), 10);


          var modalZ = overlayZ + 1;

          if (o.transitionIn !== '' && o.transitionOut !== '') {
            $modal.removeClass(o.transitionOut).addClass(o.transitionIn);
          }
          $modal.css({
            display: 'block',
            'margin-left': (parseInt(o.left, 10) > -1 ? 0 : -($modal.outerWidth() / 2)) + 'px',
            'margin-top': (parseInt(o.top, 10) > -1 ? 0 : -($modal.outerHeight() / 2)) + 'px',
            'z-index': modalZ
          });

          $overlay.css({ 'z-index': overlayZ, display: 'block' });

          if (o.onOpen && typeof o.onOpen === 'function') {
            // onOpen callback receives as argument the modal window
            o.onOpen($modal[0]);
          }
        });

        $modal.bind('closeModal', function () {
          if (o.transitionIn !== '' && o.transitionOut !== '') {
            $modal.removeClass(o.transitionIn).addClass(o.transitionOut);
            $modal.one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
              $modal.css('display', 'none');
              $overlay.css('display', 'none');
            });
          } else {
            $modal.css('display', 'none');
            $overlay.css('display', 'none');
          }
          if (o.onClose && typeof o.onClose === 'function') {
            // onClose callback receives as argument the modal window
            o.onClose($modal[0]);
          }
        });

        // Close on overlay click
        $overlay.click(function () {
          if (o.overlayClose) {
            $modal.trigger('closeModal');
          }
        });

        $(document).keydown(function (e) {
          // ESCAPE key pressed
          if (o.closeOnEscape && e.keyCode === 27) {
            $modal.trigger('closeModal');
          }
        });

        $(window).smartModalResize(function () {
          if (o.hasVariableWidth) {
            $modal.css({
              'margin-left': (parseInt(o.left, 10) > -1 ? 0 : -($modal.outerWidth() / 2)) + 'px',
              'margin-top': (parseInt(o.top, 10) > -1 ? 0 : -($modal.outerHeight() / 2)) + 'px'
            });
          }
        });

        // Close when button pressed
        $modal.on('click', o.closeButtonClass, function (e) {
          $modal.trigger('closeModal');
          e.preventDefault();
        });

        // Automatically open modal if option set
        if (o.autoOpen) {
          $modal.trigger('openModal');
        }
      });
    }
  };

  $.fn.easyModal = function (method) {
    // Method calling logic
    if (methods[method]) {
      return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
    }

    if (typeof method === 'object' || !method) {
      return methods.init.apply(this, arguments);
    }

    $.error('Method ' + method + ' does not exist on jQuery.easyModal');
  };
}(jQuery));
(function ($) {
  $(document).ready(function () {
    $('input, .icon, textarea, .btn-cancel ').click(function (event) {
      window.onbeforeunload = function (e) {
        e = e || window.event;
        e.preventDefault = true;
        e.cancelBubble = true;
        e.returnValue = 'Your beautiful goodbye message';
      };
    });

    $(document).on('submit', 'form', function (event) {
      window.onbeforeunload = null;
    });

    $('.preview').stick_in_parent({
      offset_top:200
    });

    $('.upg').click(function (event) {
      $('.valid_domain_input').val($('.valid_domain_input').val().replace(' ', ''));
      if (!/^(http(s)?:\/\/)?(www\.)?[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/.test($('.valid_domain_input').val())) {
        event.preventDefault();
        $('.valid_domain').fadeIn().css({
          display: 'block'
        });
      }
    });
    $('.del_token').click(function (event) {
      $.ajax({
        type: 'POST',
        url: ajaxurl,
        data: {
          action: 'del_token',
          nonce_code: cht_nonce_ajax.cht_nonce
        },
        success: function (bool) {
          location.reload();
        },
        error: function (xhr, status, error) {

        }
      });
    });

    (function easyModal() {
      $('.easy-modal').easyModal({
        top: 150,
        overlay: 0.2
      });
      $('.easy-modal-open').click(function (e) {
        var target = $(this).attr('href');
        $(target).trigger('openModal');
        e.preventDefault();
      });
      $('.easy-modal-close').click(function (e) {
        e.preventDefault();
        $('.easy-modal').trigger('closeModal');
      });
    }());
    var count_click = 1000000003;
    $('.show_up').click(function () {
      count_click += 10;
      $('#upgrade-modal').css({
        'z-index': count_click,
        display: 'block',
        'margin-left': '-258px'
      });
    });

    (function colorPicker() {
      $('.color-picker-btn, .color-picker-btn-close, .color-picker-custom button').on('click', function (e) {
        e.preventDefault();

        $('.color-picker').toggle();
        $('.color-picker-btn').toggle();
      });

      $('.color-picker-radio input').change(function () {
        var $this = $(this);
        $('.color-picker-custom input[name="cht_custom_color"]').val('');
        $('.color-picker-custom .circle').html('?').css({
          'background-color': '#fff'
        });
        if ($this.prop('checked')) {
          $('.color-picker-radio input').prop('checked', false);
          $this.prop('checked', true);
          var color = $this.val();
          var title = $this.prop('title');
        } else {
          color = $('.color-picker-custom input').val();
          title = 'Custom';
        }

        $('.color-picker-btn .circle').css({ backgroundColor: color });
        $('.color-picker-btn .text').text(title);
      });

      $('.color-picker-custom input').change(function () {
        $('.color-picker-radio input').prop('checked', false);

        var $this = $(this);

        var color = $this.val();

        $('.color-picker-btn .circle').css({ backgroundColor: color });
        $('.color-picker-btn .text').text('Custom');
      });
    }());

    (function customSelect() {
      $('[name="cht_position"]').change(function () {
        if ($('#positionCustom').prop('checked')) {
          $('#positionPro').show();
        } else {
          $('#positionPro').hide();
        }
      });
    }());




    /**
         * add Token
         */

    var AddTokenBtn = $('.update_token');

    AddTokenBtn.on('click', function (e) {
      e.preventDefault();
      var token = $('input[name="cht_license_key"]').val();

      $.ajax({
        type: 'POST',
        url: ajaxurl,
        data: {
          action: 'add_token',
          nonce_code: cht_nonce_ajax.cht_nonce,
          token: token
        },
        beforeSend: function (xhr) {

        },
        success: function (bool) {
          if (bool) {
            alert('Your pro plan is activated');
            location.reload();
          } else {
            alert('You`ve entered a wrong token');
          }
        },
        error: function (xhr, status, error) {

        }
      });
    });
    $('textarea[name=cht_cta]').keyup(function (event) {
      $('.tooltiptext').html($(this).val());
      if ($(this).val().length == 0) {
        $('.cta').hide(200);
        $('.tooltiptext').hide(200);
      } else {
        $('.cta').show(300);
        $('.tooltiptext').show(200);
      }
    });
  });
}(jQuery));

(function ($) {
  $(document).ready(function () {
    (function preview() {
      (function previewColor() {
        $('.color-picker-radio input').change(function () {
          var $this = $(this);

          if ($this.prop('checked')) {
            var color = $this.val();
          } else {
            color = $('.color-picker-custom input').val();
          }
          detectIcon();
        });

        $('.color-picker-custom input').change(function () {
          var $this = $(this);

          var color = $this.val();

          detectIcon();
        });
      }());

      (function previewTooltip() {
        var $widgetTooltip = $('#widgetTooltip');
        var $icon = $('.preview .page .icon');

        function tooltipToggle() {
          if ($('[name=cht_cta]').val().length >= 1) {
            $icon.removeClass('no-tooltip');
          } else {
            $icon.addClass('no-tooltip');
          }
        }

        tooltipToggle();

        $widgetTooltip.change(function () {
          tooltipToggle();
        });
      }());

      function previewPosition() {
        var $inputPosBot = $('#positionBottom');
        var $inputPosSide = $('#positionSide');
        var $chatyWidget = $('.preview .page .chaty-widget');
        var customSpace = '7px';

        var value = $('[name="cht_position"]:checked').val();

        if (value === 'right') {
          $chatyWidget.css({ right: customSpace, left: 'auto', bottom: '7px' });
        } else if (value === 'left') {
          $chatyWidget.css({ left: customSpace, right: 'auto', bottom: '7px' });
        } else if (value === 'custom') {
          if ($inputPosBot.val()) {
            var positionBottom = $inputPosBot.val() + 'px';
          } else {
            positionBottom = customSpace;
          }

          if ($inputPosSide.val()) {
            var positionSide = $inputPosSide.val() + 'px';
          } else {
            positionSide = customSpace;
          }

          $inputPosBot.change(function () {
            positionBottom = $('#positionBottom').val() + 'px';

            $chatyWidget.css({ bottom: positionBottom });
          });

          $inputPosSide.change(function () {
            var valueCustom = $('[name="positionSide"]:checked').val();
            positionSide = $(this).val() + 'px';

            if (valueCustom === 'right') {
              $('.page-body .chaty-widget ').removeClass('chaty-widget-icons-left');
              $('.page-body .chaty-widget ').addClass('chaty-widget-icons-right');
              $chatyWidget.css({ right: positionSide, left: 'auto' });
            } else if (valueCustom === 'left') {
              $('.page-body .chaty-widget ').removeClass('chaty-widget-icons-right');
              $('.page-body .chaty-widget ').addClass('chaty-widget-icons-left');
              $chatyWidget.css({ left: positionSide, right: 'auto' });
            }
          });

          $('[name="positionSide"]').change(function () {
            var valueCustom = $('[name="positionSide"]:checked').val();

            if (valueCustom === 'right') {
              $('.page-body .chaty-widget ').removeClass('chaty-widget-icons-left');
              $('.page-body .chaty-widget ').addClass('chaty-widget-icons-right');
              $chatyWidget.css({ right: positionSide, left: 'auto' });
            } else if (valueCustom === 'left') {
              $('.page-body .chaty-widget ').removeClass('chaty-widget-icons-right');
              $('.page-body .chaty-widget ').addClass('chaty-widget-icons-left');
              $chatyWidget.css({ left: positionSide, right: 'auto' });
            }
          });
        }
        $('[name="cht_position"]').change(function () {
          if ($('#positionCustom').prop('checked')) {
            var $inputPosBot = $('#positionBottom');
            var $inputPosSide = $('#positionSide');
            var $chatyWidget = $('.preview .page .chaty-widget');
            $chatyWidget.css({ bottom: positionBottom });
            var valueCustom = $('[name="positionSide"]:checked').val();
            positionSide = $('#positionSide').val() + 'px';
            if (valueCustom === 'right') {
              $('.page-body .chaty-widget ').removeClass('chaty-widget-icons-left');
              $('.page-body .chaty-widget ').addClass('chaty-widget-icons-right');
              $chatyWidget.css({ right: positionSide, left: 'auto' });
            } else if (valueCustom === 'left') {
              $('.page-body .chaty-widget ').removeClass('chaty-widget-icons-right');
              $('.page-body .chaty-widget ').addClass('chaty-widget-icons-left');
              $chatyWidget.css({ left: positionSide, right: 'auto' });
            }
          }
        });
      }

      previewPosition();


      $('[name="cht_position"]').change(function () {
        var valueCustom = $('[name="cht_position"]:checked').val();

        if (valueCustom === 'right') {
          $('.page-body .chaty-widget ').removeClass('chaty-widget-icons-left');
          $('.page-body .chaty-widget ').addClass('chaty-widget-icons-right');
        } else if (valueCustom === 'left') {
          $('.page-body .chaty-widget ').removeClass('chaty-widget-icons-right');
          $('.page-body .chaty-widget ').addClass('chaty-widget-icons-left');
        }
        previewPosition();
      });
    }());
    $('.popover').hide();
    two_soc();

    var socialIcon = $('.channels-icons > .icon-sm');


    var socialInputsContainer = $('.social-inputs');

    var click = 0;
    $('input[name=cht_custom_color]').keyup(function (event) {
      var color = $(this).val();
      $('.circle').html('');
      $('.color-picker-custom .circle').css({
        'background-color': color
      });
      if ($(this).val().length < 1) {
        $('.color-picker-custom .circle').html('?');
      }
    });
    socialIcon.on('click', function () {
      ++click;
      two_soc();

      var $this = $(this);

      var social = $this.data('social');

      var socialItem = socialInputsContainer.find('.social-form-group');

      if ($this.hasClass('active')) {
        var del = ',' + $(this).attr('data-social');

        var newlocaldata = $('.add_slug').val();
        newlocaldata = newlocaldata.replace(del, '');
        $('.add_slug').val(newlocaldata);
        newlocaldata = newlocaldata.replace(del, '');
        $('.add_slug').val(newlocaldata);
        newlocaldata = newlocaldata.replace(del, '');
        $('.add_slug').val(newlocaldata);
        newlocaldata = newlocaldata.replace(del, '');


        $('.add_slug').val(newlocaldata);

        $this.toggleClass('active');
        return;
      }
      socialIcon.addClass('disabled');
      icon = $(this).data('social');

      if ($('.add_slug').val().indexOf(icon) == '1' && $('.add_slug').val() != '') {
        var del = ',' + icon;
        var newlocaldata = $('.add_slug').val();

        newlocaldata = newlocaldata.replace(del, '');
        $('.add_slug').val(newlocaldata);
        newlocaldata = newlocaldata.replace(del, '');
        $('.add_slug').val(newlocaldata);
        newlocaldata = newlocaldata.replace(del, '');
        $('.add_slug').val(newlocaldata);
      } else {
        $('.add_slug').val($('.add_slug').val() + ',' + $(this).attr('data-social'));
      }


      /*  if($('section').is("#pro")){

             }else if(click >='3'){
                // alert(click);
                $('.popover').show().effect( "shake", {times:3}, 600 );
                click = $('.channels-selected__item.free').length;
                return;


            } */

      if (!$('section').is('#pro') && $('.channels-icons > .icon.active').length >= 2) {
        $('.popover').show().effect('shake', { times: 3 }, 600);
        socialIcon.removeClass('disabled');
        return;
      }

      $this.toggleClass('active');


      if ($('section').is('#pro')) {
        var token = 'pro';
      } else {
        var token = 'free';
      }


      $.ajax({
        type: 'POST',
        dataType: 'json',
        url: ajaxurl,
        data: {
          action: 'choose_social',
          social: social,
          nonce_code: cht_nonce_ajax.cht_nonce,
          version: token
        },
        beforeSend: function (xhr) {

        },
        success: function (data) {
          var item = $(data);
          var itemName = item.find('.icon').data('title');

          if (!$('.channels-selected div[data-title="' + itemName + '"]').length) {
            $('.channels-selected__item.disabled').before(item);
          }


          socialIcon.removeClass('disabled');
          detectIcon();
          two_soc();
        },
        error: function (xhr, status, error) {

        }
      });

      two_soc();
    });


    /**
         * Cancel Btn
         */
    var cancelBtn = $('body');

    cancelBtn.on('click', '.icon, .btn-cancel', function (e) {
      e.preventDefault();

      if ($(this).hasClass('icon') && $(this).hasClass('active')) {
        return;
      }

      icon = $(this).data('social');
      if ($(this).hasClass('btn-cancel')) {
        $('.icon.active[data-social^="' + icon + '"]').removeClass('active');

        var del = ',' + icon;
        var newlocaldata = $('.add_slug').val();
        newlocaldata = newlocaldata.replace(del, '');

        $('.add_slug').val(newlocaldata);
      }
      var del_item = $('.btn-cancel[data-social^="' + icon + '"]').parents('.channels-selected__item');
      del_item.remove();

      var item = $(this).parent('.channels-selected__item');


      var social = $(this).data('social');

      $.ajax({
        type: 'POST',
        dataType: 'json',
        url: ajaxurl,
        data: {
          action: 'remove_social',
          nonce_code: cht_nonce_ajax.cht_nonce,
          social: social
        },
        beforeSend: function (xhr) {

        },
        success: function (bool) {
          if (bool) {
            item.remove();
            del_item.remove();


            $('.icon-sm').each(function () {
              if ($(this).data('social') === social) {
                // $(this).removeClass('active');
              }
            });
          }
        },
        error: function (xhr, status, error) {

        }
      });
      detectIcon();
      two_soc();
    });


    function two_soc() {
      if ($('section').is('#pro')) {
        return;
      }

      if ($('.channels-selected__item').length <= 1) {
        $('.channels-selected__item').hide();
        $('.popover').hide();
      } else if ($('.channels-selected__item').length >= 2) {
        $('.channels-selected__item').show();
      }
    }
    $('.btn-help').click(function (event) {
      window.open(
        'https://premio.io/downloads/chaty/#faq',
        '_blank' // <- This is what makes it open in a new window.
      );
    });


    var freeCustomInput = $('.free-custom-radio, .free-custom-checkbox');

    freeCustomInput.on('click', function (e) {
      e.preventDefault();
    });
    var chatyCta = $('[name=cht_cta]');
    var toolTip = $('.preview .tooltip-show');

    chatyCta.keyup(function () {
      var $icon = $('.preview .page .icon');
      if (chatyCta.val().length >= 1) {
        $icon.removeClass('no-tooltip');
      } else {
        $icon.addClass('no-tooltip');
      }
      toolTip.attr('data-title', chatyCta.val());
    });


    var baseIcon = '<svg version="1.1" id="ch" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="-496 507.7 54 54" style="enable-background:new -496 507.7 54 54;" xml:space="preserve">\n' +
      '                            <style type="text/css">.st0 {fill: #A886CD;}  .st1 {fill: #FFFFFF;}\n' +
      '                        </style><g><circle class="st0" cx="-469" cy="534.7" r="27"/></g><path class="st1" d="M-459.9,523.7h-20.3c-1.9,0-3.4,1.5-3.4,3.4v15.3c0,1.9,1.5,3.4,3.4,3.4h11.4l5.9,4.9c0.2,0.2,0.3,0.2,0.5,0.2 h0.3c0.3-0.2,0.5-0.5,0.5-0.8v-4.2h1.7c1.9,0,3.4-1.5,3.4-3.4v-15.3C-456.5,525.2-458,523.7-459.9,523.7z"/>\n' +
      '                                                    <path class="st0" d="M-477.7,530.5h11.9c0.5,0,0.8,0.4,0.8,0.8l0,0c0,0.5-0.4,0.8-0.8,0.8h-11.9c-0.5,0-0.8-0.4-0.8-0.8l0,0\n' +
      '                            C-478.6,530.8-478.2,530.5-477.7,530.5z"/>\n' +
      '                                                    <path class="st0" d="M-477.7,533.5h7.9c0.5,0,0.8,0.4,0.8,0.8l0,0c0,0.5-0.4,0.8-0.8,0.8h-7.9c-0.5,0-0.8-0.4-0.8-0.8l0,0\n' +
      '                            C-478.6,533.9-478.2,533.5-477.7,533.5z"/>\n' +
      '                        </svg>',
      defaultIcon = '<svg version="1.1" id="ch" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="-496 507.7 54 54" style="enable-background:new -496 507.7 54 54;" xml:space="preserve">\n' +
        '                            <style type="text/css">.st0 {fill: #A886CD;}  .st1 {fill: #FFFFFF;}\n' +
        '                        </style><g><circle class="st0" cx="-469" cy="534.7" r="27"/></g><path class="st1" d="M-459.9,523.7h-20.3c-1.9,0-3.4,1.5-3.4,3.4v15.3c0,1.9,1.5,3.4,3.4,3.4h11.4l5.9,4.9c0.2,0.2,0.3,0.2,0.5,0.2 h0.3c0.3-0.2,0.5-0.5,0.5-0.8v-4.2h1.7c1.9,0,3.4-1.5,3.4-3.4v-15.3C-456.5,525.2-458,523.7-459.9,523.7z"/>\n' +
        '                                                    <path class="st0" d="M-477.7,530.5h11.9c0.5,0,0.8,0.4,0.8,0.8l0,0c0,0.5-0.4,0.8-0.8,0.8h-11.9c-0.5,0-0.8-0.4-0.8-0.8l0,0\n' +
        '                            C-478.6,530.8-478.2,530.5-477.7,530.5z"/>\n' +
        '                                                    <path class="st0" d="M-477.7,533.5h7.9c0.5,0,0.8,0.4,0.8,0.8l0,0c0,0.5-0.4,0.8-0.8,0.8h-7.9c-0.5,0-0.8-0.4-0.8-0.8l0,0\n' +
        '                            C-478.6,533.9-478.2,533.5-477.7,533.5z"/>\n' +
        '                        </svg>',
      iconBlock = document.getElementById('iconWidget'),
      desktopIcon,
      mobileIcon,
      colorFill = jQuery('.color-picker-radio input:checked').val();


    $('#testUpload').on('change', function () {
      if (this.value.length > 0) {
        document.querySelector('.js-upload').disabled = false;
      } else {
        document.querySelector('.js-upload').disabled = true;
        document.getElementById('uploadInput').checked = false;
      }
    });


    $('.js-switch-preview').on('change', function () {
      if(getPreviewDesktop()){
        $(this).closest( ".preview" ).removeClass('mobiel-view');
      }else{
        $(this).closest( ".preview" ).addClass('mobiel-view');
      }
      detectIcon();
    });

    function detectIcon() {
      var desktop,
          mobile,
          colorSelf = false;
      if(getPreviewDesktop()){
        if($('.js-chanel-desktop:checked').length === 0){
          desktop = false;
        }
        if($('.js-chanel-desktop:checked').length === 1){
          desktop = $('.icon[data-title="'+$('.js-chanel-desktop:checked').attr('data-type')+'"]').html();
        }
        if($('.js-chanel-desktop:checked').length > 1){
          desktop = defaultIcon;
          colorSelf = true;
        }
      }else{
        if($('.js-chanel-mobile:checked').length === 0){
          mobile = false;
        }
        if($('.js-chanel-mobile:checked').length === 1){
          mobile = $('.icon[data-title="'+$('.js-chanel-mobile:checked').attr('data-type')+'"]').html();
        }
        if($('.js-chanel-mobile:checked').length > 1){
          mobile = defaultIcon;
          colorSelf = true;
        }
      }


      desktopIcon = desktop;
      mobileIcon = mobile;

      if(getPreviewDesktop()){
        setIcon(desktopIcon,colorSelf)
      }else{
        setIcon(mobileIcon,colorSelf)
      }
    }
    function setIcon(icon,colorSelf) {
      if(icon){
        $('.preview .page .chaty-widget').show();
        iconBlock.innerHTML = icon;
      }else{
        $('.preview .page .chaty-widget').hide();
        iconBlock.innerHTML= '';
      }
      if(colorSelf){
        var color = $('.color-picker-custom input').val() ? $('.color-picker-custom input').val() : $('.color-picker-radio input:checked').val();
        $('.preview .page svg circle').css({ fill: color });
      }
    }

    function getPreviewDesktop() {
      return $('#previewDesktop').attr('checked') === 'checked' ? true : false;
    }

    function changeWidgetIcon() {

      $(document).on('change', '.js-chanel-icon', function () {
        detectIcon();
      });

      function calc(a) {
        var count={}, res=0, q;
        for (q=0; q<a.length; ++q) {
          count[a[q].dataset.type] = ~~count[a[q].dataset.type] + 1;
        }
        for (q in count) {
          if (count.hasOwnProperty(q) && count[q] > 1) {
            res += count[q];
          }
        }
        return res;
      }



      $(document).on('change', '.js-widget-i', function (ev) {
        if (ev.target.classList.contains('js-upload')) {
          defaultIcon = $('.file-preview-image').last().parent().html();
        } else {
          defaultIcon = $('i[data-type='+ev.target.dataset.type+']').html()
        }
        detectIcon();
      });
    }
    changeWidgetIcon();



    if($(".js-widget-i:checked").attr("data-type") !== 'chat-image'){
      defaultIcon = $('i[data-type='+$(".js-widget-i:checked").attr("data-type")+']').html();
      detectIcon();
    };




  });
}(jQuery));



