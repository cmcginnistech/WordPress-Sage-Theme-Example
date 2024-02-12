<?php

namespace Roots\Sage\Extras;

use phpseclib\Crypt\AES;
use Roots\Sage\PDF_Functions;
use Roots\Sage\Setup;
use FullNameParser;
use DateTime;
use helpers;

/**
 * Add <body> classes
 */
function body_class($classes) {
  // Add page slug if it doesn't exist
  if (is_single() || is_page() && !is_front_page()) {
    if (!in_array(basename(get_permalink()), $classes)) {
      $classes[] = basename(get_permalink());
    }
  }

  // Remove max width on wrapper
  if ( is_page_template(['template-collection.php', 'template-collection-gallery.php']) ) {
    $classes[] = 'no-max-width';
  }

  // Pages without comparative figures
  if ( !is_singular(['artwork', 'group', 'essay']) && !is_page_template('template-portrait-in-oil.php') ) {
    $classes[] = 'no-comp-figs';
  }

  return $classes;
}
add_filter('body_class', __NAMESPACE__ . '\\body_class');

/**
 * Clean up the_excerpt()
 */
function excerpt_more() {
  return ' &hellip; <a href="' . get_permalink() . '">' . __('Continued', 'sage') . '</a>';
}
add_filter('excerpt_more', __NAMESPACE__ . '\\excerpt_more');

/**
 * Get excerpt from ACF component pages
 */
function acf_excerpt($id, $query, $wordCount = 45) {
  $components = get_field('components', $id, false);
  $excerpt = '';

  if( get_page($id)->post_excerpt ) {
    $excerpt = get_page($id)->post_excerpt;
  }
  elseif ( $components ) {
    foreach ($components as $component) {
      if ( $component['acf_fc_layout'] == 'text' ) {
        $excerpt = $component['field_5a8b152f5b4ca_field_58261afc96eab'];

		  //GET SEARCH SNIPPET FOR NEW SEARCH ADVANCE
		  $excerpt = "..." . substr($excerpt, strpos($excerpt, $query));    
		  
        break;
      } else {
        $excerpt = '';
      }
    }
  }

  // strip any html tags
  $excerpt = strip_tags($excerpt);

  // replace shortcodes
  $excerpt = preg_replace('/ \[(.*?)\]|\[(.*?)\]/', '', $excerpt);
  $excerpt = wp_trim_words( $excerpt, $wordCount );

  if ( $excerpt ) {
    return '<p>'. $excerpt .'</p>';
  }
  return false;
}

/**
 * Make arbitrary strings acceptable as class names
 */
function classify($string) {
  $string = str_replace("&#8217", "", $string);
  $string = str_replace("<i", "", $string);
  $string = str_replace("i>", "", $string);
  $string = strtolower($string);
  $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
  $string = preg_replace("/[\s-]+/", " ", $string);
  $string = preg_replace("/[\s_]/", "-", $string);
  return $string;
}

/**
 * Backbone of Cuberis components
 *
 * @param string | $prefix (i.e. "artwork_components")
 * @param string | $post_id (specify which post you want to display components from)
 *
 * @return html
 */
function componify($prefix = null, $post_id = 0) {
  if ( $prefix ) {
    $prefix = $prefix .'_';
  }

  if ( $post_id == 0 ) {
    global $post;
    $post_id = $post->ID;
  }

  if ( function_exists('have_rows') && have_rows($prefix .'components', $post_id) && !post_password_required($post_id) ) :
    ?>
    <div class="components-wrapper container">
      <?php
      while ( have_rows($prefix .'components', $post_id) ) :
        the_row();
        $type = classify(get_row_layout());
        ?>
        <section class="component component--<?= $type ; ?>">
          <?= get_template_part('components/component', $type); ?>
        </section>
        <?php
      endwhile; ?>
    </div>
    <?php
  endif;
}

/**
 * SVG Icons
 */
