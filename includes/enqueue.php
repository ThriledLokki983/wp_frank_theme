<?php
// enqueue assets
function frank_enqueue_assets()
{
    // get version of theme and set it as asset version
    // $theme = wp_get_theme();
    // $version = $theme->version;

    // // fonts
    // // wp_enqueue_style('myriad', get_theme_file_uri('/public/assets/fonts/Myriad-Pro-Regular.ttf'), false, $version);

    // // CSS
    // wp_enqueue_style('frank', get_theme_file_uri('/public/css/styles.css'), false, $version);
    // wp_enqueue_style('fontawesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css?ver=6.0.2', false, $version);

    // // JS
    // wp_enqueue_script('frank', get_theme_file_uri('/public/js/script.js'), false, $version);

    $theme_version = wp_get_theme()->get('Version');

    // Path to your compiled CSS file
    $css_file = get_template_directory() . '/public/css/styles.css';
    $css_version = file_exists($css_file) ? filemtime($css_file) : $theme_version;

    wp_enqueue_style('frank', get_template_directory_uri() . '/public/css/styles.css', [], $css_version);

    $js_file = get_template_directory() . '/public/js/script.js';
    $js_version = file_exists($js_file) ? filemtime($js_file) : $theme_version;

    wp_enqueue_script('frank', get_template_directory_uri() . '/public/js/script.js', [], $js_version, true);
}

add_action('wp_enqueue_scripts', 'frank_enqueue_assets');

// set custom script tags
function frank_custom_script_tags($tag, $handle, $src)
{
    if ($handle !== 'frank') {
        return $tag;
    }

    return '<script defer type="module" src="' . esc_url($src) . '"></script>';
}

add_filter('script_loader_tag', 'frank_custom_script_tags', 10, 3);

// set custom style tags
function frank_custom_style_tags($tag, $handle, $src)
{
    if ($handle !== 'myriad') {
        return $tag;
    }

    return '<link href="' . esc_url($src) . '" type="font/ttf" rel="preload" as="font" crossorigin/>';
}

add_filter('style_loader_tag', 'frank_custom_style_tags', 10, 3);