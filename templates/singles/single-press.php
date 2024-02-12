<?php

use Roots\Sage\Extras;

?>

<article class="mv4">
  <div class="container">
    <div class="row">
      <div class="c12 cm8">
        <?php get_template_part('templates/press/links'); ?>
      </div>
    </div>
    <div class="row mb4">
      <div class="entry-content cs8">
        <?php get_template_part('templates/press/media'); ?>
        <?php the_field('press_main_content'); ?>
      </div>
      <div class="cs3 cso1 single-press__sidebar">
        <?php get_template_part('templates/press/sidebar'); ?>
      </div>
    </div>
    <?php if(have_rows('components')) : ?>
      <div class="row">
        <?php Extras\componify(); ?>
      </div>
    <?php endif; ?>
  </div>
</article>