function icons($icon, $width, $height) {
  if($icon === 'info') {
    return '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="'.$width.'" height="'.$height.'" viewBox="0 0 32 32"><title>info</title><path d="M16.247 4.733c0 1.684 1.271 2.825 2.84 2.825s2.842-1.142 2.842-2.825c0-1.685-1.272-2.826-2.842-2.826-1.568 0-2.84 1.141-2.84 2.826zm-6.151 9.642c0 .334-.061 1.163.008 1.662l2.479-2.983c.513-.562 1.106-.955 1.409-.849s.47.463.371.795L10.26 26.59c-.473 1.588.421 3.148 2.599 3.504 3.189 0 5.084-2.158 6.948-4.955 0-.334.111-1.213.044-1.713l-2.479 2.982c-.514.562-1.151.955-1.455.85-.28-.098-.444-.41-.389-.721l4.132-13.653c.344-1.734-.59-3.312-2.564-3.514-2.076.001-5.136 2.209-7 5.005z"/></svg>';
  } elseif($icon === 'caret') {
    return '<svg xmlns="http://www.w3.org/2000/svg" width="'.$width.'" height="'.$height.'" fill="#fff" viewBox="0 0 32 32"><path d="M1 12 L16 26 L31 12 L27 8 L16 18 L5 8 z"/></svg>';
  } elseif($icon === 'caret-down-white') {
    return '<svg width="'.$width.'" height="'.$height.'" version="1.1" xmlns="http://www.w3.org/2000/svg"><title>0B7027A1-6FFE-451C-BE41-75F8C00ABDE8</title><g id="Symbols" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><g id="Input-/-Select-Dark" transform="translate(-166 -32)" fill="#FFF"><path d="M178.071 31.071v8h-2v-8h-8v-2h10v2z" id="Combined-Shape" transform="rotate(135 173.071 34.071)"/></g></g></svg>';
  } elseif($icon === 'chevron-right') {
    return '<svg xmlns="http://www.w3.org/2000/svg" width="'.$width.'" height="'.$height.'" viewBox="0 0 32 32"><path d="M12 1l14 15-14 15-4-4 10-11L8 5z"/></svg>';
  } elseif( $icon === 'twitter' ) {
    return '<svg viewBox="0 0 100 100" width="'.$width.'" height="'.$height.'" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><title>Twitter</title><g><path d="M90,24.8c-2.9,1.3-6.1,2.2-9.4,2.6c3.3-2,6-5.3,7.2-9.1c-3.2,1.9-6.7,3.2-10.4,4c-3-3.2-7.2-5.2-12-5.2c-9.1,0-16.4,7.4-16.4,16.6c0,1.3,0.1,2.6,0.4,3.8C35.8,36.6,23.7,30,15.6,20c-1.4,2.4-2.2,5.2-2.2,8.3c0,5.8,2.9,10.8,7.3,13.8C18,42,15.4,41.3,13.2,40c0,0.1,0,0.1,0,0.2c0,8,5.7,14.7,13.1,16.2C25,56.8,23.6,57,22,57c-1.1,0-2.1-0.1-3.1-0.3C21,63.2,27,68,34.2,68.1c-5.7,4.4-12.7,7.1-20.3,7.1c-1.3,0-2.7-0.1-3.9-0.2c7.2,4.7,15.9,7.4,25.1,7.4c30.2,0,46.7-25.1,46.7-47c0-0.7,0-1.4,0-2.1C85,31,87.8,28,90,24.8z" fill="currentColor"></path></g></svg>';
  } elseif( $icon === 'facebook' ) {
    return '<svg viewBox="0 0 100 100" width="'.$width.'" height="'.$height.'" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><title>Facebook</title><g><path d="M67.8,50L67.8,50L67.8,50h-14v37.5H37.7V50H30V37.8h7.7v-8c0-10.8,4.7-17.3,17.5-17.3h14.7v13.3h-12c-3.6,0-4,1.9-4,5.4v6.6H70L67.8,50z" fill="currentColor"></path></g></svg>';
  } elseif( $icon === 'pinterest' ) {
    return '<svg viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" width="'.$width.'" height="'.$height.'" fill-rule="evenodd" clip-rule="evenodd" stroke-linejoin="round" stroke-miterlimit="1.414"><path d="M8 0C3.582 0 0 3.582 0 8c0 3.39 2.108 6.285 5.084 7.45-.07-.633-.133-1.604.028-2.295.146-.625.938-3.977.938-3.977s-.24-.48-.24-1.188c0-1.11.646-1.943 1.448-1.943.683 0 1.012.513 1.012 1.127 0 .687-.436 1.713-.662 2.664-.19.797.4 1.445 1.185 1.445 1.42 0 2.514-1.498 2.514-3.662 0-1.915-1.376-3.254-3.342-3.254-2.276 0-3.61 1.707-3.61 3.472 0 .687.263 1.424.593 1.825.066.08.075.15.057.23-.06.252-.196.796-.223.907-.035.146-.115.178-.268.107-.998-.465-1.624-1.926-1.624-3.1 0-2.524 1.834-4.84 5.287-4.84 2.774 0 4.932 1.977 4.932 4.62 0 2.757-1.74 4.977-4.153 4.977-.81 0-1.572-.422-1.833-.92l-.5 1.902c-.18.695-.667 1.566-.994 2.097.75.232 1.545.357 2.37.357 4.417 0 8-3.582 8-8s-3.583-8-8-8z" fill-rule="nonzero" fill="currentColor"/></svg>';
  } elseif( $icon === 'search' ) {
    return '<svg viewBox="0 0 32 32" width="'.$width.'" height="'.$height.'"><path d="M12 0 A12 12 0 0 0 0 12 A12 12 0 0 0 12 24 A12 12 0 0 0 18.5 22.25 L28 32 L32 28 L22.25 18.5 A12 12 0 0 0 24 12 A12 12 0 0 0 12 0 M12 4 A8 8 0 0 1 12 20 A8 8 0 0 1 12 4"></path></svg>';
  } elseif( $icon === 'plus' ) {
    return '<svg width="'.$width.'" height="'.$height.'" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd" stroke-linejoin="round" stroke-miterlimit="1.414"><path d="M-.002 45.834H45.83V0h8.334v45.834h45.833v8.333H54.165v45.834H45.83V54.167H-.002v-8.333z"/></svg>';
  } elseif( $icon === 'video' ) {
    return '<svg xmlns="http://www.w3.org/2000/svg" width="'.$width.'" height="'.$height.'" viewBox="0 0 24 24"><path d="M3 22v-20l18 10-18 10z" fill="currentColor"/></svg>';
  } elseif( $icon === 'info-circle' ) {
    return '<svg width="'.$width.'" height="'.$height.'" viewBox="0 0 26 26" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><defs><path d="M12.5 25C5.596 25 0 19.404 0 12.5S5.596 0 12.5 0 25 5.596 25 12.5 19.404 25 12.5 25zm0-2C18.299 23 23 18.299 23 12.5S18.299 2 12.5 2 2 6.701 2 12.5 6.701 23 12.5 23zm.05-12.952c.325 0 .593.103.805.308.212.205.319.505.319.901 0 .156-.029.428-.085.817a9.97 9.97 0 0 1-.128.753l-.784 4.03c-.029.084-.043.247-.043.487 0 .34.07.51.212.51.198 0 .478-.149.838-.446.36-.297.598-.523.71-.679.043 0 .12.071.234.212.113.142.17.24.17.297-.156.382-.661.895-1.517 1.538-.855.643-1.58.965-2.174.965-.311 0-.576-.102-.795-.307-.22-.205-.33-.506-.33-.902 0-.155.03-.428.086-.816a9.97 9.97 0 0 1 .127-.753l.785-4.03c.028-.142.042-.304.042-.488 0-.34-.07-.51-.212-.51-.198 0-.477.15-.838.446-.36.297-.597.523-.71.679-.028 0-.103-.07-.223-.212-.12-.142-.18-.24-.18-.297.155-.382.66-.894 1.516-1.538.856-.643 1.58-.965 2.174-.965zM13.227 5c.354 0 .647.127.88.382.234.254.35.565.35.933 0 .467-.166.884-.498 1.251-.332.368-.718.552-1.156.552-.354 0-.647-.127-.88-.382-.234-.254-.35-.573-.35-.954 0-.467.166-.88.498-1.241.332-.36.718-.541 1.156-.541z" id="a"/></defs><use fill="currentColor" xlink:href="#a" fill-rule="evenodd"/></svg>';
  } elseif( $icon === 'plus-circle' ) {
    return '<svg width="'.$width.'" height="'.$height.'" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg"><path d="M19 19V8h2v11h11v2H21v11h-2V21H8v-2h11zm1 21C8.954 40 0 31.046 0 20S8.954 0 20 0s20 8.954 20 20-8.954 20-20 20zm0-2c9.941 0 18-8.059 18-18S29.941 2 20 2 2 10.059 2 20s8.059 18 18 18z" fill="#8F92C6" fill-rule="evenodd"/></svg>';
  } elseif( $icon === 'minus-circle' ) {
    return '<svg width="'.$width.'" height="'.$height.'" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg"><path d="M20 40C8.954 40 0 31.046 0 20S8.954 0 20 0s20 8.954 20 20-8.954 20-20 20zm0-2c9.941 0 18-8.059 18-18S29.941 2 20 2 2 10.059 2 20s8.059 18 18 18zM8 19h24v2H8v-2z" fill="#8F92C6" fill-rule="evenodd"/></svg>';
  } elseif( $icon === 'print' ) {
    return '<svg width="'.$width.'" height="'.$height.'" viewBox="0 0 24 25" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><defs><path d="M19.334 20l.777 3.367-.111.026V25H4v-2h.085l.692-3H0V5h6V0h13v5h5v15h-4.666zm-.462-2H22V7H2v11h3.24L6 14.704V14h12v.222L18.872 18zm-2.514-2H7.753l-1.616 7h11.837l-1.616-7zM8 5h9V2H8v3zM4 9h5v2H4V9z" id="print-icon"/></defs><use fill="currentColor" xlink:href="#print-icon" fill-rule="evenodd"/></svg>';
  } elseif( $icon === 'download' ) {
    return '<svg width="'.$width.'" height="'.$height.'" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><defs><path d="M13 15.763l3.814-3.813 1.414 1.414-6.364 6.364-1.414-1.414-4.95-4.95 1.414-1.414L11 16.036V0h2v15.763zM22 22V5h-6V3h8v21H0V3h8v2H2v17h20z" id="download-icon"/></defs><use fill="currentColor" xlink:href="#download-icon" fill-rule="evenodd"/></svg>';
  } elseif( $icon === 'share' ) {
    return '<svg width="'.$width.'" height="'.$height.'" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><defs><path d="M14.667 2.404l-8.131 8.132-.943-.943 8.26-8.26H8.666V0H16v7.333h-1.333V2.404zM1.333 16H0V2.667h7.333V4h-6v10.667H12v-6h1.333V16h-12z" id="share-icon-a"/></defs><g fill="none" fill-rule="evenodd"><mask id="share-icon-b" fill="currentColor"><use xlink:href="#share-icon-a"/></mask><use fill="currentColor" xlink:href="#share-icon-a"/><g mask="url(#share-icon-b)" fill="currentColor"><path d="M0 0h17v17H0z"/></g></g></svg>';
  } elseif( $icon === 'magnifier-zoom' ) {
    return '<svg width="'.$width.'" height="'.$height.'" viewBox="0 0 58 60" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd"><rect stroke="#8F92C6" stroke-width="2" opacity=".6" x="1" y="1" width="46" height="46" rx="23"/><path d="M25 23h9v2h-9v9h-2v-9h-9v-2h9v-9h2v9z" fill="#FFF"/><path fill="#8F92C6" opacity=".6" d="M38.808 42.222l1.414-1.414 16.97 16.97-1.414 1.414z"/></g></svg>';
  } elseif( $icon === 'sort-icon' ) {
    return '<svg width="'.$width.'" height="'.$height.'" viewBox="0 0 28 25" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
              <!-- Generator: Sketch 49.3 (51167) - http://www.bohemiancoding.com/sketch -->
              <title>Combined Shape</title>
              <desc>Created with Sketch.</desc>
              <defs>
                  <path d="M18.3847763,22.9203102 L12.0208153,16.5563492 L13.4350288,15.1421356 L18.7989899,20.5060967 L18.7989899,1.70710678 L20.7989899,1.70710678 L20.7989899,20.5060967 L26.1629509,15.1421356 L27.5771645,16.5563492 L19.7989899,24.3345238 L18.3847763,22.9203102 Z M9.19238816,1.41421356 L15.5563492,7.77817459 L14.1421356,9.19238816 L8.77817459,3.82842712 L8.77817459,22.627417 L6.77817459,22.627417 L6.77817459,3.82842712 L1.41421356,9.19238816 L-1.3500312e-13,7.77817459 L7.77817459,9.41469125e-14 L9.19238816,1.41421356 Z" id="path-1"></path>
              </defs>
              <g id="Symbols" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                  <g id="Icon-/-Sort-(Default)" transform="translate(2.000000, 0.000000)">
                      <g id="Two" transform="translate(-2.000000, 0.000000)">
                          <mask id="mask-2" fill="white">
                              <use xlink:href="#path-1"></use>
                          </mask>
                          <use id="Combined-Shape" fill="#1D204F" xlink:href="#path-1"></use>
                      </g>
                  </g>
              </g>
          </svg>';
  } elseif( $icon === 'sort-icon-sorted' ) {
    return '<svg width="'.$width.'" height="'.$height.'" viewBox="0 0 25 25" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
              <!-- Generator: Sketch 49.3 (51167) - http://www.bohemiancoding.com/sketch -->
              <title>Mask</title>
              <desc>Created with Sketch.</desc>
              <defs>
                  <path d="M12,0 L12,2 L25,2 L25,0 L12,0 Z M12,5 L12,7 L22,7 L22,5 L12,5 Z M6.36396103,23.2132034 L0,16.8492424 L1.41421356,15.4350288 L6.77817459,20.7989899 L6.77817459,0 L8.77817459,0 L8.77817459,20.7989899 L14.1421356,15.4350288 L15.5563492,16.8492424 L7.77817459,24.627417 L6.36396103,23.2132034 Z M12,10 L19,10 L19,12 L12,12 L12,10 Z" id="path-1"></path>
              </defs>
              <g id="Symbols" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                  <g id="Icon-/-Sort-(Descending)" transform="translate(1.000000, 0.000000)">
                      <g id="Sort" transform="translate(-1.000000, 0.000000)">
                          <mask id="mask-2" fill="white">
                              <use xlink:href="#path-1"></use>
                          </mask>
                          <use id="Mask" fill="#000000" fill-rule="nonzero" xlink:href="#path-1"></use>
                      </g>
                  </g>
              </g>
          </svg>';
    } elseif( $icon === 'minus-circle-sm' ) {
      return '<svg width="'.$width.'" height="'.$height.'" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><defs><path d="M12 24C5.372583 24 0 18.627417 0 12S5.372583 0 12 0s12 5.372583 12 12-5.372583 12-12 12zm0-2c5.5228475 0 10-4.4771525 10-10S17.5228475 2 12 2 2 6.4771525 2 12s4.4771525 10 10 10zM5 11h14v2H5v-2z" id="minus-icon-sm"/></defs><g id="Symbols" fill="none" fill-rule="evenodd"><g id="Icon-/-Contract"><mask id="mask-2" fill="currentColor"><use xlink:href="#minus-icon-sm"/></mask><use id="Mask-Copy" fill="currentColor" xlink:href="#minus-icon-sm"/></g></g></svg>';
    } elseif( $icon === 'plus-circle-sm' ) {
      return '<svg width="'.$width.'" height="'.$height.'" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><defs><path d="M13 11h6v2h-6v6h-2v-6H5v-2h6V5h2v6zm-1 13C5.372583 24 0 18.627417 0 12S5.372583 0 12 0s12 5.372583 12 12-5.372583 12-12 12zm0-2c5.5228475 0 10-4.4771525 10-10S17.5228475 2 12 2 2 6.4771525 2 12s4.4771525 10 10 10z" id="plus-icon-sm"/></defs><g id="Symbols" fill="none" fill-rule="evenodd"><g id="Icon-/-Expand"><mask id="mask-2" fill="currentColor"><use xlink:href="#plus-icon-sm"/></mask><use id="Mask" fill="currentColor" xlink:href="#plus-icon-sm"/></g></g></svg>';
    } elseif( $icon === 'mode-sync' ) {
      return '<svg width="'.$width.'" height="'.$height.'" viewBox="0 0 26 26" xmlns="http://www.w3.org/2000/svg"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><g id="adjacent-icon"><rect class="first" stroke="currentColor" x="1.5" y="5.5" width="10" height="16"></rect><rect stroke="currentColor" x="14.5" y="5.5" width="10" height="16"></rect></g></g></svg>';
    } elseif( $icon === 'mode-curtain' ) {
      return '<svg width="'.$width.'" height="'.$height.'" viewBox="0 0 26 26" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><g><rect stroke="currentColor" x="9.5" y="6.5" width="13" height="18"></rect><rect class="first" stroke="currentColor" x="3.5" y="1.5" width="13" height="18"></rect><rect class="notch" fill="#1C1D21" x="17" y="5" width="2" height="3"></rect><rect class="notch" fill="#1C1D21" x="8" y="20" width="3" height="2"></rect></g></g></svg>';
    } elseif( $icon === 'gallery' ) {
      return '<svg width="'.$width.'" height="'.$height.'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 19 24"><g fill="#8F92C6" fill-rule="evenodd"><path d="M13 7l-1.55 1.77L8 5l-5.5 6h14L13 7zM14 6a1.5 1.5 0 1 0-1.5-1.5A1.5 1.5 0 0 0 14 6z"/><path d="M18.5 0H.5v13h18zm-1 12h-16V1h16zM18.5 17.85A1 1 0 1 0 17 17a1 1 0 0 0 .36.74 5.36 5.36 0 0 1-3.36 1.1 6.16 6.16 0 0 1-3.66-1.32.94.94 0 0 0 .16-.52 1 1 0 0 0-2 0 .94.94 0 0 0 .16.52A6.16 6.16 0 0 1 5 18.84a5.36 5.36 0 0 1-3.36-1.1A1 1 0 0 0 2 17a1 1 0 1 0-1.5.85V22H0v2h2v-2h-.5v-3.17a6.44 6.44 0 0 0 3.5 1 6.77 6.77 0 0 0 4-1.4V22h-.5v2h2v-2H10v-3.56a6.77 6.77 0 0 0 4 1.4 6.44 6.44 0 0 0 3.5-1V22H17v2h2v-2h-.5z"/></g></svg>';
    } elseif( $icon === 'grid' ) {
      return '<svg width="'.$width.'" height="'.$height.'" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><defs><path d="M0 0h20v20H0V0zm18.3333333 1.66666667H1.66666667V18.3333333H18.3333333V1.66666667zM8.33333333 8.33333333H5V5h3.33333333v3.33333333zm0 6.66666667H5v-3.3333333h3.33333333V15zM15 8.33333333h-3.3333333V5H15v3.33333333zM15 15h-3.3333333v-3.3333333H15V15z" id="path-1"/></defs><g id="Symbols" fill="none" fill-rule="evenodd"><g id="Icon-/-Grid" transform="translate(-2 -2)"><g id="Color-/-Primary-Color-1" transform="translate(2 2)"><mask id="mask-2" fill="#8F92C6"><use xlink:href="#path-1"/></mask><use id="Mask" fill="#8F92C6" fill-rule="nonzero" xlink:href="#path-1"/></g></g></g></svg>';
    } elseif( $icon === 'link' ) {
      return '<svg xmlns="http://www.w3.org/2000/svg" width="'.$width.'" height="'.$height.'" viewBox="0 0 24 24"><path d="M6.188 8.719c.439-.439.926-.801 1.444-1.087 2.887-1.591 6.589-.745 8.445 2.069l-2.246 2.245c-.644-1.469-2.243-2.305-3.834-1.949-.599.134-1.168.433-1.633.898l-4.304 4.306c-1.307 1.307-1.307 3.433 0 4.74 1.307 1.307 3.433 1.307 4.74 0l1.327-1.327c1.207.479 2.501.67 3.779.575l-2.929 2.929c-2.511 2.511-6.582 2.511-9.093 0s-2.511-6.582 0-9.093l4.304-4.306zm6.836-6.836l-2.929 2.929c1.277-.096 2.572.096 3.779.574l1.326-1.326c1.307-1.307 3.433-1.307 4.74 0 1.307 1.307 1.307 3.433 0 4.74l-4.305 4.305c-1.311 1.311-3.44 1.3-4.74 0-.303-.303-.564-.68-.727-1.051l-2.246 2.245c.236.358.481.667.796.982.812.812 1.846 1.417 3.036 1.704 1.542.371 3.194.166 4.613-.617.518-.286 1.005-.648 1.444-1.087l4.304-4.305c2.512-2.511 2.512-6.582.001-9.093-2.511-2.51-6.581-2.51-9.092 0z" fill="currentColor" /></svg>';
    } elseif( $icon === 'bubble-arrow-right' ) {
      return '<svg width="'.$width.'" height="'.$height.'" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg"><g id="Page-1" fill="none" fill-rule="evenodd"><g id="arrow-button"><circle id="circle" fill="#1D204F" opacity=".59999996" cx="20" cy="20" r="20"/><path d="M22 25.263456l3.8137085-3.8137085 1.4142136 1.4142135-6.3639611 6.3639611-1.4142135-1.4142136L14.5 22.863961l1.4142136-1.4142135L20 25.5355339V9.5h2v15.763456z" id="arrow" fill="#FFF" transform="matrix(0 -1 1 0 1.5 40.227922)"/></g></g></svg>';
    }
}

