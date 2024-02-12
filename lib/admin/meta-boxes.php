<?php

namespace Admin\MetaBoxes;

/**
 * Register meta boxes.
 */
function register_meta_boxes() {

  $boxes = [
    [
      'id' => 'post-archived-versions',
      'title' => 'PDF Archived Versions',
      'screens' => ['essay', 'artwork', 'artist'],
      'callback' => __NAMESPACE__ . '\\pdf_archive_metabox_callback',
      'position' => 'side'
    ]
  ];

  foreach ($boxes as $box) {
    add_meta_box( $box['id'], $box['title'], $box['callback'], $box['screens'], $box['position'] );
  }

}
add_action( 'add_meta_boxes', __NAMESPACE__ .'\\register_meta_boxes' );

/**
 * PDF Archive meta box display callback.
 *
 * @param WP_Post $post Current post object.
 */
function pdf_archive_metabox_callback( $post ) {

  $archives = get_posts([
    'post_type' => 'pdf_archive',
    'meta_key' => 'archived_record',
    'meta_value' => $post->ID
  ]);

  echo '<div style="padding:10px 0 20px 0;"><a id="doArchivePDF" href="#" class="button">Generate New</a> <a href="/wp-admin/edit.php?s='.$post->post_title.'&post_status=all&post_type=pdf_archive" class="button" target="_blank">Manage</a></div>';
  echo '<strong>Archived Versions:</strong>';
  echo '<ul>';

  if ( !empty($archives) ) :
    foreach ($archives as $archive) : ?>
      <li>
        <a href="<?= get_edit_post_link($archive->ID); ?>" target="_blank"><?= $archive->post_title; ?></a>
        <?= get_the_date('F j, Y', $archive->ID); ?>
      </li>
    <?php
    endforeach;
  else :
    echo '<li><em>No archives found.</em></li>';
  endif;

  echo '</ul>';

}
