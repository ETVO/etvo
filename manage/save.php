<?php

print_r($_POST);

$json = json_encode($_POST);

$source_file = './data/test.json';

file_put_contents($source_file, $json);