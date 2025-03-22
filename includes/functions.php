<?php
/**
 * Function for retrieving page content (content builder).
 *
 * @return void
 */
function frank_content_builder()
{
    if (have_rows('content_builder')) {
        while (have_rows('content_builder')) {
            the_row();

            get_template_part('parts/part', get_row_layout());
        }
    }
}


/**
 * Register custom post type for publications.
 *
 * @return void
 */
function register_publications_cpt() {
    register_post_type('publication', [
        'label'        => 'Publications',
        'public'       => true,
        'show_in_menu' => true,
        'supports'     => ['title'],
        'has_archive'  => true, // Important if you want archive pages
        'rewrite'      => ['slug' => 'publications'],
        'show_in_rest' => true, // Optional, for Gutenberg/REST API
    ]);
}
add_action('init', 'register_publications_cpt');


/**
 * Register custom taxonomy for publications.
 *
 * @return void
 */
function publications_shortcode() {
    ob_start();
    // Paste the loop code here
    return ob_get_clean();
}
add_shortcode('publications_list', 'publications_shortcode');

/**
 * Builds a hierarchical tree structure from a flat array of menu items.
 *
 * This function recursively processes the given array of menu items and
 * organizes them into a tree structure based on their parent-child relationships.
 *
 * @param array $menu_items An array of menu items, where each item is an object
 *                          with at least 'ID' and 'menu_item_parent' properties.
 * @param int $parent_id The ID of the parent menu item. Default is 0, which
 *                       represents the root level.
 *
 * @return array A hierarchical array of menu items, where each item may contain
 *               a 'children' property with its child menu items.
 */
function build_menu_tree(array $menu_items, $parent_id = 0) {
    $branch = array();

    foreach ($menu_items as $menu_item) {
        if ((int)$menu_item->menu_item_parent === $parent_id) {
            $children = build_menu_tree($menu_items, $menu_item->ID);
            if ($children) {
                $menu_item->children = $children;
            }
            $branch[] = $menu_item;
        }
    }

    return $branch;
}

/**
 * Get ACF image alt.
 *
 * @param array $imageObj An ACF image object as array
 *
 * @return string The content for the alt attribute of the image
 */
function getAcfImageAlt($imageObj)
{
    $alt = $imageObj['title'];

    if (!empty($imageObj['alt'])) {
        $alt = $imageObj['alt'];
    }

    return $alt;
}

/**
 * Gets the id of the topmost ancestor of the current page. Returns the current posts's ID if there is no parent.
 *
 * @param int|bool $post_id
 *
 * @return int|bool parent post id
 * @uses object $post - global $post object
 */
function frank_get_post_top_ancestor_id($post_id = false)
{
    global $post;

    if ($post_id) {
        $post = get_post($post_id);
    }

    if (!is_object($post)) {
        return;
    }

    if ($post->post_parent) {
        $ancestors = array_reverse(get_post_ancestors($post->ID));

        return $ancestors[0];
    }

    return $post->ID;
}

/**
 * HTML block for rendering a location on the locations page.
 *
 * @param $location
 *
 * @return false|string
 */
function frank_location_html($location)
{
    ob_start(); ?>

    <li class="products__lists--item" id="<?= strtolower($location['city']) ?>">
        <div class="products__lists--item--photo">
            <picture>
                <?php
                $src = $location['image']['url'];
                $alt = getAcfImageAlt($location['image']);
                ?>
                <img loading="lazy" decoding="async"
                     src="<?= $src ?>"
                     alt="<?= $alt ?>"
                />
            </picture>
        </div>
        <div class="products__lists--item--text">
            <header class="products__lists--item--text--header">
                <h3>De Haan Westerhoff</h3>
                <h2><?= $location['city'] ?></h2>
            </header>
            <div class="products__lists--item--text--address">
                <div class="address">
                    <span><?= $location['address'] ?></span>
                    <span><?= $location['zipcode_city'] ?></span>
                </div>
                <div class="contact">
                    <?php if ($location['is_showroom'] === 'Y') : ?>
                        <span>
                            <svg class="icon">
                                <use xlink:href="<?= TPL_DIR_URI ?>/public/assets/icons/sprites.svg#icon-map-marker-check"></use>
                            </svg>
                            Showroom
                        </span>
                    <?php endif; ?>
                    <a href="tel:<?= $location['phone_link'] ?>"><?= $location['phone'] ?></a>
                </div>
            </div>
            <a href="<?= $location['google_route_link'] ?>" class="btn">
                <span>Route naar DHW <?= $location['city'] ?></span>
                <svg class="icon">
                    <use xlink:href="<?= TPL_DIR_URI ?>/public/assets/icons/sprites.svg#icon-chevron-right"></use>
                </svg>
            </a>
        </div>
    </li>

    <?php return ob_get_clean();
}

/**
 * Trim excerpt for.
 *
 * @param $content
 * @param $length
 *
 * @return false|string
 */
function trimExcerpt($content, $length)
{
    $result = str_replace('[&hellip;]', '', $content);
    return substr($result, 0, $length) . ' ...';
}