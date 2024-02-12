<?php
/**
 * Template Name: Collection Page
 */

use Roots\Sage\Extras;

// page may be presorted by URL params
if ( isset($_GET['sortby']) && $_GET['sortby'] !== '' ) {
  $orderby = $_GET['sortby'] === 'sort_date' ? 'meta_value_num' : 'meta_value';
  $metakey = htmlentities($_GET['sortby']);
} else {
  $orderby = 'meta_value_num';
  $metakey = 'collection_grid_sort';
}
?>

<header class="collection-header">
  <h1 class="sr-only">The Collection</h1>
  <?php get_template_part('templates/partials/collection-filters-sort'); ?>
</header>

<?php get_template_part('templates/partials/collection-filters'); ?>

<div
  id="js-posts-wrapper"
  class="collection-stage"
  data-context="artworks"
  data-index="0"
  data-offset="30"
  data-template="collection"
  data-orderby="<?= esc_attr($orderby); ?>"
  data-order="ASC"
  data-meta_key="<?= esc_attr($metakey); ?>"
  <?= Filters\get_data_atts(); ?>
  >
  <div id="ajaxLoading" class="loader tc"><img src="<?= get_template_directory_uri(); ?>/dist/images/loading-circle.svg" width="60" alt="" /></div>

  <div id="js-posts-row" class="collection-grid">
    <div class="grid-sizer"></div>
    <div id="js-posts-load-more"></div>
  </div>

</div>

<nav id="collection-nav">
  <div class="nav nav-left" aria-hidden="true">
    <?= Extras\icons('bubble-arrow-right', 20, 20); ?>
  </div>
  <div class="nav nav-right" aria-hidden="true">
    <?= Extras\icons('bubble-arrow-right', 20, 20); ?>
  </div>
</nav>