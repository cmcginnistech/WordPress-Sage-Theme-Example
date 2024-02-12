<?php while (have_posts()) : the_post(); ?>
  <?php get_template_part('templates/headers/header', get_post_type()); ?>
  <?php get_template_part('templates/singles/single', get_post_type()); ?>
<?php endwhile; ?>