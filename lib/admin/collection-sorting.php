<?php

namespace Admin\CollectionSorting;

/**
 * Adds a submenu page under Artwork post type.
 */
function add_collection_sort_page() {
    add_submenu_page(
        'edit.php?post_type=artwork',
        __( 'Collection Sorting', 'sage' ),
        __( 'Collection Sorting', 'sage' ),
        'manage_options',
        'collection-sorting',
        __NAMESPACE__ . '\\collection_sort_page'
    );
}
add_action('admin_menu', __NAMESPACE__ . '\\add_collection_sort_page');

/**
 * Process sorting data.
 */
function process_sort_data() {
  if ( !isset($_POST) || empty($_POST['lcs']) ) {
    return;
  }

  check_admin_referer( 'update_lcs_sort_order', '_wpnonce' );

  $lcs = $_POST['lcs'];

  if ( !empty( $lcs['grid-sort'] ) ) {
    foreach ( $lcs['grid-sort'] as $i => $post_id ) {
      update_post_meta($post_id, 'collection_grid_sort', ($i+1));
    }
  }
  if ( !empty( $lcs['gallery-sort'] ) ) {
    foreach ( $lcs['gallery-sort'] as $i => $post_id ) {
      update_post_meta($post_id, 'collection_gallery_sort', ($i+1));
    }
  }

  return 'success';
}

/**
 * Placeholder
 */
function collection_sort_page_COMING_SOON() { ?>
  <div class="wrap collection-sorting">
    <h1><?php _e( 'Collection Sorting', 'sage' ); ?></h1>
    <p><?php _e( 'Coming Soon!', 'sage' ); ?></p>
  </div>
<?php
}

/**
 * Check view.
 */
function get_current_view() {
  if ( isset($_GET['tab']) && $_GET['tab'] == 'gallery_view' ) {
    return 'gallery_view';
  }
  return 'grid_view';
}

/**
 * Display unsorted.
 */
function display_unsorted_artwork() {
  $view = get_current_view();
  $meta_key = ($view == 'grid_view') ? 'collection_grid_sort' : 'collection_gallery_sort';

  $unsorted = get_posts([
    'post_type' => 'artwork',
    'posts_per_page' => -1,
    'meta_query' => [
      [
        'key' => $meta_key,
        'compare' => 'NOT EXISTS'
      ]
    ]
  ]); ?>
  <h2><?php _e( 'Unsorted', 'sage' ); ?></h2>
  <div class="lcs-sortable-wrap">
    <ul id="unsortedColumn" class="lcs-sortable" data-name="unsorted">
      <?php
      if ( !empty($unsorted) ) :
        foreach ( $unsorted as $index => $artwork ) :
          ?>
          <li class="lcs-item">
            <input type="hidden" name="" value="<?= esc_attr($artwork->ID); ?>" />
            <div class="lcs-item-title">
              <strong><?= $artwork->post_title; ?></strong>
              <span><?php the_field('inventory_number', $artwork->ID); ?></span>
            </div>
            <?php if ( has_post_thumbnail($artwork->ID) ) : ?>
              <div class="lcs-item-img">
                <?= get_the_post_thumbnail($artwork->ID, 'thumbnail'); ?>
              </div>
            <?php endif; ?>
          </li>
          <?php
        endforeach;
      endif;
      ?>
    </ul>
  </div>
  <?php
}

/**
 * Display sorted artwork.
 */
function display_sorted_artwork() {
  $view = get_current_view();
  $meta_key = ($view == 'grid_view') ? 'collection_grid_sort' : 'collection_gallery_sort';
  $sort_key = ($view == 'grid_view') ? 'grid-sort' : 'gallery-sort';

  $sorted = get_posts([
    'post_type' => 'artwork',
    'posts_per_page' => -1,
    'meta_key' => $meta_key,
    'orderby' => 'meta_value_num',
    'order' => 'ASC',
    'meta_query' => [
      [
        'key' => $meta_key,
        'compare' => 'EXISTS'
      ]
    ]
  ]); ?>
  <h2><?php _e( 'Sorted', 'sage' ); ?></h2>
  <div class="lcs-sortable-wrap">
    <ul id="sortedColumn" class="lcs-sortable" data-name="<?= $sort_key; ?>">
      <?php
      if ( !empty($sorted) ) :
        foreach ( $sorted as $index => $artwork ) :
          ?>
          <li class="lcs-item">
            <input type="hidden" name="lcs[<?= $sort_key; ?>][]" value="<?= esc_attr($artwork->ID); ?>" />
            <div class="lcs-item-title">
              <strong><?= $artwork->post_title; ?></strong>
              <span><?php the_field('inventory_number', $artwork->ID); ?></span>
            </div>
            <?php if ( has_post_thumbnail($artwork->ID) ) : ?>
              <div class="lcs-item-img">
                <?= get_the_post_thumbnail($artwork->ID, 'thumbnail'); ?>
              </div>
            <?php endif; ?>
          </li>
          <?php
        endforeach;
      endif;
      ?>
    </ul>
  </div>
  <?php
}

