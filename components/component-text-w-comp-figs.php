<div class="row">
  <div class="cs3 tr">
    <?php if( get_sub_field('heading_text') ) : ?>
      <h2><?php the_sub_field('heading_text'); ?></h2>
    <?php endif; ?>
  </div>
  <div class="cs9">

    <?php if ( have_rows('comp_fig_groups') ) : ?>
      <aside class="comp-figs alignright">
        <?php
        $i = 0;
        while( have_rows('comp_fig_groups') ) : the_row(); ?>

          <?php if( $i === 0 ) : ?>
            <a href="#" data-toggle="modal" data-target="#myModal">
              <?php helpers\acfimg( get_sub_field('comp_figs')[0]['comp_fig_image'], 'medium' ); ?>
              <?php the_sub_field('main_caption_text'); ?>
            </a>
          <?php endif; ?>

          <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-lg" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="myModalLabel">Modal title</h4>
                </div>
                <div class="modal-body">
                  <?php the_sub_field('lightbox_text'); ?>
                  <div class="row">
                    <?php
                    $num_figs = count(get_sub_field('comp_figs'));
                    $col_class = ($num_figs==3) ? 'cs4' : 'cs6';
                    ?>
                    <?php while( have_rows('comp_figs') ) : the_row(); ?>
                      
                      <div class="<?= esc_attr($col_class); ?>">
                        <div class="mb3">
                          <?php helpers\acfimg( get_sub_field('comp_fig_image'), 'medium' ); ?>
                        </div>
                        <?php the_sub_field('text'); ?>
                      </div>
                    
                    <?php endwhile; ?>
                  </div>
                </div>
              </div>
            </div>
          </div>

        <?php $i++; endwhile; ?>
      </aside>
    <?php endif; ?>

    <?php the_sub_field('text'); ?>

  </div>
</div>

