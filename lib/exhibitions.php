<?php

namespace Exhibitions;

use DateTime;

/**
 * Build an array of past exhibitions grouped by year.
 */
function group_past_exhibitions($exhibitions) {
  $years = [];
  $today = new DateTime();

  foreach($exhibitions as $e) {
    $end_date = get_field('end_date', $e);
    $end_date = new DateTime($end_date);
    $end_year = $end_date->format('Y');
    $years[$end_year][$e->ID] = $end_date->format('Ymd');
  }

  // Re-sort array by year (reverse order).
  krsort($years);
  return $years;
}

/**
 * Advanced Custom Fields Date Range
 * @link https://gist.github.com/brianjhanson/17e33b1d008fd69e3ea5
 */
function the_date_range($args, $id = null) {
  if( !isset($id) ) {
    global $post;
    $id = $post->ID;
  }
  $default = array(
    'start_field' => 'start_date',
    'end_field' => null,
    'base_format' => 'Ymd',
    'post_id' => $id,
    'separator' => '<span class="date-separator"> &ndash; </span>',
    'month_format' => 'F',
    'day_format' => 'j',
    'year_format' => 'Y'
  );
  $s = array_intersect_key($args + $default, $default);
  $start = get_field($s['start_field'], $s['post_id']);
  $end = get_field($s['end_field'], $s['post_id']);
  // Checks to make sure the start is a valid field
  if($start) {
    $raw_dates['start'] = DateTime::createFromFormat( $s['base_format'], $start );
  } else {
    return;
  }
  // Adds end field if there is one
  if( $end ) {
    $raw_dates['end'] = DateTime::createFromFormat( $s['base_format'], $end );
  }
  // Sets up the $dates array
  foreach($raw_dates as $key => $value) {
    $dates[$key] = array(
      'month' =>$value->format($s['month_format']),
      'day' => $value->format($s['day_format']),
      'year' => $value->format($s['year_format'])
    );
  }
  // if the years aren't the same the whole output has to change so we check that
  // at the beginning
  if($dates['start']['year'] == $dates['end']['year']) {
    // if years are the same and months are the same
    if($dates['start']['month'] == $dates['end']['month']) {
      // if years, months and days are the same (same date in both fields)
      if($dates['start']['day'] == $dates['end']['day']) {
        $range = $dates['start']['month']." ".$dates['start']['day'].", ".$dates['start']['year'];
      // if years and months are the same but not days
      } else {
        $range = $dates['start']['month']." ".$dates['start']['day'].$s['separator'].$dates['end']['day'].", ".$dates['start']['year'];
      }
    // if years are the same but months are not the same
    } else {
      $range = $dates['start']['month']." ".$dates['start']['day'].$s['separator'].$dates['end']['month']." ".$dates['end']['day'].", ".$dates['start']['year'];
    }
  } else {
    $range = $dates['start']['month']." ".$dates['start']['day'].", ".$dates['start']['year'].$s['separator'].$dates['end']['month']." ".$dates['end']['day'].", ".$dates['end']['year'];
  }
  return $range;
}