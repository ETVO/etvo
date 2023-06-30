<?php

$active_menu = 'users';
include './partials/header.php';

$stored_users = get_data_from_dir('./system/users.json') ?? [];
$model = get_data_from_dir('./system/user_model.json');

$option = 'show';
if (isset($_GET['add_new'])) {
    $option = 'add_new';
}
if (isset($_GET['edit'])) {
    $option = 'edit';
}
if (isset($_GET['show'])) {
    $option = 'show';
}

$status = false;
$message = '';

if (isset($_POST['form'])) {
    $form = $_POST['form'];
    include './user_util.php';

    if ($form == 'add_new') {
        $response = register_user($_POST);

        $status = $response[0];
        $message = $response[1];
    }
}


if ($message != '') {
    $status_label = ($status) ? 'SUCCESS' : 'ERROR';
    echo '<script>alert("' . $status_label . '\n' . $message . '");';
    echo 'window.location.href="?show";</script>';
}

?>

<main class="users container">

    <?php switch ($option):

        case 'show': ?>
            <div class="heading">
                <h1 class="title">Users</h1>
            </div>

            <a href="?add_new" class="btn btn-primary">
                Add New User
            </a>

            <?php if ($stored_users) : ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Username</th>
                            <th scope="col">Created at</th>
                            <th scope="col">Updated at</th>
                            <th scope="col">Active</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($stored_users as $user) : ?>
                            <tr>
                                <td><?php echo $user['name']; ?></td>
                                <td><?php echo $user['username']; ?></td>
                                <td><?php echo $user['created_at']; ?></td>
                                <td><?php echo $user['updated_at']; ?></td>
                                <td><?php echo $user['active'] ? 'No' : 'Yes'; ?></td>
                                <td><a href="?edit&username=<?php echo $user['username']; ?>">Edit</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else : ?>
                <h2 class="fs-5 pt-3 fw-normal">No users were found.</h2>
            <?php endif;
            break;

        case 'add_new': ?>
            <div class="heading">
                <h1 class="title">Add New User</h1>
            </div>

            <form action="" method="post" class="model row w-100 m-0">
                <input type="hidden" name="form" value="add_new">
                <div class="model-view col-6">
                    <?php foreach ($model as $key => $field) :

                        $value = $data[$key] ?? null;


                        $key = explode(':', $key)[0];
                        render_field($key, $field, $value);
                    endforeach; ?>
                </div>
                <div class="col-3">
                    <div class="model-sidebar">
                        <button class="btn btn-primary">Save</button>
                        <small>Changes are <b><i>NOT</i></b> saved automatically</small>
                    </div>
                </div>
            </form>

            <?php break; ?>


    <?php endswitch; ?>
</main>
<?php
include './partials/footer.php';

?>