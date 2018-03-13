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
        data: {id: $("#videojuegosusuarios-videojuego_id").children("option").last().val()},
        dataType: 'html',
        success: function (data) {
            $('#detalles').html(data);
        }
    })
}
