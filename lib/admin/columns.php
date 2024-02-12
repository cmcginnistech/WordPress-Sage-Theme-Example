<?php

namespace Roots\Sage\Admin;

use helpers;

/**
 * Sage Admin Columns.
 * A nicer way of adding columns in the admin screen.
 * Initialize with a post type and an array of custom columns.
 *
 * @var string | $post_type
 * @var array | $cols_to_add
 */
class Sage_Admin_Columns {

  /**
   * The post type we are adding columns to.
   */
  public $post_type;

  /**
   * The columns we are adding.
   * This variable will be used throughout the class.
   */
  public $cols_to_add = [];

  /**
   * Any pre-existing columns to remove.
   */
  public $cols_to_unset = [];

  /**
   * Construct.
   */
  public function __construct( $post_type, $cols_to_add ) {
    $this->post_type = $post_type;
    $this->cols_to_add = $cols_to_add;
  }

  /**
   * Register our custom columns.
   */
  public function register_columns() {
    // add/remove columns
    add_filter("manage_{$this->post_type}_posts_columns", array( $this, "manage_columns" ), 5);
    // add content to custom columns
    add_action("manage_{$this->post_type}_posts_custom_column", array( $this, "render_columns" ), 5, 2);
    // make custom columns sortable
    add_action("manage_edit-{$this->post_type}_sortable_columns", array( $this, "make_columns_sortable" ), 5, 2);
    // add query order for custom sortable columns
    add_action("pre_get_posts", array( $this, "admin_query_ordering" ), 5, 2);
  }

  /**
   * Custom columns to add.
   *
   * @hooked manage_{$post_type}_posts_columns.
   */
  public function manage_columns( $cols ) {
    // add custom columns
    foreach ( $this->cols_to_add as $col ) {
      $key = $col['key'];
      $cols[$key] = $col['title'];
    }
    // remove columns (if necessary)
    foreach ( $this->cols_to_unset as $col ) {
      unset($cols[$col]);
    }
    return $cols;
  }

  /**
   * Render each of our custom column content.
   * This will run the callback for each column to render
   * the column's data.
   *
   * @hooked manage_{$post_type}_posts_custom_column.
   */
  public function render_columns( $col_key, $post_id ) {
    foreach ( $this->cols_to_add as $col ) {

      $func = __NAMESPACE__ .'\\'. $col['callback'];

      if ( !function_exists($func) ) {
        break;
      }

      if ( $col_key === $col['key'] ) {
        call_user_func( $func, $post_id );
        break;
      }
    }
  }

  /**
   * Pushes columns to the $cols_to_unset[] class variable.
   *
   * @param array | $cols - an indexed array of column keys.
   */
  public function unset_columns( $cols ) {
    foreach ( $cols as $col ) {
      $this->cols_to_unset[] = $col;
    }
  }

  /**
   * Make custom columns sortable.
   * This simply enables sortability, but does not control the query.
   *
   * @hooked manage_edit-{$post_type}_sortable_columns.
   */
  public function make_columns_sortable( $cols ) {
    foreach ( $this->cols_to_add as $col ) {
      if ( isset($col['sortable']) && $col['sortable'] === 1 ) {
        $col_key = $col['key'];
        $cols[$col_key] = $col_key;
      }
    }
    return $cols;
  }

  /**
   * Pre get posts filter for sortable columns.
   * This is the logic that handles how the sorting actually works.
   *
   * @hooked pre_ge_posts.
   */
  public function admin_query_ordering( $query ) {

    if ( !is_admin() ) {
      return;
    }

    $orderby = $query->get('orderby');

    // $col['sort_key'] should be the meta_key you want to sort by.
    foreach ( $this->cols_to_add as $col ) {
      if ( $orderby === $col['key'] ) {
        $query->set('meta_key', $col['sort_key']);
        $query->set('orderby', 'meta_value');
      }
    }
  }

}

/**
 * Add admin columns.
 */
