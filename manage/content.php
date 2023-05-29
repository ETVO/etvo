<?php

$active_menu = 'content';

include './partials/header.php';

$edit_blocks = $settings['allow_editing_blocks'];

$model = get_model('content');
$data = get_data('content');

?>

<main class="content container">
    <div class="heading">
        <h1 class="title">Content</h1>
        <p class="desc">Edit the content of your main page.</p>
    </div>
    <!-- <button class="btn btn-primary">Add a block</button> -->

    <?php if ($edit_blocks) :
        $blocks = get_available_blocks();
    ?>
        <div class="section-title">
            Available blocks
        </div>
        <div class="available-blocks">
            <?php foreach ($blocks as $block) : ?>
                <button class="block" title="<?php echo $block['name']; ?>">
                    <span class="bi-<?php echo $block['icon']; ?>"></span>
                    <div class="name">
                        <?php echo $block['name']; ?>
                    </div>
                </button>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="model-view">
        <?php foreach ($model as $key => $field) :

            $value = $data[$key] ?? null;
            render_field($key, $field, $value);
            
        endforeach; ?>
    </div>
</main>


<?php

include './partials/footer.php';

?>