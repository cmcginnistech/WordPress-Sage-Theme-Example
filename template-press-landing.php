<?php
/**
 * Template Name: News & Media Page
 */
?>

<?php while (have_posts()) : the_post(); ?>
  <?php get_template_part('templates/headers/header-press-landing'); ?>
  <?php get_template_part('templates/pages/press-landing'); ?>
<?php endwhile; ?>
