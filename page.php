<?php while (have_posts()) : the_post(); ?>
  <?php get_template_part('templates/headers/header'); ?>
  <?php get_template_part('templates/pages/default'); ?>
<?php endwhile; ?>
