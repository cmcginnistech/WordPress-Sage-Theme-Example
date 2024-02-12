<?php

namespace Shortcodes;

use Roots\Sage\PDF_Functions;
use helpers;

/**
 * Entry endnote
 */
function entry_endnote_callback( $atts ) {

  global $post;

  $defaults = ['num' => '0'];
  $a = shortcode_atts($defaults, $atts );
  $index = absint($a['num']) - 1;
  $group = helpers\get_the_group($post->ID);

  // if part of a group, pull the group comp figs
  if ( $group ) {
    $post_id = $group->ID;
  } else {
    $post_id = $post->ID;
  }

  // access post meta for ACF value so we don't have to deal w/ wpautop
  $field = "entry_endnotes_{$index}_endnote";
  $endnote = get_post_meta( $post_id, $field, true );

  if ( $endnote == '' || $endnote == null ) {
    return false;
  }

  // strip all line breaks as they will break the shortcode output
  $endnote = preg_replace( '/\r|\n/', '', $endnote );

  // return the html
  // don't use line breaks otherwise they will be output
  if ( PDF_Functions\doing_pdf_gen() ) {
    return '<sup class="endnote-num">['. ($index + 1) .']</sup>';
  }

  return '<sup class="endnote-num"><a role="button" class="endnote-toggle" data-toggle="inline-ref" data-src="#endnote-'. $index .'-content" href="#endnote-'. $index .'" aria-expanded="false" aria-controls="endnote-'. $index .'">'. ($index + 1) .'</a></sup><span id="endnote-'. $index .'" class="endnote" aria-expanded="false"><span class="inline-ref-content"></span></span>';

}
add_shortcode( 'end', __NAMESPACE__ .'\\entry_endnote_callback' );


function entry_endnote_callback_exhibitions( $atts ) {

  global $post;

  $defaults = ['num' => '0'];
  $a = shortcode_atts($defaults, $atts );
  $index = absint($a['num']) - 1;
  $group = helpers\get_the_group($post->ID);

  // if part of a group, pull the group comp figs
  if ( $group ) {
    $post_id = $group->ID;
  } else {
    $post_id = $post->ID;
  }

  // access post meta for ACF value so we don't have to deal w/ wpautop
  $field = "exhibition_history_endnotes_{$index}_remark";
  $endnote = get_post_meta( $post_id, $field, true );

  if ( $endnote == '' || $endnote == null ) {
    return false;
  }

  // strip all line breaks as they will break the shortcode output
  $endnote = preg_replace( '/\r|\n/', '', $endnote );

  // return the html
  // don't use line breaks otherwise they will be output
  if ( PDF_Functions\doing_pdf_gen() ) {
    return '<sup class="endnote-num">['. ($index + 1) .']</sup>';
  }

  return '<sup class="endnote-num"><a role="button" class="endnote-toggle" data-toggle="inline-ref" data-src="#exhibitionEndnotes-'. $index .'-content" href="#exhibitionEndnotes-'. $index .'" aria-expanded="false" aria-controls="exhibitionEndnotes-'. $index .'">'. ($index + 1) .'</a></sup><span id="exhibitionEndnotes-'. $index .'" class="endnote" aria-expanded="false"><span class="inline-ref-content"></span></span>';

}
add_shortcode( 'exh', __NAMESPACE__ .'\\entry_endnote_callback_exhibitions' );


/**
 * Comparative figures
 */
function comp_fig_callback( $atts ) {

  global $post;

  $defaults = ['num' => '0'];
  $a = shortcode_atts($defaults, $atts );
  $index = absint($a['num']) - 1;
  $uniqid = uniqid();

  // return the html
  // don't use line breaks otherwise they will be output
  return '<span class="inline-fig-ref">(<a role="button" class="fig-toggle" data-toggle="inline-ref" data-src="#fig-'. $index .'-content" href="#fig-'. $index .'-'. $uniqid .'" aria-expanded="false" aria-controls="fig-'. $index .'-'. $uniqid .'">fig '. ($index+1) .'</a>)</span><span id="fig-'. $index .'-'. $uniqid .'" aria-expanded="false" class="endnote"><span class="inline-ref-content"></span></span>';

}
add_shortcode( 'fig', __NAMESPACE__ .'\\comp_fig_callback' );

/**
 * Horizontal comparative figure
 * Same as normal, but just doesn't output the image
 */
