<?php
$services = array(
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
)
?>
<section class="services">
    <a class="anchor" id="services"></a>
    <div class="container">
        <div class="content">
            <p class="pre-title">SERVICES</p>
            <h2 class="title">Offering high quality services for projects that want to grow.</h2>
        </div>
        <div class="main-action">
            <a href="" class="btn btn-primary">start your project</a>
        </div>
        <div class="service-view">
            <?php foreach ($services as $key => $service) : ?>
                <div class="service">
                    <div class="icon bi-<?php echo $service['icon']; ?>">
                        <span class="icon-bg bi-<?php echo $service['icon']; ?>"></span>
                    </div>
                    <h3 class="name"><?php echo $service['name']; ?></h3>
                    <p class="desc"><?php echo $service['description']; ?></p>
                    <div class="d-flex">
                        <div class="more me-auto">
                            <button class="more-toggle collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#<?php echo 'isForMe' . $key; ?>" aria-expanded="false" aria-controls="<?php echo 'isForMe' . $key; ?>">
                                Is this for me? <span class="bi-question-circle"></span>
                            </button>
                            <div class="collapse" id="<?php echo 'isForMe' . $key; ?>">
                                <div class="more-content card card-body">
                                    <?php echo $service['more']; ?>

                                    <!-- <div class="action">
                                            <a href="" class="btn btn-primary">This is for me!</a>
                                        </div> -->
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            <?php endforeach; ?>
        </div>
        <div class="main-action final">
            <div class="caption">Still unsure of what your company needs?</div>
            <a href="" class="btn btn-primary">contact etvo</a>
        </div>
    </div>
</section>