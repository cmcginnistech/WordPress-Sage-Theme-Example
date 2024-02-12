<?php

use Roots\Sage\Extras;

$the_views = '';
$view_labels = [
  'vis'  => 'Visual Spectrum',
  'xra'  => 'X-Ray',
  'irr'  => 'Infrared'
];

$dzi_legacy_views = get_field('available_image_types');
$iiif_views = get_field('iiif_image_types');

$available_views = !empty($iiif_views) ? $iiif_views : $dzi_legacy_views;

if ($available_views) {
  $count_views = count($available_views);

  foreach ( $available_views as $key => $view ) {
    $label = $view_labels[$view['type']];
    if ( $key === ($count_views-1) && $count_views > 1 ) {
      $views[] = 'and '.$label;
    } else {
      $views[] = $label;
    }
  }

  $the_views = implode(', ', $views);
}

?>

<div class="artwork-stage">
  <figure class="artwork-main-image">
    <?php if ($the_views) : ?>
    <a href="<?= get_site_url() .'/viewer/'. $post->post_name; ?>" target="_blank" class="overlay dib">
      <?php the_post_thumbnail('medium_large'); ?>
      <div class="overlay-content overlay-content--center">
        <span aria-hidden="true" class="db tc mb4">
          <?= Extras\icons('magnifier-zoom', 58, 60); ?>
        </span>
        <?= sprintf( __('Click here for a closer view of this artwork with %s photography'), $the_views ); ?>
      </div>
    </a>
    <?php else : ?>
      <?php the_post_thumbnail('medium_large'); ?>
    <?php endif; ?>
  </figure>
</div>