<?php

namespace Roots\Sage\SEO;

/**
 * Move Yoast SEO metabox below all other field groups
 */
add_filter('wpseo_metabox_prio', function () {
  return 'low';
});

/**
 * Blacklist field types causing high number of query-attatchments.
 *
 * @link https://github.com/Yoast/yoast-acf-analysis/issues/120
 * @source https://wordpress.org/support/topic/high-number-of-query-attachments/
 */
add_filter('yoast-acf-analysis/blacklist_type', function ($blacklist_type) {
  $blacklist_type->add('image');
  $blacklist_type->add('gallery');
  return $blacklist_type;
});
