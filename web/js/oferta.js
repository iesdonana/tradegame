function vaciarDatos()
{
    $('#mi-oferta-caratula').prop('src', defaultImg);
    $('#mi-oferta-titulo').empty();
}

function peticionVideojuego(url)
{
    $.ajax({
        url: url,
        data: {id: $('#ofertas-videojuego_ofrecido_id').val()},
        dataType: 'json',
        success: function (data) {
            $('#mi-oferta-caratula').prop('src', data.caratula);
            $('#mi-oferta-titulo').text(data.titulo);
        }
    })
}
