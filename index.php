<?php

$page_title = 'ETVO';

include './partials/header.php';


$content_json = file_get_contents('./manage/data/test.json');
$content = json_decode($content_json, true, 512, JSON_FORCE_OBJECT);

?>

<section class="hero">
    <div class="container">
        <div class="content">
            <h1 class="title">Specialized in web design & development.</h1>
            <p class="desc">Crafting efficient tools and memorable experiences.</p>
        </div>
    </div>
</section>


<?php
include './partials/services.php';


include './partials/contact.php';

include './partials/money.php';

include './partials/portfolio.php';

include './partials/footer.php';

?>