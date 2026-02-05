<section id="home" class="content">
    <article class="home__right">
        <picture>
            <?php
            $img = get_sub_field('image');
            $src = $img['url'];
            $alt = getAcfImageAlt($img);
            ?>
            <img
                loading="lazy"
                decoding="async"
                width="300"
                height="200"
                src="<?= $src ?>"
                alt="<?= $alt ?>"
            />
            <div></div>
        </picture>
    </article>
    <article class="home__left">
        <header class="home__header">
            <h1>
                <?= get_sub_field('name'); ?>,
                <small>
                    <?= get_sub_field('position'); ?>
                </small>
            </h1>
            <span>
                <?= get_sub_field('title'); ?>
                <small>
                    (<?= get_sub_field('workplace'); ?>)
                </small>
            </span>
            <span><?= get_sub_field('workplace'); ?></span>
            <?= get_sub_field('intro_content'); ?>

    </header>
        <p>
            <?= get_sub_field('text'); ?>
        </p>
        <ul class="socials socials--circles">
            <?php if (get_sub_field('linkedin_link')): ?>
            <li>
                <a href="<?= get_sub_field('linkedin_link'); ?>" target="_blank" rel="noopener" aria-label="LinkedIn Profile">
                    <svg class="icon">
                        <use xlink:href="<?= TPL_DIR_URI ?>/public/assets/icons/sprites.svg#icon-linkedin"></use>
                    </svg>
                </a>
            </li>
            <?php endif; ?>
            <?php if (get_sub_field('email')): ?>
            <li>
                <a href="mailto:<?= get_sub_field('email'); ?>" target="_blank" rel="noopener" aria-label="Send Email">
                    <svg class="icon">
                        <use xlink:href="<?= TPL_DIR_URI ?>/public/assets/icons/sprites.svg#icon-envelop"></use>
                    </svg>
                </a>
            </li>
            <?php endif; ?>
            <?php if (get_sub_field('telephone')): ?>
            <li>
                <a href="tel:<?= get_sub_field('telephone'); ?>" target="_blank" rel="noopener" aria-label="Call">
                    <svg class="icon">
                        <use xlink:href="<?= TPL_DIR_URI ?>/public/assets/icons/sprites.svg#icon-phone-box"></use>
                    </svg>
                </a>
            </li>
            <?php endif; ?>
        </ul>
    </article>
</section>