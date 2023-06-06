<?php

define('DATA_PATH', './data/');
define('MODEL_PATH', './model/');

$settings = null;

// Read settings
$settings_json = file_get_contents(DATA_PATH . '/settings.json');
$settings = json_decode($settings_json, true);

function get_available_blocks()
{
    $blocks = [];

    // Get all files inside blocks folder and loop
    $blocks_files = glob(MODEL_PATH . '/blocks/*.{json}', GLOB_BRACE);
    foreach ($blocks_files as $block_file) {
        // Get contents
        $block_json = file_get_contents($block_file);

        if (!$block_json) continue;

        // Decode JSON as associative array
        $block = json_decode($block_json, true);

        if (!$block) continue;

        // Get file name to use as block ID
        $block_id = str_replace('.json', '', $block_file);
        $block_id = explode('/', $block_id)[0];
        $block['id'] = $block_id;

        // Push new block to blocks array
        array_push($blocks, $block);
    }

    return $blocks;
}

function get_model($name)
{
    $model_json = file_get_contents(MODEL_PATH . "/$name.json");
    return json_decode($model_json, true);
}

function get_data($name)
{
    $model_json = file_get_contents(DATA_PATH . "/$name.json");
    return json_decode($model_json, true);
}

function render_field($field_name, $field, $value, $parent_block = null, $echo = true)
{
    $type = $field['type'];
    $label = $field['label'];

    $name = ($parent_block)
        ? $parent_block . '[' . $field_name . ']'
        : $field_name;

    ob_start(); // Start HTML buffering
?>
    <div class="field <?php echo $type; ?>">
        <label for="<?php echo $name; ?>">
            <?php echo $label ?>
        </label>
        <?php

        switch ($type):
            case 'string':
        ?>
                <input type="text" class="form-control" id="<?php echo $name ?>" name="<?php echo $name; ?>" value="<?php echo $value; ?>">
            <?php
                break;

            case 'rich':
            ?>
                <div class="rich-editor form-control"><?php echo $value; ?></div>
            <?php
                break;

            case 'textarea':
            ?>
                <textarea name="<?php echo $name; ?>" id="<?php echo $name ?>" class="form-control" rows="2"><?php echo $value; ?></textarea>
            <?php
                break;

            case 'blocks':
                $allowed_blocks = (isset($field['allowed_blocks']))
                    ? htmlspecialchars(json_encode($field['allowed_blocks']))
                    : "[\"all\"]";
            ?>
                <input type="hidden" class="render-helper" name="allowed_blocks" value="<?php echo $allowed_blocks; ?>">
                <input type="hidden" class="render-helper" name="block_group_name" value="<?php echo $name; ?>">
        <?php
                render_block($value, $field, $name);
                break;

        endswitch;

        ?>
    </div>
    <?php

    $output = ob_get_contents(); // collect buffered contents

    ob_end_clean(); // Stop HTML buffering

    // Echo or return contents
    if ($echo)
        echo $output;
    else
        return $output;
}

function render_block($blocks, $field_attributes, $block_group_name = null)
{
    $expanded = false;
    $header_tag = ($expanded) ? 'h3' : 'h4';

    if ($blocks == null) {
        $blocks = $field_attributes['allowed_blocks'] ?? [];
    }

    $allow = array(
        'add' =>false,
        'remove' =>false,
        'reorder' =>false,
    );
    if (isset($field_attributes['allow'])) {
        $allow['add'] = $field_attributes['allow']['add'] ?? false;
        $allow['remove'] = $field_attributes['allow']['remove'] ?? false;
        $allow['reorder'] = $field_attributes['allow']['reorder'] ?? false;
    }

    ?>
    <input type="hidden" class="render-helper" name="allow" value="<?php echo htmlspecialchars(json_encode($allow)); ?>">
    <input type="hidden" class="render-helper" name="header_tag" value="<?php echo ($header_tag); ?>">

    <div class="blocks">
    <?php
    foreach ($blocks as $i => $block) :

        render_block_field($i, $block, $block_group_name, $allow, $expanded, $header_tag);

    endforeach;
    ?>
    </div>
    <?php

    // Show add button to add new blocks
    if ($allow['add']) {
    ?>
    <div class="d-flex align-items-center add-new">
        <button class="btn-add-block btn icon-btn" type="button" title="Add new block">
            <span class="icon bi-plus-lg"></span>
            <span class="text">Add new</span>
        </button>
        <small class="ms-2" style="display: none;">No block was selected.</small>
    </div>
    <?php
    }
}

function render_block_field($index, $block, $block_group_name, $allow = [], $expanded = false, $header_tag = 'h3')
{

    $block_id = isset($block['id'])
        ? $block['id']
        : $block;

    $block_model = get_block_model($block_id);

    if(isset($block_model['expanded']))
        $expanded = $block_model['expanded'];

    $accordion_id = $block_id . random_int(0, 99);

    $accordion_title = $block_model['title'];

    $block_field_name = ($block_group_name)
        ? $block_group_name . '[' . $index . ']' . '[' . $block_id . ']'
        : $block_id;

    ?>
    <fieldset class="block-field accordion-item" name="<?php echo $block_field_name; ?>">
        <<?php echo $header_tag; ?> class="block-title accordion-header" id="heading_<?php echo $accordion_id; ?>">
            <button class="btn-header" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_<?php echo $accordion_id; ?>" aria-expanded="true" aria-controls="collapse_<?php echo $accordion_id; ?>">
                <span class="bi-<?php echo $block_model['icon']; ?>"></span>
                <?php echo $accordion_title; ?>
            </button>
        </<?php echo $header_tag; ?>>


        <div id="collapse_<?php echo $accordion_id; ?>" class="accordion-collapse collapse <?php if ($expanded) echo 'show'; ?>" aria-labelledby="heading_<?php echo $accordion_id; ?>">

            <div class="accordion-body">
                <div class="options">
                    <?php if ($allow['remove']) : ?>
                        <button class="btn-remove-block btn icon-btn" type="button" title="Remove block">
                            <span class="icon bi-x-lg"></span>
                            <span class="text">Remove</span>
                        </button>
                    <?php endif; ?>
                    <?php if ($allow['reorder']) : ?>
                        <button class="btn-moveup-block btn icon-btn" type="button" title="Move block up">
                            <span class="icon bi-arrow-up"></span>
                        </button>
                        <button class="btn-movedown-block btn icon-btn" type="button" title="Move block down">
                            <span class="icon bi-arrow-down"></span>
                        </button>
                    <?php endif; ?>

                </div>
                <?php foreach ($block_model['attributes'] as $key => $field) :

                    $value = isset($block['data'])
                        ? $block['data'][$key] ?? null
                        : null;
                    render_field($key, $field, $value, $block_field_name);

                endforeach; ?>
            </div>
        </div>
    </fieldset>
<?php
}

function get_block_model($id)
{
    // Get contents
    $block_json = file_get_contents(MODEL_PATH . "/blocks/$id.json");

    if (!$block_json) return null;

    // Decode JSON as associative array
    $block = json_decode($block_json, true);

    return $block;
}
