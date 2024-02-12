<?php

namespace Filters;
use Roots\Sage\Extras;
use WP_Term;
use WP_Post_Type;

$allowed_params = ['pc', 'pe', 'se', 'order', 'orderby', 'meta_key', 'post_type', 'video_cat', 'filter', 'c_artist', 'date', 'subject', 'medium', 'show'];

/**
 * Get all of the active filters for the page.
 * Will only return params if in the $allowed_params array.
 */
function get_active_filters() {
  global $allowed_params;

  $query_string = $_GET;
  $queried_obj = get_queried_object();
	$params = [];
	$html = '';

  // set these defaults based on WP_Query
  if ( $queried_obj instanceof WP_Term ) {
    $params[$queried_obj->taxonomy] = $queried_obj->slug;
  }
  elseif ( $queried_obj instanceof WP_Post_Type ) {
    if ( $queried_obj->name == 'artist') {
      $params['order'] = 'ASC';
      $params['orderby'] = 'meta_value';
      $params['meta_key'] = 'artist_sort_name';
    }
    elseif ( in_array($queried_obj->name, ['essay', 'video', 'bibliography_entry']) ) {
      $params['order'] = 'ASC';
      $params['orderby'] = 'title';
    }
    elseif ( $queried_obj->name == 'entry_author' ) {
      $params['order'] = 'ASC';
      $params['orderby'] = 'meta_value';
      $params['meta_key'] = 'entry_author_sort_name';
    }
  }
  elseif ( is_search() ) {
    $params['se'] = get_search_query();
  }

  // set these params based on $_GET params
  if( !empty($query_string) ) {
    foreach( $query_string as $key => $value ) {

      if( in_array($key, $allowed_params) )
        $params[$key] = htmlentities($value);
    }
  }

	return $params;
}

/**
 * Check if a term is current active for a filter.
 */
function is_filter_active( $value_to_check, $param_key ) {

	$filters = get_active_filters();

	if( empty($filters) )
		return;

	foreach( $filters as $i => $filter ) {
		$values = explode(',', $filter);

		if( in_array($value_to_check, $values) )
			return true;
	}

	return false;
}

/**
 * Build a string of data attributes for ajax wrapper.
 */
function get_data_atts() {

  $filters = get_active_filters();
  $html = '';

	if( empty($filters) )
    return;

	foreach( $filters as $key => $value ) {
		$html .= ' data-'. $key .'="'. $value .'"';
	}

	return $html;
}
