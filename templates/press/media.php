<?php if(have_rows('press_media')) : ?>
<div class="media-wrap mb4">
  <?php
  while(have_rows('press_media')) : the_row();
    if( get_row_layout() == 'video' ):
      $namespace = uniqid();
      $video_url = get_sub_field('video_embed_url', false, false);
      $video_url .= '?autoplay=1&rel=0';
      ?>
      <a class="modaal db video-play-thumb" data-modaal-type="video" href="<?= $video_url; ?>" data-modaal-animation="fade">
        <img class="w-100" src="<?= get_sub_field('video_thumbnail')['sizes']['large']; ?>" alt="">
        <span class="sr-only"><?= __('play video'); ?></span>
      </a>
      <?php if(get_sub_field('video_caption')) : ?>
        <div class="media-caption">
          <?= get_sub_field('video_caption'); ?>
        </div>
      <?php endif;
    endif;
    ?>

    <?php
    if( get_row_layout() == 'soundcloud' ) :
      $soundcloud = get_sub_field('soundcloud_embed');
      if (strpos($soundcloud, 'soundcloud') ) {
        $soundcloud = preg_replace('/=true/', '=false', $soundcloud);
        $soundcloud = preg_replace('/height="400"/', 'height="125"', $soundcloud);
        $soundcloud = preg_replace('/show_artwork=false/', 'show_artwork=true', $soundcloud);
        echo '<div class="embed-responsive" style="height: 125px;">' . $soundcloud . '</div>';
      }
    endif;
    ?>

  <?php endwhile; ?>
</div>
<?php endif; ?>