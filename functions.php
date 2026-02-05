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
require 'includes/post-types.php'; // Include our custom post types

/**
 * Enqueue admin scripts for single lead author selection
 */
function frank_admin_scripts($hook) {
    global $post_type;
    
    // Only load on publication post type edit screens
    if (($hook === 'post.php' || $hook === 'post-new.php') && $post_type === 'publication') {
        wp_add_inline_script('acf-input', "
            (function($) {
                if (typeof acf === 'undefined') return;
                
                acf.addAction('ready', function() {
                    // Handle lead author radio button selection - only one lead author allowed
                    $(document).on('change', '[data-name=\"lead_author\"] input[type=\"radio\"]', function() {
                        var \$this = $(this);
                        var selectedValue = \$this.val();
                        
                        // Only act if 'yes' is selected
                        if (selectedValue === 'yes' || selectedValue === 'Yes' || selectedValue === '1') {
                            // Find all other lead_author fields and set them to 'no'
                            $('[data-name=\"lead_author\"]').each(function() {
                                var \$field = $(this);
                                var \$radio = \$field.find('input[type=\"radio\"]:checked');
                                
                                // If this is not the one we just clicked and it's set to yes
                                if (!\$field.find('input[type=\"radio\"]').is(\$this)) {
                                    \$field.find('input[type=\"radio\"][value=\"no\"], input[type=\"radio\"][value=\"No\"], input[type=\"radio\"][value=\"0\"]').prop('checked', true);
                                }
                            });
                        }
                    });
                });
            })(jQuery);
        ");
    }
}
add_action('admin_enqueue_scripts', 'frank_admin_scripts');