function horiz_comp_fig_callback( $atts ) {

  global $post;

  $defaults = ['num' => '0'];
  $a = shortcode_atts($defaults, $atts );
  $index = absint($a['num']) - 1;
  $uniqid = uniqid();

  // return the html
  // don't use line breaks otherwise they will be output
  return '<span class="inline-fig-ref">(<a role="button" class="fig-toggle" data-toggle="inline-ref" data-src="#fig-'. $index .'-content" href="#fig-'. $index .'-'. $uniqid .'" aria-expanded="false" aria-controls="fig-'. $index .'-'. $uniqid .'">fig '. ($index+1) .'</a>)</span><span id="fig-'. $index .'-'. $uniqid .'" aria-expanded="false" class="endnote"><span class="inline-ref-content"></span></span>';

}
add_shortcode( 'hfig', __NAMESPACE__ .'\\horiz_comp_fig_callback' );

/**
 * Technical summary endnote
 */
function tech_endnote_callback( $atts ) {

  global $post;

  $defaults = ['num' => '0'];
  $a = shortcode_atts($defaults, $atts );
  $index = absint($a['num']) - 1;

  // access post meta for ACF value so we don't have to deal w/ wpautop
  $field = "tech_endnotes_{$index}_endnote";
  $endnote = get_post_meta( $post->ID, $field, true );

  if ( $endnote == '' || $endnote == null ) {
    return false;
  }

  // strip all line breaks as they will break the shortcode output
  $endnote = preg_replace( '/\r|\n/', '', $endnote );

  // return the html
  // don't use line breaks otherwise they will be output
  if ( PDF_Functions\doing_pdf_gen() ) {
    return '<sup class="endnote-num">['. ($index + 1) .']</sup>';
  }

  return '<sup><a role="button" data-toggle="inline-ref" data-src="#tech-endnote-'. $index .'-content" href="#tech-endnote-'. $index .'" aria-expanded="false" aria-controls="tech-endnote-'. $index .'">'. ($index + 1) .'</a></sup><span id="tech-endnote-'. $index .'" class="endnote" aria-expanded="false"><span class="inline-ref-content"></span></span>';

}
add_shortcode( 'tech', __NAMESPACE__ .'\\tech_endnote_callback' );

/**
 * Provenance remark endnote
 */
function prov_remark_callback( $atts ) {

  global $post;

  $defaults = ['num' => '0'];
  $a = shortcode_atts($defaults, $atts );
  $index = absint($a['num']) - 1;

  // access post meta for ACF value so we don't have to deal w/ wpautop
  $field = "provenance_remarks_{$index}_remark";
  $endnote = get_post_meta( $post->ID, $field, true );

  if ( $endnote == '' || $endnote == null ) {
    return false;
  }

  // strip all line breaks as they will break the shortcode output
  $endnote = preg_replace( '/\r|\n/', '', $endnote );

  // return the html
  // don't use line breaks otherwise they will be output
  if ( PDF_Functions\doing_pdf_gen() ) {
    return '<sup class="endnote-num">['. ($index + 1) .']</sup>';
  }

  return '<sup><a role="button" data-toggle="inline-ref" data-src="#prov-remark-'. $index .'-content" href="#prov-remark-'. $index .'" aria-expanded="false" aria-controls="prov-remark-'. $index .'">'. ($index + 1) .'</a></sup><span id="prov-remark-'. $index .'" class="endnote" aria-expanded="false"><span class="inline-ref-content"></span></span>';

}
add_shortcode( 'prov', __NAMESPACE__ .'\\prov_remark_callback' );

/**
 * Provenance remark endnote
 */
function ver_note_callback( $atts ) {

  global $post;

  $defaults = ['num' => '0'];
  $a = shortcode_atts($defaults, $atts );
  $index = absint($a['num']) - 1;

  // access post meta for ACF value so we don't have to deal w/ wpautop
  $field = "versions_notes_{$index}_version_note";
  $endnote = get_post_meta( $post->ID, $field, true );

  if ( $endnote == '' || $endnote == null ) {
    return false;
  }

  // strip all line breaks as they will break the shortcode output
  $endnote = preg_replace( '/\r|\n/', '', $endnote );

  // return the html
  // don't use line breaks otherwise they will be output
  if ( PDF_Functions\doing_pdf_gen() ) {
    return '<sup class="endnote-num">['. ($index + 1) .']</sup>';
  }

  return '<sup><a role="button" data-toggle="inline-ref" data-src="#ver-note-'. $index .'-content" href="#ver-note-'. $index .'" aria-expanded="false" aria-controls="ver-note-'. $index .'">'. ($index + 1) .'</a></sup><span id="ver-note-'. $index .'" class="endnote" aria-expanded="false"><span class="inline-ref-content"></span></span>';

}
add_shortcode( 'ver', __NAMESPACE__ .'\\ver_note_callback' );