<?php
//Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require dirname(__FILE__) . '/../vendor/autoload.php';

function filter_field($value, $type, $required)
{
    $filter = FILTER_DEFAULT;

    if ($type == 'email') {
        $filter = FILTER_SANITIZE_EMAIL;
    }

    $value = filter_var(htmlspecialchars($value), $filter);

    if ($required && empty($value)) {
        return false;
    } else if ($type == 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
        return false;
    }

    return true;
}

function send_mail(
    $auth,
    $from,
    $to,
    $subject,
    $body
) {

    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer();

    //Server settings
    $mail->isSMTP();
    $mail->Host       = $auth['host'];
    $mail->Username   = $auth['username'];
    $mail->Password   = $auth['password'];
    $mail->SMTPAuth   = true;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = 465;
    $mail->CharSet = "UTF-8";

    //Recipients
    $mail->setFrom($from);
    $mail->addAddress($to);
    $mail->addReplyTo($from);

    //Content
    $mail->Subject = $subject;
    $mail->Body    = $body;

    $mail->send();
}

function render_form_fields($fields)
{
    $in_row = false;

    foreach ($fields as $name => $field) :
        $label = $field['label'];
        $type = $field['type'];
        $required = $field['required'] ? 'required' : '';
        $attrs = $field['attrs'] ?? '';

        $prev_in_row = $in_row;
        $in_row = $field['in_row'] ?? false;

        if ($in_row) :

            if (!$prev_in_row) :
?>
                <div class="mb-3 row g-3 row-cols-1 row-cols-md-2">
                <?php
            endif;
                ?>
                <div class="col field-<?php echo $name; ?>">
                    <?php render_form_field($name, $label, $type, $required, $attrs); ?>
                </div>

                <?php

            else :
                if ($prev_in_row) :
                ?>
                </div>
            <?php
                endif;
            ?>
            <div class="mb-3">
                <?php render_form_field($name, $label, $type, $required, $attrs); ?>
            </div>
    <?php
            endif;
        endforeach;
    }

    function render_form_field($name, $label, $type, $required, $attrs)
    {
    ?>
    <label for="<?php echo $name; ?>"><?php echo $label; ?></label>
    <?php if ($type == 'textarea') : ?>
        <textarea name="<?php echo $name; ?>" class="form-control" <?php echo $required; ?> <?= $attrs; ?>>
        </textarea>
    <?php else : ?>
        <input type="<?php echo $type; ?>" name="<?php echo $name; ?>" class="form-control" <?php echo $required; ?> <?= $attrs; ?>>
    <?php endif; ?>
    <div class="invalid-feedback"></div>
<?php
    }
