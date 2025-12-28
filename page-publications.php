<?php get_header(); ?>

<main class="main" data-publications-page>
    <?php get_template_part('parts/part', 'page-header'); ?>
    <?php frank_content_builder() ?>

    <!-- // make a dropdown list to filter the publications by year -->
    <div class="publications__filter">
        <!-- <label for="publications-filter">Filter by year:</label> -->
        <div class="publications__filter-select">
            <select id="publications-filter" class="publications__filter-select-dropdown" data-select-element>
                <option value="all">Select publication type</option>
                <option value="abstract">Published abstract</option>
                <option value="paper">Papers</option>
            </select>
            <svg class="icon nav__icon">
               <use xlink:href="<?php echo TPL_DIR_URI; ?>/public/assets/icons/sprites.svg#icon-chevron-right"></use>
            </svg>
        </div>
    </div>

    <?php
        $publications = new WP_Query([
            'post_type'      => 'publication',
            'posts_per_page' => -1,
            'orderby'        => 'meta_value_num',
            'meta_query'     => [
                [
                    'key'     => 'journal_year',
                    'compare' => 'EXISTS',
                ],
            ],
            'meta_key'       => 'journal_year',
            'order'          => 'DESC'
        ]);

        if ($publications->have_posts()) :
            echo '<ul class="publications js-publication__list" data-pub-list>';
            while ($publications->have_posts()) : $publications->the_post();
                // Get all ACF fields
                $title  = get_field('journal_title');
                $field  = get_field('journal_field');
                $year          = get_field('journal_year');
                $authors       = get_field('journal_authors');
                $pdf_file      = get_field('journal_pdf_download_link');
                $external_link = get_field('journal_page_link');
                $status        = get_field('status');
                $type        = get_field('publication_type');
            ?>
                <li class="publication__item" data-publication-type="<?php echo esc_attr(strtolower($type)) ?>">
                     <div>
                        <h3> <?php echo esc_html($title)?></h3>
					    <span>
                            <?php echo esc_html($field) ?>
                            <strong>
                                (<?php echo esc_html($year) ?>)
                            </strong>
                        </span>
                    </div>
                    <footer>
                        <?php if (have_rows('journal_authors')) : ?>
                            <ul class="publishers">
                                <?php while (have_rows('journal_authors')) : the_row();
                                    $author_name = get_sub_field('authour_name');
                                    if ($author_name): ?>
                                        <li data-autor="<?php echo esc_attr($author_name); ?>">
                                            <span>
                                                <?php echo esc_html($author_name); ?>
                                            </span>
                                        </li>
                                    <?php endif; ?>
                                <?php endwhile; ?>
                            </ul>
                        <?php endif; ?>
                        <ul class="links">
                        <?php
                        ?>
                            <li>
                                <a href="<?php echo esc_url($pdf_file); ?>" aria-labelledby="Download article">
                                    <svg class="icon">
                                        <use xlink:href="<?php echo TPL_DIR_URI; ?>/public/assets/icons/sprites.svg#icon-download"></use>
                                    </svg>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo esc_url($external_link); ?>" aria-labelledby="View article">
                                    <svg class="icon">
                                        <use xlink:href="<?php echo TPL_DIR_URI; ?>/public/assets/icons/sprites.svg#icon-link"></use>
                                    </svg>
                                </a>
                            </li>
					    </ul>
                    </footer>
                </li>
            <?php
            endwhile;
            echo '</ul>'; // close publications-list
            wp_reset_postdata();
        else :
            echo '<p>No publications found.</p>';
        endif;
    ?>
</main>

<?php get_footer(); ?>