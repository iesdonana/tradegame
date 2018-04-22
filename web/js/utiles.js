function badgePlataforma(plataforma) {
    var clase = '';
    switch (plataforma) {
        case 'PlayStation 4':
            clase = 'badge badge-ps4';
            break;
        case 'PlayStation 3':
            clase = 'badge';
            break;
        case 'PlayStation 2':
            clase = 'badge badge-ps2';
            break;
        case 'Nintendo Switch':
            clase = 'badge badge-switch';
            break;
        case 'PC':
            clase = 'badge badge-pc';
            break;
        case 'XBOX 360':
            clase = 'badge badge-xbox360';
            break;
        case 'XBOX One':
            clase = 'badge badge-xboxone';
            break;
    }

    return `<span class="${clase}">${plataforma}</span>`
}

$('.caracteresRestantes').each(function() {
    var campo = $(this).next().val().trim();
    if (campo != undefined && campo != '') {
        var valor = $(this).next().data('length') - campo.length;
        if (valor <= 0) {
            $(this).prev().css('display', 'none');
        } else {
            $(this).prev().css('display', 'inline');
        }
        $(this).text(valor);
    }
})
$('.caracteresRestantes').next().on('keyup', function() {
    var valor = parseInt($(this).data('length')) - $(this).val().trim().length;
    var estilo = 'inline'
    if (valor <= 0) {
        estilo = 'none';
    }
    $(this).prev().css('display', estilo);
    $(this).prev().text(valor);
});
