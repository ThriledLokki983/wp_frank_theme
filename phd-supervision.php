<?php get_header(); ?>

<main class="main" data-phd-page>
    <?php get_template_part('parts/part', 'page-header'); ?>
    <?php frank_content_builder() ?>

    <!-- Filter for PhD supervision projects -->
    <div class="publications__filter">
        <div class="publications__filter-select">
            <select id="phd-projects-filter" class="publications__filter-select-dropdown" data-select-element>
                <option value="all">All PhD Students</option>
                <option value="current">Current Students</option>
                <option value="completed">Graduated Students</option>
            </select>
            <svg class="icon nav__icon">
               <use xlink:href="<?php echo TPL_DIR_URI; ?>/public/assets/icons/sprites.svg#icon-chevron-right"></use>
            </svg>
        </div>
    </div>

    <?php
        $phd_projects = new WP_Query([
            'post_type'      => 'project',
            'posts_per_page' => -1,
            'orderby'        => 'meta_value',
            'meta_query'     => [
                [
                    'key'     => 'project_type',
                    'value'   => 'phd',
                    'compare' => '=',
                ],
            ],
            'order'          => 'DESC'
        ]);

        if ($phd_projects->have_posts()) :
            echo '<ul class="publications js-publication__list" data-phd-list>';
            while ($phd_projects->have_posts()) : $phd_projects->the_post();
                // Get all ACF fields
                $title = get_field('project_title');
                $description = get_field('project_description');
                $status = get_field('project_status');
                $year = get_field('project_year');
                $student = get_field('project_student');
                $external_link = get_field('project_link');
                $collaborators = get_field('project_collaborators');
                $start_date = get_field('project_start_date');
                $expected_completion = get_field('project_expected_completion');
                $publications = get_field('project_related_publications');
                
                // If ACF fields aren't set up or aren't returning values, use default WP fields
                if (empty($title)) $title = get_the_title();
                if (empty($description)) $description = get_the_excerpt();
                if (empty($status)) $status = 'current';
            ?>
                <li class="publication__item phd-item" data-phd-status="<?php echo esc_attr(strtolower($status)); ?>" data-hidden="false">
                    <div>
                        <small><?php echo esc_html(strtolower($status) == 'completed' ? 'Graduated' : 'Current Student'); ?></small>
                        
                        <?php if ($student) : ?>
                        <div class="phd-student">
                            <h2 class="student-name"><?php echo esc_html($student); ?></h2>
                            <?php if ($start_date) : ?>
                                <span class="student-year"><?php echo esc_html($start_date); ?> - 
                                <?php echo strtolower($status) == 'completed' ? esc_html($expected_completion) : 'Present'; ?></span>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                        
                        <h3><?php echo esc_html($title); ?></h3>
                        
                        <div class="project-description">
                            <?php echo wp_kses_post($description); ?>
                        </div>
                        
                        <?php if ($expected_completion && strtolower($status) !== 'completed') : ?>
                        <div class="project-timeline">
                            <span>
                                <svg class="icon">
                                    <use xlink:href="<?php echo TPL_DIR_URI; ?>/public/assets/icons/sprites.svg#icon-calendar"></use>
                                </svg>
                                Expected completion: <?php echo esc_html($expected_completion); ?>
                            </span>
                        </div>
                        <?php endif; ?>

                        <?php if ($collaborators) : ?>
                        <div class="project-collaborators">
                            <span class="collaborators-label">Collaborators:</span>
                            <?php echo esc_html($collaborators); ?>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($publications) : ?>
                        <div class="project-publications">
                            <h4>Related Publications</h4>
                            <div class="publications-list">
                                <?php echo wp_kses_post($publications); ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <?php if ($external_link) : ?>
                    <footer>
                        <ul class="links">
                            <li>
                                <a href="<?php echo esc_url($external_link); ?>" aria-label="View project details" target="_blank">
                                    <svg class="icon">
                                        <use xlink:href="<?php echo TPL_DIR_URI; ?>/public/assets/icons/sprites.svg#icon-link"></use>
                                    </svg>
                                </a>
                            </li>
                        </ul>
                    </footer>
                    <?php endif; ?>
                </li>
            <?php
            endwhile;
            echo '</ul>'; // close projects list
            wp_reset_postdata();
        else :
            echo '<p class="no-phd-found">No PhD supervision projects found. Please check back later.</p>';
        endif;
    ?>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterSelect = document.getElementById('phd-projects-filter');
    const projectItems = document.querySelectorAll('[data-phd-list] li');
    
    if (filterSelect) {
        filterSelect.addEventListener('change', function() {
            const selectedValue = this.value;
            
            projectItems.forEach(item => {
                const status = item.getAttribute('data-phd-status');
                
                if (selectedValue === 'all' || status === selectedValue) {
                    item.setAttribute('data-hidden', 'false');
                } else {
                    item.setAttribute('data-hidden', 'true');
                }
            });
        });
    }
});
</script>

<?php get_footer(); ?>