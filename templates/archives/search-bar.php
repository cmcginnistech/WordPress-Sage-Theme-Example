<?php
use Roots\Sage\Extras;

$query_obj = get_queried_object();
$placeholder = "Search {$query_obj->label}&hellip;";
?>

<?php if ( is_post_type_archive('bibliography_entry') ) :
  $file = get_field('downloadable_bibliography_file', 'options');
  ?>
  <a href="<?= esc_url($file['url']); ?>" target="_blank" class="btn btn-lg btn-outline hidden-xs mr3">
    <?php _e('Print', 'sage'); ?>
    <span class="icon" aria-hidden="true"><?= Extras\icons('print', 18, 18); ?></span>
  </a>
<?php endif; ?>


<?php if ( is_post_type_archive('video') ) : ?>
<div class="archive-video-filter">
  <div class="filter-item" data-filter="video_cat">
  <label class='sort-filter-select-label' for='sort-select' class=''>Filter by</label>
    <select class="sort-filter-select selectpicker" multiple title="All categories" data-selected-text-format="count>1">
      <?php
      $video_cats = get_terms([
        'taxonomy' => 'video_cat'
      ]);
      foreach( $video_cats as $term ) :
        $selected = Filters\is_filter_active($term->slug, 'video_cat') ? 'selected' : '';
        ?>
        <option data-tokens="<?= esc_attr($term->slug); ?>" <?= $selected; ?>>
          <?= $term->name; ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>
</div>
<?php endif; ?>


<div class="form-inline archive-search">
  <div class="input-group">
    <label for="js-search"><span class="sr-only">Search</span></label>
    <input type="search" id="js-search" class="form-control" value="" placeholder="<?= esc_attr($placeholder); ?>" />
    <div class="input-group-append">
      <button class="btn btn-search" aria-label="<?= __('Search'); ?>">
        <?= Extras\icons('search', 24, 24); ?>
      </button>
    </div>
  </div>
</div>

