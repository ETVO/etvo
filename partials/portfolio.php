<?php

$projects_json = file_get_contents(__DIR__ . '/../projects.json');
$projects = json_decode($projects_json);

// Sort newer to older
usort($projects, function ($a, $b) {
    return $a->year < $b->year;
});


?>

<section class="portfolio">
    <a class="anchor" id="portfolio"></a>
    <div class="container">
        <div class="content">
            <img class="art" src="/assets/img/web-net.svg" alt="">
            <p class="pre-title">PORTFOLIO</p>
            <h2 class="title">Take a look at what has been done.</h2>
            <p class="desc">
                Efficient websites and experiences, a tailor-made, custom-built, professional house for a company, in the web.
            </p>
        </div>
        <div class="projects">
            <div class="row row-cols-2">
                <?php foreach ($projects as $project) : ?>
                    <div class="col">
                        <div class="project" data-project-slug="<?php echo $project->slug; ?>" data-project-year="<?php echo $project->year; ?>">
                            <img class="thumbnail" src="/assets/img/projects/<?php echo $project->slug; ?>/thumbnail.webp" alt="">
                            <div class="overlay">
                                <div class="inner">
                                    <h3 class="title fs-4">
                                        <?php echo $project->name; ?>
                                    </h3>

                                    <div class="year">
                                        <?php echo $project->year; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="action">
            <div class="caption">Curious if your idea is possible?</div>
            <a href="" class="btn btn-primary">contact etvo</a>
        </div>
    </div>
</section>

<div class="project-modal" id="projectModal">
    <div class="close" id="closeModal" aria-controls="#projectModal" aria-label="Close">
        <span class="bi-x"></span>
    </div>
    <div class="content">
        <div class="container-fluid">
            <img class="art" src="/assets/img/web-net.svg" alt="">
            <h2 class="title"></h2>
            <div class="attr">
                <span class="year"></span>
                <span class="tech"></span>
            </div>
            <div class="desc"></div>
            <a class="link">View website <span class="bi-box-arrow-up-right"></span></a>
            <div class="images"></div>

            <div class="action">
                <div class="caption">Did you get inspired?</div>
                <a href="" class="btn btn-primary">start your project</a>
            </div>
        </div>
    </div>
</div>