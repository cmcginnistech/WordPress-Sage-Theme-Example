<?php
/*
Content is split up into sections.
Use get_post_meta() as it is 75% faster when on server.
*/
$cfc = 0; // comp fig count
$num_sections = get_post_meta($post->ID, 'memoir_sections', true);

for ( $i=0; $i < $num_sections; $i++ ) : ?>

<table class="main-entry">
  <tr>
    <td width="70%">
      <?=$i?>
      <?php
      $content = get_post_meta($post->ID, "memoir_sections_{$i}_content", true);
      var_dump($content);
      echo apply_filters('the_content', $content);
      ?>
    </td>

    <td width="4%"></td>
    <td width="26%">
      <p>&nbsp;</p>
      <?php
      /*
      The comp figs.
      These should be grouped with the sections. The end_comp_fig field
      denotes what figure to stop on in each section.
      */
      $end_fig = get_post_meta($post->ID, "memoir_sections_{$i}_end_comp_fig", true);

      if ( $end_fig && $end_fig !== 0 ) : ?>

      <table cellpadding="0" cellspacing="0">

        <?php while ( $cfc < $end_fig ) :

          $main_fig_type = get_post_meta($post->ID, "comp_fig_groups_{$cfc}_main_figure_type", true);
          $main_fig_image = get_post_meta($post->ID, "comp_fig_groups_{$cfc}_main_figure_image", true);
          $main_fig_artwork = get_post_meta($post->ID, "comp_fig_groups_{$cfc}_main_figure_artwork", true);
          $main_fig_caption = get_post_meta($post->ID, "comp_fig_groups_{$cfc}_main_caption_text", true);

          if ( $main_fig_type == 'artwork' ) {
            $main_fig_image_id = get_post_thumbnail_id( $main_fig_artwork );
          } else {
            $main_fig_image_id = $main_fig_image;
          }
          ?>

          <tr>
            <td>
              <div><?= helpers\get_img_markup($main_fig_image_id, 'medium'); ?></div>
              <div class="caption"><strong>Fig <?= ($cfc+1); ?>. </strong><?= $main_fig_caption; ?></div>
            </td>
          </tr>

          <tr><td height="20px"></td></tr>

        <?php $cfc++; endwhile; ?>

      </table>
      <?php endif; ?>

    </td>

  </tr>
</table>

<?php endfor; ?>
