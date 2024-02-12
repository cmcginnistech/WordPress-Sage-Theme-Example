<?php use Roots\Sage\Extras; ?>

<div class="gen-wrapper container">
  <div class="gen-header" >
    <table class="two-column" nobr="true" style="page-break-inside:avoid;">
        <tr>
            <td width="45%">
              <div><h3 class="artist-title"><?php the_title(); ?></h3></div>
            </td>
            <td width="5%">&nbsp;</td>
            <td width="5%" class="border-left">&nbsp;</td>
            <td width="45%" align="right">
              <?php
              $dates = helpers\get_artist_display_dates($post->ID);
              echo "({$dates['birth']} – {$dates['death']})";
              ?>
            </td>
        </tr>
        <tr><td>&nbsp;</td></tr>
    </table>

    <table nobr="true" style="page-break-inside:avoid;">
        <tr><td><hr></td></tr>
        <tr><td><?php Extras\how_to_cite(); ?></td></tr>
        <tr><td><hr></td></tr>
    </table>
  </div>

</div>