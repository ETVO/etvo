<?php

$projects_json = file_get_contents(__DIR__ . '/../manage/data/projects.json');
$projects = json_decode($projects_json);

// Sort newer to older
usort($projects, function ($a, $b) {
    return $b->year - $a->year;
});


$portfolio = $blocks['portfolio'];
?>

<section class="portfolio">
    <a class="anchor" id="portfolio"></a>
    <div class="container">
        <div class="content">
            <img class="art" src="/assets/img/web-net.svg" alt="">
            <p class="pre-title"><?php echo $portfolio['subtitle']; ?></p>
            <h2 class="title"><?php echo $portfolio['title']; ?></h2>
            <p class="desc">
                <?php echo $portfolio['desc']; ?>
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
            <?php if ($portfolio['final_action']) :
                $caption = $portfolio['final_action']['caption'];
                $text = $portfolio['final_action']['text'];
                $link = $portfolio['final_action']['link'];
            ?>
                <div class="caption"><?php echo $caption; ?></div>
                <a href="<?php echo $link; ?>" class="btn btn-primary"><?php echo $text; ?></a>
            <?php endif; ?>
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