<?php
/**
 * Adjust engine settings for the default engine.
 * If search query is an inventory number, switch the engine.
 */
function searchwp_engine_customizations( $settings, $query ) {
  // Regex pattern to match the first part of any inventory number.
  // This seems to return a match for any partial or full inventory number.
  // Example: must match "RR-" as well as "RR-104"
  // Variations: "RR-104", "CN-110.a", "HBr-100" or "HbR-100"
  // @return array - matches "XX-" or "XxX-"
  $prefix_only = preg_grep("/[A-Z|a-z]{2,3}(-$|(-\d))\d{0,2}.?[a-z]?/", $query);

  // if we match the regex patterns above, we have an
  // inventory number then switch the engine.
  if ( !empty($prefix_only) ) {
    return SWP()->settings['engines']['artwork_by_inventory_number'];
  }

  // otherwise return the default engine settings
  return $settings;
}
add_filter( 'searchwp_engine_settings_default', __NAMESPACE__ . '\\searchwp_engine_customizations', 10, 2 );

/**
 * Adjust searchwp min word length.
 */
function adjust_searchwp_min_word_length() {
  return 2;
}
add_filter( 'searchwp_minimum_word_length', __NAMESPACE__ . '\\adjust_searchwp_min_word_length' );

/**
 * Add to searchWP's whitelist term patterns.
 */
function my_searchwp_term_pattern_whitelist( $whitelist ) {

	$my_whitelist = [
    "/[A-Z|a-z]{2,3}-/u", // inventory prefix "RR-" or "HBr-" (must include dash)
  ];

	// we want our pattern to be considered the most specific
	// so that false positive matches do not interfere
  $whitelist = array_merge( $my_whitelist, $whitelist );

	return $whitelist;
}
add_filter( 'searchwp_term_pattern_whitelist', __NAMESPACE__ . '\\my_searchwp_term_pattern_whitelist' );

/**
 * Tell SearchWP to only do "and" based searched, not "or".
 * This is so searching phrases like "Ilona Van Tuinen" will only return
 * the proper results.
 *
 * @link https://searchwp.com/docs/hooks/searchwp_and_logic_only/
 */
add_filter( 'searchwp_and_logic_only', __NAMESPACE__ . '\\__return_true' );

/**
 * Exclude artists from search.
 * Include this here as it is 10x faster than in wp-api.php
 *
 * @param array $ids
 * @param string $engine
 * @param array $terms
 * @return void
 */
function leiden_searchwp_exclude( $ids, $engine, $terms ) {

  $entries_to_exclude = get_posts(
		array(
			'post_type'      => 'artist',
			'posts_per_page' => -1,
			'fields'         => 'ids',
			'meta_query'     => [
				[
          'key'     => 'hide_on_list_view',
          'value'   => 1,
          'compare' => '='
        ]
      ]
		)
  );

  $ids = array_unique( array_merge( $ids, array_map( 'absint', $entries_to_exclude ) ) );
  return $ids;
}
add_filter( 'searchwp_exclude', __NAMESPACE__ . '\\leiden_searchwp_exclude', 10, 3 );