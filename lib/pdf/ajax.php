<?php

namespace Roots\Sage\PDF_Ajax;

use Roots\Sage\PDF_Generate;

/**
 * Ajax function
 */
function async_pdf_gen() {

  // verify nonce
  check_ajax_referer('request-nonce', '_ajax_nonce');

  if ( isset($_POST['action']) && $_POST['action'] == 'leiden_generate_pdf' ) {
    
    // TCPDF apparently does not work w/ HTTPS using reverse proxy stuff in wp-config
    PDF_Generate\generate_pdf( $_POST['postID'], $_POST['archive'] );

  } else {
    echo 'error';
  }
  
  wp_die();
}
add_action( 'wp_ajax_leiden_generate_pdf', __NAMESPACE__ . '\\async_pdf_gen' );