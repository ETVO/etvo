import 'bootstrap';

(jQuery)(
    function ($) {
        $('[data-bs-toggle="tooltip"]').tooltip();

        $(window).scroll(function () {
            var header = $('#header'),
                scroll = $(window).scrollTop();

            if (scroll >= 30) {
                header.addClass('fixed');
            }
            else header.removeClass('fixed');
        });
    }
)