$(function () {
  var lastScrollTop = 0;
  var $navbar = $('.navbar');

  $(window).scroll(function(event){
    var st = $(this).scrollTop();

    $navbar.stop();
    if (st > lastScrollTop && (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100)) {
        $navbar.fadeOut();
    } else {
        $navbar.fadeIn();
    }
    lastScrollTop = st;
  });
});

function cambiarIdioma(lang) {
    $.post(langPath, {'lang': lang}, function(data) {
        location.reload();
    });
}

$('.flag-selectable').on('click', function() {
    cambiarIdioma($(this).children('img').data('lang'));
});


window.onscroll = function() {scrollFunction()};

function scrollFunction() {
    if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
        $('.scrollTop').css({display: 'block'});
    } else {
        $('.scrollTop').css({display: 'none'});
    }
}

function topFunction() {
    $('html, body').animate({scrollTop: 0});
}

$('.scrollTop').on('click', topFunction);

$('.cargaForm').on('beforeSubmit', function() {
    var btn = $(this).find('button');
    btn.prop('disabled', true);
    var i = $('<i></i>');
    i.addClass('fa fa-spinner fa-spin');
    btn.append(i);
});

$('.panel-admin .title').on('click', function() {
    var panel = $(this).siblings('.panel');
    if (panel.css('display') === 'block') {
        panel.slideUp();
        $(this).html('Panel admin ' + '<i class="fa fa-angle-down"></i>');
        $.cookie('panel', false);
    } else {
        panel.slideDown();
        $(this).html('Panel admin ' + '<i class="fa fa-angle-up"></i>');
        $.cookie('panel', true);
    }
});

function escapeHtml(text) {
  var map = {
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#039;'
  };

  return text.replace(/[&<>"']/g, function(m) { return map[m]; });
}

(function($) {
    $.fn.goTo = function() {
        var val = 50;
        if (screen.width <= 768) {
            val = 0;
        }
        $('html, body').animate({
            scrollTop: (parseInt($(this).offset().top) - val) + 'px'
        }, 'fast');
        return this;
    }
})(jQuery);
