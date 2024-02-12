<?php
$artwork = get_field('artwork_to_group_2way');
?>

<div class="artwork-stage">
  <div class="artwork-stage-inner-flex-wrap <?php if(count($artwork) > 3) echo 'flex-wrap'; ?>">

    <?php
    if ( !empty($artwork) ) :
      foreach ( $artwork as $art ) :

        $thumb_id = get_post_thumbnail_id( $art->ID );
        $img_data = wp_get_attachment_image_src($thumb_id, 'medium_large');
        $ratio = $img_data[1] / $img_data[2];
        $flex = '';

        // flex property to make sure items show with same height
        if ( count($artwork) > 3 ) {
          $flex = "style=\"flex:{$ratio}\"";
        }
        ?>

        <figure class="group-stage-item" <?= $flex; ?>>
          <?= get_the_post_thumbnail($art->ID, 'medium_large'); ?>
        </figure>

    <?php
      endforeach;
    endif; ?>

  </div>
</div>