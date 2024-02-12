<?php
/**
 * Template Name: Archive Page
 */
?>

<?php while (have_posts()) : the_post(); ?>
  <?php get_template_part('templates/pages/archive'); ?>
<?php endwhile; ?>
