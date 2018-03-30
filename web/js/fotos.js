function cargarImagen(input) {

  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function(e) {
        $('#img-edit').siblings('a').remove();
        $('#img-edit').attr('src', e.target.result);
        var a = $('<a>');
        a.addClass('badge-corner');
        a.addClass('badge-corner-base');
        a.attr('title', 'Pendiente de subida');
        var span = $('<span></span>');
        span.addClass('glyphicon glyphicon-cloud-upload');
        a.append(span);
        $('#img-edit').parent().append(a);
    }

    reader.readAsDataURL(input.files[0]);
  }
}

$(".preview_control").change(function() {
    cargarImagen(this);
});
