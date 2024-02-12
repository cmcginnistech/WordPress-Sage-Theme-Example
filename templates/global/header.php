<?php

use Roots\Sage\Nav;

$is_transparent = false;

if ( is_singular(['exhibition', 'press', 'essay', 'video']) || is_front_page() || is_page_template('template-exhibitions.php') || is_tax('press_exhibition') ) {
  $is_transparent = true;
}
?>


<a class="sr-only sr-only-focusable" id="skippy" href="#main"><?= __('Skip to main content', 'sage'); ?></a>

<header class="navbar-wrap <?php if ( is_front_page() ) { echo 'hidden-md hidden-lg'; }?>">
  <div class="navbar <?php if($is_transparent) echo 'navbar--transparent'; ?>">
    <div class="container">
      <div class="navbar-header">
        <a class="navbar-brand" href="<?= esc_url(home_url('/')); ?>">
          <img src="<?= get_template_directory_uri(); ?>/dist/images/logos/leiden-logo-full.png" alt="<?php bloginfo('name'); ?>" height="41" width="350">
        </a>

        <?php if ( !is_page_template('template-viewer.php') ) : ?>
          <div class="navbar-toggle-wrap">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#site-nav-wrapper" aria-expanded="false">
              <span class="sr-only"><?= __('Toggle navigation', 'sage'); ?></span>
              <span class="hamburger">
                <span class="icon-bar icon-bar--top"></span>
                <span class="icon-bar icon-bar--middle"></span>
                <span class="icon-bar icon-bar--bottom"></span>
              </span>
            </button>
          </div>
        <?php endif; ?>
      </div>

      <?php if ( is_page_template('template-viewer.php') ) : ?>
        <?php get_template_part('templates/partials/viewer-nav'); ?>
      <?php else : ?>
        <nav id="site-nav-wrapper" class="collapse navbar-collapse" role="navigation">
          <?php
          wp_nav_menu([
            'depth'          => 2,
            'menu_class'     => 'nav nav-primary navbar-nav',
            'theme_location' => 'primary_navigation',
            'walker'         => new Nav\SageNavWalker()
          ]);
          ?>
          <div class="navbar-form">
            <?php get_template_part('templates/global/searchform'); ?>
          </div>
        </nav>
      <?php endif; ?>

    </div>
  </div>
</header>
