<?php

namespace Roots\Sage\PDF_Generate;

use MYPDF;
use ConcatPDF;
use Roots\Sage\Extras;
use Roots\Sage\PDF_Functions;

/**
 * Default vtags for PDF docs.
 */
function get_default_vtags()
{
  return array(
    'div' => array(
      0 => array('h' => 0, 'n' => 0),
      1 => array('h' => 0, 'n' => 0)
    ),
    'h1' => array(
      0 => array('h' => 0, 'n' => 0),
      1 => array('h' => 0.5, 'n' => 1)
    ),
    'h3' => array(
      0 => array('h' => 0, 'n' => 0),
      1 => array('h' => 0, 'n' => 0)
    ),
    'table' => array(
      0 => array('h' => 0, 'n' => 0),
      1 => array('h' => 0, 'n' => 0)
    ),
    'ul' => array(
      0 => array('h' => 0, 'n' => 0),
      1 => array('h' => 0, 'n' => 0)
    ),
    'a' => array(
      0 => array('h' => 0, 'n' => 0),
      1 => array('h' => 0, 'n' => 0)
    ),
    'p' => array(
      0 => array('h' => 0.3, 'n' => 1),
      1 => array('h' => 1.5, 'n' => 1)
    )
  );
}

/**
 * Generate a PDF document and save to server.
 *
 * @param int | $post_id
 * @param bool | $make_archive (is this an archive PDF?)
 * @param bool | $header_only (only generate first page of PDF)
 * @global $post
 *
 */
function generate_pdf($post_id, $make_archive = false, $header_only = false)
{

  global $post;
  $post = get_post($post_id);
  setup_postdata($post);

  // supress errors for parsing
  libxml_use_internal_errors(true);

  // create new PDF document
  if (!$make_archive || $header_only) {
    $pdf = new ConcatPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false, false);
  } else {
    $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false, false);
  }

  //set doc info
  $pdf->SetCreator(PDF_CREATOR);
  $pdf->SetAuthor('The Leiden Collection');
  $pdf->SetTitle('The Leiden Collection');
  $pdf->SetSubject(get_the_title());

  //set image scale factor
  $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

  // set JPEG quality
  $pdf->setJPEGQuality(80);

  //other settings
  $pdf->SetMargins(PDF_MARGIN_LEFT, 30, PDF_MARGIN_RIGHT);
  $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
  $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
  $pdf->SetCellPadding(0);
  $pdf->SetAutoPageBreak(TRUE, 15);
  $pdf->SetListIndentWidth(7);
  $pdf->SetFont('freeserif', '', 12, '', false);
  $pdf->setFontStretching(95);
  $pdf->setFontSpacing(0);

  //vertical dimensions
  $pdf->setHtmlVSpace(get_default_vtags());

  $pdf->AddPage();

  // base goods
  $loc = get_stylesheet_directory_uri();
  $title = get_the_title();
  $post_type = $post->post_type;
  $filename_prefix = '';
  $title_underscored = Extras\classify($title);
  $baseurl = get_site_url(null, '/');
  $author_lastname = get_field('author_lastname');
  $entry_author = get_field('entry_author');
  $author_selection = get_field('attributed_author_entries_2way');


  // for artwork: add inventory number to filename
  if ($post_type === 'artwork') {
    $filename_prefix = get_field('inventory_number') . '_';
  }

  // for essay: add author name to filename
  if ($post_type === 'essay') {
    if ($author_lastname) {
      $author = $author_lastname;
    } elseif ($entry_author) {
      $author = $entry_author;
    } elseif($author_selection) {
      $author = $author_selection;
    }

    $author = str_replace(' ', '_', $author);
    $filename_prefix = strtolower($author) . '_';
  }

  // for portrait and oil page, reset post type to essay
  $is_portrait_in_oil = false;
  if ($post_type === 'page') {
    $template = get_post_meta($post->ID, '_wp_page_template', true);
    if ($template === 'template-portrait-in-oil.php') {
      $post_type = 'essay';
      $filename_prefix = 'the-lieden-collection-';
      $is_portrait_in_oil = true;
    }
  }

  /*
   * add styles first
   */
  $styles = '<style>';
  ob_start();
  echo file_get_contents(STYLESHEETPATH . '/lib/pdf/css/print.css');
  $styles .= ob_get_clean();
  $styles .= '</style>';

  /*
   * the header
   */
  if ($make_archive || $header_only) {
    ob_start();
    include(STYLESHEETPATH . "/lib/pdf/templates/header-{$post_type}.php");
    $the_header = ob_get_clean();
    $pdf->writeHTML($styles . $the_header, true, false, false, false, '');
  }

  if ($header_only) {
    $filepath = "pdfs/temp/_header_{$title_underscored}.pdf";
    $pdf->Output(ABSPATH . $filepath, 'F');
    wp_reset_postdata();
    return 'success';
  }

  /*
   * the content
   */
  $content_template = $is_portrait_in_oil ? 'portrait-in-oil' : $post_type;
  ob_start();
  include(STYLESHEETPATH . "/lib/pdf/templates/content-{$content_template}.php");
  $main_entry = ob_get_clean();
  $pdf->writeHTML($styles . $main_entry, true, false, false, false, '');

  /*
   * add a page break before outputing the endnotes
   */
  if (!$is_portrait_in_oil) {
    $pdf->AddPage();
  }

  /*
   * the endnotes
   */
  ob_start();
  include(STYLESHEETPATH . "/lib/pdf/templates/endnotes.php");
  $the_endnotes = ob_get_clean();
  $pdf->writeHTML($styles . $the_endnotes, true, false, false, false, '');

  /*
   * the appendix
   */
  ob_start();
  include(STYLESHEETPATH . "/lib/pdf/templates/appendix.php");
  $the_appendix = ob_get_clean();
  $pdf->writeHTML($styles . $the_appendix, true, false, false, false, '');

  /*
   * the footer
   */
  if (file_exists(STYLESHEETPATH . "/lib/pdf/templates/footer-{$post_type}.php")) {
    ob_start();
    include(STYLESHEETPATH . "/lib/pdf/templates/footer-{$post_type}.php");
    $the_footer = ob_get_clean();
    $pdf->writeHTML($styles . $the_footer, true, false, false, false, '');
  }

  /*
   * Either make an archive or save to post meta.
   * Make sure to use $post->ID instead of $post_id here b/c $post_id
   * has been changed to the post ID of the group by this point.
   */
  if ($make_archive) {

    // the path to the file (note: we will append the version # and extension later)
    $pdfpath = "archives/{$post_type}/";

    $data = [
      'post_id' => $post->ID,
      'post_title' => $title,
      'path' => $pdfpath,
      'filename' => $filename_prefix . $title_underscored
    ];

    $archive = PDF_Functions\create_archive_post($data);
    $archive_title = $archive['title'];
    $archive_filename = $archive['filename'];
    $filepath = "{$pdfpath}{$archive_filename}.pdf";
    $message = "{$archive_title} was generated successfully!";

  } else {

    $filepath = "pdfs/{$post_type}s/{$filename_prefix}{$title_underscored}.pdf";
    $message = 'Printable PDF was generated successfully!';

  }

  // output the PDF to the directory
  $pdf->Output(ABSPATH . $filepath, 'F');

  /*
   * Cleanup for non-archive PDFs.
   * Update post meta and remove old file.
   * Make sure to use $post->ID instead of $post_id here b/c $post_id
   * has been changed to the post ID of the group by this point.
   */
  if (!$make_archive) {
    $old_file_url = get_post_meta($post->ID, 'downloadable_pdf', true);
    update_post_meta($post->ID, 'downloadable_pdf', $baseurl . $filepath);
    if ($old_file_url !== $baseurl . $filepath) {
      PDF_Functions\replace_file($old_file_url, ABSPATH . $filepath);
    }
  }

  // finish up
  wp_reset_postdata();

  if (defined('DOING_CRON')) {
    return $message;
  }

  die($message);

}


