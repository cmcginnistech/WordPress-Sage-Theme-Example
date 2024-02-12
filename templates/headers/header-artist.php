<?php
use Roots\Sage\Extras;
?>

<header class="single-artist-header">
  <div class="container">
    <div class="row">

      <?php if ( has_post_thumbnail() ) : ?>
        <div class="cs6 hidden-xs">
          <?php the_post_thumbnail('medium_large', ['class' => 'pr5']); ?>
        </div>
      <?php endif; ?>

      <div class="cs6">
        <h1 class="size-h1 heading-reset"><?php the_title(); ?></h1>

        <div class="size-h4 color-stormy text-italic serif">
          <?php
          $dates =  helpers\get_artist_display_dates($post->ID);
          if ( $dates ) {
            echo "({$dates['birth']} – {$dates['death']})";
          } ?>
        </div>

        <?php if ( has_post_thumbnail() ) : ?>
          <div class="visible-xs-block mt4">
            <?php the_post_thumbnail('medium_large'); ?>
          </div>
        <?php endif; ?>

        <?php
        /*
        * Works in the collection.
        * Do an additional query here so we don't display draft posts.
        */
        $collection_link = esc_url( '/collection/?c_artist='.$post->ID );

        if ($connected_artworks = get_field('artwork_to_artist_2way')){
          $artwork_ids = wp_list_pluck( $connected_artworks, 'ID' );
          $artworks = get_posts([
            'post_type'      => 'artwork',
            'post__in'       => $artwork_ids,
            'posts_per_page' => -1
          ]);
        };

        if (!empty($artworks)) :
         $artwork_count = count($artworks);
        ?>
          <a href="<?= $collection_link; ?>" target="_blank" class="btn btn--work-in-collection btn-outline btn-caret-right visible-xs"><?php echo $artwork_count > 1 ? $artwork_count .' '. __('works in the Collection', 'sage') : $artwork_count .' '. __('work in the Collection', 'sage'); ?></a>
          <div class="works-in-collection hidden-xs">
            <div class="wic-label mb2">
              <strong class="upper">
                <?= __('Works in the collection', 'sage'); ?>
              </strong>
              <a href="<?= $collection_link; ?>" target="_blank" class="serif text-italic dib ml3">
                <?= __('(view all)', 'sage'); ?>
              </a>
            </div>
            <ul class="wic-inline-grid">

              <?php
              foreach ( $artworks as $key => $artwork ) :
                if ( $key < 4 ) : ?>
                  <li>
                    <a href="<?= get_the_permalink($artwork->ID); ?>" class="db overlay">
                      <span class="sr-only"><?= $artwork->post_title; ?></span>
                      <?= get_the_post_thumbnail($artwork->ID, 'thumb-xs'); ?>
                    </a>
                  </li>
                <?php else : ?>
                  <li class="view-all">
                    <a href="<?= $collection_link; ?>" target="_blank">
                      <span>+ <?= (count($artworks) - 4); ?> Others</span>
                    </a>
                  </li>
                <?php break;
                endif;
              endforeach; ?>

            </ul>
          </div>
        <?php endif; ?>

      </div>

      <div class="cs6 pt3-ns">
        <?php get_template_part('templates/partials/post-utility-buttons'); ?>
        <?php Extras\how_to_cite(); ?>
      </div>

    </div>
  </div>
</header>

<?php if(have_rows('components')){ ?>
  <div class="container mt4">
    <div class="row">
      <div class="cl2 cm1"></div>
      <div class="cl8 cm10">
        <h2 class="artist-entry-heading"><?php _e('Biography', 'sage'); ?></h2>
      </div>
    </div>
  </div>
<?php };?>

