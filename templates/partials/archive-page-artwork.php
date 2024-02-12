<?php
/**
 * Used on archive page template.
 * This template represents one tab panel.
 *
 * @param $tabs      | array (the other tabs)
 * @param $key       | integer (the tab number we are on)
 * @param $selectedTab  | gets selected tab from URL param
 * @param $post_type | string (tab name and post type)
 */

$artists = get_posts([
  'post_type'      => 'artist',
  'posts_per_page' => -1,
  'meta_key'       => 'artist_sort_name',
  'orderby'        => 'meta_value',
  'order'          => 'ASC',
  'meta_query'     => [
    'relation' => 'OR',
    [
      'key'     => 'hide_on_archive',
      'value'   => 1,
      'compare' => '!='
    ],
    [
      'key'     => 'hide_on_archive',
      'value'   => 1,
      'compare' => 'NOT EXISTS'
    ]
  ]
]);

?>

<div role="tabpanel" class="tab-pane pv5 <?php if($selectedTab  == 'artwork') echo 'active'; ?>" id="<?= $post_type; ?>">
  <?php
  // since we want to organize artwork by artist,
  // we start w/ a query for all artists
  foreach ($artists as $artist) :

    /**
     * Now, get artwork by this artist.
     */
    if ( get_field('attributed_artist_2way', $artist->ID)) {
      $isAttributedArtist = true;
      $artworks = get_field('attributed_artist_2way', $artist->ID);
    } else {
      $isAttributedArtist = false;
      $artworks = get_field('artwork_to_artist_2way', $artist->ID);
    }

    if ( !empty ( $artworks ) ) : ?>


    <article class="row">
      <div class="cs4" id="<?php echo $artist->ID;?>">
        <h2 class="size-h3-ns size-h3 mb4"><?= get_the_title($artist->ID); ?></h2>
      </div>

      <div class="cs8 archive-tab__column-right">
        <div class="row pl5-ns">
          <?php

          $artwork_ids = wp_list_pluck( $artworks, 'ID' );
          $artworks_ordered_by_title = get_posts([
            'post_type'      => 'artwork',
            'post__in'       => $artwork_ids,
            'posts_per_page' => -1,
            'orderby'        => 'title',
            'order'          => 'ASC'
          ]);

          foreach ( $artworks_ordered_by_title as $artwork ) :
            $isAttributeArtwork = get_field('attributed_artist_2way', $artwork->ID);
            if ($isAttributedArtist == $isAttributeArtwork) {
            $archives = Archives\get_pdf_archives( $artwork->ID );
              if ( $archives ) :
                ?>
                <div class="cs6 archive-tab__single-artwork">
                <a  id="<?= $artwork->ID; ?>"style="padding-top: 40px; margin-top: -40px; display:block;" name="<?= $artwork->ID; ?>"></a>

                  <h3 class="size-h4 size-h4-ns mb0 mb2-ns">
                    “<?= $artwork->post_title; ?>”
                    <small class="artwork-inventory"><?php the_field('inventory_number', $artwork->ID); ?></small>
                  </h3>
                  <?php
                  /**
                   * Get all PDF archives for this artwork.
                   * (must pass through $archives variable)
                   */
                  include(locate_template('templates/partials/pdf-archive-list.php'));
                  ?>
                </div>
              <?php endif; ?>
            <?php } ?>
          <?php endforeach; ?>
        </div>
      </div>
    </article>
  <?php endif; ?>
  <hr class="visible-xs-block" aria-hidden="true">
  <?php endforeach; ?>

</div>