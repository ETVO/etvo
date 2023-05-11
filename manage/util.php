<?php

$settings = null;

// Read settings
$settings_json = file_get_contents('../data/settings.json');
$settings = json_decode($settings_json, true);

function get_available_blocks()
{
    $blocks = [];

    // Get all files inside blocks folder and loop
    $blocks_files = glob('../blocks/*.{json}', GLOB_BRACE);
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
    $model_json = file_get_contents("./model/$name.json");
    return json_decode($model_json, true);
}

function get_data($name)
{
    $model_json = file_get_contents("../data/$name.json");
    return json_decode($model_json, true);
}

function render_field($name, $type, $value, $echo = true)
{
    ob_start(); // Start HTML buffering
    switch ($type):
        case 'string':
?>
            <input type="text" class="form-control" name="<?php echo $name; ?>" value="<?php echo $value; ?>">
<?php
            break;
        case 'blocks':
            render_blocks_field($name, $value);
            break;

    endswitch;

    $output = ob_get_contents(); // collect buffered contents

    ob_end_clean(); // Stop HTML buffering

    // Echo or return contents
    if ($echo)
        echo $output;
    else
        return $output;
}

function render_blocks_field($field_name, $blocks) {
    foreach($blocks as $block) :

        $block_id = $block['id'];

        $block_model = get_block_model($block_id);
        
        echo $block_model['name'];

    endforeach;
}

function get_block_model($id) {
    // Get contents
    $block_json = file_get_contents("./model/blocks/$id.json");

    if (!$block_json) return null;

    // Decode JSON as associative array
    $block = json_decode($block_json, true);

    return $block;
}