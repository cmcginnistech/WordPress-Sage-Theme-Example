<?php

namespace Admin\Assets;

use Roots\Sage\Assets;

/**
 * Admin enquque scripts
 */
function admin_assets( $hook ) {

    global $post;

    // $types = ['artwork', 'group', 'artist', 'essay'];

    // if ( $hook !== 'post-new.php' && $hook !== 'post.php' ) {
    //   return;
    // }

    $utility_vars = [
      'ajaxURL' => admin_url('admin-ajax.php'),
      'requestNonce' => wp_create_nonce('request-nonce'),
      'postID' => $post ? $post->ID : 0,
      'postName' => $post ? $post->post_name : '',
      'screen' => get_current_screen(),
      'params' => filter_input_array(INPUT_GET),
    ];

    // the admin styles
    wp_enqueue_style('sage/admin', Assets\asset_path('styles/admin.css'), false, null);

    // admin javascript
    wp_enqueue_script( 'sage/admin', Assets\asset_path('scripts/admin.js'), 'jquery', '', true );

    // some handy variables to use in our javascript
    wp_localize_script('sage/admin', 'utilityVars', $utility_vars);
    wp_enqueue_script('sage/admin-utilityVars');

}
add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\\admin_assets' );

