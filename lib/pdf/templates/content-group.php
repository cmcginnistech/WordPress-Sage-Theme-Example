<?php
use Roots\Sage\Extras;
?>

<table class="main-entry" nobr="true" style="page-break-inside:avoid;" cellpadding="0" cellspacing="0">
  <tr>
    <td width="70%">
      <?= Extras\componify(null, $post_id); ?>
      <?= helpers\get_the_entry_author(); ?>
    </td>
    <td width="4%"></td>
    <td width="26%">
      <p><strong>Comparative Figures</strong></p>
      <table cellpadding="0" cellspacing="0">

        <?php
        /*
        Make sure to pass $post_id to have_rows so we are pulling correct figs
        */
        $i = 0;
        while ( have_rows('comp_fig_groups', $post_id) ) : the_row();

          $main_fig_type = get_sub_field('main_figure_type');
          $main_fig_image = get_sub_field('main_figure_image');
          $main_fig_artwork = get_sub_field('main_figure_artwork');
          $main_fig_caption = get_sub_field('main_caption_text');

          if ( $main_fig_type == 'artwork' ) {
            $main_fig_image_id = get_post_thumbnail_id( $main_fig_artwork );
          } else {
            $main_fig_image_id = $main_fig_image['id'];
          }
          ?>

          <tr>
            <td>
              <div><?= helpers\get_img_markup($main_fig_image_id, 'medium'); ?></div>
              <div class="caption"><strong>Fig <?= ($i+1); ?>. </strong><?= $main_fig_caption; ?></div>
            </td>
          </tr>

          <tr><td height="20px"></td></tr>

        <?php $i++; endwhile; ?>

      </table>
    </td>
  </tr>
</table>