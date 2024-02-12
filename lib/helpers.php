<?php

namespace helpers;

/**
 * Outputs an image from ACF array.
 *
 * @echo string | (an img tag)
 */
function acfimg( $array, $size, $classes = null ) {
  if( !$array )
    return;

  $src = esc_url($array['sizes'][$size]);
  $alt = esc_attr($array['alt']);

  echo '<img src="'. $src .'" alt="'. $alt .'" class="'. esc_attr($classes) .'" />';
}

/**
 * Outputs an image from media library.
 *
 * @return string (an img tag)
 */
function get_img_markup($id, $size, $classes = null) {
  $src = wp_get_attachment_image_src($id, $size)[0];
  $alt = get_post_meta($id, '_wp_attachment_image_alt', true);
  $alt = esc_html($alt);

  return '<img src="'. $src .'" class="'. $classes .'" alt="'. $alt .'"/>';
}

/**
 * Check if we are on a press archive page.
 */
function is_press_archive() {
  return is_post_type_archive('press') || is_tax('press_category') || is_tax('press_exhibition');
}

/**
 * Output an html anchor based on an ACF link field.
 *
 * @param array | $array (the acf link field)
 * @param string | $classes (a space separated list of classes)
 */
function acflink( $array, $classes = null ) {

  if ( empty($array) ) {
    return;
  }

  $atts[] = 'href="'.esc_url($array['url']).'"';

  if ( $classes ) {
    $atts[] = 'class="'.esc_attr($classes).'"';
  }
  if ( $array['target'] ) {
    $atts[] = 'target="'.esc_attr($array['target']).'"';
  }

  $the_atts = implode(' ', $atts);

  echo "<a {$the_atts}>{$array['title']}</a>";
}

/**
 * Get the artwork's group.
 *
 * @return boolean | false (if artwork does not belong to a group)
 * @return object | WP_Post (if artwork has a group)
 */
function get_the_group( $post_id ) {
  $group = get_field('artwork_to_group_2way', $post_id);

  if ( get_post_type($post_id) == 'artwork' && $group !== false && $group !== '' ) {
    return $group;
  }
  return false;
}

/**
 * Get the entry author.
 */
function get_the_entry_author( $post_id = 0 ) {

  if ( $post_id !== 0 ) {
    $post = get_post($post_id);
  } else {
    global $post;
  }

  $supported_types = ['artwork', 'artist', 'group', 'essay'];
  $group = get_the_group($post->ID);

  // if part of a group, pull the group comp figs
  if ( $group ) {
    $post_id = $group->ID;
  } else {
    $post_id = $post->ID;
  }

  $connected_authors = get_field('attributed_author_entries_2way', $post_id);
  $author_override = get_field('entry_author', $post_id);
  // $entry_year = get_field('entry_year', $post_id);

  if ( !in_array(get_post_type(), $supported_types) || empty($connected_authors) ) {
    return false;
  }

  // if ( $entry_year ) {
  //   $entry_year = '<br>'. $entry_year;
  // }

  if ( $author_override ) {
    return "- {$author_override}{$entry_year}";
  }

  foreach ( $connected_authors as $author ) {
    $authors[] = $author->post_title;
  }
  $authors_out = implode(', ', $authors);

  return "- {$authors_out}";
}

/**
 * Get the author display dates.
 * @param int | $post_id
 *
 * @return array | $dates (if applicable)
 * @return false | (if no dates are found)
 */
function get_artist_display_dates( $post_id ) {
  $birth_place = get_field('birth_place', $post_id);
  $birth_yr = get_field('birth_year', $post_id);
  $death_place = get_field('death_place', $post_id);
  $death_yr = get_field('death_year', $post_id);

  // add spaces
  $birth_place = $birth_place ? $birth_place.' ' : '';
  $death_yr = $death_yr ? $death_yr.' ' : '';

  // build our default $dates array
  $dates = [
    'birth' => $birth_place . $birth_yr,
    'death' => $death_yr . $death_place
  ];

  // check if we have any overrides
  if ( $birth_override = get_field('birth_override_text', $post_id) ) {
    $dates['birth'] = $birth_override;
  }
  if ( $death_override = get_field('death_override_text', $post_id) ) {
    $dates['death'] = $death_override;
  }

  // only return dates if we have both a birth and death year
  if ( $birth_yr && $death_yr ) {
    return $dates;
  }
  return false;
}

