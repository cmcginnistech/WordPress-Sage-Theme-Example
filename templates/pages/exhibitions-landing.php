<?php

use Roots\Sage\Extras;

?>

<section class="page-header--hero">
  <div class="container flex justify-center pt6 pb3 tc">
    <div class="hero-content">
      <h1 class="text-white size-yuge"><?php the_title(); ?></h1>
      <span class="text-white serif text-italic size-h2"><?php the_field('page_subtitle'); ?></span>
    </div>
  </div>
  <?php if($hero = get_field('page_header_image')) : ?>
  <figure class="loading">
    <div class="lazyload bg-cover bg-cover-overlay plax" data-bgset="<?= $hero['sizes']['hero']; ?>"></div>
  </figure>
  <?php endif; ?>
</section>

<?php if($exhibitions = get_field('exhibitions')) : ?>
<div class="featured-exhibitions container pv5">

  <div class="row flex-ns justify-center">
  <div id="" class="tiny-slider">
      <?php $first = true;
      foreach($exhibitions as $e) :
        $date_args = [
          'start_field'  => 'start_date',
          'end_field'    => 'end_date',
          'post_id'      => $e,
          'month_format' => 'F'
        ];
      ?>
          <div class=" ">

        <div class="text-center">
          <a href="<?= get_the_permalink($e); ?>">
            <?php if($graphic = get_field('exhibition_graphic', $e)) : ?>
            <img class="" src="<?= $graphic['sizes']['large']; ?>" alt="">
            <?php endif; ?>
            <strong class="db upper"><?= get_the_title($e); ?></strong>
          </a>

          <?php if($location = get_field('exhibition_short_location', $e)) : ?>
            <span class="db"><?= $location; ?></span>
          <?php endif; ?>

          <?php if ( $date_override = get_field('date_override', $e ) ) : ?>
            <?= $date_override; ?>
          <?php else : ?>
            <time class="db"><?= Exhibitions\the_date_range($date_args); ?></time>
          <?php endif; ?>

        </div>
          </div>
      <?php
    $first = false;
    endforeach; ?>

  </div>
  </div>
</div>
<?php endif; ?>


<section class="exhibition-image-section section-full pv5">
  <div class="container">
    <div class="row">
      <div class="cs6 text-white z-5">
        <h2 class="size-h3-ns text-white"><?= get_field('home_fc_heading'); ?></h2>
        <div class="mv4"><?= get_field('home_fc_content'); ?></div>
        <?php helpers\acflink(get_field('home_fc_cta_button'), 'btn btn-outline btn-outline--alt btn-lg text-white btn-caret-right'); ?>
      </div>
    </div>
  </div>
  <?php if($background = get_field('home_fc_background')) : ?>
  <div class="loading">
    <div class="lazyload bg-cover bg-cover--artwork bg-cover-overlay--left" data-bgset="<?= $background['sizes']['hero']; ?>"></div>
  </div>
  <?php endif; ?>
</section>


<?php if($artworks = get_field('exhibition_highlights')) : ?>
<section class="exhibition-highlights bg-lighter-gray pv5">
  <div class="container">
    <h2 class="size-h2-ns size-h1"><?= __('Exhibition Highlights'); ?></h2>
    <div class="row flex-ns justify-between-ns mt4">
    <?php foreach($artworks as $artwork) : ?>
      <?php
        $namespace = uniqid();
        $thumb_id = get_post_thumbnail_id($artwork);
        $lightboxes[] = (object) [
          'artwork'   => $artwork->ID,
          'namespace' => $namespace,
          'thumb_id'  => $thumb_id
        ];
      ?>
      <div class="cs4 mb3 mb0-ns">
        <a class="modaal-inline db" href="#modaal-<?= $namespace; ?>">
          <figure class="overlay">
            <div class="ratio-container ratio-container--thumb-portrait loading">
              <img class="lazyload" data-src="<?= wp_get_attachment_image_src($thumb_id, "thumb-portrait")[0]; ?>" alt="" data-expand="-10"/>
            </div>
            <figcaption class="overlay-content sr-only-xs">
              <strong class="db artist-name"><?= helpers\get_artwork_artist($artwork, true); ?></strong>
              <span class="db artwork-title"><?= get_the_title($artwork); ?></span>
            </figcaption>
          </figure>
        </a>
      </div>
    <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php foreach($lightboxes as $lightbox) : ?>
<?php
  $artwork = $lightbox->artwork;
  $artist = get_field('artwork_to_artist_2way', $artwork);
  $dates = helpers\get_artist_display_dates($artist->ID);
?>
<div id="modaal-<?= $lightbox->namespace; ?>" class="dn">
  <div class="text-center">
    <img class="lazyload" data-src="<?= wp_get_attachment_image_src($lightbox->thumb_id, 'medium_large')[0]; ?>" alt=""/>
    <h2 class="heading-reset sans-serif size-root">
      <?= helpers\get_artwork_artist($artwork, true); ?>
      <?php if($dates) {
        echo "({$dates['birth']} – {$dates['death']})";
      } ?>
    </h2>
    <span class="block"><em><?= get_the_title($artwork); ?></em></span>
    <?php
      // ~TO DO:~ Artwork description / "tombstone" data goes here.
    ?>
    <div>
    <?php if($catalog_entry = get_permalink($artwork)) : ?>
      <a class="btn btn-outline" href="<?= $catalog_entry; ?>"><?= __('See Catalogue Entry', 'sage'); ?></a>
    <?php endif; ?>
    <?php if($artist) : ?>
      <a class="btn btn-outline" href="<?= get_permalink($artist); ?>"><?= __('See Artist’s Bio', 'sage'); ?></a>
    <?php endif; ?>
    </div>
  </div>
</div>
<?php endforeach; ?>

<?php if(have_rows('components')) : ?>
<div class="container pv6">
  <?php Extras\componify(); ?>
</div>
<?php endif; ?>