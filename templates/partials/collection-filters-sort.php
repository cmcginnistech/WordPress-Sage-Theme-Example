<?php use Roots\Sage\Extras; ?>

<div class="filter-group filter-group--sorting">

  <?php
  /**
   * Grid/Gallery toggle
   */
  $is_grid_view = $post->post_name === 'collection' ? 'selected' : '';
  $is_gallery_view = $post->post_name === 'collection-gallery' ? 'selected' : '';
  if($_GET['show'] != 'on-view-now') {
	$grid_url = get_site_url(null, '/collection/');
	$gallery_url = get_site_url(null, '/collection-gallery/');
  }else{
	$grid_url = get_site_url(null, '/collection/?show=on-view-now');
	$gallery_url = get_site_url(null, '/collection-gallery/?sortby=&show=on-view-now');	  
  }
  $icon_name = $_SERVER['REQUEST_URI'] === '/collection/' ? 'grid' : 'gallery';
  ?>
  <?php if($_GET['show'] != 'on-view-now'): ?>
  <div id="collectionViewSelect" class="c6 collection-view-select">
  <?php else: ?>
  <div id="collectionViewSelect" class="c12 collection-view-select">
  <?php endif; ?>
    <label for="lcfViewSelect">
      <small class="db"><?= __('View As', 'sage'); ?></small>
    </label>
    <div>
      <span class="icon" aria-hidden="true"><?= Extras\icons($icon_name, 22, 22); ?></span>
      <select class="selectpicker" id="lcfViewSelect">
        <option <?= $is_grid_view; ?> data-tokens="<?= $grid_url; ?>"><?= __('Grid View', 'sage'); ?></option>
        <option <?= $is_gallery_view; ?> data-tokens="<?= $gallery_url; ?>"><?= __('Gallery View', 'sage'); ?></option>
      </select>
    </div>
  </div>


  <?php
  /**
   * Sorting filter
   */
  $sortby = isset($_GET['sortby']) && $_GET['sortby'] !== '' ? htmlentities($_GET['sortby']) : null;
  $sortby_artist = $sortby === 'artwork_artist_sort_name' ? 'selected' : '';
  $sortby_date = $sortby === 'sort_date' ? 'selected' : '';
  $sortby_medium = $sortby === 'artwork_medium_sort_name' ? 'selected' : '';
  $sortby_location = $sortby === 'artwork_location_sort_name' ? 'selected' : '';
  ?>
  <div class="sort-filter c6">
  <?php if($_GET['show'] != 'on-view-now'): ?>
    <label for="lcfSortBy">
      <small class="db"><?= __('Sort', 'sage'); ?></small>
    </label>
    <select class="selectpicker" id="lcfSortBy">
      <option data-tokens="<?= $is_grid_view ? 'collection_grid_sort' : 'collection_gallery_sort'; ?>" selected><?= __('None', 'sage'); ?></option>
      <option data-tokens="artwork_artist_sort_name" <?= $sortby_artist; ?>>
        <?= __('Artist', 'sage'); ?>
      </option>
      <option data-tokens="sort_date" <?= $sortby_date; ?>>
        <?= __('Date', 'sage'); ?>
      </option>
      <option data-tokens="artwork_medium_sort_name" <?= $sortby_medium; ?>>
        <?= __('Medium', 'sage'); ?>
      </option>
	  <option data-tokens="artwork_location_sort_name" <?= $sortby_location; ?>>
        <?= __('Location', 'sage'); ?>
      </option>
    </select>
	<?php endif; ?>
  </div>


</div>