/**
 * Returns an adjusted item label for overlay use.
 */
function get_item_label($post) {
  $post_type = get_post_type($post->ID);
  $adjust = ['press'];

  if(!in_array($post_type, $adjust)) {
    return $post_type;
  }

  if($post_type == 'press') {
    return __('News & Media');
  }
}

/**
 * Returns adjusted text for certain terms. Used primarily in summary links on archive pages.
 * @param $press (object)
 */
function get_press_link_text($press) {
  if(has_term('videos', 'press_category', $press)) {
    return __('Watch Video', 'sage');
  } elseif(has_term('audio', 'press_category', $press)) {
    return __('Listen', 'sage');
  } else {
    return __('View Article', 'sage');
  }
}

/**
 * Returns the artist of an artwork.
 * @param $artwork (object)
 * @param $strip_tags (boolean)
 */
function get_artwork_artist( $artwork, $strip_tags = null ) {
  $artist = get_field('artwork_to_artist_2way', $artwork);

  if(!$artist) {
    return;
  }

  // Sometimes artists have a name variant. Optional strips out tags.
  if(get_field('artist_name_variant', $artwork)) {
    $artist = get_field('artist_name_variant', $artwork);
    $artist = $strip_tags ? strip_tags($artist) : $artist;
    return $artist;
  }

  $artist = get_the_title($artist);
  return $artist;
}

/**
 * Get all of the artists for a group entry.
 * Build from connected artwork's artists.
 * @return array | array of WP_Post objects
 */
function get_group_artists( $post_id ) {
  $connected_artwork = get_field('artwork_to_group_2way', $post_id);
  $artists = [];

  if ( !empty($connected_artwork) ) {
    foreach ( $connected_artwork as $artwork ) {
      $this_artist = get_field('artwork_to_artist_2way', $artwork->ID);
      if ( !in_array($this_artist, $artists) ) {
        $artists[] = $this_artist;
      }
    }
  }

  return $artists;
}

/**
 * Get the artwork medium. If an override is set, pull that.
 * Otherwise, get the medium from the taxonomy.
 */
function get_artwork_medium( $post_id ) {
  $mediums = get_the_terms($post_id, 'medium');
  $medium_variant = get_field('medium_variant', $post_id);
  $tomb_medium = null;

  if ( $medium_variant ) {
    return $medium_variant;
  } elseif ( !empty($mediums) ) {
    return $mediums[0]->name;
  }
}

/**
 * Get artwork location from Collector Systems.
 */
function get_artwork_location( $post_id ) {
  // $csdata = get_option('leiden_cs_data');
  // $inv_num = get_field('inventory_number', $post_id);

  // if ( array_key_exists($inv_num, $csdata) ) {
  //   return $csdata[$inv_num]['locationname'];
  // }
  // return false;
  $location = get_field('location_name',$post_id);
  return $location;
}

function get_related_essay_links( $post_id = 0) {
  if ( $post_id !== 0 ) {
    $post = get_post($post_id);
  } else {
    global $post;
  }

  $output = '';
  if ($essays = get_field('artwork_to_essay_2way', $post->ID)) {
    $output = __('For further discussion about this artwork, see ', 'sage');

    $essayArr = [];
    foreach ($essays as $essay) {
      $url = get_the_permalink($essay);
      $link = "<a href='{$url}'>{$essay->post_title}</a>";
      array_push($essayArr, $link);
    }

    // Semicolon separated list of links.
    $output .= join('; and ', array_filter(array_merge(array(join('; ', array_slice($essayArr, 0, -1))), array_slice($essayArr, -1)), 'strlen'));
    $output .= '.';
  }

  return $output;
}
