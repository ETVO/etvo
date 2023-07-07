<?php

include './manage/util/blog_util.php';

$post = get_post_by_slug($slug);

$title = $post['title']['rendered'];
$content = $post['content']['rendered'];
$date = $post['date'];
$categories = $post['category'] ?? null;
$image = get_post_featured_image($post);

$page_title = $title;

$yoast = get_yoast_head($post);

include './partials/header.php';

?>
<link rel="stylesheet" href="/assets/css/blog.css">

<div class="single page">
    <?php if ($image) :
        $src = $image['src'];
        $alt = $image['alt'];
        if ($alt == '') $alt = $title;
    ?>
        <div class="heading" style="background-image: url('<?php echo $src; ?>');">
            <!-- <div class="image">
            </div> -->
            <div class="container">
                <h1 class="title"><?php echo $title; ?></h1>
            </div>
        </div>
        <?php else: ?>
            <div class="heading no-image">
                <div class="container">
                    <h1 class="title"><?php echo $title; ?></h1>
                </div>
            </div>
        <?php endif; ?>
        
    <div class="container content-view">
        <div class="categories">
            <?php foreach($categories as $category) :
                $name = $category['name']; 
                $slug = $category['slug']; 

                echo "<a href='/blog/$slug'>$name</a>";

             endforeach; ?>
        </div>
        <div class="content">
            <?php echo $content; ?>
        </div>
    </div>

</div>

<?php

include './partials/footer.php';

?>