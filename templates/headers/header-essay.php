<?php
use Roots\Sage\Extras;
?>

<?php if ( $hero = get_the_post_thumbnail_url(null, 'hero') ) : ?>

  <?php if ( is_page_template('template-portrait-in-oil.php') ) { ?>
    <div class="page-header--hero page-header--portrait-in-oil flex items-center justify-center">
      <figure class="db mh2">
        <?= get_the_post_thumbnail(null, 'medium_large'); ?>
      </figure>
    </div>
  <?php } else { ?>
    <div class="page-header--hero">
      <figure class="loading">
        <div class="lazyload bg-cover bg-cover-overlay bg-cover-overlay--top plax" data-bgset="<?= esc_url($hero); ?>"></div>
      </figure>
    </div>
  <?php } ?>

<?php endif; ?>

<header class="entry-header container">
  <div class="row">
    <div class="entry-header__main">
      <?php if ( !is_page_template('template-portrait-in-oil.php') ) : ?>
        <small class="object-label mb3"><?php _e('Scholarly Essay', 'sage'); ?></small>
      <?php endif; ?>
      <h1 class="entry-header__title"><?php the_title(); ?></h1>
    </div>

    <div class="entry-header__sidebar">
      <?php get_template_part('templates/partials/post-utility-buttons'); ?>
      <?php Extras\how_to_cite(); ?>
    </div>
  </div>
</header>