/**
 * Get the attributed authors
 */
function get_attributed_authors( $post_id ) {

  $author_data = get_field('attributed_author_entries_2way', $post_id);

  // bail early if no authors
  // and if we are not on the portrait and oil page
  if ( empty($author_data) && get_post_type($post_id) !== 'page' ) {
    return false;
  }

  if ( get_page_template_slug($post_id) === 'template-portrait-in-oil.php' ) {
    $author_override = get_field('entry_author', $post_id);
    $author_data = [$author_override];
  }

  // build authors array with display names
  foreach ( $author_data as $key => $author ) {
    // format first author lastname, firstname
    if ( $key === 0 ) {
      $parser = new FullNameParser();
      $author_title = is_object($author) ? $author->post_title : $author;
      $author_parsed = $parser->parse_name($author_title);
      $fname = isset($author_parsed['fname']) ? $author_parsed['fname'] : null;
      $initials = isset($author_parsed['initials']) ? ' '.$author_parsed['initials'] : null;
      $lname = isset($author_parsed['lname']) ? $author_parsed['lname'] : null;
      $suffix = isset($author_parsed['suffix']) && !empty($author_parsed['suffix']) ? ', '.$author_parsed['suffix'] : null;
      $authors[] = trim("{$lname}, {$fname}{$initials}{$suffix}");
    } else {
      $authors[] = $author->post_title;
    }

  }

  // handle display of multiple authors
  if ( count($authors) > 2 ) {
    $last_author = array_pop($authors);
    $last_author = rtrim($last_author, '.');
    $authors = implode(', ', $authors) . ', and ' . $last_author;
  }
  elseif ( count($authors) == 2 ) {
    $authors = implode(' and ', $authors);
    $authors = rtrim($authors, '.');
  }
  else {
    $authors = implode(', ', $authors);
    $authors = rtrim($authors, '.');
  }

  // return
  return $authors;

}