/**
 * Scripts.
 */
function output_collection_sorting_page_scripts() {
  ?>
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script>
  $(function() {
    /**
     * @link http://api.jqueryui.com/sortable/#event-update
     */
    $( "#sortedColumn, #unsortedColumn" ).sortable({
      // make it so we can move items between rows
      connectWith: ".lcs-sortable",
      // trigger event when the user stopped sorting and the DOM position has changed.
      update: function( event, ui ) {

        // if moving into a different row.
        // ui.sender is the row the item is coming from.
        if ( ui.sender !== null ) {
          var rowID = ui.sender.attr('id'),
              $targetRow = $(event.target).attr('data-name');

          // update the hidden input with the new row name.
          if ( $targetRow !== 'unsorted' ) {
            ui.item.find('input[type="hidden"]').attr('name', 'lcs['+ $targetRow +'][]');
          } else {
            ui.item.find('input[type="hidden"]').attr('name', '');
          }
        }

      }
    }).disableSelection();
  });
  </script>
  <?php
}

/**
 * Outputs the save box.
 */
function display_save_box() {
  $view = get_current_view();
  $view_label = ($view == 'grid_view') ? 'Grid' : 'Gallery';
  ?>
  <div class="postbox">
    <h2 class="hndle"><span>Save <?= $view_label; ?> Order</span></h2>
    <div class="inside">
      <input type="submit" name="submit" class="button button-primary button-large" value="<?php _e( 'Save', 'sage' ); ?>" />
    </div>
  </div>
<?php
}

/**
 * The callback for collection sorting page.
 */
function collection_sort_page() {
  if ( isset($_POST['submit']) ) {
    $msg = process_sort_data();
  }
  ?>
  <div class="wrap collection-sorting">

    <?php if ( isset($msg) && $msg == 'success' ) : ?>
      <div class="notice notice-success"><p>Save successful!</p></div>
    <?php endif; ?>

    <h1><?php _e( 'Collection Sorting', 'sage' ); ?></h1>

    <?php
    $current_tab = get_current_view();
    $screen = '?post_type=artwork&page=collection-sorting';
    ?>
    <h2 class="nav-tab-wrapper">
      <a href="<?= $screen .'&tab=grid_view'; ?>" class="nav-tab <?php echo $current_tab == 'grid_view' ? 'nav-tab-active' : ''; ?>">Grid View</a>
      <a href="<?= $screen .'&tab=gallery_view'; ?>" class="nav-tab <?php echo $current_tab == 'gallery_view' ? 'nav-tab-active' : ''; ?>">Gallery View</a>
    </h2>

    <form action="<?= htmlentities($_SERVER['PHP_SELF']); ?>?post_type=artwork&page=collection-sorting&tab=<?= $current_tab; ?>" method="post">
      <div id="poststuff">
        <div id="post-body" class="columns-2">
          <div id="post-body-content" class="lcs-main">
            <div class="col-third">
              <?php display_unsorted_artwork(); ?>
            </div>
            <div class="col-twothirds">
              <?php display_sorted_artwork(); ?>
            </div>
          </div><!-- #post-body-content -->
          <div id="postbox-container-1">
            <div class="postbox-container">
              <?php display_save_box(); ?>
            </div>
          </div>
        </div><!-- #post-body -->
      </div><!-- #poststuff -->
      <input type="hidden" id="_wpnonce" name="_wpnonce" value="<?= wp_create_nonce( 'update_lcs_sort_order' ); ?>" />
    </form>

  </div><!-- .wrap -->

  <?php
  output_collection_sorting_page_scripts();
}
