<?php 

$page_title = 'Page not found...';

include './partials/header.php';

?>

<div class="error py-5">
    <div class="container d-flex flex-column align-items-center">
        <h1>
            <div class="title">404</div>
            <div class="subtitle">Page Not Found</div>
        </h1>
        <p>We couldn't find what you were looking for :(</p>
        <a href="/" class="btn btn-primary">return to home</a>
    </div>
</div>

<?php

include './partials/footer.php';

?>
