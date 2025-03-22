<?php
// define constants
define('TPL_DIR_URI', get_template_directory_uri());
wp_enqueue_style('theme-style', get_stylesheet_uri(), array(), time());

// include required files
require 'includes/functions.php';
require 'includes/enqueue.php';
require 'includes/theme-support.php';
require 'includes/nav-menus.php';
require 'includes/theme-options.php';