<?php

$contact = $blocks['contact'];
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
                    <form action="">
                        <div class="mb-3 row g-3">
                            <div class="col-6">
                                <label for="firstname">First Name</label>
                                <input type="text" name="firstname" class="form-control" required>
                            </div>
                            <div class="col-6">
                                <label for="lastname">Last Name</label>
                                <input type="text" name="lastname" class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="email">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="industry">Industry / Field of Work</label>
                            <input type="text" name="industry" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="message">Message</label>
                            <textarea name="message" class="form-control" rows="4"></textarea>
                        </div>
                    </form>
                </div> 
            </div>
        </div>
    </div>
</section>