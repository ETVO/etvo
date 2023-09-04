import 'bootstrap';
import 'fslightbox';
import './contact.js';

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

        $('.project').on('click', function () {
            var path = $(this).data('project-path');
            var year = $(this).data('project-year');

            $.getJSON(path, function (info) {
                console.log(info);

                var images = (() => {
                    let images = [];
                    let imageKeys = Object.keys(info.images);
                    imageKeys.forEach((key) => {
                        var image = info.images[key];
                        var src = image.src;
                        var style = image.style ?? '';

                        images.push(`<img src="${src}" style="${style}" />`);
                    })

                    return images;
                })();

                var modal = $('#projectModal');

                $(modal).find('.title').html(info.title);
                $(modal).find('.link').attr('href', info.link);
                $(modal).find('.desc').html(info.description);
                $(modal).find('.tech').html(info.tech);
                $(modal).find('.year').html(year);
                $(modal).find('.images').html(images.join(' '));

                $(modal).fadeIn(200);
            });

        });

        $('#closeModal').on('click', function () {
            $('#projectModal').fadeOut(200);
        });
        $('#projectModal').on('click', function (e) {
            if (e.target.id == 'projectModal')
                $('#projectModal').fadeOut(200);
        });
        $('#projectModal .action').on('click', function (e) {
            $('#projectModal').fadeOut(200);
        });
    }
)