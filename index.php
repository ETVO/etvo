<?php

$page_title = 'ETVO';

include './partials/header.php';


$content_json = file_get_contents('./manage/data/content.json');
$content = json_decode($content_json, true, 512, JSON_FORCE_OBJECT);

$blocks = $content['blocks'];

// $blocks = [];
// foreach($content['blocks'] as $key => $value){
//     $key = explode(':', $key)[0];
//     $blocks[$key] = $value;
// }

?>


<?php
include './partials/hero.php';

include './partials/services.php';

include './partials/contact.php';

include './partials/money.php';

include './partials/portfolio.php';

include './partials/footer.php';

?>