<?php
/**
 * Template Name: A Portait in Oil
 */
?>

<?php while (have_posts()) : the_post(); ?>
  <?php get_template_part('templates/headers/header-essay'); ?>
  <?php get_template_part('templates/pages/portrait-in-oil'); ?>
<?php endwhile; ?>
