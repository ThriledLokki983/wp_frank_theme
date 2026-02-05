<?php get_header(); ?>

<main class="main" data-publications-page>
    <?php get_template_part('parts/part', 'page-header'); ?>
    <?php frank_content_builder() ?>

    <!-- // make a dropdown list to filter the projects by type -->
    <div class="publications__filter">
        <div class="publications__filter-group">
            <label for="projects-filter" class="publications__filter-label">Filter by Type</label>
            <div class="publications__filter-select">
                <select id="projects-filter" class="publications__filter-select-dropdown" data-select-element>
                    <option value="all">All types</option>
                    <option value="phd supervision">PhD Supervision</option>
                    <option value="master students">Master Students</option>
                </select>
                <svg class="icon nav__icon">
                   <use xlink:href="<?php echo TPL_DIR_URI; ?>/public/assets/icons/sprites.svg#icon-chevron-right"></use>
                </svg>
            </div>
        </div>
    </div>

    <?php
        $publications = new WP_Query([
            'post_type'      => 'projects',
            'posts_per_page' => -1,
            'orderby'        => 'date',
            'order'          => 'DESC'
        ]);

        if ($publications->have_posts()) :
            echo '<ul class="publications js-publication__list" data-project-list>';
            while ($publications->have_posts()) : $publications->the_post();
                // Get all ACF fields
                $title = get_field('projects_title');
                $type = get_field('project_type');
                $description = get_field('description');
                
                // Fallback to post title if ACF title is empty
                if (empty($title)) {
                    $title = get_the_title();
                }
            ?>
                <li class="publication__item projects" data-publication-type="<?php echo esc_attr(strtolower($type)) ?>">
                     <div>
                        <h3><?php echo esc_html($title); ?></h3>
					    <span>
                            <?php echo esc_html($type) ?>
                        </span>
                        <?php if(!empty($description)): ?>
                            <p class="project-description"><?php echo wp_trim_words($description, 25); ?></p>
                        <?php endif; ?>
                    </div>
                    <footer>
                        <?php if (have_rows('contributors')) : ?>
                            <ul class="publishers">
                                <?php while (have_rows('contributors')) : the_row();
                                    $contributor_name = get_sub_field('authour_name');
                                    $is_lead = get_sub_field('lead_author');
                                    // ACF radio button returns string - check for 'yes' value
                                    $is_lead_contributor = ($is_lead === 'yes' || $is_lead === 'Yes' || $is_lead === true || $is_lead === '1');
                                    if ($contributor_name): ?>
                                        <li data-autor="<?php echo esc_attr($contributor_name); ?>"<?php if ($is_lead_contributor) echo ' data-lead-author="true"'; ?>>
                                            <span>
                                                <?php echo esc_html($contributor_name); ?>
                                            </span>
                                        </li>
                                    <?php endif; ?>
                                <?php endwhile; ?>
                            </ul>
                        <?php endif; ?>
                    </footer>
                </li>
            <?php
            endwhile;
            echo '</ul>';
            wp_reset_postdata();
            ?>
            
            <!-- Empty state for when filter returns no results -->
            <div class="publications__empty-state" data-empty-state data-visible="false">
                <svg class="publications__empty-state-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M9 12h6m-3-3v6m-7 4h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <h3 class="publications__empty-state-title">No projects found</h3>
                <p class="publications__empty-state-text">There are no projects matching your current filter. Try selecting a different type.</p>
            </div>
        <?php else : ?>
            <!-- Empty state when no projects exist -->
            <div class="publications__empty-state" data-empty-state data-visible="true">
                <svg class="publications__empty-state-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M9 12h6m-3-3v6m-7 4h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <h3 class="publications__empty-state-title">No projects found</h3>
                <p class="publications__empty-state-text">There are no ongoing projects to display at this time.</p>
            </div>
        <?php endif; ?>
</main>

<?php get_footer(); ?>