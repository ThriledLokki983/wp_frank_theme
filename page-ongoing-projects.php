<?php get_header(); ?>

<main class="main" data-projects-page>
    <?php get_template_part('parts/part', 'page-header'); ?>
    <?php frank_content_builder() ?>

    <!-- // make a dropdown list to filter the publications by year -->
    <div class="publications__filter">
        <div class="publications__filter-select">
            <select id="publications-filter" class="publications__filter-select-dropdown" data-select-element>
                <option value="all">Select project type</option>
                <option value="phd supervision">PhD Supervision</option>
                <option value="master's students">Master's Students</option>
            </select>
            <svg class="icon nav__icon">
               <use xlink:href="<?php echo TPL_DIR_URI; ?>/public/assets/icons/sprites.svg#icon-chevron-right"></use>
            </svg>
        </div>
    </div>

    <?php
        $publications = new WP_Query([
            'post_type'      => 'projects',
            'posts_per_page' => -1,
            'orderby'        => 'meta_value_num',
            'meta_query'     => [
                [
                    'key'     => 'title',
                    'compare' => 'EXISTS',
                ],
            ],
            'meta_key'       => 'title',
            'order'          => 'DESC'
        ]);

        if ($publications->have_posts()) :
            echo '<ul class="publications js-publication__list" data-project-list>';
            while ($publications->have_posts()) : $publications->the_post();
                // Get all ACF fields
                $title = get_field('title');
                $type = get_field('project_type');
                $description = get_field('description');
                $status = get_field('project_status');
                $collaborators = get_field('collaborators');
                $year = get_field('year');
            ?>
                <li class="publication__item alt" data-publication-type="<?php echo esc_attr(strtolower($type)) ?>">
                     <div>
                        <h3><?php echo esc_html($title); ?></h3>
                        <small><?php echo esc_html($type); ?></small>

                        <?php if(!empty($description)): ?>
                            <p class="project-description"><?php echo wp_trim_words($description, 25); ?></p>
                        <?php endif; ?>

                        <div class="project-meta">
                            <?php if(!empty($status)): ?>
                                <span class="project-status">
                                    <svg class="icon" aria-hidden="true">
                                        <use xlink:href="<?php echo TPL_DIR_URI; ?>/public/assets/icons/sprites.svg#icon-time"></use>
                                    </svg>
                                    <?php echo esc_html($status); ?>
                                </span>
                            <?php endif; ?>

                            <?php if(!empty($year)): ?>
                                <span class="project-year">
                                    <svg class="icon" aria-hidden="true">
                                        <use xlink:href="<?php echo TPL_DIR_URI; ?>/public/assets/icons/sprites.svg#icon-calendar"></use>
                                    </svg>
                                    <?php echo esc_html($year); ?>
                                </span>
                            <?php endif; ?>
                        </div>

                        <?php if(!empty($collaborators)): ?>
                            <div class="project-collaborators">
                                <span class="collaborators-label">Collaborators:</span>
                                <span class="collaborators-list"><?php echo esc_html($collaborators); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </li>
            <?php
            endwhile;
            echo '</ul>';
            wp_reset_postdata();
        else :
            echo '<p>No projects found.</p>';
        endif;
    ?>
</main>

<?php get_footer(); ?>