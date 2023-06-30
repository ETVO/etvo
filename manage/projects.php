<?php

$active_menu = 'projects';

include './partials/header.php';
$data_source = 'projects';

$model = get_model('projects');
$data = get_data($data_source);

?>

<main class="projects container">
    <div class="heading">
        <h1 class="title">Projects</h1>
        <p class="desc">Edit the projects in your Portfolio.</p>
    </div>

    <form action="./save.php" method="POST" class="model row w-100 m-0" enctype="multipart/form-data">
        <input type="hidden" name="form_action" value="POST">
        <input type="hidden" name="data_source" value="<?php echo $data_source; ?>">
        <div class="model-view col-9">
            <?php foreach ($model as $key => $field) :

                $value = $data[$key] ?? null;


                $key = explode(':', $key)[0];
                render_field($key, $field, $value, null, true, $data_source);
            endforeach; ?>
        </div>
        <div class="col-3">
            <div class="model-sidebar">
                <button class="btn btn-primary">Save</button>
                <small>Changes are <b><i>NOT</i></b> saved automatically</small>
            </div>
        </div>
    </form>
</main>
<?php
include './partials/footer.php';

?>