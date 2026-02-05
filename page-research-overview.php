<?php
/**
 * Template Name: Research Overview
 * Description: Combined page showing both Publications and Ongoing Projects
 */
get_header();
?>

<main class="main" data-research-overview-page>
    <?php get_template_part('parts/part', 'page-header'); ?>
    <?php frank_content_builder() ?>

    <!-- Publications Section -->
    <section class="research-section" id="publications">
        <header class="research-section__header">
            <h2 class="research-section__title">Publications</h2>
            <a href="<?php echo home_url('/research/publications/'); ?>" class="research-section__link">
                View all publications
                <svg class="icon" width="16" height="16">
                    <use xlink:href="<?php echo TPL_DIR_URI; ?>/public/assets/icons/sprites.svg#icon-arrow-right"></use>
                </svg>
            </a>
        </header>

        <div class="publications__filter">
            <div class="publications__filter-group">
                <label for="publications-year-filter" class="publications__filter-label">Filter by Year</label>
                <div class="publications__filter-select">
                    <select id="publications-year-filter" class="publications__filter-select-dropdown" data-filter-publications-year>
                        <option value="all">All years</option>
                        <?php
                            $pub_query = new WP_Query([
                                'post_type'      => 'publication',
                                'posts_per_page' => -1,
                                'orderby'        => 'meta_value_num',
                                'meta_key'       => 'journal_year',
                                'order'          => 'DESC'
                            ]);

                            $unique_years = [];
                            $unique_types = [];
                            if ($pub_query->have_posts()) :
                                while ($pub_query->have_posts()) : $pub_query->the_post();
                                    $year = get_field('journal_year');
                                    $pub_type = get_field('publication_type');
                                    if (!empty($year) && !in_array($year, $unique_years)) {
                                        $unique_years[] = $year;
                                    }
                                    if (!empty($pub_type) && !in_array($pub_type, $unique_types)) {
                                        $unique_types[] = $pub_type;
                                    }
                                endwhile;
                                wp_reset_postdata();
                            endif;

                            rsort($unique_years);
                            foreach ($unique_years as $year) :
                                echo '<option value="' . esc_attr($year) . '">' . esc_html($year) . '</option>';
                            endforeach;
                        ?>
                    </select>
                    <svg class="icon nav__icon">
                       <use xlink:href="<?php echo TPL_DIR_URI; ?>/public/assets/icons/sprites.svg#icon-chevron-right"></use>
                    </svg>
                </div>
            </div>

            <div class="publications__filter-group">
                <label for="publications-type-filter" class="publications__filter-label">Filter by Type</label>
                <div class="publications__filter-select">
                    <select id="publications-type-filter" class="publications__filter-select-dropdown" data-filter-publications-type>
                        <option value="all">All types</option>
                        <?php
                            sort($unique_types);
                            foreach ($unique_types as $pub_type) :
                                echo '<option value="' . esc_attr(strtolower($pub_type)) . '">' . esc_html(ucfirst($pub_type)) . '</option>';
                            endforeach;
                        ?>
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
                'posts_per_page' => 5, // Show limited items, link to full page
                'orderby'        => 'meta_value_num',
                'meta_key'       => 'journal_year',
                'order'          => 'DESC'
            ]);

            if ($publications->have_posts()) :
                echo '<ul class="publications js-publications-list">';
                while ($publications->have_posts()) : $publications->the_post();
                    $title = get_field('journal_title');
                    $field = get_field('journal_field');
                    $year = get_field('journal_year');
                    $pdf_file = get_field('journal_pdf_download_link');
                    $external_link = get_field('journal_page_link');
                    $type = get_field('publication_type');
        ?>
                    <li class="publication__item" data-publication-year="<?php echo esc_attr($year) ?>" data-publication-type="<?php echo esc_attr(strtolower($type)) ?>">
                        <div>
                            <h3><?php echo esc_html($title); ?></h3>
                            <span>
                                <?php echo esc_html($field) ?>
                                <strong>(<?php echo esc_html($year) ?>)</strong>
                            </span>
                        </div>
                        <footer>
                            <?php if (have_rows('journal_authors')) : ?>
                                <ul class="publishers">
                                    <?php while (have_rows('journal_authors')) : the_row();
                                        $author_name = get_sub_field('authour_name');
                                        $is_lead = get_sub_field('lead_author');
                                        $is_lead_author = ($is_lead === 'yes' || $is_lead === 'Yes' || $is_lead === true || $is_lead === '1');
                                        if ($author_name): ?>
                                            <li<?php if ($is_lead_author) echo ' data-lead-author="true"'; ?>>
                                                <span><?php echo esc_html($author_name); ?></span>
                                            </li>
                                        <?php endif; ?>
                                    <?php endwhile; ?>
                                </ul>
                            <?php endif; ?>
                            <ul class="links">
                                <?php if ($pdf_file): ?>
                                <li>
                                    <a href="<?php echo esc_url($pdf_file); ?>" aria-label="Download article">
                                        <svg class="icon">
                                            <use xlink:href="<?php echo TPL_DIR_URI; ?>/public/assets/icons/sprites.svg#icon-download"></use>
                                        </svg>
                                    </a>
                                </li>
                                <?php endif; ?>
                                <?php if ($external_link): ?>
                                <li>
                                    <a href="<?php echo esc_url($external_link); ?>" aria-label="View article">
                                        <svg class="icon">
                                            <use xlink:href="<?php echo TPL_DIR_URI; ?>/public/assets/icons/sprites.svg#icon-link"></use>
                                        </svg>
                                    </a>
                                </li>
                                <?php endif; ?>
                            </ul>
                        </footer>
                    </li>
        <?php
                endwhile;
                echo '</ul>';
                wp_reset_postdata();
        ?>
                <div class="publications__empty-state" data-empty-state-publications data-visible="false">
                    <svg class="publications__empty-state-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M9 12h6m-3-3v6m-7 4h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <h3 class="publications__empty-state-title">No publications found</h3>
                    <p class="publications__empty-state-text">There are no publications matching your current filters.</p>
                </div>
        <?php else : ?>
                <div class="publications__empty-state" data-visible="true">
                    <p>No publications available at this time.</p>
                </div>
        <?php endif; ?>
    </section>

    <!-- Ongoing Projects Section -->
    <section class="research-section" id="ongoing-projects">
        <header class="research-section__header">
            <h2 class="research-section__title">Ongoing Projects</h2>
            <a href="<?php echo home_url('/ongoing-projects/'); ?>" class="research-section__link">
                View all projects
                <svg class="icon" width="16" height="16">
                    <use xlink:href="<?php echo TPL_DIR_URI; ?>/public/assets/icons/sprites.svg#icon-arrow-right"></use>
                </svg>
            </a>
        </header>

        <div class="publications__filter">
            <div class="publications__filter-group">
                <label for="projects-filter" class="publications__filter-label">Filter by Type</label>
                <div class="publications__filter-select">
                    <select id="projects-filter" class="publications__filter-select-dropdown" data-filter-projects-type>
                        <option value="all">All types</option>
                        <option value="phd supervision">PhD Supervision</option>
                        <option value="master's students">Master's Students</option>
                    </select>
                    <svg class="icon nav__icon">
                       <use xlink:href="<?php echo TPL_DIR_URI; ?>/public/assets/icons/sprites.svg#icon-chevron-right"></use>
                    </svg>
                </div>
            </div>
        </div>

        <?php
            $projects = new WP_Query([
                'post_type'      => 'projects',
                'posts_per_page' => 5, // Show limited items
                'orderby'        => 'date',
                'order'          => 'DESC'
            ]);

            if ($projects->have_posts()) :
                echo '<ul class="publications js-projects-list">';
                while ($projects->have_posts()) : $projects->the_post();
                    $title = get_field('projects_title');
                    $type = get_field('project_type');
                    $description = get_field('description');
                    $status = get_field('project_status');
                    $collaborators = get_field('collaborators');
                    $year = get_field('year');
                    
                    // Fallback to post title if ACF title is empty
                    if (empty($title)) {
                        $title = get_the_title();
                    }
        ?>
                    <li class="publication__item" data-project-type="<?php echo esc_attr(strtolower($type)) ?>">
                        <div>
                            <h3><?php echo esc_html($title); ?></h3>
                            <span>
                                <?php echo esc_html($type) ?>
                                <?php if(!empty($year)): ?>
                                <strong>(<?php echo esc_html($year) ?>)</strong>
                                <?php endif; ?>
                            </span>
                            <?php if(!empty($description)): ?>
                                <p class="project-description"><?php echo wp_trim_words($description, 25); ?></p>
                            <?php endif; ?>
                        </div>
                        <footer>
                            <?php if(!empty($collaborators)): ?>
                                <ul class="publishers">
                                    <li><span><?php echo esc_html($collaborators); ?></span></li>
                                </ul>
                            <?php endif; ?>
                            <?php if(!empty($status)): ?>
                                <ul class="links">
                                    <li><span class="project-status-badge"><?php echo esc_html($status); ?></span></li>
                                </ul>
                            <?php endif; ?>
                        </footer>
                    </li>
        <?php
                endwhile;
                echo '</ul>';
                wp_reset_postdata();
        ?>
                <div class="publications__empty-state" data-empty-state-projects data-visible="false">
                    <svg class="publications__empty-state-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M9 12h6m-3-3v6m-7 4h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <h3 class="publications__empty-state-title">No projects found</h3>
                    <p class="publications__empty-state-text">There are no projects matching your current filter.</p>
                </div>
        <?php else : ?>
                <div class="publications__empty-state" data-visible="true">
                    <p>No ongoing projects available at this time.</p>
                </div>
        <?php endif; ?>
    </section>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const staggerDelay = 50;

    // Publications filters
    const pubYearFilter = document.querySelector('[data-filter-publications-year]');
    const pubTypeFilter = document.querySelector('[data-filter-publications-type]');
    const publicationItems = document.querySelectorAll('.js-publications-list li');
    const pubEmptyState = document.querySelector('[data-empty-state-publications]');

    function applyPublicationsFilters() {
        const selectedYear = pubYearFilter?.value?.toLowerCase() || 'all';
        const selectedType = pubTypeFilter?.value?.toLowerCase() || 'all';
        let visibleCount = 0;

        publicationItems.forEach(item => {
            const itemYear = item.dataset.publicationYear?.toLowerCase() || '';
            const itemType = item.dataset.publicationType?.toLowerCase() || '';
            const yearMatch = selectedYear === 'all' || itemYear === selectedYear;
            const typeMatch = selectedType === 'all' || itemType === selectedType;

            if (!yearMatch || !typeMatch) {
                item.setAttribute('data-hidden', 'true');
            }
        });

        publicationItems.forEach(item => {
            const itemYear = item.dataset.publicationYear?.toLowerCase() || '';
            const itemType = item.dataset.publicationType?.toLowerCase() || '';
            const yearMatch = selectedYear === 'all' || itemYear === selectedYear;
            const typeMatch = selectedType === 'all' || itemType === selectedType;

            if (yearMatch && typeMatch) {
                setTimeout(() => {
                    item.setAttribute('data-hidden', 'false');
                }, visibleCount * staggerDelay);
                visibleCount++;
            }
        });

        if (pubEmptyState) {
            pubEmptyState.setAttribute('data-visible', visibleCount === 0 ? 'true' : 'false');
        }
    }

    if (pubYearFilter) pubYearFilter.addEventListener('change', applyPublicationsFilters);
    if (pubTypeFilter) pubTypeFilter.addEventListener('change', applyPublicationsFilters);

    // Projects filter
    const projectTypeFilter = document.querySelector('[data-filter-projects-type]');
    const projectItems = document.querySelectorAll('.js-projects-list li');
    const projectEmptyState = document.querySelector('[data-empty-state-projects]');

    function applyProjectsFilter() {
        const selectedType = projectTypeFilter?.value?.toLowerCase() || 'all';
        let visibleCount = 0;

        projectItems.forEach(item => {
            const itemType = item.dataset.projectType?.toLowerCase() || '';
            if (selectedType !== 'all' && itemType !== selectedType) {
                item.setAttribute('data-hidden', 'true');
            }
        });

        projectItems.forEach(item => {
            const itemType = item.dataset.projectType?.toLowerCase() || '';
            if (selectedType === 'all' || itemType === selectedType) {
                setTimeout(() => {
                    item.setAttribute('data-hidden', 'false');
                }, visibleCount * staggerDelay);
                visibleCount++;
            }
        });

        if (projectEmptyState) {
            projectEmptyState.setAttribute('data-visible', visibleCount === 0 ? 'true' : 'false');
        }
    }

    if (projectTypeFilter) projectTypeFilter.addEventListener('change', applyProjectsFilter);
});
</script>

<?php get_footer(); ?>
