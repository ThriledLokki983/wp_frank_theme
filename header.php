<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="supported-color-schemes" content="light dark"/>
    <meta name="msapplication-TileColor" content="#fff9e5bf"/>
    <meta name="theme-color" content="#fff9e5bf"/>
    <?php wp_head(); ?>
</head>

<body dir="ltr" <?php body_class(); ?> data-page-id="<?php echo get_the_ID(); ?>" data-page-name="<?php echo strtolower(get_the_title()); ?>">
	<?php if (get_the_id() !== 13093) : ?>
		<header class="navigation primary__header" data-header>
			<section id="header">
				<div class="logo">
					<a href="<?= get_site_url(); ?>" class="logo--link">
						<picture class="logo-picture">
							<source srcset="<?= TPL_DIR_URI ?>/public/assets/images/logo.png" type="image/webp">
							<img src="<?= TPL_DIR_URI ?>/public/assets/images/logo.png" alt="Frank Tsiwah logo" class="header__bottom--logo-img">
						</picture>
					</a>
				</div>
				<nav class="nav primary-navigation" id="primary-navigation" aria-label="Primary Navigation" data-mobile-expanded="false">
					<ul class="nav__list" role="list" aria-label="Primary">
						<?php
							$locations = get_nav_menu_locations();

							if (isset($locations['mega-menu'])) {
								$menu = get_term($locations['mega-menu'], 'nav_menu');
								if ($items = wp_get_nav_menu_items($menu->term_id)) {
									$menu_tree = build_menu_tree($items);

									function render_menu($menu_items) {
										foreach ($menu_items as $item) {
											$has_children = !empty($item->children);
											$current = ( $item->object_id == get_queried_object_id() ) ? 'aria-current="page"' : '';
											echo '<li class="nav__item'. ($has_children ? ' has-children' : '') .'" data-id="' . $item->ID . '">';
												echo '<div class="nav__link--wrapper">';
													echo '<a href="' . $item->url . '" class="nav__link" aria-label="Navigation link" ' . $current . '>' . $item->title . '</a>';
													if ($has_children) {
														echo '<button class="toggle__icon" data-mobile-expanded="false" aria-label="Toggle submenu">';
															echo '<svg class="icon nav__icon">';
																echo '<use xlink:href="' . TPL_DIR_URI . '/public/assets/icons/sprites.svg#icon-chevron-right"></use>';
															echo '</svg>';
														echo '</button>';
													}
												echo '</div>';

												if ($has_children) {
													echo '<div class="sub__menu ' . (count($item->children) == 1 ? 'single-column' : '') . '">';
														echo '<ul class="sub__menu--lists">';
															foreach ($item->children as $child) {
																$child_has_children = !empty($child->children);

																echo '<li class="sub__menu--lists--item' . ($child_has_children ? ' has-children' : '') . '" data-id="' . $child->ID . '">';
																	echo '<a href="' . $child->url . '" class="sub__menu--list-s-item-link">' . $child->title . '</a>';

																	// If child has its own children (nested submenu)
																	if ($child_has_children) {
																		echo '<div class="nested-sub__menu ' . (count($child->children) == 1 ? 'single-column' : '') . '">';
																			echo '<ul>';
																				foreach ($child->children as $sub_child) {
																					echo '<li>';
																						echo '<a href="' . $sub_child->url . '">' . $sub_child->title . '</a>';
																					echo '</li>';
																				}
																			echo '</ul>';
																		echo '</div>';
																	}
																echo '</li>';
															}
														echo '</ul>';
													echo '</div>';
												}

											echo '</li>';
										}
									}
									render_menu($menu_tree);
								}
							}
						?>
					</ul>
				</nav>
				<template id="burger-template">
						<button type="button" class="burger" aria-label="Menu" aria-expanded="false" aria-controls="mainnav">
								<span class="burger__line" aria-hidden="true"></span>
								<span class="burger__line" aria-hidden="true"></span>
								<span class="burger__line" aria-hidden="true"></span>
						</button>
				</template>
			</section>
		</header>
	<?php endif; ?>