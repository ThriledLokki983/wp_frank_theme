<?php
/**
 * Register custom post types for Frank theme
 */

if (!function_exists('frank_register_post_types')) {
    function frank_register_post_types() {
        // Register the Project post type
        register_post_type('project', [
            'labels' => [
                'name'               => __('Projects', 'frank'),
                'singular_name'      => __('Project', 'frank'),
                'add_new'            => __('Add New', 'frank'),
                'add_new_item'       => __('Add New Project', 'frank'),
                'edit_item'          => __('Edit Project', 'frank'),
                'new_item'           => __('New Project', 'frank'),
                'view_item'          => __('View Project', 'frank'),
                'search_items'       => __('Search Projects', 'frank'),
                'not_found'          => __('No projects found', 'frank'),
                'not_found_in_trash' => __('No projects found in Trash', 'frank'),
                'parent_item_colon'  => __('Parent Project:', 'frank'),
                'menu_name'          => __('Projects', 'frank'),
            ],
            'public'              => true,
            'hierarchical'        => false,
            'show_ui'             => true,
            'show_in_nav_menus'   => true,
            'supports'            => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
            'has_archive'         => true,
            'rewrite'             => ['slug' => 'projects'],
            'query_var'           => true,
            'menu_icon'           => 'dashicons-clipboard',
            'show_in_rest'        => true,
        ]);
    }
    add_action('init', 'frank_register_post_types');
}