<?php

use Roots\Sage\Extras;

// Exhibitons have the ability to "unpublish" this component.
$global_display_on =
  get_post_type() == 'exhibition' ?
  get_field('publish_artwork_sections') :
  true;

if($global_display_on) :
  // Setup arrays for modaal lightboxes down the road.
  $lightboxes_info = []; // inline subgroups
  $lightboxes_art = []; // artwork image "tombstones"
?>

<div class="artwork-group">

<?php

$heading = get_sub_field('heading');
if($heading) : ?>
<header class="artwork-group__heading">
  <div class="content-text <?php if(!$heading['text']) echo 'tc'; ?>">
    <?php if ( $heading['title'] ) : ?>
      <h2 class="component-title"><?= $heading['title']; ?></h2>
    <?php endif; ?>
    <?= $heading['text']; ?>
  </div>
</header>
<?php endif; ?>

<?php
/**
 * Main section / featured artwork.
 */
$main = get_sub_field('main_section');
if($main['text']) :
  $feat_artwork = $main['featured_artwork'];
  $feat_artwork_id = get_post_thumbnail_id($feat_artwork);
  $thumb_id = $main['image'] ? $main['image'] : $feat_artwork_id;
  $classes = '';
  $artwork_classes = '';

  if($feat_artwork || $main['image']) {
    $classes = 'full';
  }
?>
<div class="artwork-group--main <?= $classes; ?>">
  <div class="inner-pad bg-lighter-gray"><?= $main['text']; ?></div>
  <?php if($feat_artwork) : ?>
    <?php
      $namespace = uniqid();
      // Push some artwork data to our modaal lightbox array for later!
      $lightboxes_art[] = (object) [
        'artwork'   => $feat_artwork->ID,
        'namespace' => $namespace,
        'thumb_id'  => $thumb_id
      ];
    ?>
    <a class="modaal-inline artwork-wrapper overlay" href="#modaal-<?= $namespace; ?>" data-modaal-type="inline" data-modaal-animation="fade">
      <figure class="image-wrapper bg-light-gray w-100">
        <div class="artwork--ration-container ratio-container ratio-container--thumb-portrait loading">
          <img class="lazyload" src="<?= $main['image']['sizes']['full']; ?>" data-src="<?= $main['image'] ? $main['image']['sizes']['thumb-portrait'] : wp_get_attachment_image_src($thumb_id, 'medium_large')[0]; ?>" alt="" data-expand="-10">
        </div>
        <figcaption class="overlay-content sr-only-xs">
          <strong class="db artist-name"><?= helpers\get_artwork_artist($feat_artwork, true); ?></strong>
          <em class="db artwork-title"><?= get_the_title($feat_artwork); ?></em>
        </figcaption>
      </figure>
    </a>
  <?php elseif($main['image']) : ?>
  <figure class="artwork-wrapper image-wrapper item--overlay bg-light-gray w-100">
    <div class="artwork--ration-container ratio-container ratio-container--thumb-portrait loading">
      <img class="lazyload" src="<?= $main['image']['sizes']['full']; ?>" data-src="<?= $main['image']['sizes']['medium_large']; ?>" alt="" data-expand="-10">
    </div>
  </figure>
  <?php endif; ?>
</div>
<?php endif; ?>

<?php
/**
 * Artwork groups.
 */
?>
<?php if(have_rows('artwork_groups')) : ?>
  <?php while(have_rows('artwork_groups')) : the_row(); ?>
    <?php if($group_title = get_sub_field('group_title')) : ?>
    <div class="artwork-group--sub">
      <div class="content-wrap">
        <h4><?= $group_title; ?></h4>
        <?php if($group_info = get_sub_field('information')) : ?>
          <small class="db hidden-xs text-periwinkle"><?= strlen($group_title) > 40 ? wp_trim_words($group_info, 8) : wp_trim_words($group_info, 10); ?></small>
        <?php endif; ?>
      </div>
      <?php if($group_info) : ?>
        <?php
          $namespace = uniqid();
          // Push some artwork data to our modaal lightbox array for later!
          $lightboxes_info[] = (object) [
            'group_title' => $group_title,
            'info'        => $group_info,
            'namespace'   => $namespace
          ];
        ?>
        <a href="#modaal-<?= $namespace; ?>" data-modaal-type="inline" data-modaal-animation="fade" class="modaal-inline">
          <span class="icon-info"><?= Extras\icons('info', 24, 24); ?></span>
          <span class="sr-only"><?= __('Open more details'); ?></span>
        </a>
      <?php endif; ?>
    </div>
    <?php endif; ?>

    <?php if($artworks = get_sub_field('artwork')) : ?>
      <?php foreach($artworks as $artwork) : ?>
      <?php
        $namespace = uniqid();
        $thumb_id = get_post_thumbnail_id($artwork);
        // Push some artwork data to our modaal lightbox array for later!
        $lightboxes_art[] = (object) [
          'artwork'   => $artwork->ID,
          'namespace' => $namespace,
          'thumb_id'  => $thumb_id
        ];
      ?>
      <div class="artwork-group--item">
        <a class="db overlay artwork-wrapper modaal-inline" href="#modaal-<?= $namespace; ?>" data-modaal-type="inline" data-modaal-animation="fade">
          <figure class="image-wrapper">
            <div class="ratio-container ratio-container--thumb-portrait loading">
              <img class="lazyload" data-src="<?= wp_get_attachment_image_src($thumb_id, 'thumb-portrait')[0]; ?>" alt="" data-expand="-10">
            </div>
            <figcaption class="overlay-content sr-only-xs">
              <strong class="db artist-name">
                <?php
                if ( get_post_type($artwork->ID) === 'artwork' ) {
                  echo helpers\get_artwork_artist($artwork, true);
                } elseif ( get_post_type($artwork->ID) === 'exb_artwork' ) {
                  echo get_field('exba_artist', $artwork->ID);
                }
                ?>
              </strong>
              <em class="db artwork-title"><?= get_the_title($artwork); ?></em>
            </figcaption>
          </figure>
        </a>
      </div>
      <?php endforeach; ?>
    <?php endif; ?>
  <?php endwhile; ?>
