<?php

use Roots\Sage\Titles;

$query = get_queried_object();
$header_classes = '';

if ( is_tax('press_exhibition') ) {
  $header_classes = 'page-header--exhibition offset-hero';
}
?>

<header class="page-header page-header--press <?= $header_classes; ?>">
  <div class="container tc">
    <h1 class="heading-reset text-white size-h1-plus dib"><?= Titles\title(); ?></h1>
    <span class="archive-subtitle"><?= __('News & Media'); ?></span>
    <div class="filters">
      <div class="filter-item dib" data-filter="pe">
        <label class="db text-left text-white text-uppercase" for="pressExbFilter"><?= __('Exhibition', 'sage'); ?></label>
        <select id="pressExbFilter" class="selectpicker" multiple title="All exhibitions" data-selected-text-format="count>1" data-count-selected-text="Exhibitions ({0})">
          <?php
          $exhibitions = get_terms([
            'taxonomy' => 'press_exhibition'
          ]);
          foreach( $exhibitions as $term ) :
            $selected = Filters\is_filter_active($term->slug, 'pe') ? 'selected' : '';
            ?>
            <option data-tokens="<?= esc_attr($term->slug); ?>" <?= $selected; ?>>
              <?= $term->name; ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="filter-item dib" data-filter="pc">
        <label class="db text-left text-white text-uppercase" for="pressCategoryFilter"><?= __('Category', 'sage'); ?></label>
        <select id="pressCategoryFilter" class="selectpicker" multiple title="All categories" data-selected-text-format="count>1" data-count-selected-text="Categories ({0})">
          <?php
          $categories = get_terms([
            'taxonomy' => 'press_category'
          ]);
          foreach( $categories as $term ) :
            $selected = Filters\is_filter_active($term->slug, 'pc') ? 'selected' : '';
            ?>
            <option data-tokens="<?= esc_attr($term->slug); ?>" <?= $selected; ?>>
              <?= $term->name; ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="filter-item dib search-form">
        <label for="js-search" class="sr-only"><?= __('Search by keyword', 'sage'); ?></label>
        <input
          id="js-search"
          class="search-field search-field--icon-light form-control"
          type="search"
          value=""
          name="se"
          placeholder="Search..."
        />
      </div>
    </div>

    <?php
    if ( is_tax('press_exhibition') ) :
      $img = get_field('header_image', 'press_exhibition_' . $query->term_id);?>
      <div class="exb-header-graphic">
        <img src="<?= esc_url($img['sizes']['medium_large']); ?>" alt="" />
      </div>
    <?php endif; ?>

  </div>
</header>


<?php get_template_part('templates/press/archive-featured'); ?>

