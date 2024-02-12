<?php
  use Roots\Sage\Extras;
?>

<article class="partial partial--video">
  <a href="<?= get_the_permalink($id); ?>">
    <?php echo get_the_post_thumbnail($id, 'thumb-medium'); ?>
    <h2 class="mb2">
      <?php if ( $override_title = get_field('video_title_display', $id) ) {
        echo $override_title;
      } else {
        echo get_the_title($id);
      } ?>
    </h2>
    <span class="video-icon"><?php _e('Watch Video', 'sage'); ?>
      <?php echo Extras\icons('video', 10, 10);?>
    </span>
  </a>
</article>