/**
 * Build a PDF document for printing.
 * Looks for a header file located in /pdfs/temp.
 * Combines a header pdf which contains the dynamica "access date"
 * and the content which is saved to server on post save in admin.
 *
 * @param int | $post_id
 * @return DOMDocument
 */
function build_pdf_for_print($post_id)
{

//  $title = get_the_title($post_id);
  $title = get_the_title($post_id);
  $title_underscored = Extras\classify($title);
  $post_type = get_post_type($post_id);
  $author_lastname = get_field('author_lastname');
  $entry_author = get_field('entry_author');
  $author_selection = get_field('attributed_author_entries_2way');
  $filename_prefix = '';

  // for artwork: add inventory number to filename
  if ($post_type === 'artwork') {
    $filename_prefix = get_field('inventory_number') . '_';
  }

  // for essay: add author name to filename
  if ($post_type === 'essay') {
    if ($author_lastname) {
      $author = $author_lastname;
    } elseif ($entry_author) {
      $author = $entry_author;
    } elseif($author_selection) {
      $author = $author_selection;
    }

    $author = str_replace(' ', '_', $author);
    $filename_prefix = strtolower($author) . '_';
  }

  // for portrait and oil page, reset post type to essay
  $is_portrait_in_oil = false;
  if ($post_type === 'page') {
    $template = get_post_meta($post_id, '_wp_page_template', true);
    if ($template === 'template-portrait-in-oil.php') {
      $post_type = 'essay';
      $filename_prefix = 'the-lieden-collection-';
      $is_portrait_in_oil = true;
    }
  }

  $pdf_header_file_path = ABSPATH . "pdfs/temp/_header_{$title_underscored}.pdf";
  $pdf_content_file_path = ABSPATH . "pdfs/{$post_type}s/{$filename_prefix}{$title_underscored}.pdf";

  $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false, false);

  //set doc info
  $pdf->SetCreator(PDF_CREATOR);
  $pdf->SetAuthor('The Leiden Collection');
  $pdf->SetTitle('The Leiden Collection');
  $pdf->SetSubject(get_the_title());

  // images
  $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
  $pdf->setJPEGQuality(80);

  // other settings
  $pdf->SetMargins(PDF_MARGIN_LEFT, 30, PDF_MARGIN_RIGHT);
  $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
  $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
  $pdf->SetCellPadding(0);
  $pdf->SetAutoPageBreak(TRUE, 15);
  $pdf->SetListIndentWidth(7);
  $pdf->SetFont('freeserif', '', 12, '', false);
  $pdf->setFontStretching(95);
  $pdf->setFontSpacing(0);
  $pdf->setHtmlVSpace(get_default_vtags());

  // concat files
  $pdf->setFiles([$pdf_header_file_path, $pdf_content_file_path]);
  $pdf->concat();
  $pdf->Output("{$filename_prefix}{$title_underscored}.pdf", 'I');

  // delete temp header file from server
  unlink($pdf_header_file_path);

}
