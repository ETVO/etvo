<?php

include CONTROL_DIR . '/blog_util.php';

if (isset($category_slug)) {
    $category = get_category_by_slug($category_slug);
    $selected_category = $category['id'] ?? null;
} else {
    $selected_category = null;
}

if (isset($_GET['category']))
    $selected_category = $_GET['category'];

$posts_per_page = 4;

$args = array(
    'context' => 'embed',
    'categories' => $selected_category,
    'per_page' => $posts_per_page
);
$posts = fetch_posts($args);

$page_title = 'Blog ETVO';
$page_url = SITE_URL . '/blog/';

include './partials/header.php';

?>
<link rel="stylesheet" href="/assets/css/blog.css">

<div class="blog page">
    <div class="container">
        <h1 class="page-title">
            Blog
        </h1>

        <div class="row row-cols-1 row-cols-lg-2">
            <div class="categories-filter col">
                <?php

                $categories = get_categories();
                if (!$categories) $categories = [];

                foreach ($categories as $category) :
                    $name = $category['name'];
                    $category_id = $category['id'];
                    $slug = $category['slug'];
                    $selected = ($category_id == $selected_category);
                    $class = $selected
                        ? 'selected'
                        : '';

                    $href = $selected
                        ? "?"
                        : "?category=$category_id";
                ?>
                    <label class="category <?php echo $class; ?>">
                        <span class="name">
                            <?php echo $name; ?>
                        </span>
                        <input type="radio" name="filter-category" value="<?php echo $category_id; ?>" <?php if ($selected)  echo 'checked'; ?>>
                    </label>
                <?php endforeach; ?>
                <label class="clear-filters" id="clearFilters" title="Clear filters">
                    <span class="bi-arrow-clockwise"></span>
                </label>
            </div>

            <div class="search-wrap col">
                <?php render_search(); ?>
            </div>
        </div>

        <?php if (is_array($posts)) : ?>
            <div class="posts-wrap">
                <input type="hidden" id="postsPerPage" value="<?php echo $posts_per_page; ?>">
                <input type="hidden" id="page" value="1">
                <input type="hidden" id="selectedCategory" value="<?php echo $selected_category; ?>">
                <div class="loading-overlay" id="loading">
                    <div class="inner">
                        <span class="bi-arrow-clockwise" id="spinner"></span>
                    </div>
                </div>

                <div class="message-wrap">
                    <span class="message" id="searchMsg" style="display: none;">
                        Search results for <b></b>
                    </span>
                </div>

                <div class="posts" id="posts">

                    <?php foreach ($posts as $post) :

                        $title = $post['title']['rendered'];
                        $excerpt = $post['excerpt']['rendered'];

                        $img = get_post_featured_image($post);
                        $img_src = $img ? $img['src'] : false;
                        $img_alt = $title;

                        $categories = $post['category'];
                        if ($categories) {
                            $categories = array_column($categories, 'name');
                            $categories = implode(', ', $categories);
                        }

                        $date = $post['date'];

                        $link = '/post/' . $post['slug'];
                    ?>
                        <div class="post">
                            <a class="image" href="<?php echo $link; ?>">
                                <?php if ($img_src) : ?>
                                    <img src="<?php echo $img_src; ?>" alt="<?php echo $img_alt; ?>">
                                <?php else : ?>
                                    <div class="placeholder-img"><span class="bi-image"></span></div>
                                <?php endif; ?>
                            </a>
                            <div class="content">
                                <div class="meta">
                                    <div class="post-categories">
                                        <?php echo $categories; ?>
                                    </div>
                                    <?php if ($categories) echo '<div class="separator">&bull;</div>'; ?>
                                    <div class="post-date">
                                        <?php echo date('d/m/Y', strtotime($date)); ?>
                                    </div>
                                </div>
                                <a href="<?php echo $link; ?>">
                                    <h3 class="title">
                                        <?php echo $title; ?>
                                    </h3>
                                </a>
                                <div class="excerpt">
                                    <?php echo $excerpt; ?>
                                </div>
                                <div class="action">
                                    <a href="<?php echo $link; ?>" class="btn btn-primary read-more">
                                        read more
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="col" id="templatePost" style="display: none;">
                <div class="post">
                    <a href="" class="image">
                        <div class="placeholder-img"><span class="bi-image"></span></div>
                    </a>
                    <div class="content">
                        <div class="meta">
                            <div class="post-categories"></div>
                            <div class="separator">&bull;</div>
                            <div class="post-date"></div>
                        </div>
                        <a href="">
                            <h3 class="title"></h3>
                        </a>
                        <div class="excerpt"></div>
                        <div class="action">
                            <a class="btn btn-primary read-more">read more</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="load-more d-flex justify-content-center">
                <div class="btn btn-secondary" id="loadMoreBtn">
                    Load more
                </div>
                <div class="message-wrap">
                    <span class="message" id="noMoreMsg" style="display: none;">
                        No more posts were found.
                    </span>
                    <span class="message" id="notFoundMsg" style="display: none;">
                        No posts were found.
                    </span>
                </div>
            </div>
        <?php
        else :
        ?>
            <div class="m-auto" id="noFoundMsg">
                No posts were found...
            </div>

        <?php
        endif;
        ?>

    </div>
</div>
<?php

include './partials/footer.php';

?>