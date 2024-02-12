<?php

use Roots\Sage\Extras;

?>

<div class="container author-intro-container">
  <div class="row">
    <div class="cs8 cso4">
      <div class="pl5-ns ">
        <?php the_field('intro_text'); ?>
      </div>
    </div>
  </div>
</div>

<div class="single-author-content">
  <?php
  /**
   * Artwork
   */
  $artwork = get_posts([
    'post_type'      => 'artwork',
    'posts_per_page' => -1,
    'orderby'        => 'title',
    'order'          => 'ASC',
    'meta_query'     => [
      [
        'key'     => 'attributed_author_entries_2way',
        'value'   => '"' . get_the_ID() . '"',
        'compare' => 'LIKE'
      ]
    ]
  ]);
  ?>

  <?php if ( !empty($artwork) ) :

    $sorted_artists = [];

    foreach ( $artwork as $art ) {
      $artist =  get_field('artwork_to_artist_2way', $art->ID);
      $sort_name = get_field('artist_sort_name', $artist->ID);
      $sorted_artists[$sort_name][] = $art;
    }

    ksort($sorted_artists);
    ?>

  <div class="container">
    <div class="row row__border-bottom">
      <div class="cs4">
        <h2 class="hidden-xs size-root-2x w-90"><?= __('Contributed Artwork Entries', 'sage'); ?></h2>
      </div>
      <div class="cs8 ">
          <a class="btn btn-collapse visible-xs-flex collapsed" role="button" data-toggle="collapse" data-target="#jsArtwork" aria-expanded="false" aria-controls="jsArtwork">
            <h2 class="size-h3"><?= __('Contributed Artwork Entries', 'sage'); ?></h2>
            <div class="closed-text">
              <span class="sr-only"><?= __('Expand', 'sage'); ?></span>
              <span aria-hidden="true"><?= Extras\icons('plus-circle-sm', 24, 24); ?></span>
            </div>
            <div class="open-text">
              <span class="sr-only"><?= __('Collapse', 'sage'); ?></span>
              <span aria-hidden="true"><?= Extras\icons('minus-circle-sm', 24, 24); ?></span>
            </div>
          </a>
        <div class="pl5-ns author-single-right-column artwork-entries collapse-for-small" id="jsArtwork">
            <?php
            $artist_title = "";
            $i = 1;
            $last = count($artwork);
            foreach ( $sorted_artists as $sorted_artwork){
              foreach ( $sorted_artwork as $art ) : ?>
                <?php
                $artist = get_field('artwork_to_artist_2way', $art->ID);
                if ($artist_title !== get_the_title($artist)){
                  $artist_title = get_the_title($artist);
                  echo $i !== 0 ? "</ul>" : "";
                  echo "<h3 class='sans-serif'>".$artist_title."</h3><ul>";
                } ?>
                <li>
                  <a href="<?= get_the_permalink($art->ID); ?>"><?= get_the_title($art->ID); ?></a>
                </li>
                <?php echo $i == $last ? "</ul>": ""; ?>
                <?php $i++; ?>
              <?php endforeach;
            }?>
          </div>
      </div>
    </div>
  </div>

  <?php endif; ?>

  <?php
  /**
   * Artists
   */
  $artists = get_posts([
    'post_type' => 'artist',
    'posts_per_page' => -1,
    'meta_query' => [
      'relation' => 'AND',
      'author_filter' => [
        'key' => 'attributed_author_entries_2way',
        'value' => '"' . get_the_ID() . '"',
        'compare' => 'LIKE'
      ],
      'sort_name' => [
        'key' => 'artist_sort_name',
        'compare' => 'exists'
      ]
    ],
    'orderby' => 'sort_name',
    'order' => 'ASC'
  ]);

  if ( !empty($artists) ) : ?>

  <div class="container artist-biographies-container">
    <div class="row row__border-bottom">
      <div class="cs4">
        <h2 class="hidden-xs size-root-2x w-90"><?= __('Contributed Artist Biographies', 'sage'); ?></h2>
      </div>
      <div class="cs8">
        <a class="btn btn-collapse visible-xs-flex collapsed" role="button" data-toggle="collapse" data-target="#jsBiographies" aria-expanded="false" aria-controls="jsBiographies">
          <h2 class="size-h3"><?= __('Contributed Artist Biographies', 'sage'); ?></h2>
          <div class="closed-text">
            <span class="sr-only"><?= __('Expand', 'sage'); ?></span>
            <span aria-hidden="true"><?= Extras\icons('plus-circle-sm', 24, 24); ?></span>
          </div>
          <div class="open-text">
            <span class="sr-only"><?= __('Collapse', 'sage'); ?></span>
            <span aria-hidden="true"><?= Extras\icons('minus-circle-sm', 24, 24); ?></span>
          </div>
        </a>
        <div class="pl5-ns author-single-right-column collapse-for-small" id="jsBiographies">
          <ul>
            <?php foreach ( $artists as $artist ) : ?>
              <li>
                <a href="<?= get_the_permalink($artist->ID); ?>"><?= get_the_title($artist->ID); ?></a>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
    </div>

  </div>

  <?php endif; ?>


  <?php
  /**
   * Essays
   */
  $essays = get_posts([
    'post_type' => 'essay',
    'posts_per_page' => -1,
    'orderby' => 'title',
    'order' => 'ASC',
    'meta_query' => [
      [
        'key' => 'attributed_author_entries_2way',
        'value' => '"' . get_the_ID() . '"',
        'compare' => 'LIKE'
      ]
    ]
  ]);

  if ( !empty($essays) ) : ?>

  <div class="container">
    <div class="row row__border-bottom">
      <div class="cs4">
        <h2 class="hidden-xs size-root-2x w-90"><?= __('Contributed Scholarly Essays', 'sage'); ?></h2>
      </div>
      <div class="cs8">
        <a class="btn btn-collapse visible-xs-flex collapsed" role="button" data-toggle="collapse" data-target="#jsEssays" aria-expanded="false" aria-controls="jsEssays">
          <h2 class="size-h3"><?= __('Contributed Scholarly Essays', 'sage'); ?></h2>
          <div class="closed-text">
            <span class="sr-only"><?= __('Expand', 'sage'); ?></span>
            <span aria-hidden="true"><?= Extras\icons('plus-circle-sm', 24, 24); ?></span>
          </div>
          <div class="open-text">
            <span class="sr-only"><?= __('Collapse', 'sage'); ?></span>
            <span aria-hidden="true"><?= Extras\icons('minus-circle-sm', 24, 24); ?></span>
          </div>
        </a>
        <div class="pl5-ns author-single-right-column collapse-for-small" id="jsEssays">
          <ul>
            <?php foreach ( $essays as $essay ) : ?>
              <li>
                <a href="<?= get_the_permalink($essay->ID); ?>"><?= get_the_title($essay->ID); ?></a>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <?php endif; ?>
  <?php
  /**
   * Essays
   */
  $videos = get_posts([
    'post_type' => 'video',
    'posts_per_page' => -1,
    'orderby' => 'title',
    'order' => 'ASC',
    'meta_query' => [
      [
        'key' => 'attributed_author_entries_2way',
        'value' => '"' . get_the_ID() . '"',
        'compare' => 'LIKE'
      ]
    ]
  ]);

  if ( !empty($videos) ) : ?>

  <div class="container">
    <div class="row row__border-bottom">
      <div class="cs4">
        <h2 class="hidden-xs size-root-2x w-90"><?= __('Scholarly Video Contributor', 'sage'); ?></h2>
      </div>
      <div class="cs8">
        <a class="btn btn-collapse visible-xs-flex collapsed" role="button" data-toggle="collapse" data-target="#jsVideos" aria-expanded="false" aria-controls="jsVideos">
          <h2 class="size-h3"><?= __('Scholarly Video Contributor', 'sage'); ?></h2>
          <div class="closed-text">
            <span class="sr-only"><?= __('Expand', 'sage'); ?></span>
            <span aria-hidden="true"><?= Extras\icons('plus-circle-sm', 24, 24); ?></span>
          </div>
          <div class="open-text">
            <span class="sr-only"><?= __('Collapse', 'sage'); ?></span>
            <span aria-hidden="true"><?= Extras\icons('minus-circle-sm', 24, 24); ?></span>
          </div>
        </a>
        <div class="pl5-ns author-single-right-column collapse-for-small" id="jsVideos">
          <ul>
            <?php foreach ( $videos as $video ) : ?>
              <li>
                <a href="<?= get_the_permalink($video->ID); ?>"><?= get_the_title($video->ID); ?></a>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <?php endif; ?>
</div><!-- .single-author-content -->


<?= Extras\componify(); ?>