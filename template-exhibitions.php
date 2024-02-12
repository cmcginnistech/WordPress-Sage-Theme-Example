<?php
/**
 * Template Name: Exhibitions Landing Page
 */
?>

<?php while (have_posts()) : the_post(); ?>
  <?php get_template_part('templates/pages/exhibitions-landing'); ?>
<?php endwhile; ?>
