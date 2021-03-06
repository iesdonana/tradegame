var old = [];

function peticionConversacion(urlConver, urlNuevos, receptor_id, intervalo = false)
{
    $.ajax({
        url: urlNuevos,
        data: {id: $('.nav-pills li.active').find('a').data('id')},
        success: function(data) {
            if (data.join(',') != old.join(',')) {
                old = data;
                $.ajax({
                    url: urlConver,
                    data: {id: $('.nav-pills li.active').find('a').data('id')},
                    beforeSend: function () {
                        var row = $('<div></div>');
                        row.addClass('row');

                        var loader = $('<div></div>');
                        loader.addClass('loader');
                        loader.addClass('center-block')
                        row.append(loader);

                        var cargando = $('<div></div>');
                        cargando.addClass('row');
                        var padre = $('<div></div>');
                        padre.addClass('loading');
                        padre.append(cargando);
                        padre.append(row);

                        $('.mensajes').append(padre);
                    },
                    success: function (content) {
                        $('.mensajes').find('.loading').remove();
                        $('.mensajes').html(content);

                        if (!intervalo) {
                            $('.nav-pills').find('a[data-id=' + receptor_id + ']').parent().addClass('active');
                            $('#mensajes-receptor_id').val(receptor_id);
                            $('#mensajes-contenido').val('');
                        }
                        $('.scrollable').scrollTop($('.scrollable')[0].scrollHeight);
                        $('.cargaForm button').prop('disabled', false);

                        if (receptor_id === 1) {
                            $('.nuevo-mensaje').addClass('hidden');
                        } else {
                            $('.nuevo-mensaje').removeClass('hidden');
                        }
                    },
                });
            }
        },
    });

}
