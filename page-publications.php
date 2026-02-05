<?php 
get_header(); 
$pub_filter = get_query_var('pub_filter', '');
?>

<main class="main" data-publications-page<?php if ($pub_filter): ?> data-initial-filter="<?php echo esc_attr($pub_filter); ?>"<?php endif; ?>>
    <?php get_template_part('parts/part', 'page-header'); ?>
    <?php frank_content_builder() ?>

    <!-- // make a dropdown list to filter the publications by type -->
    <div class="publications__filter">
        <div class="publications__filter-group">
            <label for="publications-filter" class="publications__filter-label">Filter by Type</label>
            <div class="publications__filter-select">
                <select id="publications-filter" class="publications__filter-select-dropdown" data-select-element>
                    <option value="all">All types</option>
                    <option value="abstract"<?php if ($pub_filter === 'abstract'): ?> selected<?php endif; ?>>Published abstract</option>
                    <option value="paper"<?php if ($pub_filter === 'paper' || $pub_filter === 'papers'): ?> selected<?php endif; ?>>Papers</option>
                </select>
                <svg class="icon nav__icon">
                   <use xlink:href="<?php echo TPL_DIR_URI; ?>/public/assets/icons/sprites.svg#icon-chevron-right"></use>
                </svg>
            </div>
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
                                    $is_lead = get_sub_field('lead_author');
                                    // ACF radio button returns string - check for 'yes' value
                                    $is_lead_author = ($is_lead === 'yes' || $is_lead === 'Yes' || $is_lead === true || $is_lead === '1');
                                    if ($author_name): ?>
                                        <li data-autor="<?php echo esc_attr($author_name); ?>"<?php if ($is_lead_author) echo ' data-lead-author="true"'; ?>>
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
            ?>
            
            <!-- Empty state for when filter returns no results -->
            <div class="publications__empty-state" data-empty-state data-visible="false">
                <svg class="publications__empty-state-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M9 12h6m-3-3v6m-7 4h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <h3 class="publications__empty-state-title">No publications found</h3>
                <p class="publications__empty-state-text">There are no publications matching your current filter. Try selecting a different type.</p>
            </div>
        <?php else : ?>
            <!-- Empty state when no publications exist -->
            <div class="publications__empty-state" data-empty-state data-visible="true">
                <svg class="publications__empty-state-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M9 12h6m-3-3v6m-7 4h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <h3 class="publications__empty-state-title">No publications found</h3>
                <p class="publications__empty-state-text">There are no publications to display at this time.</p>
            </div>
        <?php endif; ?>
</main>

<?php get_footer(); ?>