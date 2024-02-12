<?php
/**
 * Template Name: Dedicated Focused Exhibition
 */
?>

<?php while (have_posts()) : the_post(); ?>
  <?php get_template_part('templates/pages/dedicated-focused-exhibition'); ?>
<?php endwhile; ?>