/**
 * Get the "How to Cite" text.
 * Also used for PDFs.
 */
function how_to_cite() {

  global $post;
  $namespace = uniqid();
  $group = helpers\get_the_group($post->ID);

  if ( $group ) {
    $post_id = $group->ID;
  } else {
    $post_id = $post->ID;
  }

  if ( $author_override = get_field('how_to_cite_authors_override') ) {
    $authors = $author_override;
  } else {
    $authors = get_attributed_authors($post_id);
  }

  // bail early if there are no authors set
  if ( !$authors ) {
    return;
  }


  if ( get_field('citation_title_override') ) {
    $post_title = get_field('citation_title_override');
  } else {
    $post_title = get_the_title($post_id);
  }
  $post_type = get_post_type($post_id);
  $post_type = $post_type === 'group' ? 'artwork' : $post_type;
  $revised_text = get_field('revised_text', $post_id);
  $revised_year = get_field('revised_year', $post_id);
  $revised = $revised_text ? 'Revised by ' . $revised_text . ' ' . '(' . $revised_year . '). ' : '';
  $unformatted_year = get_field('entry_year', $post_id);
  $entry_year = $unformatted_year ? ' (' . $unformatted_year . ').' : '';

  if ($post_type === 'artist') {
      $type_label = 'biography';
  } elseif ($post_type === 'artwork') {
    $type_label = 'entry';
  } else {
    $type_label = $post_type;
  }
  $archive_link = "<a href='/archive/?tab={$post_type}#{$post->ID}' target='_blank'>here</a>";
  $pg_template = get_post_meta( $post_id, '_wp_page_template', true );
  $editor = 'Arthur K. Wheelock Jr. and Lara Yeager-Crasselt';
  $year = date('Y');
  //$archive_url = 'https://www.theleidencollection.com/archive/';
  $permalink = get_permalink();
  $archive_url = str_replace( home_url(), "https://theleidencollection.com", $permalink );
  if ( !PDF_Functions\doing_pdf_gen()) {
    $footerClass = 'cite-footer';

    $cite_footer = "A PDF of every version of this {$type_label} is available in this Online Catalogue's Archive, and the Archive is managed by a permanent URL. New versions are added only when a substantive change to the narrative occurs. Click {$archive_link} to see the archived version(s) of this {$type_label}.";
  } else {
    $footerClass = '';
    $cite_footer = "A PDF of every version of this {$type_label} is available in this Online Catalogue's Archive, and the Archive is managed by a permanent URL. New versions are added only when a substantive change to the narrative occurs.";
  }

  if ( !PDF_Functions\doing_archive_pdf_gen()) {
    $today = date('F d, Y');
  $accessed_date = " (accessed {$today})";
  } else {
    $today = date('F Y');
    $accessed_date = " (archived {$today})";
  }

  $plus_icon = icons('plus-circle-sm', 24, 24);
  $minus_icon = icons('minus-circle-sm', 24, 24);
  $heading = __('How to cite', 'sage');
  $heading_mkup = "<div><strong>{$heading}</strong></div>";

  // if doing PDF gen, just output the heading text.
  // otherwise, output a collapse for mobile button.
  if ( !PDF_Functions\doing_pdf_gen() ) {
    $heading_mkup = "<button class=\"btn btn-collapse collapsed btn-block btn--how-to-cite\" data-toggle=\"collapse\" data-target=\"#howToCite{$namespace}\" aria-expanded=\"false\" aria-controls=\"howToCite{$namespace}\">{$heading}
      <div class=\"closed-text\">
        <span class=\"sr-only\">Expand</span>
        <span aria-hidden=\"true\">{$plus_icon}</span>
      </div>
      <div class=\"open-text\">
        <span class=\"sr-only\">Collapse</span>
        <span aria-hidden=\"true\">{$minus_icon}</span>
      </div>
    </button>
    <p class=\"hidden-xs how-to-cite-title\">{$heading}</p>";
  }

  // final output
  echo "<div class=\"how-to-cite\">
    {$heading_mkup}
    <div class=\"collapse-for-small\" id=\"howToCite{$namespace}\">
      <div class=\"pa3 pa0-ns\">";

        if ( $pg_template === 'template-portrait-in-oil.php' ) {

          echo "<p>{$authors}.{$revised} <em>{$post_title}</em>. The Leiden Collection.</p>
          <p>https://theleidencollection.com/a-portrait-in-oil/{$accessed_date}.</p>";

        } else {

          echo "<p>{$authors}. “{$post_title}”{$entry_year} {$revised}In <em>The Leiden Collection Catalogue</em>, 3rd ed. Edited by {$editor}. New York, 2020–23. {$archive_url}{$accessed_date}.</p>";

          echo "<div class='{$footerClass}'><p style='margin-bottom:0px;'>{$cite_footer}</p></div>";

      }

  echo "</div>
    </div>
  </div>";

}

