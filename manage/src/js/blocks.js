
(jQuery)(
    function ($) {
        // render_block_field($block, $block_group_name, $allow, $expanded, $header_tag);

        $('form').on('submit', function(e) {
            e.preventDefault();
            $('input.render-helper').attr('disabled', true);
            this.submit();
        })

        $(document).on('click', '.btn-add-block', function () {
            $parent_field = $(this).parents('.field').eq(0);
            $blocks_div = $(this).parent('.add-new').siblings('.blocks');

            let index = $blocks_div.children().length;
            let block_id = '';
            var allowed_blocks = JSON.parse($parent_field.find('input[name="allowed_blocks"]').val());
            const block_group_name = $parent_field.find('input[name="block_group_name"]').val();
            const allow = JSON.parse($parent_field.find('input[name="allow"]').val());
            const expanded = true; // start expanded by default 
            const header_tag = $parent_field.find('input[name="header_tag"]').val();

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
                url: '/manage/use_util.php',
                data: {
                    function: 'render_block_field',
                    args: JSON.stringify([
                        index,
                        block_id,
                        block_group_name,
                        allow,
                        expanded,
                        header_tag
                    ])
                },
                dataType: "html",
                success: function (data) {
                    $blocks_div.append(data);
                }
            });

        });


        $(document).on('click', '.btn-remove-block', function () {
            $block_field = $(this).parents('.block-field').eq(0);

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
            $block_field = $(block).parents('.block-field').eq(0);
            $blocks_div = $block_field.parent('.blocks');

            var name = $block_field.attr('name').split('][');
            index = name[name.length - 2];


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

    }
)