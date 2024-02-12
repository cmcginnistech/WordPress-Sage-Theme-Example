<?php
$videos = (get_post_type() == 'group') ? get_field('group_to_video_2way') : get_field('artwork_to_video_2way');
?>

<div class="container mb5">
  <div class="row flex flex-wrap">

    <?php
    if ( !empty($videos) ) {
      foreach ( $videos as $video ) {
        $id = $video->ID;
        include(locate_template('templates/api-partials/partial-video.php'));
      }
     } ?>

  </div>
</div>