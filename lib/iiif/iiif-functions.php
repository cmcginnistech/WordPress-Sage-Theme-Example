<?php

namespace Roots\Sage\IIIF;

/**
 * Get a IIIF link
 *
 * @var WP_Post object | $post
 * @var string | $type (what type of link)
 *
 * @return string
 */
function get_iiif_link( $post, $type = 'manifest' ) {
  return esc_url( home_url( "/iiif/presentation/v2/{$post->ID}/manifest/" ) );
}

//add_action( 'admin_head', __NAMESPACE__ . '\\import_icc_terms' );

function import_icc_terms() {

  // Get the contents of your txt file and separate the objects into an array
  $src = file_get_contents(ABSPATH.'/icc/tlc.txt');
  $objects = explode('$', $src);

  foreach ( $objects as $i => $obj ) {

    if ( $obj == '' ) break;

    // Get all of the text in between `IC` and `TYPE` and set to $text var.
    // This is a text blob of all the ICC terms.
    preg_match_all('/IC(.*)TYPE/s', $obj, $text);

    // Split our ICC terms blob into an array on every new line.
    $lines = preg_split( '/\r\n|\r|\n/', $text[1][0] );

    // Get the text in between `INSTIT.ID` and `URL.IMAGE` and set to $inv var
    preg_match_all('/INSTIT.ID(.*)URL.IMAGE/s', $obj, $inv);

    // Trim whitespace from our inventory number and save for later
    $inv_num = trim($inv[1][0]);
    // Loop over each of the $lines and trim `;` and ` ` characters
    foreach ( $lines as &$line ) {
      $line = ltrim($line, '; ');
    }
    $the_terms = implode("\r\n", $lines);
    // Do a meta query for the first post with a matching inventory #
    $q = get_posts([
      'post_type'      => 'artwork',
      'posts_per_page' => 1,
      'meta_query'     => [
        [
          'key' => 'inventory_number',
          'value' => $inv_num,
        ]
      ]
    ]);

    // If we found a post, go ahead and save the ICC terms
    if ( !empty($q) ) {
      $post_id = $q[0]->ID;
      update_field('iconclass_terms_v2', $the_terms, $post_id);
    } else {
      error_log('Not found: ');
      error_log(print_r($obj, true));
    }

  }
}