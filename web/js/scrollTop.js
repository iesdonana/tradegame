window.onscroll = function() {scrollFunction()};

function scrollFunction() {
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
        $('.scrollTop').css({display: 'block'});
    } else {
        $('.scrollTop').css({display: 'none'});
    }
}

function topFunction() {
    $('html, body').animate({scrollTop: 0});
}

$('.scrollTop').on('click', topFunction);
