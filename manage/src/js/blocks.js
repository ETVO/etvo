import './image_upload.js';

(jQuery)(
    function ($) {
        const UTIL_URL = '/manage/util/';

        $('form').on('submit', function (e) {
            e.preventDefault();
            $('input.render-helper').attr('disabled', true);
            $('.image-upload').each(function () {
            });
            this.submit();
        })

        $(document).on('click', '.btn-add-block', function () {
            const $blocks_div = $(this).parent('.add-new').siblings('.blocks');
            const $parent_field = $blocks_div.parent('.field');

            let index = $blocks_div.children().length;
            let block_id = '';
            var allowed_blocks = JSON.parse($parent_field.find('input[name="allowed_blocks"]').val());
            const block_group_name = $parent_field.find('input[name="block_group_name"]').val();
            const allow = JSON.parse($parent_field.find('input[name="allow"]').val());
            const expanded = true; // start expanded by default 

            let allAllowed = false;
            if (allowed_blocks.length == 0) {
                allAllowed = true;
            }
            else if (allowed_blocks.length == 1) {
                if (allowed_blocks[0] == 'all') {
                    allAllowed = true;
                }
                else {
                    block_id = allowed_blocks[0];
                }
            }

            if (block_id == '') { // user needs to choose which block to add
                if (allAllowed) {
                    // yet to be finished...
                    allowed_blocks = fetchBlocksIds();
                }
                else {
                    block_id = prompt(
                        'Please write the name of the desired block.' +
                        '\nALLOWED BLOCKS: (' + allowed_blocks.join(', ') + ')',
                        allowed_blocks[0]
                    );
                }
            }

            if (!block_id) {
                let $notice = $(this).siblings('small');
                $notice.fadeIn(200);
                setTimeout(() => {
                    $notice.fadeOut(200);
                }, 2000)
                return;
            }

            $.ajax({
                url: UTIL_URL + 'use_util.php',
                data: {
                    function: 'render_block_field',
                    args: JSON.stringify([
                        block_id + ':' + index,
                        null,
                        block_group_name,
                        allow,
                        expanded
                    ])
                },
                dataType: "html",
                success: function (data) {
                    $blocks_div.append(data);
                    trackFieldAsTitle($blocks_div.children('.block-field').last());
                }
            });


        });


        $(document).on('click', '.btn-remove-block', function () {
            const $block_field = $(this).parents('.block-field').eq(0);

            if (confirm('Are you sure you want to remove this block and lose all its content?')) {
                $block_field.fadeOut(500, function () {
                    $block_field.remove();
                });
            }
        });


        $(document).on('click', '.btn-moveup-block', function () {
            move(this, 1)
        });
        $(document).on('click', '.btn-movedown-block', function () {
            move(this, 2)
        });

        function move(block, direction) {
            const $block_field = $(block).parents('.block-field').eq(0);
            const $blocks_div = $block_field.parent('.blocks');

            var name = $block_field.attr('name').split('][');
            var index = name[name.length - 2];


            if (direction == 1) {
                if (index == 0) return;
                $block_field.prev().before($block_field);
            }

            if (direction == 2) {
                if (index == $blocks_div.children().length - 1) return;
                $block_field.next().after($block_field);
            }

            // updateIndexes($blocks_div);
        }

        function updateIndexes(div) {

            $(div).children('.block-field').each((i, el) => {
                var name = $(el).attr('name').split('][');
                name[name.length - 2] = i.toString();
                name = name.join('][');
                $(el).attr('name', name);
            });
        }

        $('.block-field').each(function () { trackFieldAsTitle(this) });

        function trackFieldAsTitle(blockField) {
            const field_as_title = $(blockField).data('field-as-title');
            if (field_as_title == '') return;
            const blockName = $(blockField).attr('name');

            var fieldName = blockName + '[' + field_as_title + ']';
            
            $(blockField).find('[name="' + fieldName + '"').on('keyup change', function () {
                updateBlockFieldTitle(blockField, $(this).val());
            });
        }

        function updateBlockFieldTitle(blockField, value = null) {
            const block_id = $(blockField).data('block-id');
            var blockTitle = $(blockField).find('#blockTitle').eq(0);

            if (block_id != 'image' && value != null) {
                blockTitle.text(value);
            }
            else {
                blockTitle.text(blockTitle.data('og-title'));
            }
        }

    }
)