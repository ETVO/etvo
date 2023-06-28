
(jQuery)(
    function ($) {

        $(document).on('click', '.image-upload .as-file', function (e) {
            const $this = e.target;
            const imageField = $($this).parents('.image-upload').eq(0);
            $(imageField).children('input.url').hide();
            $(imageField).children('input.file').fadeIn(200);
        });

        $(document).on('click', '.image-upload .as-url', function (e) {
            const $this = e.target;
            const imageField = $($this).parents('.image-upload').eq(0);
            $(imageField).children('input.file').hide();
            $(imageField).children('input.url').fadeIn(200);
        });

        $(document).on('change', '.image-upload input.file', function (e) {
            const $this = e.target;
            readURL($this);
            $($this).siblings('input.url').val('');
        });

        $(document).on('change', '.image-upload input.url', function (e) {
            const $this = e.target;
            var src = $($this).val();
            $($this).siblings('input.file').val('');
            afterChangeImage($this, src, src);
        });

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    var src = e.target.result;
                    afterChangeImage(input, src, null);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        function afterChangeImage(input, src, value) {
            $(input).siblings('.preview').attr('src', src);
            $(input).siblings('input.value').val(value);
            $(input).siblings('.preview').fadeIn(200);
            $(input).siblings('.remove').fadeIn(200);
        }
        
        $(document).on('click', '.image-upload .remove', function (e) {
            const $this = e.target;
            const imageField = $($this).parents('.image-upload').eq(0);
            $(imageField).children('.remove').fadeOut(200);
            $(imageField).children('.preview').fadeOut(200);
            $(imageField).children('.preview').attr('src', '');
            $(imageField).children('input.file, input.url, input.value').val('');
        });

    }
)