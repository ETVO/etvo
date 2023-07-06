<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['data_source'])) {
    $data_source = $_POST['data_source'];
    $processed_data = $_POST;

    $has_image = isset($_POST['has_image'])
        ? $_POST['has_image']
        : null;
    save_images($processed_data, $has_image);

    $save_in_dir = isset($_POST['save_in_dir'])
        ? $_POST['save_in_dir']
        : null;

    save_in_dir($processed_data, $save_in_dir);

    unset($processed_data['form_action']);
    unset($processed_data['data_source']);
    unset($processed_data['has_image']);
    unset($processed_data['save_in_dir']);
    unset($processed_data['keep_fields']);

    $json = json_encode($processed_data);

    $source_file = "./data/$data_source.json";

    file_put_contents($source_file, $json);

    header("Location: $data_source.php");
}

function save_images(&$data, $has_image)
{
    if (!$data || !$has_image) return; // early bird gets the worm 

    $data_source = $data['data_source'] ?? '';

    $upload_dir = realpath(dirname(__FILE__)) . "/data/uploads/$data_source/";
    $upload_uri = dirname($_SERVER['PHP_SELF']) . "/data/uploads/$data_source/";

    foreach ($has_image as $image_key) {
        $key_parts = explode('[', str_replace(']', '', $image_key));
        $src = get_value_by_key_parts($data, $key_parts);

        // Image is saved as URL or value is unchanged
        if ($src) continue;

        $image = get_file_by_key_parts($_FILES, $key_parts);

        // Image was not uploaded
        if (!$image || (isset($image['error']) && $image['error'] == 4)) continue;

        $upload_name = implode('_', $key_parts);
        $upload_name = str_replace(':', '_', $upload_name);

        $file_info = upload_image($image, $upload_dir, $upload_name);

        if ($file_info) {
            $filepath = $upload_uri . $file_info['name'] . '.' . $file_info['type'];
            $current_data = &$data;
            foreach ($key_parts as $i => $part) {
                if ($i == count($key_parts) - 1) {
                    $current_data[$part] = $filepath;
                } else {
                    if (!isset($current_data[$part])) {
                        $current_data[$part] = [];
                    }
                    $current_data = &$current_data[$part];
                }
            }
        }
    }
}

function upload_image($image, $upload_dir, $upload_name)
{
    $file_type = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));

    $upload_file = $upload_dir . $upload_name . ".$file_type";

    // Check if file is image
    $check = getimagesize($image['tmp_name']);
    if (!$check) return false;

    // Check if directory exists
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    $new_upload_name = '';

    // Check if file already exists
    $i = 1;
    while (file_exists($upload_file)) {
        $new_upload_name = $upload_name . "_$i";
        $upload_file = $upload_dir . $new_upload_name . ".$file_type";
        $i++;
    }

    if ($new_upload_name) $upload_name = $new_upload_name;

    // Check file size
    if ($image["size"] > 5000000) return false;

    if (move_uploaded_file($image["tmp_name"], $upload_file))
        return array(
            'dir' => $upload_dir,
            'name' => $upload_name,
            'type' => $file_type,
        ); // Return filename
    else
        return false;
}


function save_in_dir(&$data, $save_in_dir)
{
    if (!$data || !$save_in_dir) return; // early bird gets the worm 

    $data_source = $data['data_source'] ?? '';
    $keep_fields = $data['keep_fields'] ?? '';

    $upload_dir = realpath(dirname(__FILE__)) . "/data/$data_source/";
    $upload_uri = dirname($_SERVER['PHP_SELF']) . "/data/$data_source/";

    foreach ($save_in_dir as $save_index => $field_key) {
        $field_to_save = $data[$field_key];

        foreach ($field_to_save as $key => $block) {
            $block_slug = str_replace(':', '_', $key);

            $block_filepath = $upload_dir . $block_slug . '.json';

            // Check if directory exists
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            $block_data = &$data[$field_key][$key];

            if (isset($keep_fields[$save_index])) {
                $keep = json_decode($keep_fields[$save_index]);

                $block_data = array_intersect_key($block_data, array_flip($keep));
            }

            $block_data['filepath'] = array(
                'dir' => $block_filepath,
                'uri' => $upload_uri . $block_slug . '.json',
                'slug' => $block_slug,
            );

            $json = json_encode($block);

            file_put_contents($block_filepath, $json);
        }
    }
}

function get_file_by_key_parts($array, $key_parts)
{
    $value = array();

    if (isset($array[$key_parts[0]])) {
        $array = $array[$key_parts[0]];
    }

    foreach ($array as $prop_key => $file_props) {
        $result = $file_props;
        foreach ($key_parts as $i => $part) {
            if ($i == 0) continue;

            if (isset($result[$part])) {
                $result = $result[$part];
            }
        }
        $value[$prop_key] = $result;
    }

    return $value;
}

function get_value_by_key_parts($array, $key_parts)
{
    foreach ($key_parts as $part) {
        if (isset($array[$part])) {
            $array = $array[$part];
        } else {
            return null;
        }
    }
    return $array;
}
