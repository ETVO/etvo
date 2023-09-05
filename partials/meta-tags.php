<?php

$meta_default = array(
    'page_url' => $page_url ?? 'https://etvo.me',
    'page_title' => $page_title ?? 'ETVO Web',
    'page_date' => '2023-07-07 22:22:22',
    'page_language' => 'en',
    'page_description' => 'Professional, high quality web development and design for projects that want to be remembered.',
    'page_keywords' => 'web design, web development, memorable experiences, efficient tools, website front-end and back-end, performance, user experience, digital marketing resources, blog, contact forms, prototype, user experience design, remarkable websites, authority, maintenance plans, branding, visual identity, strong brand concept, professional visual resources, fair pricing.',
    'page_image' => HOME_URL . '/assets/img/meta-image.png'
);

foreach ($meta_default as $key => $value) {
    if (isset($$key)) {
        unset($meta_default[$key]);
    }
}

if (isset($yoast)) :
    echo $yoast;
else :
    extract($meta_default);

    $schema = [
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type'  => 'WebPage',
                '@id' => $page_url,
                'url' => $page_url,
                'name' => $page_title,
                'thumbnailUrl' => $page_image,
                'datePublished' => $page_date,
                'description' => $page_description,
                'inLanguage' => $page_language,
                'potentialAction' => [[
                    '@type' => 'ReadAction',
                    'target' => [$page_url]
                ]],
            ]
        ]
    ];

?>

    <title><?= $page_title; ?></title>

    <!-- Description -->
    <meta name="description" content="<?= $page_description; ?>">

    <!-- Keywords -->
    <meta name="keywords" content="<?= $page_keywords; ?>">

    <!-- Canonical -->
    <meta property="canonical" content="<?= $page_url; ?>">

    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="<?= $page_title; ?>">
    <meta property="og:description" content="<?= $page_description; ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= $page_url; ?>">
    <meta property="og:image" content="<?= $page_image; ?>">

    <!-- Twitter Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= $page_title; ?>">
    <meta name="twitter:description" content="<?= $page_description; ?>">
    <meta name="twitter:image" content="<?= $page_image; ?>">
    <script type="application/ld+json" class="yoast-schema-graph">
        <?= json_encode($schema); ?>
    </script>

<?php endif;
