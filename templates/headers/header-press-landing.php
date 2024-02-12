<?php

use Roots\Sage\Titles;

// Get navigation items from theme options field.
// note: this is becauase custom ordering is important.
$cats = get_field('landing_pg_nav_cats', 'options');

?>

<header class="page-header page-header--press">
  <div class="container tc">
    <h1 class="heading-reset text-white size-h1-plus dib"><?= Titles\title(); ?></h1>

    <ul class="list-inline list-unstyled list-categories mt3">
      <li><a class="serif" href="<?= get_post_type_archive_link('press'); ?>"><?= __('All Coverage', 'sage'); ?></a></li>

      <?php
      if ( $cats ) {
        foreach ( $cats as $cat ) {
          $term_link = esc_url( get_term_link($cat) );
          echo "<li><a class=\"serif\" href=\"{$term_link}\">{$cat->name}</a></li>";
        }
      } ?>

      <?php if(get_field('press_inquiries_page', 'option')) : ?>
        <li><a class="serif" href="<?= get_field('press_inquiries_page', 'option'); ?>"><?= __('Press Inquiries', 'sage'); ?></a></li>
      <?php endif; ?>
    </ul>

  </div>
</header>
