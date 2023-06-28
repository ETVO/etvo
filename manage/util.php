<?php

define('DATA_PATH', dirname(__FILE__) . './data/');
define('MODEL_PATH', dirname(__FILE__) . './model/');

$settings = null;

// Read settings
$settings = get_data('settings');

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
    if (!$model_json) return null;
    return json_decode($model_json, true);
}

function get_block_model($id)
{
    return get_model("blocks/$id");
}

function get_data($name)
{
    $data_json = file_get_contents(DATA_PATH . "/$name.json");
    if (!$data_json) return null;
    return json_decode($data_json, true);
}

function get_data_from_dir($uri)
{
    $data_json = file_get_contents($uri);
    if (!$data_json) return null;
    return json_decode($data_json, true);
}

function render_field($field_name, $field, $value, $parent_block = null, $echo = true, $data_source = null)
{
    $type = $field['type'];
    $label = $field['label'];

    $has_parent = $parent_block != null;

    $name = ($has_parent)
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

            case 'image':
            ?>
                <div class="image-upload">
                    <input type="hidden" name="has_image[]" value="<?php echo $name ?>">

                    <?php if ($value) : ?>
                        <img class="preview" src="<?php echo $value; ?>">
                    <?php else : ?>
                        <img class="preview" style="display: none;">
                    <?php endif; ?>
                    <button class="remove btn icon-btn my-2" type="button" title="Remove image" <?php if (!$value) echo 'style="display: none;"' ?>>
                        <span class="icon bi-x-lg"></span>
                        <span class="text">Remove</span>
                    </button>

                    <input type="file" class="file form-control" name="<?php echo $name ?>" style="display: none">
                    <input type="text" class="url form-control" name="<?php echo $name ?>" style="display: none" placeholder="Image URL">
                    <input type="hidden" class="value" name="<?php echo $name ?>" style="display: none" value="<?php echo $value; ?>">

                    <div class="d-flex load-options mb-2">
                        <button class="as-file btn icon-btn me-2" type="button" title="Remove block">
                            <span class="icon bi-file-earmark-image"></span>
                            <span class="text">Load as File</span>
                        </button>
                        <button class="as-url btn icon-btn" type="button" title="Remove block">
                            <span class="icon bi-link-45deg"></span>
                            <span class="text">Load by URL</span>
                        </button>
                    </div>
                </div>
            <?php
                break;

            case 'blocks':
                $save_in_dir = $field['save_in_dir'] ?? false;

                $allowed_blocks = (isset($field['allowed_blocks']))
                    ? htmlspecialchars(json_encode($field['allowed_blocks']))
                    : "[\"all\"]";
            ?>
                <?php if ($save_in_dir) : ?>
                    <input type="hidden" name="save_in_dir[]" value="<?php echo $name; ?>">
                <?php endif; ?>
                <input type="hidden" class="render-helper" name="allowed_blocks" value="<?php echo $allowed_blocks; ?>">
                <input type="hidden" class="render-helper" name="block_group_name" value="<?php echo $name; ?>">
        <?php
                render_block($value, $field, $name, $save_in_dir, $has_parent);
                break;

            case 'block':
                render_single_block($value, $field, $name);
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

function render_block($blocks, $field_attributes, $block_group_name = null, $save_in_dir = false, $has_parent = true)
{

    if ($save_in_dir) {
        foreach ($blocks as $key => $block_path) {
            $blocks[$key] = get_data_from_dir($block_path['dir']);
        }
    }

    if ($blocks == null) {
        $blocks = $field_attributes['preset'] ?? ($field_attributes['allowed_blocks'] ?? []);
    }

    $allow = array(
        'add' => false,
        'remove' => false,
        'reorder' => false,
    );
    if (isset($field_attributes['allow'])) {
        $allow['add'] = $field_attributes['allow']['add'] ?? false;
        $allow['remove'] = $field_attributes['allow']['remove'] ?? false;
        $allow['reorder'] = $field_attributes['allow']['reorder'] ?? false;
    }

?>
    <input type="hidden" class="render-helper" name="allow" value="<?php echo htmlspecialchars(json_encode($allow)); ?>">

    <div class="blocks">
        <?php
        foreach ($blocks as $id => $block) :

            render_block_field($id, $block, $block_group_name, $allow);

        endforeach;
        ?>
    </div>
    <?php

    // Show add button to add new blocks
    if ($allow['add']) {

        $class = (!$has_parent) ? 'top-level' : '';
    ?>
        <div class="d-flex align-items-center add-new <?php echo $class; ?>">
            <button class="btn-add-block btn icon-btn" type="button" title="Add new block">
                <span class="icon bi-plus-lg"></span>
                <span class="text">Add new</span>
            </button>
            <small class="ms-2" style="display: none;">No block was selected.</small>
        </div>
    <?php
    }
}

function render_single_block($block, $field_attributes, $block_group_name = null)
{
    $block_id = $field_attributes['block_id'];

    $allow = array(
        'add' => false,
        'remove' => false,
        'reorder' => false,
    );

    render_block_field($block_id, $block, $block_group_name, $allow, false, true);
}

function render_block_field($block_id, $block, $block_group_name, $allow = [], $expanded = false, $is_single  = false)
{

    $explode_id = explode(':', $block_id);
    $block_id = $explode_id[0];

    if (isset($explode_id[1])) {
        $index = ':' . $explode_id[1];
    } else if (is_array($block)) {
        $index = null;
    } else {
        $index = null;
        $block_id = $block;
    }

    $block_model = get_block_model($block_id);

    // Set expanded if set in block model
    $expanded = isset($block_model['expanded'])
        ? $block_model['expanded']
        : false;

    // Set header_tag according to expanded
    $header_tag = ($expanded) ? 'h3' : 'h4';

    // Create accordion id
    $accordion_id = $block_id . random_int(0, 99) . random_int(0, 99);

    // Get field as title
    $field_as_title = $block_model['field_as_title'] ?? '';

    $accordion_title = ($field_as_title && is_array($block) && $block[$field_as_title])
        ? $block[$field_as_title]
        : $block_model['title'];

    if ($is_single) {
        $block_field_name = ($block_group_name)
            ? $block_group_name
            : $block_id;
    } else {
        $block_field_name = ($block_group_name)
            ? $block_group_name . '[' . $block_id . $index . ']'
            : $block_group_name;
    }


    ?>

    <fieldset class="block-field accordion-item" name="<?php echo $block_field_name; ?>" data-field-as-title="<?php echo $field_as_title; ?>" data-block-id="<?php echo $block_id; ?>">

        <<?php echo $header_tag; ?> class="block-title accordion-header" id="heading_<?php echo $accordion_id; ?>">
            <button class="btn-header" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_<?php echo $accordion_id; ?>" aria-expanded="true" aria-controls="collapse_<?php echo $accordion_id; ?>">
                <span class="icon bi-<?php echo $block_model['icon']; ?>"></span>
                <span class="title" id="blockTitle" data-og-title="<?php echo $accordion_title; ?>">
                    <?php echo $accordion_title; ?>
                </span>
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

                    $value = $block[$key] ?? null;

                    render_field($key, $field, $value, $block_field_name);

                endforeach; ?>
            </div>
        </div>
    </fieldset>
<?php
}