<?php endif; ?>

<?php
/**
 * All the modal windows.
 */
?>
<?php foreach($lightboxes_info as $lightbox) : ?>
<div id="modaal-<?= $lightbox->namespace; ?>" class="dn">
  <h2 class="mt0"><?= $lightbox->group_title; ?></h2>
  <div class="pb4">
    <?= $lightbox->info; ?>
  </div>
</div>
<?php endforeach; ?>

<?php
/*
 * Output the lightboxes.
 * Handles 2 possible post types = `artwork` and `exb_artwork`
 */
foreach($lightboxes_art as $lightbox) :

  $artwork_id = $lightbox->artwork;
  $artist_obj = get_field('artwork_to_artist_2way', $artwork_id);
  $artist_display_name = null;
  $artist_page_link = null;
  $artist_dates = null;
  $description = null;
  $artwork_date = null;

  if ( get_post_type($artwork_id) === 'artwork' ) {
    $artist_dates = helpers\get_artist_display_dates($artist_obj->ID);
    $artist_display_name = helpers\get_artwork_artist($artwork_id, true);
    $description = helpers\get_artwork_medium($artwork_id);
    $artwork_date = get_field('tombstone_date', $artwork_id);
  }
  elseif ( get_post_type($artwork_id) === 'exb_artwork' ) {
    $artist_display_name = get_field('exba_artist', $artwork_id);
    $artist_page_link = get_field('exba_artist_page_link', $artwork_id);
    $artist_dates = get_field('exba_artist_date', $artwork_id);
    $description = get_field('exba_description', $artwork_id);
    $artwork_date = get_field('exba_artwork_date', $artwork_id);
  }

?>
<div id="modaal-<?= $lightbox->namespace; ?>" class="dn">
  <div class="text-center">
    <img class="lazyload" data-src="<?= wp_get_attachment_image_src($lightbox->thumb_id, 'medium_large')[0]; ?>" alt=""/>

    <h5 class="mt0 sans-serif size-root">
      <span class="sr-only"><?= __('Artist', 'sage'); ?></span>
      <?= $artist_display_name; ?>
      <?php if( is_array($artist_dates) ) {
        echo "({$artist_dates['birth']} – {$artist_dates['death']})";
      } elseif ( $artist_dates ) {
        echo "({$artist_dates})";
      } ?>
    </h5>

    <dl class="mb0">
      <dt class="sr-only"><?= __('Artwork title', 'sage'); ?></dt>
      <dd><em><?= get_the_title($artwork_id); ?></em><?php if($artwork_date) echo ', '.$artwork_date; ?></dd>
      <dt class="sr-only"><?= __('Medium', 'sage'); ?></dt>
      <dd class="size-root-xs"><?= $description; ?></dd>
    </dl>

    <?php
    $show_credit = get_field('include_credit_line', $artwork_id);
    if ( $show_credit !== false ) : ?>
      <span class="db size-root-xs">The Leiden Collection, New York</span>
    <?php endif; ?>

    <div>
      <?php if ( get_post_type($artwork_id) === 'artwork' ) : ?>
        <a class="btn btn-outline" href="<?= get_permalink($artwork_id); ?>"><?= __('See Catalogue Entry', 'sage'); ?></a>
      <?php endif; ?>
      <?php if ( $artist_obj ) : ?>
        <a class="btn btn-outline" href="<?= get_permalink($artist_obj); ?>"><?= __('See Artist’s Bio', 'sage'); ?></a>
      <?php endif; ?>
      <?php if ( $artist_page_link ) : ?>
        <a class="btn btn-outline" href="<?= $artist_page_link; ?>"><?= __('See Artist’s Bio', 'sage'); ?></a>
      <?php endif; ?>
    </div>

  </div>
</div>
<?php endforeach; ?>

</div><!-- /.artwork-group -->

<?php endif; // End $global_display_on check. ?>
