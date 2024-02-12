<?php

use Roots\Sage\{Extras, Titles};

?>

<header class="page-header">
  <div class="container">
    <div class="row">
      <div class="cs4">
        <h1 class="page-title size-h1-plus-ns"><?= Titles\title(); ?></h1>
      </div>
      <?php if ($intro = get_field('essays_introduction', 'option')) : ?>
      <div class="cs8">
        <?= $intro; ?>
      </div>
      <?php endif; ?>
    </div>
  </div>
</header>
<div class="container">
  <?php if (have_rows('essays_by_theme', 'options')) : ?>
    <?php while (have_rows('essays_by_theme', 'options')) : the_row(); ?>
      <?php
        $theme = get_sub_field('theme');
        $thumb_id = get_sub_field('background_image');
        $essays = get_posts([
          'post_type'      => 'essay',
          'posts_per_page' => -1,
          'tax_query'      => [
            [
              'taxonomy' => 'theme',
              'field'    => 'slug',
              'terms'    => $theme->slug,
            ],
          ],
        ]);
      ?>
      <div class="theme loading">
      <div
          class="theme__bg theme__bg--underlayer lazyload"
          data-bgset="<?= wp_get_attachment_image_src($thumb_id, "large")[0]; ?>"
        ></div>
        <div
          class="theme__bg lazyload bg-matrix-purple bg-cover-overlay bg-cover-overlay--purple"
          data-bgset="<?= wp_get_attachment_image_src($thumb_id, "large")[0]; ?>"
          data-bg-matrix
        ></div>
        <div class="theme__content">
          <div style="position: relative;" class="a11y-link-wrap">
            <h2 class="theme__title size-h1"><?= $theme->name; ?></h2>
            <?php if ($essays) : ?>
              <button class="theme__toggle" data-toggle="collapse" href="#collapseTheme-<?= $theme->slug; ?>" aria-expanded="false" aria-controls="collapseTheme-<?= $theme->slug; ?>">
                <?= Extras\get_related_essays_count_str(count($essays)); ?>
                <?= Extras\icons('caret-down-white', 15, 10); ?>
              </button>
            </div>
            <div class="collapse" id="collapseTheme-<?= $theme->slug; ?>">
              <?php foreach ($essays as $essay) : ?>
                <div class="essay">
                  <a class="essay__link" href="<?= get_the_permalink($essay); ?>">
                    <h3 class="essay__title size-h3"><?= $essay->post_title; ?></h3>
                    <span class="essay__author db mb3 mb0-ns">by <?= get_field('entry_author', $essay->ID); ?></span>
                  </a>
                </div>
                <?php wp_reset_postdata(); ?>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>
      </div>
    <?php endwhile; ?>
  <?php endif; ?>
</div>