<?php

use Roots\Sage\{Extras, Wrapper};

?>

<!doctype html>
<html class="no-js" <?php language_attributes(); ?>>
  <?php get_template_part('templates/global/head'); ?>
  <body <?php body_class(); ?>>
    <?php do_action('leiden_body_start'); ?>
    <!--[if IE]>
      <div class="alert alert-warning">
        <?php _e('You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.', 'sage'); ?>
      </div>
    <![endif]-->
    <?php
      do_action('get_header');
      get_template_part('templates/global/header');
    ?>
    <div class="wrap" role="document">
      <main class="main" id="main" tabindex="-1">
        <?php include Wrapper\template_path(); ?>
      </main><!-- /.main -->
    </div><!-- /.wrap -->
    <?php
      do_action('get_footer');
      if ( !is_page_template(['template-viewer.php', 'template-collection.php', 'template-collection-gallery.php']) ) {
        get_template_part('templates/global/footer');
      }
      wp_footer();
    ?>
    <div class="device-xs visible-xs"></div>
    <div class="device-sm visible-sm"></div>
    <div class="device-md visible-md"></div>
    <div class="device-lg visible-lg"></div>
    <?= Extras\get_color_matrix_svg(); ?>
  </body>
</html>
