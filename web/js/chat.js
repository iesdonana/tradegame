function peticionConversacion(url, receptor_id, intervalo = false)
{
    var scrollActual = $('.scrollable').scrollTop();
    $.ajax({
        url: url,
        data: {usuario: $('.nav-pills li.active').find('a').find('.usuario').text().trim()},
        success: function (content) {
            var old = $('.mensajes').html();
            $('.mensajes').html(content);
            if (!intervalo) {
                $('.nav-pills').find('a[data-id=' + receptor_id + ']').parent().addClass('active');
                $('#mensajes-receptor_id').val(receptor_id);
                $('#mensajes-contenido').val('');
            }
            if ($('.mensajes').html() != old) {
                $('.scrollable').scrollTop($('.scrollable')[0].scrollHeight);
            } else {
                $('.scrollable').scrollTop(scrollActual);
            }
        }
    });
}
