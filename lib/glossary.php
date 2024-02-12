<?php

namespace Glossary;

/**
 * Deregister plugin styles and JS.
 */
function deregister_plugin_scripts() {
  wp_deregister_script('wpg-tooltipster-script');
  wp_deregister_style('wpg-tooltipster-style');
  wp_deregister_script('wpg-mixitup-script');
}
add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\deregister_plugin_scripts', 100);

/**
 * Show all glossary terms.
 */
function glossary_pre_get_posts( $query ) {
  // glossary archive page
  if ( $query->is_post_type_archive('glossary') && $query->is_main_query() ) {
    $query->set( 'posts_per_page', -1 );
  }
}
add_action( 'pre_get_posts', __NAMESPACE__ . '\\glossary_pre_get_posts' );