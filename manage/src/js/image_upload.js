
(jQuery)(
    function ($) {


        $(".image-upload .as-file").click(function () {
            $('.image-upload input.url').hide();
            $('.image-upload input.file').fadeIn(200);
        });

        $(".image-upload .as-url").click(function () {
            $('.image-upload input.file').hide();
            $('.image-upload input.url').fadeIn(200);
        });

        $(".image-upload input.file").change(function () {
            readURL(this);
            $(this).siblings('input.url').val('');
        });
        $(".image-upload input.url").change(function () {
            var src = $(this).val();
            $(this).siblings('input.file').val('');
            afterChangeImage(this, src);
        });

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    afterChangeImage(input, e.target.result);
                }
                
                reader.readAsDataURL(input.files[0]);
            }
        }
        
        function afterChangeImage(input, src) {
            $(input).siblings('.preview').attr('src', src);
            $(input).siblings('.preview').fadeIn(200);
            $(input).siblings('.remove').fadeIn(200);
            $(input).siblings('input.value').val('');
        }

        $(".image-upload .remove").click(function () {
            $(this).fadeOut(200);
            $(this).siblings('.preview').fadeOut(200);
            $(this).siblings('input').val('');
            $(this).siblings('.preview').attr('src', '');
        });

    }
)