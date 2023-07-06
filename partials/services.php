<?php
$default_services = array(
    array(
        "icon" => "house-door",
        "name" => "Website Development",
        "description" => "Developing a website front-end and back-end, creating web pages, blogs, catalogs, and any type of web resource, with a strong care for performance and user experience.",
        "more" => "Good tool for a company that values their marketing, having a professional display of their services and products online, with strong digital marketing resources such as a blog and contact forms."
    ),
    array(
        "icon" => "brush",
        "name" => "Web Design",
        "description" => "Creating a prototype for a website, in its desktop and mobile versions, and designing the User Experience of the website.",
        "more" => "Useful resource for companies that are looking to use their website to sell, convert and grow authority.
        <br><b>Remarkable websites make remarkable companies.</b>"
    ),
    array(
        "icon" => "cloud-check",
        "name" => "Website Maintenance Plans",
        "description" => "Taking care of keeping the website always updated and in good shape.",
        "more" => "Important for companies that are looking to create a strong digital presence and expand their business."
    ),
    array(
        "icon" => "gem",
        "name" => "Branding & Visual Id",
        "description" => "Offering a specialized input for existing brands and developing new brands and visual identities from scratch.",
        "more" => "A good asset for projects looking for a strong brand concept and professional visual resources to start up their marketing efforts."
    ),
);

$services = $blocks['services'];
?>
<section class="services">
    <div class="inner-shadow"></div>
    <a class="anchor" id="services"></a>
    <div class="container">
        <div class="content">
            <p class="pre-title"><?php echo $services['subtitle']; ?></p>
            <h2 class="title"><?php echo $services['title']; ?></h2>
        </div>
        <div class="main-action">
            <?php if ($services['main_action']) :
                $caption = $services['main_action']['caption'];
                $text = $services['main_action']['text'];
                $link = $services['main_action']['link'];
            ?>
                <div class="caption"><?php echo $caption; ?></div>
                <a href="<?php echo $link; ?>" class="btn btn-primary"><?php echo $text; ?></a>
            <?php endif; ?>
        </div>
        <div class="service-view">
            <?php foreach ($services['services'] as $key => $service) : 
                $key = implode('', explode(':', $key));
                $id = 'isForMe' . $key;
                ?>
                <div class="service">
                    <div class="icon bi-<?php echo $service['icon']; ?>">
                        <span class="icon-bg bi-<?php echo $service['icon']; ?>"></span>
                    </div>
                    <h3 class="name"><?php echo $service['title']; ?></h3>
                    <p class="desc"><?php echo $service['description']; ?></p>
                    <div class="d-flex">
                        <div class="more me-auto">
                            <button class="more-toggle collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#<?php echo $id; ?>" aria-expanded="false" aria-controls="<?php echo 'isForMe' . $key; ?>">
                                Is this for me? <span class="bi-question-circle"></span>
                            </button>
                            <div class="collapse" id="<?php echo $id; ?>">
                                <div class="more-content card card-body">
                                    <?php echo $service['is_for_me']; ?>

                                    <div class="action">
                                        <a href="#contact" class="btn btn-primary">This is for me!</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            <?php endforeach; ?>
        </div>
        <div class="main-action final">
            <?php if ($services['final_action']) :
                $caption = $services['final_action']['caption'];
                $text = $services['final_action']['text'];
                $link = $services['final_action']['link'];
            ?>
                <div class="caption"><?php echo $caption; ?></div>
                <a href="<?php echo $link; ?>" class="btn btn-primary"><?php echo $text; ?></a>
            <?php endif; ?>
        </div>
    </div>
</section>