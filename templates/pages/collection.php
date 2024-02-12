<?php

// use Roots\Sage\Extras;

// $template = get_post_meta( $post->ID, '_wp_page_template', true );
// $template = basename($template, '.php');
// $template_name = str_replace('template-', '', $template);
?>

<!-- required for isotope grid sizing -->
<div class="grid-sizer"></div>

<?php

// $location_data = get_option('leiden_cs_data');

// $artwork_args = [
//   'post_type' => 'artwork',
//   'posts_per_page' => -1,
//   'orderby' => 'meta_value_num',
//   'order' => 'ASC'
// ];

// if ( is_page_template('template-collection.php') ) {
//   $artwork_args['meta_key'] = 'collection_grid_sort';
// } else {
//   $artwork_args['meta_key'] = 'collection_gallery_sort';
// }

// $artworks = get_posts($artwork_args);

// foreach ( $artworks as $artwork ) :
//   $image = get_field('framed_image', $artwork->ID);
//   $artist = get_field('artwork_to_artist_2way', $artwork->ID);
//   $artist_sort_name = $artist ? Extras\classify(get_field('artist_sort_name', $artist->ID)) : '';
//   $taxis = ['date', 'medium', 'subject'];
//   $inv_num = get_field('inventory_number', $artwork->ID);
//   $sort_date = get_field('sort_date', $artwork->ID);
//   $data_atts = [];

//   $classes = [
//     $artist ? $artist->post_name : '',
//     array_key_exists($inv_num, $location_data) ? 'on-view-now' : ''
//   ];

//   foreach( $taxis as $taxi ) {
//     $terms = get_the_terms($artwork, $taxi);
//     if ( !empty($terms) ) {
//       foreach ( $terms as $term ) {
//         $classes[] = $term->slug;
//         $data_atts[$taxi] = $term->slug;
//       }
//     }
//   }

//   // a comma sep list of classes for isotope filtering.
//   $iso_classes = implode(' ', $classes);

//   // used to calc the aspect ration in JS
//   $w = $image['sizes']['medium_large-width'];
//   $h = $image['sizes']['medium_large-height'];

//   // used to calc proportional sizing for gallery
//   $fw = get_field('frame_width_in', $artwork->ID);
//   $fh = get_field('frame_height_in', $artwork->ID);
  ?>

  <div
    class="item <?= $iso_classes; ?>"
    data-artist="<?= $artist_sort_name; ?>"
    data-date="<?= $sort_date ? $sort_date : $data_atts['date']; ?>"
    data-medium="<?= $data_atts['medium']; ?>"
    data-frame-h="<?= $fh; ?>"
    data-frame-w="<?= $fw; ?>"
    >
    <?php include(locate_template('templates/artwork/'.$template_name.'-item.php')); ?>
  </div>

<?php // endforeach; ?>