$(function () {
       function closeSearch() {
           var $form = $('.navbar-collapse form[role="search"].active')
           $form.find('input').val('');
           $form.removeClass('active');
       }

       // Show Search if form is not active // event.preventDefault() is important, this prevents the form from submitting
       $(document).on('click', '.navbar-collapse form[role="search"]:not(.active) button[type="submit"]', function(event) {
           event.preventDefault();
           var $form = $(this).closest('form'),
               $input = $form.find('input');
           $form.addClass('active');
            if (screen.width > 768) {
                $form.prev().css('visibility', 'hidden');
            }
           $input.focus();
       });
       // ONLY FOR DEMO // Please use $('form').submit(function(event)) to track from submission
       // if your form is ajax remember to call `closeSearch()` to close the search container
       $(document).on('click', '.navbar-collapse form[role="search"].active button[type="submit"]', function(event) {
           event.preventDefault();
           var $form = $(this).closest('form'),
               $input = $form.find('input');
            if (screen.width > 768) {
                $form.prev().css('visibility', 'visible');
            }

           console.log(event.keyCode);
           console.log('hola');
           closeSearch();

           if (event.keyCode == 13) {
               window.location.href = baseUrl + '?q=' + encodeURIComponent($('input[name="videojuegos"]').val());
           }
       });

       $('input[name=videojuegos]').on('keydown', function(e) {
           if (e.keyCode == 13) {
               e.preventDefault();
               window.location.href = baseUrl + '?q=' + encodeURIComponent($(this).val());
           }
       });


   });
