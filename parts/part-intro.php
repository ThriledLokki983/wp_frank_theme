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
        <ul class="socials">
            <li>
                <a href="<?= get_sub_field('linkedin_link'); ?>" target="_blank" rel="noopener" aria-label="">
                    <svg class="icon">
                        <use
                            xlink:href="<?= TPL_DIR_URI ?>/public/assets/icons/sprites.svg#icon-linkedin"
                        ></use>
                    </svg>
                    <span><?= get_sub_field('linkedin_text'); ?></span>
                </a>
            </li>
            <li>
                <a href="email:<?= get_sub_field('email'); ?>"  target="_blank" rel="noopener" aria-label="">
                    <svg class="icon">
                        <use
                            xlink:href="<?= TPL_DIR_URI ?>/public/assets/icons/sprites.svg#icon-envelop"
                        ></use>
                    </svg>
                    <span><?= get_sub_field('email_text'); ?></span>
                </a>
            </li>
            <li>
                <a href="tel:<?= get_sub_field('telephone'); ?>"  target="_blank" rel="noopener" aria-label="">
                    <svg class="icon">
                        <use
                            xlink:href="<?= TPL_DIR_URI ?>/public/assets/icons/sprites.svg#icon-phone-box"
                        ></use>
                    </svg>
                    <span><?= get_sub_field('telephone'); ?></span>
                </a>
            </li>
        </ul>
    </article>
</section>