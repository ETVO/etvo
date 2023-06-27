import 'bootstrap';
import 'fslightbox';
import './contact.js';

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

        $('.project').on('click', function () {
            var slug = $(this).data('project-slug');
            var year = $(this).data('project-year');

            $.getJSON('/projects/' + slug + '.json', function (info) {

                var images = (() => {
                    let images = [];
                    info.images.forEach((image) => {
                        var src = '/assets/img/projects/';
                        src += slug + '/';
                        src += image.filename;

                        var style = image.style ?? '';

                        images.push(`<img src="${src}" style="${style}" />`);
                    });
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
    }
)