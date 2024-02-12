<?php

use Roots\Sage\Extras;

$group = helpers\get_the_group($post->ID);

// if part of a group, pull the group comp figs
if ( $group ) {
  $post_id = $group->ID;
} else {
  $post_id = $post->ID;
}
?>

<?php if ( have_rows('comp_fig_groups', $post_id) ) : ?>

<aside class="comp-fig-sidebar">

  <strong class="db mb2"><?php _e('Comparative Figures', 'sage'); ?></strong>
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
      $main_fig_image_url = get_the_post_thumbnail_url( $main_fig_artwork, 'large' );
    } else {
      $main_fig_image_id = $main_fig_image['id'];
      $main_fig_image_url = $main_fig_image['sizes']['large'];
    }
    ?>

    <?php
    /*
     * The main figure for comparison
     */
    $comp_fig_arr = get_sub_field('comp_figs');
    $num_figs = $comp_fig_arr ? count($comp_fig_arr) : 0;
    $open_in_lightbox = $num_figs === 0 ? true : false;
    $open_in_bs_modal = $num_figs > 0 ? true : false;
    ?>
    <figure class="comp-fig">
      <?php if ( $open_in_bs_modal ) : ?>
        <a href="#"
          data-toggle="modal"
          data-target="#compFigModal-<?= $i; ?>"
          >
          <?= helpers\get_img_markup($main_fig_image_id, 'medium'); ?>
        </a>
      <?php elseif ( $open_in_lightbox ) : ?>
        <a href="<?= esc_url($main_fig_image_url); ?>"
          class="modaal"
          data-modaal-type="image"
          data-modaal-animation="fade"
          data-modaal-overlay-opacity="0.9"
          data-modaal-desc="<?= strip_tags($main_fig_caption, '<i>'); ?>"
          data-fig-ref="#fig-<?= $i; ?>-content"
          >
          <?= helpers\get_img_markup($main_fig_image_id, 'medium'); ?>
        </a>
      <?php endif; ?>
      <figcaption class="comp-fig__caption">
        <strong>Fig <?= ($i+1); ?>.</strong>
        <?= $main_fig_caption; ?>
      </figcaption>
    </figure>

    <?php
    /*
     * If there are any comp figs, clicking a comp fig
     * in the sidebar will open a bootstrap modal.
     */
    if ( $open_in_bs_modal ) : ?>
    <div class="modal fade" id="compFigModal-<?= $i; ?>" tabindex="-1" role="dialog" aria-labelledby="compFigModal-<?= $i; ?>-title">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close (press escape to close)"><span class="sr-only">Close</span></button>
            <?php if ( $group_title = get_sub_field('group_title') ) : ?>
              <h4 class="modal-title size-root-2x m0" id="compFigModal-<?= $i; ?>-title"><?php the_sub_field('group_title'); ?></h4>
            <?php endif; ?>
          </div>

          <div class="modal-body" id="fig-<?= $i; ?>-content">
            <?php if ( $modal_desc = get_sub_field('lightbox_text') ) : ?>
              <div class="modal-description">
                <p><?= $modal_desc; ?></p>
              </div>
            <?php endif; ?>
            <div class="row">

              <?php
              $col_class = ($num_figs==2) ? 'cs4' : 'cs6';
              $modal_img_size = ($num_figs==2) ? 'medium' : 'medium_large';

              /**
               * Show the main figure again
               */
              ?>
              <div class="<?= esc_attr($col_class); ?>">
                <div class="mb3">
                  <a href="<?= esc_url($main_fig_image_url); ?>"
                    class="modaal overlay dib rel"
                    data-modaal-type="image"
                    data-modaal-animation="fade"
                    data-modaal-overlay-opacity="0.95"
                    data-modaal-desc="<?= strip_tags($main_fig_caption,'<i>'); ?>"
                    >
                    <?= helpers\get_img_markup($main_fig_image_id, $modal_img_size); ?>
                    <div class="overlay-content overlay-content--center tc">
                      <span class="plus-icon" aria-hidden="true">
                        <?= Extras\icons('plus', 20, 20); ?>
                      </span>
                    </div>
                  </a>
                </div>
                <div class="modal-fig-caption">
                  <?= $main_fig_caption; ?>
                </div>
              </div>

              <?php
              /**
               * The images for comparison
               */
              while( have_rows('comp_figs') ) : the_row();

                $type = get_sub_field('type');

                if ( $type === 'current' ) {
                  $image_tag = get_the_post_thumbnail( get_the_ID(), $modal_img_size );
                } elseif ( $type === 'artwork' ) {
                  $art = get_sub_field('artwork');
                  $image_tag = get_the_post_thumbnail( $art->ID, $modal_img_size );
                  $image_lg_url = get_the_post_thumbnail_url( $art->ID, 'large' );
                } else {
                  $img = get_sub_field('image');
                  $image_tag = "<img src={$img['sizes'][$modal_img_size]} alt=\"{$img['alt']}\" />";
                  $image_lg_url = $img['sizes']['large'];
                }
                ?>

                <div class="<?= esc_attr($col_class); ?>">
                  <div class="mb3">
                    <?php
                    // if comparing with current artwork,
                    // open the viewer page.
                    if ( $type === 'current' && get_post_type() !== 'group' ) : ?>
                      <a href="/viewer/<?= esc_attr($post->post_name); ?>" class="overlay dib rel" target="_blank">
                        <?= $image_tag; ?>
                        <div class="overlay-content overlay-content--center tc">
                          <span class="plus-icon" aria-hidden="true">
                            <?= Extras\icons('plus', 20, 20); ?>
                          </span>
                        </div>
                      </a>
                    <?php
                    // for everything other than groups,
                    // open a modaal lightbox window.
                    elseif ( (get_post_type() === 'group' && $type !== 'current') || get_post_type() !== 'group' ) : ?>
                      <a href="<?= esc_url($image_lg_url); ?>"
                        class="modaal overlay dib rel"
                        data-modaal-type="image"
                        data-modaal-animation="fade"
                        data-modaal-overlay-opacity="0.95"
                        data-modaal-desc="<?= strip_tags(the_sub_field('text'),'<i>'); ?>"
                        >
                        <?= $image_tag; ?>
                        <div class="overlay-content overlay-content--center tc">
                          <span class="plus-icon" aria-hidden="true">
                            <?= Extras\icons('plus', 20, 20); ?>
                          </span>
                        </div>
                      </a>
                    <?php
                    // otherwise, just output the plain image, no link.
                    else : ?>
                      <?= $image_tag; ?>
                    <?php endif; ?>
                  </div>
                  <div class="modal-fig-caption">
                    <?php the_sub_field('text'); ?>
                  </div>
                </div>

              <?php endwhile; ?>
            </div><!-- .row -->
          </div><!-- .modal-body -->
        </div><!-- .modal-content -->
      </div><!-- .modal-dialog -->
    </div><!-- .modal -->
    <?php endif; ?>

  <?php $i++;
  endwhile; ?>

</aside>

<?php endif; ?>