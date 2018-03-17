$('input[name=videojuegos]').on('keyup', function(e) {
    if (e.keyCode == 13) {
        window.location.href = baseUrl + '?q=' + encodeURIComponent($(this).val());
    }
});
