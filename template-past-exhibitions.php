<?php
/**
 * Template Name: Past Exhibitions Page
 */
?>

<?php while (have_posts()) : the_post(); ?>
  <?php get_template_part('templates/headers/header'); ?>
  <?php get_template_part('templates/pages/past-exhibitions'); ?>
<?php endwhile; ?>
