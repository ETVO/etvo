import 'bootstrap';
import 'fslightbox';
import './contact.js';
import './projects.js';

(jQuery)(
    function ($) {
        $('[data-bs-toggle="tooltip"]').tooltip();

        if(enableStickyHeader) {
            $(window).scroll(function () {
                var header = $('#header'),
                    scroll = $(window).scrollTop();
    
                if (scroll >= 30) {
                    header.addClass('fixed');
                }
                else header.removeClass('fixed');
            });
        }
    }
)