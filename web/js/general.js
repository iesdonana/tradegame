$(function () {
  var lastScrollTop = 0;
  var $navbar = $('.navbar');

  $(window).scroll(function(event){
    var st = $(this).scrollTop();

    if (st > lastScrollTop) {
      $navbar.fadeOut()
    } else {
      $navbar.fadeIn()
    }
    lastScrollTop = st;
  });
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
    console.log('hola');
    var btn = $(this).find('button');
    btn.prop('disabled', true);
    var i = $('<i></i>');
    i.addClass('fa fa-spinner fa-spin');
    btn.append(i);
});
