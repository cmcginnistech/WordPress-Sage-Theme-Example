<?php

namespace Archives;

use Roots\Sage\Extras;
use helpers;

/**
 * Get archive table headings
 */
function get_archive_table_head() {
  $type = get_queried_object()->name;

  $cols['artist'] = [
    [
      'name' => 'Name',
      'class' => 'cs5',
      'orderby' => 'meta_value',
      'metakey' => 'artist_sort_name'
    ],
    [
      'name' => 'Birth',
      'class' => 'cs2 col-width-wide text-right',
      'orderby' => 'meta_value_num',
      'metakey' => 'birth_year'
    ],
    [
      'name' => 'Death',
      'class' => 'cs2 col-width-compensate',
      'orderby' => 'meta_value_num',
      'metakey' => 'death_year'
    ],
    [
      'name' => 'Artworks in Collection',
      'class' => 'cs3 text-right',
      'orderby' => 'meta_value_num',
      'metakey' => 'artist_collection_items_count'
    ]
  ];

  $cols['video'] = [
    [
      'name' => 'Title',
      'class' => 'cs2',
      'orderby' => 'title'
    ]
  ];

  $cols['bibliography_entry'] = [
    [
      'name' => 'Authors',
      'class' => 'cs4',
      'orderby' => 'title'
    ],
    [
      'name' => 'Date',
      'class' => 'cs2 bibliography-date',
      'orderby' => 'meta_value_num',
      'metakey' => 'bibli_sort_date'
    ]
  ];

  if (empty($cols[$type])) {
    return;
  }

  foreach ( $cols[$type] as $th ) {
    $orderby = isset($th['orderby']) ? "data-orderby={$th['orderby']}" : '';
    $metakey = isset($th['metakey']) ? "data-metakey={$th['metakey']}" : '';
    echo '
    <div class="'. $th['class'] .'">
      <a href="#" class="sort-filter" role="button" '. $orderby .' '. $metakey .'>
        <span>'. $th['name'] .'</span>
        <span class="sort-handle unsorted"></span>
      </a>
    </div>';
  };
  echo "<label class='sort-filter-select-label' for='sortJS'>Sort By</label>
    <select class='sort-filter-select sortJS selectpicker' title='Sort Options' id='sortJS'>";
    foreach ( $cols[$type] as $th ) {
      $orderby = isset($th['orderby']) ? "data-orderby={$th['orderby']}" : '';
      $metakey = isset($th['metakey']) ? "data-metakey={$th['metakey']}" : '';
      echo '
      <option class="" value"'. $th['name'] . '" '. $orderby .' '. $metakey .'>
        '. $th['name'] . '
      </div>';
    }
  echo "</select>";
}

/**
 * Get archived post nicename.
 */
function get_archived_post_nicename( $post_id ) {

  $pub_yr = get_the_date('Y', $post_id);
  $ver = get_field('version', $post_id) > 1 ? ' v'.get_field('version', $post_id) : '';

  return "{$pub_yr} Archived Version{$ver}";
}

/**
 * Get the PDF archives for a post.
 *
 * @return array (an array of post objects)
 */
function get_pdf_archives( $post_id ) {

  $archives = get_posts([
    'post_type' => 'pdf_archive',
    'posts_per_page' => -1,
    'meta_key' => 'archived_record',
    'meta_value' => $post_id,
  ]);

  if ( !empty($archives) ) {
    return $archives;
  }

  return false;
}

/**
 * Javascript globals for the press archives.
 */
function press_archives_js_globals() {
  $query = get_queried_object();
  $current_tax = isset($query) && property_exists($query, 'taxonomy') ? $query->taxonomy : '';
  if ( helpers\is_press_archive() ) :
    ?>
<script type="text/javascript">
var LeidenPressVars = {
  base: '<?= get_post_type_archive_link('press'); ?>',
  taxonomy: '<?= $current_tax; ?>'
}
</script>
    <?php
  endif;
}
add_action( 'wp_footer', __NAMESPACE__ . '\\press_archives_js_globals' );