/**
 * Custom Gravity Forms AJAX spinner.
 */
function spinner_url($src) {
  return  'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';
}
add_filter('gform_ajax_spinner_url', __NAMESPACE__ . '\\spinner_url', 10, 2);

/**
 * Return SVG filter for "duo-tone" effect.
 *
 * @source https://tympanus.net/codrops/2019/02/05/svg-filter-effects-duotone-images-with-fecomponenttransfer/
 *
 * @return string
 */
function get_color_matrix_svg() {
  return '<svg xmlns="http://www.w3.org/2000/svg" style="height:0px;">
    <filter id="duotone_purple">
      <feColorMatrix
        type="matrix"
        result="grayscale"
        values="1 0 0 0 0
                1 0 0 0 0
                1 0 0 0 0
                0 0 0 1 0">
      </feColorMatrix>
      <feComponentTransfer color-interpolation-filters="sRGB" result="duotone">
        <feFuncR type="table" tableValues="0.105882352941176 0.407843137254902"></feFuncR>
        <feFuncG type="table" tableValues="0.12156862745098 0.423529411764706"></feFuncG>
        <feFuncB type="table" tableValues="0.219607843137255 0.52156862745098"></feFuncB>
        <feFuncA type="table" tableValues="0 1"></feFuncA>
      </feComponentTransfer>
    </filter>
  </svg>';
}


/**
 * Undocumented function
 *
 * @param int $count
 *
 * @return string
 */
function get_related_essays_count_str($count) {
  if ($count > 1) {
    return $str = sprintf(__('View the %s essays related to this theme', 'sage'), $count);
  }

  return __('View the essay related to this theme', 'sage');
}
