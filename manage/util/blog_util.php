<?php

define('WP_URL', 'http://blog-velvetcare.test');
define('API_URL', WP_URL . '/wp-json/wp/v2/');
define('SITE_URL', 'http://etvo.test');

function make_api_request($endpoint, $data = null, $associative = true)
{
    $query = ($data)
        ? html_entity_decode(http_build_query($data))
        : '';

    // make request
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, API_URL . "$endpoint?$query");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);

    // convert response
    $output = json_decode($output, $associative);

    // handle error; error output
    if ($code = curl_getinfo($ch, CURLINFO_HTTP_CODE) !== 200) {
        return false;
    }

    curl_close($ch);

    return $output;
}

// Fetch all posts
function fetch_posts($args = null)
{
    return make_api_request('posts', $args);
}

// Get post by slug
function get_post_by_slug($slug)
{
    $args = array(
        'slug' => $slug
    );

    $response = make_api_request('posts', $args);

    if (!$response) return false;
    return $response[0];
}

// Get post by slug
function get_category_by_slug($slug)
{
    $args = array(
        'slug' => $slug
    );

    $response = make_api_request('categories', $args);

    if (!$response) return false;
    return $response[0];
}

function fetch_posts_json($context = 'embed', $categories = 0, $per_page = 4, $page = 1, $search_terms = '')
{
    $args = array(
        'context' => $context,
        'categories' => $categories,
        'per_page' => $per_page,
        'page' => $page,
        'search' => $search_terms
    );

    if (!$categories) unset($args['categories']);

    if (!$search_terms) unset($args['search']);


    $response = make_api_request('posts', $args, false);
    if (!$response) return [false, 'No posts were found'];
    return [true, $response];
}

function get_post_featured_image($post)
{
    if (!$post) return false;

    return isset($post['featured_image_data'])
        ? $post['featured_image_data']
        : false;
}

function get_categories()
{
    return make_api_request('categories');
}

function render_search()
{
?>
    <form action="" id="searchBar">
        <input type="text" class="search form-control" id="searchInput" placeholder="Search...">
        <span class="bi-search" id="searchSubmit"></span>
    </form>
<?php
}

function get_yoast_head($post)
{
    $yoast = $post['yoast_head'] ?? false;
    if (!$yoast) return null; // early bird gets the worm

    $yoast = str_replace(WP_URL . '/post/', SITE_URL . '/post/', $yoast);
    $yoast = str_replace(WP_URL . '/?s=', SITE_URL . '/blog/?s=', $yoast);
    $yoast = str_replace('"' . WP_URL . '/"', '"' . SITE_URL . '/"', $yoast);
    $yoast = str_replace(WP_URL . '/#', SITE_URL . '/#', $yoast);


    return $yoast;

    $post_url = SITE_URL . '/post/' . $post['slug'];
    $robots = implode(', ', $yoast['robots']);
    $og_keys = ['locale', 'type', 'title', 'description', 'site_name'];
    $article_keys = ['publisher', 'published_time', 'modified_time'];
    $og_image = $yoast['og_image'][0] ?? null;
    $twitter = $yoast['og_image'][0] ?? null;

    ob_start(); // Start HTML buffering
    error_reporting(0);
    echo '<!-- Yoast SEO -->';
?>
    <title><?= $yoast['title'] ?></title>
    <meta name="description" content="<?= $yoast['description'] ?>" />
    <meta name="robots" content="<?= $robots ?>" />
    <meta name="canonical" content="<?= $post_url ?>" />
    <meta name="og:url" content="<?= $post_url ?>" />
    <!-- OG -->
    <?php foreach ($og_keys as $key) :
        $name = "og:$key";
        $value = $yoast["og_$key"];
    ?>
        <meta name="<?= $name ?>" content="<?= $value ?>" />
    <?php endforeach; ?>

    <!-- ARTICLE -->
    <?php foreach ($article_keys as $key) :
        $name = "article:$key";
        $value = $yoast["article_$key"];
    ?>
        <meta name="<?= $name ?>" content="<?= $value ?>" />
    <?php endforeach; ?>

    <!-- OG:IMAGE -->
    <?php if (isset($og_image)) : ?>
        <meta name="og:image" content="<?= $og_image['url'] ?>" />
        <meta name="og:image:width" content="<?= $og_image['width'] ?>" />
        <meta name="og:image:height" content="<?= $og_image['height'] ?>" />
        <meta name="og:image:type" content="<?= $og_image['type'] ?>" />
    <?php endif; ?>

    <!-- TWITTER -->
    <meta name="twitter:card" content="<?= $yoast['twitter_card'] ?>" />
    <?php $t = 1;
    foreach ($twitter as $label => $data) :
        $label_name = "twitter:label$t";
        $data_name = "twitter:data$t";
    ?>
        <meta name="<?= $label_name ?>" content="<?= $label ?>" />
        <meta name="<?= $data_name ?>" content="<?= $data ?>" />
    <?php endforeach; ?>
<?php

    echo '<!-- End of Yoast SEO -->';
    $output = ob_get_contents(); // collect buffered contents

    ob_end_clean(); // Stop HTML buffering

    return $output; // Render contents
}