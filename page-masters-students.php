<?php get_header(); ?>

<main class="main" data-masters-page>
    <?php get_template_part('parts/part', 'page-header'); ?>
    <?php frank_content_builder() ?>

    <!-- Filter for masters students projects -->
    <div class="publications__filter">
        <div class="publications__filter-select">
            <select id="masters-projects-filter" class="publications__filter-select-dropdown" data-select-element>
                <option value="all">All projects</option>
                <option value="ongoing">Ongoing</option>
                <option value="completed">Completed</option>
            </select>
            <svg class="icon nav__icon">
               <use xlink:href="<?php echo TPL_DIR_URI; ?>/public/assets/icons/sprites.svg#icon-chevron-right"></use>
            </svg>
        </div>
    </div>

    <?php
        // Debug information
        echo '<div class="debug-info" style="background: #f5f5f5; padding: 15px; margin: 10px 0; border: 1px solid #ccc;">';
        echo '<h4 style="margin-top: 0;">Debug Information</h4>';
        
        // First, let's check if 'project' post type exists
        $post_types = get_post_types([], 'names');
        echo '<p>Available post types: ' . implode(', ', $post_types) . '</p>';
        
        // Get total count of project posts of any type
        $all_projects = new WP_Query([
            'post_type' => 'project',
            'posts_per_page' => -1,
        ]);
        echo '<p>Total projects: ' . $all_projects->found_posts . '</p>';
        
        // Query master projects
        $projects = new WP_Query([
            'post_type'      => 'project',
            'posts_per_page' => -1,
            'orderby'        => 'meta_value',
            'meta_query'     => [
                [
                    'key'     => 'project_type',
                    'value'   => 'masters',
                    'compare' => '=',
                ],
            ],
            'order'          => 'DESC'
        ]);
        
        echo '<p>Masters projects found: ' . $projects->found_posts . '</p>';
        echo '<p>Is Advanced Custom Fields active? ' . (function_exists('get_field') ? 'Yes' : 'No') . '</p>';
        
        // Debug meta query
        if ($projects->found_posts == 0 && function_exists('get_field')) {
            // Check for any projects that have the project_type field set
            global $wpdb;
            $meta_values = $wpdb->get_results(
                "SELECT meta_value, COUNT(*) as count 
                FROM {$wpdb->postmeta} 
                WHERE meta_key = 'project_type'
                GROUP BY meta_value"
            );
            
            if ($meta_values) {
                echo '<p>Available project_type values:</p><ul>';
                foreach ($meta_values as $value) {
                    echo '<li>' . esc_html($value->meta_value) . ' (' . $value->count . ')</li>';
                }
                echo '</ul>';
            } else {
                echo '<p>No project_type meta values found in database.</p>';
            }
        }
        echo '</div>';
        
        if ($projects->have_posts()) :
            echo '<ul class="publications js-publication__list" data-project-list>';
            while ($projects->have_posts()) : $projects->the_post();
                // Get all ACF fields
                $title = get_field('project_title');
                $description = get_field('project_description');
                $status = get_field('project_status');
                $year = get_field('project_year');
                $student = get_field('project_student');
                $external_link = get_field('project_link');
                $collaborators = get_field('project_collaborators');
                
                // If ACF fields aren't set up or aren't returning values, use default WP fields
                if (empty($title)) $title = get_the_title();
                if (empty($description)) $description = get_the_excerpt();
                if (empty($status)) $status = 'unknown';
            ?>
                <li class="publication__item alt" data-project-status="<?php echo esc_attr(strtolower($status)); ?>" data-hidden="false">
                    <div>
                        <small><?php echo esc_html($status); ?></small>
                        <h3><?php echo esc_html($title); ?></h3>
                        <div class="project-description">
                            <?php echo wp_kses_post($description); ?>
                        </div>
                        <div class="project-meta">
                            <?php if ($student) : ?>
                            <span>
                                <svg class="icon">
                                    <use xlink:href="<?php echo TPL_DIR_URI; ?>/public/assets/icons/sprites.svg#icon-user"></use>
                                </svg>
                                <?php echo esc_html($student); ?>
                            </span>
                            <?php endif; ?>

                            <?php if ($year) : ?>
                            <span>
                                <svg class="icon">
                                    <use xlink:href="<?php echo TPL_DIR_URI; ?>/public/assets/icons/sprites.svg#icon-calendar"></use>
                                </svg>
                                <?php echo esc_html($year); ?>
                            </span>
                            <?php endif; ?>
                        </div>

                        <?php if ($collaborators) : ?>
                        <div class="project-collaborators">
                            <span class="collaborators-label">Collaborators:</span>
                            <?php echo esc_html($collaborators); ?>
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
            echo '<p>No masters projects found.</p>';
        endif;
    ?>

    <!-- Empty state for when filter returns no results -->
    <div class="publications__empty-state" data-empty-state data-visible="false">
        <svg class="publications__empty-state-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <path d="M9 12h6m-3-3v6m-7 4h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
        </svg>
        <h3 class="publications__empty-state-title">No projects found</h3>
        <p class="publications__empty-state-text">There are no projects matching your current filter. Try selecting a different status.</p>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterSelect = document.getElementById('masters-projects-filter');
    const projectItems = document.querySelectorAll('[data-project-list] li');
    const emptyState = document.querySelector('[data-empty-state]');

    if (filterSelect) {
        filterSelect.addEventListener('change', function() {
            const selectedValue = this.value;
            let visibleCount = 0;

            projectItems.forEach(item => {
                const status = item.getAttribute('data-project-status');

                if (selectedValue === 'all' || status === selectedValue) {
                    item.setAttribute('data-hidden', 'false');
                    visibleCount++;
                } else {
                    item.setAttribute('data-hidden', 'true');
                }
            });

            // Show/hide empty state
            if (emptyState) {
                emptyState.setAttribute('data-visible', visibleCount === 0 ? 'true' : 'false');
            }
        });
    }
});
</script>

<?php get_footer(); ?>