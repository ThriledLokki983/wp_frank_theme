<?php
// add theme support
function frank_add_theme_support()
{
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
}

add_action('after_setup_theme', 'frank_add_theme_support');