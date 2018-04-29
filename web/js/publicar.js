var formatVideojuego = function (videojuego) {
    if (videojuego.loading) {
        return videojuego.nombre;
    }
    var markup =
        '<div class="row">' +
            '<div class="col-sm-12">' +
            videojuego.nombre + ' ' +
            badgePlataforma(videojuego.plataforma.nombre) +
            '</div>' +
        '</div>';
    return markup;
};

function peticionDetalles(url) {
    $.ajax({
        url: url,
        data: {id: $("#videojuegosusuarios-videojuego_id").val()},
        dataType: 'html',
        success: function (data) {
            $('#detalles').html(data);
        },
        beforeSend: function () {
            var row = $('<div></div>');
            row.addClass('row');

            var loader = $('<div></div>');
            loader.addClass('loader');
            loader.addClass('center-block')
            row.append(loader);

            var cargando = $('<div></div>');
            cargando.addClass('row');
            cargando.append($('<h3 class="text-center">Cargando detalles...</h3>'));

            var padre = $('<div></div>');
            padre.append(cargando);
            padre.append(row);

            $('#detalles').append(padre);
        }
    })
}
