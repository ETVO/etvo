<?php

$page_title = 'ETVO';



$content_json = file_get_contents('./manage/data/content.json');
$content = json_decode($content_json, true, 512, JSON_FORCE_OBJECT);

$blocks = array();

foreach($content['blocks'] as $key => $block) {
    $new_key = explode(':', $key);
    if(count($new_key) > 1) {
        $new_key = $new_key[0];
    } 
    else {
        $new_key = $key;
    }

    $blocks[$new_key] = $block;
}

?>
<script>const enableStickyHeader = true;</script>
<?php

include './partials/header.php';

include './partials/hero.php';

include './partials/services.php';

include './partials/contact.php';

include './partials/money.php';

include './partials/portfolio.php';

include './partials/footer.php';

?>