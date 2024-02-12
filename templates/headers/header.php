<?php use Roots\Sage\Titles; ?>

<header class="page-header">
  <div class="container">
    <div class="row">
      <div class="cs4">
        <h1 class="page-title"><?= Titles\title(); ?></h1>
      </div>
      <div class="cs8 text-right flex item-end justify-end-ns flex-wrap">
        <?php if ( is_archive() && !is_post_type_archive(['staff_member', 'glossary']) ) : ?>
          <?php get_template_part('templates/archives/search-bar'); ?>
        <?php elseif ( is_post_type_archive('glossary') ) : ?>
          <?php get_template_part('templates/archives/glossary-search'); ?>
        <?php endif; ?>
      </div>
    </div>
  </div>
</header>
