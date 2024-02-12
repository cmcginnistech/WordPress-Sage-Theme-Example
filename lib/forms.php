<?php

namespace Roots\Sage\Forms;

/**
 * Remove "Add Forms" from WYSIWYG editors (we want to enforce using the Form Component instead).
 */
add_filter('gform_display_add_form_button', '__return_false');

/**
 * Add custom AJAX spinner for Gravity Forms.
 */
function spinner_url($src) {
  return 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';
}
add_filter('gform_ajax_spinner_url', __NAMESPACE__ . '\\spinner_url', 10, 2);