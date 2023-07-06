<?php

define('API_URL', 'http://blog-velvetcare.test/wp-json/wp/v2/');

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
