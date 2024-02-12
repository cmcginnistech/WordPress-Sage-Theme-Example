<?php

namespace Roots\Sage\Assets;

/**
 * Get paths for assets
 */
class JsonManifest {
  private $manifest;

  public function __construct($manifest_path) {
    if (file_exists($manifest_path)) {
      $this->manifest = json_decode(file_get_contents($manifest_path), true);
    } else {
      $this->manifest = [];
    }
  }

  public function get() {
    return $this->manifest;
  }

  public function getPath($key = '', $default = null) {
    $collection = $this->manifest;
    if (is_null($key)) {
      return $collection;
    }
    if (isset($collection[$key])) {
      return $collection[$key];
    }
    foreach (explode('.', $key) as $segment) {
      if (!isset($collection[$segment])) {
        return $default;
      } else {
        $collection = $collection[$segment];
      }
    }
    return $collection;
  }
}

function asset_path($filename) {
  $dist_path = get_template_directory_uri() . '/dist/';
  $directory = dirname($filename) . '/';
  $file = basename($filename);
  static $manifest;

  if (empty($manifest)) {
    $manifest_path = get_template_directory() . '/dist/' . 'assets.json';
    $manifest = new JsonManifest($manifest_path);
  }

  if (array_key_exists($file, $manifest->get())) {
    return $dist_path . $directory . $manifest->get()[$file];
  } else {
    return $dist_path . $directory . $file;
  }
}

function bower_map_to_cdn($dependency, $fallback) {
  static $bower;
  if (empty($bower)) {
    $bower_path = get_template_directory() . '/bower.json';
    $bower = new JsonManifest($bower_path);
  }
  $templates = [
    'google' => 'https://ajax.googleapis.com/ajax/libs/%name%/%version%/%file%'
  ];
  $version = $bower->getPath('dependencies.' . $dependency['name']);
  if (isset($version) && preg_match('/^(\d+\.){2}\d+$/', $version)) {
    $search = ['%name%', '%version%', '%file%'];
    $replace = [$dependency['name'], $version, $dependency['file']];
    return str_replace($search, $replace, $templates[$dependency['cdn']]);
  } else {
    return $fallback;
  }
}

/**
 * Local jQuery fallback
 */
function jquery_local_fallback($src, $handle = null) {
  static $add_jquery_fallback = false;
  if ($add_jquery_fallback) {
    echo '<script>window.jQuery || document.write(\'<script src="' . $add_jquery_fallback .'"><\/script>\')</script>' . "\n";
    $add_jquery_fallback = false;
  }
  if ($handle === 'jquery') {
    $add_jquery_fallback = apply_filters('script_loader_src', asset_path('scripts/jquery.js'), 'jquery-fallback');
  }
  return $src;
}
add_action('wp_head', __NAMESPACE__ . '\\jquery_local_fallback');

/**
 * 1. Test if JS is enabled and replace 'no-js' class from html element with 'js' class (similar to Modernizr)
 * http://www.paulirish.com/2009/avoiding-the-fouc-v3/
 * 2. Checks to see if fonts are loaded through cache via sessionStorage data.
 */
function some_head_scripts() { ?>
<script>(function(H){H.className=H.className.replace(/\bno-js\b/,'js')})(document.documentElement);if(sessionStorage.wfLoaded){var html=document.documentElement;html.classList.add('wf-active')}</script>
<?php }
add_action('wp_head',  __NAMESPACE__ . '\\some_head_scripts');

/**
 * Load up some webfawnts asynchronously.
 * @link https://github.com/typekit/webfontloader
 */
function add_webfontloader() { ?>
<script>
  WebFontConfig = {
    google: {
      families: ['Montserrat:300,400i,400,500', 'Crimson+Text:400,400i,600']
    },
    timeout: 5000,
    active: function() { sessionStorage.wfLoaded = true; }
  };
  (function(d){var wf=d.createElement('script'),s=d.scripts[0];wf.src='https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js';wf.async=!0;s.parentNode.insertBefore(wf,s)})(document)
</script>
<?php }
add_action('wp_footer',  __NAMESPACE__ . '\\add_webfontloader');

/**
 * Theme assets
 */
function assets() {
  wp_enqueue_style('sage/css', asset_path('styles/main.css'), false, null);

  /**
   * Remove block editor CSS (using Classic Editor instead).
   */
  wp_dequeue_style('wp-block-library');

  if (is_single() && comments_open() && get_option('thread_comments')) {
    wp_enqueue_script('comment-reply');
  }

  // Load jQuery from CDN
  if (!is_admin() && current_theme_supports('jquery-cdn')) {
    wp_deregister_script('jquery');

    wp_register_script('jquery', bower_map_to_cdn([
      'name' => 'jquery',
      'cdn' => 'google',
      'file' => 'jquery.min.js'
    ], asset_path('scripts/jquery.js')), [], null, true);

    add_filter('script_loader_src', __NAMESPACE__ . '\\jquery_local_fallback', 10, 2);

    /**
     * Prevent auto-execution of scripts when no explicit dataType was provided (See gh-2432)
     * @link https://github.com/jquery/jquery/issues/2432
     */
    wp_add_inline_script('jquery', 'jQuery.ajaxPrefilter(function(s){if(s.crossDomain){s.contents.script=!1}})', 'after');
  }

  wp_enqueue_script('sage/js', asset_path('scripts/main.js'), ['jquery'], null, true);

  if ( is_page_template(['template-collection.php', 'template-collection-gallery.php']) ) {
    wp_enqueue_script('sage/collection', asset_path('scripts/collection.js'), ['jquery'], null, true);
  }
}
add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\assets', 100);

/**
 * Moves all scripts to wp_footer action
 */
function js_to_footer() {
  remove_action('wp_head', 'wp_print_scripts');
  remove_action('wp_head', 'wp_print_head_scripts', 9);
  remove_action('wp_head', 'wp_enqueue_scripts', 1);
}
add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\js_to_footer');

/**
 * Moves Gravity Forms JS to footer
 * http://www.gravityhelp.com/documentation/gravity-forms/extending-gravity-forms/hooks/filters/gform_init_scripts_footer/
 */
//add_filter('gform_init_scripts_footer', '__return_true');

/**
 * Moves remaining inline Gravity Forms JS to footer
 * https://bjornjohansen.no/load-gravity-forms-js-in-footer
 */
function wrap_gform_cdata_open($content = '') {
  if ((defined('DOING_AJAX') && DOING_AJAX) || isset($_POST['gform_ajax'])) {
    return $content;
  }
  $content = 'document.addEventListener("DOMContentLoaded", function() { ';
  return $content;
}
//add_filter('gform_cdata_open', __NAMESPACE__ . '\\wrap_gform_cdata_open', 1);

function wrap_gform_cdata_close($content = '') {
  if ((defined('DOING_AJAX') && DOING_AJAX) || isset($_POST['gform_ajax'])) {
    return $content;
  }
  $content = ' }, false );';
  return $content;
}
//add_filter('gform_cdata_close', __NAMESPACE__ . '\\wrap_gform_cdata_close', 99);


