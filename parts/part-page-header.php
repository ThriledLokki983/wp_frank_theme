<?php $fields = get_fields(); ?>

<?php if ($fields['hero_show'] === 'T1') : ?>
<figure class="hero">
    <?php
        $src = $fields['hero_image']['url'];
        $alt = getAcfImageAlt($fields['hero_image']);
    ?>
    <img
            loading="eager"
            decoding="sync"
            width="1440"
            height="220"
            src="<?= $src ?>"
            alt="<?= $alt ?>"
            srcset="<?= $src ?>"
            sizes="(max-width: 960px) 100%, ..."
            class="hero__image-box--img"
    />
    <figcaption><?= get_field('hero_title'); ?></figcaption>
</figure>

<?php elseif ($fields['hero_show'] === 'T2') : ?>
    <!-- <section class="hero">
        <figure class="hero__image-box span-6">
            <?php
            $src = $fields['hero_image']['url'];
            $alt = getAcfImageAlt($fields['hero_image']);
            ?>
            <img
                    loading="eager"
                    decoding="sync"
                    width="300"
                    height="200"
                    src="<?= $src ?>"
                    alt="<?= $alt ?>"
                    srcset="<?= $src ?>"
                    sizes="(max-width: 960px) 100%, ..."
                    class="hero__image-box--img"
            />
            <div class="hero__overlay">
                <div class="hero__overlay__content">
                    <h1 class="hero__overlay__content-title clr-primary"><?= get_field('hero_title'); ?></h1>
                    <p class="hero__overlay__content-paragraph"><?= get_field('hero_text'); ?></p>
                </div>

                <?php if (!empty(get_field('hero_button_label')) && !empty(get_field('hero_button_link'))) : ?>
                    <div class="header__top--btn">
                        <a href="<?= get_field('hero_button_link') ?>" class="btn bn primary">
                            <span><?= get_field('hero_button_label') ?></span>
                            <svg class="icon">
                                <use xlink:href="<?= TPL_DIR_URI ?>/public/assets/icons/sprites.svg#icon-chevron-right"></use>
                            </svg>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </figure>
    </section> -->
    <h1><?= get_field('hero_title'); ?></h1>
<?php endif; ?>