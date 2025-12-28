<?php
/**
 * Template Name: Default Text Page
 * Description: A modern, elegant, and minimalistic template for text-based pages
 */
?>

<?php get_header(); ?>

<main class="main default-page" data-default-page>
    <?php get_template_part('parts/part', 'page-header'); ?>
    
    <div class="default-page-container">
        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>
                <article class="default-page-content">
                    <header class="default-page-header">
                        <h1 class="default-page-title"><?php the_title(); ?></h1>
                        <div class="default-page-divider"></div>
                    </header>
                    
                    <div class="default-page-body wysiwyg">
                        <?php the_content(); ?>
                    </div>
                </article>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>
</main>

<?php get_footer(); ?>