function add_admin_columns() {

  /**
   * Artwork post type columns.
   */
  $artwork = new Sage_Admin_Columns( 'artwork', [
    [
      'key'      => 'artwork_artist',
      'title'    => 'Artist',
      'sortable' => 1,
      'sort_key' => 'artwork_artist_sort_name',
      'callback' => 'get_artwork_artist_column'
    ],
    [
      'key'      => 'artwork_dims',
      'title'    => 'Dimensions',
      'callback' => 'get_artwork_dims_column'
    ],
    [
      'key'      => 'artwork_inv_num',
      'title'    => 'Inv. Num.',
      'sortable' => 1,
      'sort_key' => 'inventory_number',
      'callback' => 'get_artwork_inv_num_column'
    ],
    [
      'key'      => 'artwork_medium',
      'title'    => 'Medium',
      'callback' => 'get_artwork_medium_column'
    ],
    [
      'key'      => 'artwork_dates',
      'title'    => 'Dates',
      'callback' => 'get_artwork_dates_column'
    ],
    [
      'key'      => 'artwork_group',
      'title'    => 'Group',
      'callback' => 'get_artwork_group_column'
    ],
    [
      'key'      => 'artwork_frame_img',
      'title'    => 'Frame Image',
      'callback' => 'get_artwork_frame_img_column'
    ]
  ]);
  $artwork->register_columns();
  $artwork->unset_columns( ['date'] );


  /**
   * Groups post type columns
   */
  $groups = new Sage_Admin_Columns( 'group', [
    [
      'key'      => 'group_artists',
      'title'    => 'Artist(s)',
      'callback' => 'get_group_artists_column'
    ]
  ]);
  $groups->register_columns();


  /**
   * Groups post type columns
   */
  $artists = new Sage_Admin_Columns( 'artist', [
    [
      'key'      => 'artist_artwork',
      'title'    => 'Artworks',
      'callback' => 'get_artist_artwork_column'
    ]
  ]);
  $artists->register_columns();

}
add_action('after_setup_theme', __NAMESPACE__ . '\\add_admin_columns');

/**
 * Callbacks for displaying artwork post type columns.
 */
function get_artwork_artist_column( $post_id ) {
  $artist = get_field('artwork_to_artist_2way', $post_id);
  if ( $artist ) {
    echo '<a href="'. get_edit_post_link($artist->ID) .'">'. $artist->post_title .'</a>';
  }
}

function get_artwork_dims_column( $post_id ) {
  echo '<b>Frame</b>: '. get_field('frame_width_in', $post_id) .' x '. get_field('frame_height_in', $post_id) .'<br>';
  echo '<b>In</b>: '. get_field('artwork_width_in', $post_id) .' x '. get_field('artwork_height_in', $post_id) .'<br>';
  echo '<b>Cm</b>: '. get_field('artwork_width_cm', $post_id) .' x '. get_field('artwork_height_cm', $post_id);
}

function get_artwork_inv_num_column( $post_id ) {
  echo get_field('inventory_number');
}

function get_artwork_medium_column( $post_id ) {
  $terms = get_the_terms( $post_id, 'medium');
  $term_names = !empty($terms) ? wp_list_pluck( $terms, 'name' ) : [];
  echo '<b>Medium terms: </b><br>';
  echo implode(', ', $term_names) .'<br>';
  echo '<b>Medium variant: </b><br>';
  echo get_field('medium_variant', $post_id);
}

function get_artwork_dates_column( $post_id ) {
  $terms = get_the_terms( $post_id, 'date');
  $term_names = !empty($terms) ? wp_list_pluck( $terms, 'name' ) : [];
  echo '<b>Date terms: </b><br>';
  echo implode(', ', $term_names) .'<br>';
  echo '<b>Date specific: </b><br>';
  echo get_field('sort_date', $post_id);
}

function get_artwork_group_column( $post_id ) {
  $group = get_field('artwork_to_group_2way', $post_id);
  if ( $group ) {
    echo '<a href="'. get_edit_post_link($group->ID) .'">'. $group->post_title .'</a>';
  }
}

function get_artwork_frame_img_column( $post_id ) {
  if ( $img = get_field('framed_image') ) {
    echo "<img src=\"{$img['sizes']['thumb-xs']}\" alt=\"\" style=\"max-width:100%;height:auto;\" />";
  }
}

/**
 * Callbacks for displaying group post type columns.
 */
function get_group_artists_column( $post_id ) {
  $group_artists = helpers\get_group_artists($post_id);
  if ( $group_artists ) {
    echo '<ul>';
    foreach ( $group_artists as $artist ) {
      echo '<li><a href="'. get_edit_post_link($artist->ID) .'">'. $artist->post_title .'</a></li>';
    }
    echo '</ul>';
  }
}

/**
 * Callbacks for displaying artist post type columns.
 */
function get_artist_artwork_column( $post_id ) {
  $artworks = get_field('artwork_to_artist_2way', $post_id);
  if ( $artworks ) {
    echo '<ul>';
    foreach ( $artworks as $artwork ) {
      echo '<li><a href="'. get_edit_post_link($artwork->ID) .'">'. $artwork->post_title .'</a></li>';
    }
    echo '</ul>';
  }
}