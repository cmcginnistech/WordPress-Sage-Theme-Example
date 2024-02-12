<?php

use Roots\Sage\Extras;

/**
 * For a list of available variables,
 * see templates/api-partials/partial-artwork.php
 */
?>

<div class="item-img-wrap">

  <img
    data-src="<?= esc_url($image['sizes']['medium_large']); ?>"
    alt="<?= esc_attr($image['alt']); ?>"
    data-expand="100"
    width="<?= $w; ?>"
    height="<?= $h; ?>"
    class="lazyload" />

</div>

<a tabindex="0" class="popup-trigger btn" role="button">
  <?= Extras\icons('info-circle', 24, 24); ?>
</a>

<div class="hidden">
  <div class="popover-content">
    <span class="db serif text-italic size-h4 mb2"><?= get_the_title($id); ?></span>
    <span class="db mb3"><?= helpers\get_artwork_artist($id, true); ?></span>
    <?php include(locate_template('templates/artwork/tombstone.php')); ?>
    <a href="<?= get_the_permalink($id); ?>" target="_blank">View Artwork</a>
    <span class="dib mh2">|</span>
    <a href="<?= get_the_permalink($artist->ID); ?>" target="_blank">Read Artist Bio</a>
  </div>
</div>
