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
    <h1 class="heading-reset text-white size-h1-plus dib">News & Media</h1>
    <!--<span class="archive-subtitle"><?= __('News & Media'); ?></span>-->
    <div class="filters">
      <div class="filter-item dib" data-filter="pe">
		<!-- Exhibitions Dropdown -->
        <label class="db text-left text-white text-uppercase" for="pressExbFilter"><?= __('Category', 'sage'); ?></label>
		  <?php //echo $query->slug; ?>
        <select id="pressExbFilter" class="selectpicker" title="All News & Media" data-selected-text-format="count>1" data-count-selected-text="Exhibitions ({0})">
        <optgroup>
		<option data-tokens="../">All News & Media</option>
		<option data-tokens="general-news-category" <?php if($query->slug == "general-news-category") { echo "selected"; }?>>General News</option>
		<option data-tokens="vermeer" <?php if($query->slug == "vermeer") { echo "selected"; }?>>Vermeer</option>
		</optgroup>
          <?php
          /**
           * Break up options into opt groups based on if the
           * exhibition is current/upcoming/past.
           */
          $optgroups = [
            'Upcoming' => [],
            'Current'  => [],
            'Past'     => []
          ];

			
          $terms = get_terms([
            'taxonomy'   => 'press_exhibition',
            'hide_empty' => true
          ]);

          $today = strtotime( date('Ymd') );

          foreach ( $terms as $term ) {

            // Get linked post object and dates.
            // The term must have the same slug as the exhibition post it is related to.
            $exb_post = get_page_by_path($term->slug, OBJECT, 'exhibition');
            $start = strtotime( get_field('start_date', $exb_post->ID) );
            $end = strtotime( get_field('end_date', $exb_post->ID) . ' +1 days');
			$focused_exh = get_field('focused_exhibition', $exb_post->ID);

            // Determine if exhibition should be pre-selected.
            if ( is_tax() ) {
              $selected = $query->slug == $term->slug || Filters\is_filter_active($term->slug, 'pe') ? 'selected' : '';
            } else {
              $selected = Filters\is_filter_active($term->slug, 'pe') ? 'selected' : '';
            }
			  ?>
			
			<?php
			  

            // Store exhibition term info for option markup.
            $t = [
              'name'     => $term->name,
              'slug'     => $term->slug,
              'selected' => $selected
            ];

            if ( $start <= $today && $end > $today ) {
             // $optgroups['Current'][] = $t;
            } elseif ( $start > $today ) {
             // $optgroups['Upcoming'][] = $t;
			}elseif (!empty($focused)) {
				$optgroups['Focused'][] = $t;
            } else {
              $optgroups['International'][] = $t;
            }
          }
		  
		  	 
		  $args = array(
		    'post_type'        => 'exhibition',
			'numberposts'	=> 100,
			);
			$my_posts = get_posts( $args );
			if( ! empty( $my_posts ) ){
				echo "<optgroup label='Upcoming & Current Exhibitions'>";
				$getselect = '';
				if($query->slug == "hermitage-amsterdam") { $getselect = "selected"; }else{ $getselect = ""; }
				foreach ( $my_posts as $p ){
					$exhibitionID = $p->ID;
					$exhibition_slug = $p->post_name;
					$focused_exhibition = get_field('focused_exhibition', $exhibitionID);
					$exhibition_title = $p->post_title;
					if($focused_exhibition) {
						?>
					 <option data-tokens="<?php echo $exhibition_slug; ?>" <?php if($query->slug == "hermitage-amsterdam") { echo "selected"; } ?>><?php echo $exhibition_title; ?></option>
			<?php
					}
				}
				echo '</optgroup>';
			}
			
			

		  if(!$focused_exh) :
		  foreach ( $optgroups as $label => $group ) {
            if( !empty($group) ) {
              		echo "<optgroup label='{$label} Exhibitions'>";
              foreach ( $group as $term ) {
                echo "<option data-tokens=\"{$term['slug']}\" {$term['selected']}>{$term['name']}</option>";
              }
              echo '</optgroup>';
            }
          }
		  endif;
		  
		  
          ?>
        </select>
      </div>


      <div class="filter-item dib" data-filter="pc">
        <label class="db text-left text-white text-uppercase" for="pressCategoryFilter"><?= __('News Type', 'sage'); ?></label>
        <select id="pressCategoryFilter" class="selectpicker" multiple title="All News Types" data-selected-text-format="count>2" data-count-selected-text="Categories ({0})">
		<option data-tokens="">All News Types</option>
			
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