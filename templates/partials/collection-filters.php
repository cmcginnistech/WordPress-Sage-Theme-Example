<?php use Roots\Sage\Extras; ?>

<footer class="collection-grid-filters row">

  <?php get_template_part('templates/partials/collection-filters-sort'); ?>

  <div class="visible-xs-block">
    <a class="collection-filters-toggle btn btn-collapse collapsed" role="button" data-toggle="collapse" data-target="#theFilters" aria-expanded="false" aria-controls="theFilters">
      <div class="closed-text">
        <?= __('Filters', 'sage'); ?>
        <span aria-hidden="true"><?= Extras\icons('plus-circle-sm', 24, 24); ?></span>
      </div>
      <div class="open-text">
        <?= __('Close Filters', 'sage'); ?>
        <span aria-hidden="true"><?= Extras\icons('minus-circle-sm', 24, 24); ?></span>
      </div>
    </a>
  </div>

  <div class="collapse-for-small" id="theFilters">
    <div class="filter-group filter-group--filtering">

      <?php
      /**
       * Artist filter
       */
      ?>
      <div class="filter filter--artist" data-filter="c_artist">
        <label for="lcfArtist">
          <small class="db"><?= __('Artisttt', 'sage'); ?></small>
        </label>
        <select class="selectpicker" multiple title="Select an Artist" data-selected-text-format="count>1" data-count-selected-text="{0} artists selected" id="lcfArtist">
		<?php if($_GET['show'] = 'on-view-now') { ?>
          <?php
          $q = get_posts([
        'post_type' => 'artwork',
        'posts_per_page' => -1,
        'meta_query' => array(
          array(
              'key' => 'location_name',
              'value' => '', //The value of the field.
              'compare' => '!=', //Conditional statement used on the value.
          )
      )
      ]);
      if ( !empty($q) ) {
        foreach ( $q as $p ) {
          // $inv_num = get_field('inventory_number', $p->ID);
          // if ( array_key_exists($inv_num, $csdata) ) {
            $ids[] = $p->ID;
          // }
        }
      }
      $args['post__in'] = $ids;

      // if we are not already sorting, set the sort order
      if ( $params['meta_key'] !== 'artwork_artist_sort_name' && $params['meta_key'] !== 'sort_date' && $params['meta_key'] !== 'artwork_medium_sort_name' ) {
        $args['meta_key'] = 'artwork_location_sort_name';
        $args['orderby'] = 'meta_value';
      }
	  $results = new WP_Query( $args );

$artists_array = array(); // Create an empty array to store artist names

// First loop to collect artist names
foreach( $q as $term ) {
    $artist = get_post_meta( $term->ID, 'artwork_to_artist_2way', true );
    $artist_name = get_the_title($artist);
    if(!in_array($artist_name, $artists_array)) { // Check if the artist name is not already in the array
        $artists_array[] = $artist_name; // Add the artist name to the array
    }
}

$artists_array = array_unique($artists_array); // Remove duplicates

// Next loop to display options
foreach($artists_array as $artist_name) {
    $artist = get_page_by_title($artist_name, OBJECT, 'artist'); // Assuming 'artist' is the post type
    $artist_id = $artist ? $artist->ID : '';
    $selected = Filters\is_filter_active($artist_id, 'c_artist') ? 'selected' : '';
?>
    <option data-tokens="<?= esc_attr($artist_id); ?>" <?= $selected; ?>>
        <?= esc_html($artist_name); ?>
    </option>
<?php
}
?>
		<?php }else{ ?>
		
		 <?php
          $artists = get_posts([
            'post_type'      => 'artist',
            'posts_per_page' => -1,
            'orderby'        => 'meta_value',
            'meta_key'       => 'artist_sort_name',
            'order'          => 'ASC',
            'meta_query'     => [
              [
                'key'     => 'artwork_to_artist_2way',
                'value'   => '',
                'compare' => '!='
              ]
            ]
          ]);
          foreach( $artists as $term ) :
            $selected = Filters\is_filter_active($term->ID, 'c_artist') ? 'selected' : '';
            ?>
            <option data-tokens="<?= esc_attr($term->ID); ?>" <?= $selected; ?>>
              <?= $term->post_title; ?>
            </option>
          <?php endforeach; ?>
        </select>
		
		<?php } ?>

        </select>

      </div>

      <?php
      /**
       * Date filter
       */
      ?>
	  <?php if($_GET['show'] != 'on-view-now'): ?>
      <div class="filter" data-filter="date">
        <label for="lcfDate">
          <small class="db"><?= __('Date', 'sage'); ?></small>
        </label>
        <select class="selectpicker" multiple title="Select a Date" data-selected-text-format="count>1" data-count-selected-text="{0} dates selected" id="lcfDate">
          <?php
          $dates = get_terms([
            'taxonomy' => 'date'
          ]);
          foreach( $dates as $term ) :
            $selected = Filters\is_filter_active($term->slug, 'date') ? 'selected' : '';
            ?>
            <option data-tokens="<?= esc_attr($term->slug); ?>" <?= $selected; ?>>
              <?= $term->name; ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
    <?php endif; ?>
	
	
	
	 <?php
      /**
       * Location filter
       */
      ?>
	  <?php if($_GET['show'] == 'on-view-now'): ?>
      <div class="filter" data-filter="location">
        <label for="lcfMedium">
          <small class="db"><?= __('Location', 'sage'); ?></small>
        </label>
        <select class="selectpicker" multiple title="Select a Location" data-selected-text-format="count>1" data-count-selected-text="{0} mediums selected" id="lcfMedium">
          <?php
          $mediums = get_terms([
            'taxonomy' => 'location'
          ]);
          foreach( $mediums as $term ) :
            $selected = Filters\is_filter_active($term->slug, 'location') ? 'selected' : '';
            ?>
            <option data-tokens="<?= esc_attr($term->slug); ?>" <?= $selected; ?>>
              <?= $term->name; ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
	  <?php endif; ?>

      <?php
      /**
       * Medium filter
       */
      ?>
	  <?php if($_GET['show'] != 'on-view-now'): ?>
      <div class="filter" data-filter="medium">
        <label for="lcfMedium">
          <small class="db"><?= __('Medium', 'sage'); ?></small>
        </label>
        <select class="selectpicker" multiple title="Select a Medium" data-selected-text-format="count>1" data-count-selected-text="{0} mediums selected" id="lcfMedium">
          <?php
          $mediums = get_terms([
            'taxonomy' => 'medium'
          ]);
          foreach( $mediums as $term ) :
            $selected = Filters\is_filter_active($term->slug, 'medium') ? 'selected' : '';
            ?>
            <option data-tokens="<?= esc_attr($term->slug); ?>" <?= $selected; ?>>
              <?= $term->name; ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <?php endif; ?>
	  
	  
      <?php
      /**
       * Subject filter
       */
      ?>
	  <?php if($_GET['show'] != 'on-view-now'): ?>
      <div class="filter" data-filter="subject">
        <label for="lcfSubject">
          <small class="db"><?= __('Subject', 'sage'); ?></small>
        </label>
        <select class="selectpicker" multiple title="Select a Subject" data-selected-text-format="count>1" data-count-selected-text="{0} subjects selected" id="lcfSubject">
          <?php
          $subjects = get_terms([
            'taxonomy' => 'subject'
          ]);

          // loop over all subjects
          foreach( $subjects as $subject ) :
            $children = get_terms([
              'taxonomy' => 'subject',
              'parent' => $subject->term_id
            ]);

            // if a subject is a top level and does not have any children,
            // output a regular option.
            if ( $subject->parent === 0 && empty($children) ) : ?>
              <option data-tokens="<?= esc_attr($subject->slug); ?>">
                <?= $subject->name; ?>
              </option>
            <?php
            // otherwise, assume we have a parent with children and
            // push those into an array.
            else :
              $parent_subjects[$subject->name] = $children;
            endif;
          endforeach; ?>

          <?php
          // Now loop over that parent array and output those as optgroups.
          foreach( $parent_subjects as $parent => $children ) : ?>
            <optgroup label="<?= $parent; ?>">
              <?php foreach ( $children as $child ) :
                $selected = Filters\is_filter_active($child->slug, 'subject') ? 'selected' : '';
                ?>
                <option data-tokens="<?= esc_attr($child->slug); ?>" <?= $selected; ?>>
                  <?= $child->name; ?>
                </option>
              <?php endforeach; ?>
            </optgroup>
          <?php endforeach; ?>

        </select>
      </div>
	  <?php endif; ?>
	  
	  
      <?php
      /**
       * On view now toggle
       */
      ?>
	  <?php if($_GET['show'] != 'on-view-now'): ?>
      <div class="filter" data-filter="show">
        <label for="lcfShow">
          <small class="db"><?= __('Show', 'sage'); ?></small>
        </label>
        <select class="selectpicker" id="lcfShow">
          <?php
          $show_all_selected = Filters\is_filter_active('all', 'show') ? 'selected' : '';
          $show_ovn_selected = Filters\is_filter_active('on-view-now', 'show') ? 'selected' : '';
          ?>
          <option data-tokens="all" <?= $show_all_selected; ?>>All</option>
          <option data-tokens="on-view-now" <?= $show_ovn_selected; ?>>Currently on View</option>
        </select>
      </div>
	 <?php endif; ?>
    </div>
  </div>
</footer>