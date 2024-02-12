<?php

namespace Roots\Sage\Titles;

/**
 * Page titles
 */
function title() {
  if (is_home()) {
    if (get_option('page_for_posts', true)) {
      return get_the_title(get_option('page_for_posts', true));
    } else {
      return __('Latest Posts', 'sage');
    }
  } elseif (is_post_type_archive('press')) {
    return __('All Coverage', 'sage');
  } elseif (is_post_type_archive('essay')) {
    return __('Scholarly Essays', 'sage');
  } elseif (is_post_type_archive('entry_author')) {
    return __('Authors & Contributors', 'sage');
  } elseif (is_tax('press_exhibition')) {
    return str_replace('Exhibition: ', '', get_the_archive_title());
  } elseif (is_tax('press_category')) {
    return str_replace('Category: ', '', get_the_archive_title());
  } elseif (is_archive()) {
    return str_replace('Archives: ', '', get_the_archive_title());
  } elseif (is_search()) {
    $search_query = '<br><span>'.get_search_query().'</span>';
    return __(sprintf('Search Results for: %s', $search_query), 'sage');
  } elseif (is_404()) {
    return __('Not Found', 'sage');
  } else {
    return get_the_title();
  }
}
