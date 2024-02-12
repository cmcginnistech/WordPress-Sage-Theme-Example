<?php
/**
 * The $includes array determines the code library included in your theme.
 * Add or remove files to the array as needed. Supports child theme overrides.
 *
 * Please note that missing files will produce a fatal error.
 */
$includes = [

  // Admin Area
  'lib/admin.php',
  'lib/admin/meta-boxes.php',
  'lib/admin/collection-sorting.php',
  'lib/admin/assets.php',
  'lib/admin/columns.php',

  // Base theme stuff
  'lib/archives.php',   // Post type archive stuff
  'lib/assets.php',     // Scripts and stylesheets
  'lib/extras.php',     // Custom functions
  'lib/forms.php',      // Gravity Forms
  'lib/setup.php',      // Theme setup
  'lib/routing.php',    // Rewrites and redirects
  'lib/taxonomies.php', // Custom taxonomies
  'lib/titles.php',     // Page titles
  'lib/types.php',      // Custom post types
  'lib/walker.php',     // Bootstrap nav walker class
  'lib/wp-api.php',     // WP rest api
  'lib/wrapper.php',    // Theme wrapper class
  'lib/shortcodes.php', // Shortcodes for inline content
  'lib/helpers.php',    // Various helper functions
  'lib/seo.php',        // Yoast SEO

  // 3rd Party libraries
  'lib/vendor/php-name-parser/parser.php',
  'lib/vendor/tcpdf/tcpdf.php',
  'lib/vendor/setasign/fpdi/fpdi.php',

  // PDF generation
  'lib/pdf/ajax.php',
  'lib/pdf/functions.php',
  'lib/pdf/tcpdf-extension.php',
  'lib/pdf/generate.php',

  // Collector Systems Integration
  'lib/collector-systems/admin.php',
  'lib/collector-systems/client.php',

  // IIIF
  'lib/iiif/Manifest.php',
  'lib/iiif/CollectionManifest.php',
  'lib/iiif/PostManifest.php',
  'lib/iiif/Image.php',
  'lib/iiif/iiif-functions.php',

  // Custom function files
  'lib/exhibitions.php',
  'lib/filters.php',
  'lib/viewer.php',
  'lib/glossary.php',
  'lib/search.php'

];

foreach ($includes as $file) {
  if (!$filepath = locate_template($file)) {
    trigger_error(sprintf(__('Error locating %s for inclusion', 'sage'), $file), E_USER_ERROR);
  }

  require_once $filepath;
}
unset($file, $filepath);

/**
 * When debug is on, enable native WP custom fields meta box.
 * Some post types save non-client-facing info in post_meta.
 */
if ( WP_DEBUG ) {
  add_filter('acf/settings/remove_wp_meta_box', '__return_false', 20);
}
