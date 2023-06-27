<?php

if (isset($_POST['data_source'])) {

    $data_source = $_POST['data_source'];

    $has_image = isset($_POST['has_image'])
        ? $_POST['has_image']
        : null;

    $processed_data = $_POST;
    save_images($processed_data, $has_image);

    $json = json_encode($processed_data);

    $source_file = "./data/$data_source.json";

    file_put_contents($source_file, $json);

    // header("Location: $data_source.php");
}

function save_images(&$data, $has_image)
{
    if (!$data || !$has_image) return; // early bird gets the worm 

    $data_source = $data['data_source'] ?? '';

    $upload_dir = realpath(dirname(__FILE__)) . "/data/uploads/$data_source/";
    $upload_uri = dirname($_SERVER['PHP_SELF']) . "/data/uploads/$data_source/";

    foreach ($has_image as $image_key) {
        $key_parts = explode('[', str_replace(']', '', $image_key));

        $image = get_file_by_key_parts($_FILES, $key_parts);

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

    if($new_upload_name) $upload_name = $new_upload_name;

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

function get_file_by_key_parts($array, $key_parts)
{

    $value = array();

    if (isset($array[$key_parts[0]])) {
        $array = $array[$key_parts[0]];
    }

    foreach ($array as $prop_key => $file_props) {
        $result = $array[$prop_key];
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



function get_value_by_key($array, $key)
{
    $key_parts = explode('[', str_replace(']', '', $key));
    foreach ($key_parts as $part) {
        if (isset($array[$part])) {
            $array = $array[$part];
        } else {
            return null;
        }
    }
    return $array;
}
