<?php

use Roots\Sage\PDF_Generate;
use Roots\Sage\IIIF;

/**
 * Custom query vars.
 */
function leiden_add_query_vars($aVars) {
  $custom_vars = [
    'viewer_post_slug',
    'iiif',
    'art_id',
    'collection_manifest'
  ];
  $aVars = array_merge($custom_vars,$aVars);

  return $aVars;
}
add_filter('query_vars', __NAMESPACE__ .'\\leiden_add_query_vars');

/**
 * Custom rewrite rules.
 */
function leiden_add_rewrite_rules( $aRules ) {

  $aNewRules = array(
    'viewer/([^/]+)/?$' => 'index.php?post_type=page&pagename=viewer&viewer_post_slug=$matches[1]',
    'iiif/presentation/v2/([0-9]+)/manifest' => 'index.php?iiif=true&art_id=$matches[1]',
    'iiif/presentation/v2/collection/manifest' => 'index.php?iiif=true&collection_manifest=true',
  );

  $aRules = $aNewRules + $aRules;
  return $aRules;
}
add_filter('rewrite_rules_array', __NAMESPACE__ .'\\leiden_add_rewrite_rules');

/**
 * Redirects search results from /?s=query to /search/query/, converts %20 to +
 *
 * @link http://txfx.net/wordpress-plugins/nice-search/
 */
function search_redirect() {
  global $wp_rewrite;

  if (!isset($wp_rewrite) || !is_object($wp_rewrite) || !$wp_rewrite->get_search_permastruct()) {
    return;
  }

  $search_base = $wp_rewrite->search_base;

  if (is_search() && !is_admin() && strpos($_SERVER['REQUEST_URI'], "/{$search_base}/") === false && strpos($_SERVER['REQUEST_URI'], '&') === false) {
    wp_redirect(get_search_link());
    exit();
  }
}
add_action('template_redirect', __NAMESPACE__ . '\\search_redirect');

/**
 * Yoast search rewrite.
 */
function yoast_search_rewrite($url) {
  return str_replace('/?s=', '/search/', $url);
}
add_filter('wpseo_json_ld_search_url', __NAMESPACE__ . '\\yoast_search_rewrite');

/**
 * Redirect to PDF download.
 * Stick a ?pdf=1 on a post url to download the PDF.
 */
function redirect_to_PDF_download() {

  global $post;

  $printable = [
    is_singular(['essay', 'artwork', 'artist', 'group']),
    is_page_template('template-portrait-in-oil.php')
  ];

  if ( !is_admin() && isset($_GET['pdf']) && $_GET['pdf'] == 1 ) {
    $pdf_download_url = get_post_meta($post->ID, 'downloadable_pdf', true);

    if ( $pdf_download_url && in_array( true, $printable) ) {

      $build_header = PDF_Generate\generate_pdf($post->ID, false, true);

      if ( $build_header === 'success' ) {
        PDF_Generate\build_pdf_for_print($post->ID);
      }

    }
  }
}
add_action( 'template_redirect', __NAMESPACE__ . '\\redirect_to_PDF_download' );

/**
 * Output the IIIF manifest if the appropriate query_vars exist.
 *
 * @param $template
 *
 * @return mixed
 */
function iiif_manifest_endpoint( $template ){

  global $wp_query;
  $vars = $wp_query->query_vars;

  if ( isset($vars['iiif']) ) {
    if ( isset($vars['art_id']) ) {
      $manifest = new IIIF\PostManifest( $vars['art_id'] );
      $manifest->output();
    }
    elseif ( isset($vars['collection_manifest']) ) {
      $manifest = new IIIF\CollectionManifest();
    }
    $manifest->output();
  }


  return $template;
}
add_action( 'template_include', __NAMESPACE__ . '\\iiif_manifest_endpoint' );