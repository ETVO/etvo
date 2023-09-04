<?php
$page_title = 'ETVO';
$body_class = 'home';

include_once 'integrate.php';

$content = get_data('content');
$blocks = filter_blocks($content['blocks']);

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
