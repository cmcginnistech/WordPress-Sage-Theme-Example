<?php

namespace Roots\Sage\Setup;

use Roots\Sage\Assets;

/**
 * Theme setup
 */
function setup() {
  // Make theme available for translation
  // Community translations can be found at https://github.com/roots/sage-translations
  load_theme_textdomain('sage', get_template_directory() . '/lang');

  // Enable plugins to manage the document title
  // http://codex.wordpress.org/Function_Reference/add_theme_support#Title_Tag
  add_theme_support('title-tag');

  // Register wp_nav_menu() menus
  // http://codex.wordpress.org/Function_Reference/register_nav_menus
  register_nav_menus([
    'primary_navigation' => __('Primary Navigation', 'sage')
  ]);

  // Enable post thumbnails
  // http://codex.wordpress.org/Post_Thumbnails
  // http://codex.wordpress.org/Function_Reference/set_post_thumbnail_size
  // http://codex.wordpress.org/Function_Reference/add_image_size
  add_theme_support('post-thumbnails');

  // Enable HTML5 markup support
  // http://codex.wordpress.org/Function_Reference/add_theme_support#HTML5
  add_theme_support('html5', ['caption', 'comment-form', 'comment-list', 'gallery', 'search-form']);

  // Use main stylesheet for visual editor
  // To add custom styles edit /assets/styles/layouts/_tinymce.scss
  add_editor_style(Assets\asset_path('styles/main.css'));

  // Enable to load jQuery from the Google CDN
  add_theme_support('jquery-cdn');
}
add_action('after_setup_theme', __NAMESPACE__ . '\\setup');

/**
 * Determine which pages should NOT display the sidebar
 */
function display_sidebar() {
  static $display;

  isset($display) || $display = !in_array(true, [
    // The sidebar will NOT be displayed if ANY of the following return true.
    // @link https://codex.wordpress.org/Conditional_Tags
    is_404(),
    is_front_page(),
    is_page_template('template-custom.php'),
  ]);

  return apply_filters('sage/display_sidebar', $display);
}

/**
 * Custom Image Sizes
 */
add_image_size('thumb-xs', 100, 100, false);
add_image_size('thumb-small', 250, 300, true);
add_image_size('thumb-medium', 550, 460, true);
add_image_size('thumb-square', 550, 550, true);
add_image_size('thumb-portrait', 360, 420, ['center', 'center']);
add_image_size('thumb-landscape', 430, 315, ['center', 'center']);
add_image_size('thumb-home-small', 250, 300, true);
add_image_size('thumb-home-portrait', 360, 420, ['center', 'center']);
add_image_size('thumb-home-landscape', 860, 630, ['center', 'center']);
add_image_size('thumb-home-past-exhibitions', 800, 685, ['center', 'center']);

add_image_size('hero', 1600, 900, ['center', 'center']);
add_image_size('home-hero', 1600, 900, ['center', 'center']);

/**
 * ACF Theme Options
 */
if( function_exists('acf_add_options_page') ) {
  acf_add_options_page(array(
    'page_title'  => 'Theme Options',
    'menu_title'  => 'Theme Options',
    'menu_slug'   => 'theme-general-options',
    'capability'  => 'edit_posts',
    'redirect'    => false,
    'position'    => '2.1'
  ));
  acf_add_options_page(array(
    'page_title'  => 'Settings',
    'menu_title'  => 'Settings',
    'parent_slug' => 'edit.php?post_type=essay',
  ));
  acf_add_options_sub_page([
    'page_title'  => 'News & Media Settings',
    'menu_title'  => 'News & Media Settings',
    'parent_slug' => 'edit.php?post_type=press'
  ]);
  acf_add_options_sub_page(array(
    'page_title'  => 'Additional Thanks To',
    'menu_title'  => 'Additional Thanks',
    'parent_slug' => 'edit.php?post_type=video',
  ));
  acf_add_options_sub_page(array(
    'page_title'  => 'Glossary Credit',
    'menu_title'  => 'Glossary Credit',
    'parent_slug' => 'edit.php?post_type=glossary',
  ));
    acf_add_options_sub_page(array(
    'page_title'  => 'Focused Exhibition',
    'menu_title'  => 'Focused Exhibition',
    'parent_slug' => 'edit.php?post_type=exhibition',
  ));
}

/**
 * Google Tag Manager
 */
function google_tag_manager() {
  if (defined('WP_ENV') && WP_ENV === 'production' && !current_user_can('manage_options')) :
  ?>
<!-- Google Tag Manager -->
<script>(function (w, d, s, l, i) {
  w[l] = w[l] || [];
  w[l].push({'gtm.start':
              new Date().getTime(), event: 'gtm.js'});
  var f = d.getElementsByTagName(s)[0],
          j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
  j.async = true;
  j.src =
          'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
  f.parentNode.insertBefore(j, f);
})(window, document, 'script', 'dataLayer', 'GTM-KTB5JP9');</script>
<!-- End Google Tag Manager -->
  <?php endif;
}
add_action('wp_head', __NAMESPACE__ . '\\google_tag_manager', 1);

/**
 * Hook for opening body tag.
 */
function gtm_noscript() {
  if (defined('WP_ENV') && WP_ENV === 'production' && !current_user_can('manage_options')) :
  ?>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KTB5JP9" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<?php endif;
}
add_action('leiden_body_start', __NAMESPACE__ . '\\gtm_noscript', 1);