<?php

include './manage/util/form_util.php';

$contact_model_json = file_get_contents('./manage/model/contact.json');
$contact_model = json_decode($contact_model_json, true, 512, JSON_FORCE_OBJECT);

$contact = $blocks['contact'];

$form_status = isset($_GET['form_status'])
    ? $_GET['form_status']
    : '';

$form_message = isset($_GET['form_message'])
    ? $_GET['form_message']
    : '';

?>
<section class="contact">
    <a class="anchor" id="contact"></a>
    <div class="container">

        <div class="content">
            <div class="inner">
                <span class="pre-title"><?php echo $contact['subtitle'] ?></span>
                <h2 class="title">
                    <?php echo $contact['title']; ?>
                </h2>
                <p class="desc">
                    <?php echo $contact['desc']; ?>
                </p>
                <div class="form">
                    <form id="contact-form" action="/manage/util/send_mail.php" method="post" novalidate>
                        <div id="form-message" class="<?php if ($form_status) echo $form_status ?>">
                            <div class="message">
                                <?php echo $form_message; ?>
                            </div>
                        </div>
                        <div class="fields">
                            <?php
                            render_form_fields($contact_model['fields']);
                            ?>
                            <div class="d-flex">
                                <button type="submit" class="btn btn-primary ms-auto">
                                    send message
                                    <span class="bi-arrow-clockwise" id="spinner" style="display: none;"></span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>