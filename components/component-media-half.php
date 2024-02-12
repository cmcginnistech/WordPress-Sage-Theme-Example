<?php
$namespace = uniqid();
$media = get_sub_field('media_type');
$media_image = get_sub_field('image');
$media_video = get_sub_field('video');
$media_video_thumbnail = get_sub_field('video_thumbnail');
$has_media = ($media_image || $media_video) ? true : false;

?>

<header class="tc">
  <?php if ( $heading = get_sub_field('heading') ) : ?>
    <h2 class="component-title"><?= $heading; ?></h2>
  <?php endif; ?>
</header>

<div class="row">
  <figure class="flex no-gutters-xs <?php if($has_media) echo 'has-media'; ?>">

    <div class="media-wrap">
      <?php if ( $media == 'image' ) : ?>
        <?php helpers\acfimg( $media_image, 'large' ); ?>
      <?php elseif ( $media == 'video' ) :
        if($media_video_thumbnail) { ?>
          <a class="modaal db media-half__video-thumbnail" data-modaal-type="video" href="<?= get_sub_field('video', false); ?>">
            <img class="lazyload" src="<?= $media_video_thumbnail['sizes']['medium_large']; ?>">
          </a>
        <?php } else { ?>
          <div class="embed-responsive embed-responsive-16by9">
            <?= $media_video; ?>
          </div>
      <?php }
    endif; ?>
    </div>

    <?php if($text = get_sub_field('text')) : ?>
      <figcaption class="pa3 bg-light-gray text-black"><?= $text; ?></figcaption>
    <?php endif; ?>

  </figure>
</div>
