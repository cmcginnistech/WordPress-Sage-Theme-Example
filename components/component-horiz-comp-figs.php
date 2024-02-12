<div class="row">
  <div class="cl2"></div>
  <div class="cl8">
    <div class="row">
      
      <?php
      /**
       * Figs are a comma separated text field.
       * Make sure we only show a max of 2 with array_slice().
       */
      $figs = explode(',', get_sub_field('horiz_comp_figs') );
      $figs = array_slice($figs, 0, 2);

      if ( !empty($figs) ) :
        foreach ( $figs as $fig ) :

          // trim any whitespace
          $fig = trim($fig);

          // make sure we have an absolute integer
          $fig_index = absint($fig);
          $fig_index = $fig_index - 1;

          // check custom fields
          $main_fig_type = get_post_meta( $post->ID, "comp_fig_groups_{$fig_index}_main_figure_type", true );
          $main_fig_image = get_post_meta( $post->ID, "comp_fig_groups_{$fig_index}_main_figure_image", true );
          $main_fig_artwork_id = get_post_meta( $post->ID, "comp_fig_groups_{$fig_index}_main_figure_artwork", true );
          $caption = get_post_meta( $post->ID, "comp_fig_groups_{$fig_index}_main_caption_text", true );
          
          // get the image markup
          if ( $main_fig_type == 'artwork' ) {
            $main_fig_image_tag = get_the_post_thumbnail( $main_fig_artwork_id, 'medium' );
          } else {
            $main_fig_image_tag = helpers\get_img_markup( $main_fig_image, 'medium' );
          }
          
          ?>

          <figure class="comp-fig comp-fig--horiz">
            <a href="#" data-toggle="modal" data-target="#compFigModal-<?= $fig_index; ?>">
              <?= $main_fig_image_tag; ?>
            </a>
            <figcaption class="comp-fig__caption">
              <strong>Fig <?= $fig_index + 1; ?>. </strong>
              <?= $caption; ?>
            </figcaption>
          </figure>

          <?php
        endforeach;
      endif;
      ?>

    </div>
  </div>
</div>