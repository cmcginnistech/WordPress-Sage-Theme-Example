<?php

use Roots\Sage\Extras;

?>

<article class="container">
  <div class="row mt4 mb4 mb5-ns">
    <div class="cs8 entry-content">
      <?php the_field('introductory_content'); ?>
    </div>
    <div class="cs1"></div>
    <aside class="single-exhibition__sidebar cs3">
      <?php get_template_part('templates/exhibitions/sidebar'); ?>
    </aside>
  </div>

  <?php if(have_rows('components')) : ?>
  <div class="row">
    <?php Extras\componify(); ?>
  </div>
  <?php endif; ?>
</article>