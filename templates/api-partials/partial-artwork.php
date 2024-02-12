<?php

use Roots\Sage\Extras;

/**
 * Required variables:
 * @var string | $template_name
 */

$location_data = get_option('leiden_cs_data');
$image = get_field('framed_image', $id);
$artist = get_field('artwork_to_artist_2way', $id);
$artist_sort_name = $artist ? Extras\classify(get_field('artist_sort_name', $artist->ID)) : '';
$taxis = ['date', 'medium', 'subject', 'location'];
$inv_num = get_field('inventory_number', $id);
$sort_date = get_field('sort_date', $id);
$data_atts = [];

$classes = [
  $artist ? $artist->post_name : '',
  array_key_exists($inv_num, $location_data) ? 'on-view-now' : ''
];

foreach( $taxis as $taxi ) {
  $terms = get_the_terms($id, $taxi);
  if ( !empty($terms) ) {
    foreach ( $terms as $term ) {
      $classes[] = $term->slug;
      $data_atts[$taxi] = $term->slug;
    }
  }
}

// a comma sep list of classes for isotope filtering.
$iso_classes = implode(' ', $classes);

// used to calc the aspect ration in JS
$w = $image['sizes']['medium_large-width'];
$h = $image['sizes']['medium_large-height'];

// used to calc proportional sizing for gallery
$fw = get_field('frame_width_in', $id);
$fh = get_field('frame_height_in', $id);
?>

<div
  class="item <?= $iso_classes; ?>"
  data-artist="<?= $artist_sort_name; ?>"
  data-date="<?= $sort_date ? $sort_date : $data_atts['date']; ?>"
  data-medium="<?= $data_atts['medium']; ?>"
  data-location="<?= $data_atts['location']; ?>"
  data-frame-h="<?= $fh; ?>"
  data-frame-w="<?= $fw; ?>"
  >
  <?php include(locate_template('templates/artwork/'.$template_name.'-item.php')); ?>
</div>