<?php
/**
 * Template Name: Future Exhibitions Landing Page
 */
?>

<?php while (have_posts()) : the_post(); ?>
  <?php get_template_part('templates/pages/future-exhibitions'); ?>
<?php endwhile; ?>
