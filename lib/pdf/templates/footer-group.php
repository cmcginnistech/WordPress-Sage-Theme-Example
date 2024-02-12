<?php
$artworks_header = '<div class="gen-wrapper container">
  <table>
    <tr>
      <td width="5%">&nbsp;</td>
      <td width="90%">
        <br><br>
        <div class="tab-title">Artworks in this Group</div>
      </td>
    </tr>
  </table>
</div>';

$pdf->writeHTML($styles . $artworks_header, true, false, false, false, '');

/*
 * Append all of the artworks in the group to the end.
 */
$group_id = $post->ID;
$artworks_in_group = get_field('artwork_to_group_2way', $group_id);

if ( !empty ( $artworks_in_group ) ) :
  foreach ( $artworks_in_group as $artwork ) :

    /*
     * reset the post to the current artwork
     */
    $post = $artwork;
    $id = $artwork->ID;

    /*
     * the header
     */
    ob_start();
    include(STYLESHEETPATH . "/lib/pdf/templates/header-artwork.php");
    $the_header = ob_get_clean();
    $pdf->writeHTML($styles . $the_header, true, false, false, false, '');

    /*
     * the footer
     */
    ob_start();
    include(STYLESHEETPATH . "/lib/pdf/templates/footer-artwork.php");
    $the_footer = ob_get_clean();
    $pdf->writeHTML($styles . $the_footer, true, false, false, false, '');

    /*
     * if this is not the last page, add a page break after each artwork.
     */
    if ( $pdf->getAliasNumPage() === $pdf->getAliasNbPages() ) {
      $pdf->AddPage();
    }

  endforeach;

  /*
   * Reset $post to the group post object so
   * we can still access in main script.
   */
  $post = get_post($group_id);

endif;
