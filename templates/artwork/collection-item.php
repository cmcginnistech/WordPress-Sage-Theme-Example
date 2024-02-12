<?php
/**
 * For a list of available variables,
 * see templates/api-partials/partial-artwork.php
 */
?>

<a href="<?= get_the_permalink($id); ?>" class="collection-grid-item" target="_blank">

  <div class="collection-grid-item__overlay">
    <span class="collection-grid-item__title"><?= get_the_title($id); ?></span>
    <span class="db mb3"><?= helpers\get_artwork_artist($id, true); ?></span>
    <?php include(locate_template('templates/artwork/tombstone.php')); ?>
    <?php if (get_field('location_name',$id)) {?>
      <span class="db mb3 collection-grid__location">
        <?php echo 'Currently on view: ' . get_field('location_name',$id);?>
      </span>
    <?php  }  ?>

    <span class="btn btn-default btn-sm btn-outline btn-white"><?= __('View Artwork', 'sage'); ?></span>
  </div>

  <img
    data-src="<?= esc_url($image['sizes']['medium_large']); ?>"
    alt="<?= esc_attr($image['alt']); ?>"
    data-expand="100"
    width="<?= $w; ?>"
    height="<?= $h; ?>"
    class="lazyload" />

</a>