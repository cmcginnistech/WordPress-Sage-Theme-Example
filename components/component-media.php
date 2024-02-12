<?php
$media = get_sub_field('media_type');
?>

<div class="row">
  <figure class="no-gutters-xs">
    <?php if ( $media == 'image' ) : ?>
      <?php helpers\acfimg( get_sub_field('image'), 'large', 'w100' ); ?>
    <?php elseif ( $media == 'video' ) : ?>
      <div class="embed-responsive embed-responsive-16by9">
        <?php the_sub_field('video'); ?>
      </div>
    <?php endif; ?>
    <figcaption class="pa3 bg-lighter-gray">
      <?php the_sub_field('caption'); ?>
    </figcaption>
  </figure>
</div>
