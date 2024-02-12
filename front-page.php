<?php
use Roots\Sage\{Extras,Nav};

$hero_subtext = get_field('hero_section_sub-hero_text');

?>

<section class="page-header--hero page-header--hero-home-page">
      <header id="homeHeroNavbar" class="navbar-wrap hidden-xs hidden-sm">
        <div class="navbar">
          <nav id="site-nav-wrapper" class="" role="navigation">
            <div class="top-wrapper">
              <a class="" href="<?= esc_url(home_url('/')); ?>">
                <img src="<?= get_template_directory_uri(); ?>/dist/images/logos/leiden-logo-full.png" alt="<?php bloginfo('name'); ?>" >
              </a>
              <?php
              wp_nav_menu([
                'depth'          => 2,
                'menu_class'     => 'nav nav-primary navbar-nav',
                'theme_location' => 'primary_navigation',
                'walker'         => new Nav\SageNavWalker()
              ]);
              ?>
            </div>
            <div class="navbar-form">
              <?php get_template_part('templates/global/searchform'); ?>
            </div>
          </nav>
        </div>
      </header>
      <div class="c12 hero-content-wrapper">
        <div id="heroCarousel" class="carousel slide h-100" data-ride="carousel">
          <div class="carousel-inner h-100">
          <?php $first = true;
          $count = 1;
             if( have_rows('hero_section_carousel') ): while( have_rows('hero_section_carousel') ): the_row();
                $count++;
                endwhile;
                endif;
          if( have_rows('hero_section_carousel') ): ?>
                <?php while( have_rows('hero_section_carousel') ): the_row();?>
            <div class="item <?php echo $first ? 'active' : '';?> h-100">
              <div class="carousel-item-inner h-100">
                <figure class="loading">
                  <div class="lazyload bg-cover" data-bgset="<?= get_sub_field('image')['sizes']['home-hero']; ?>"></div>
                </figure>
                <div class="hero-content">
                  <a href="<?php echo get_sub_field('hero_link') ? get_sub_field('hero_link')['url'] : '';?>" <?php echo get_sub_field('hero_link') ? 'target="' . get_sub_field('hero_link')['target'] . '"' : '';?>>
                    <span class="heading-type"><?php echo get_sub_field('type');?></span>
                    <h1 class="heading-title">
                      <?php echo get_sub_field('text');?>
                    </h1>
                    <span class="heading-attribution"><?php echo get_sub_field('attribution');?></span>
                  </a>
                  <a class="carousel-control next-link" href="#heroCarousel" role="button" data-slide="next">
                    <span class="sr-only">Next</span>
                  </a>
                </div>
              </div>
            </div>
                <?php
                $first = false;
                $count++;

              endwhile; ?>
              <?php endif; ?>

            </div>

          </div>
  </div>
</section>
<section class="home--hero-text bg-lighter-gray">
  <div class="container pv5">
        <?php echo $hero_subtext;?>
  </div>
</section>
<?php
/**
 * Featured artwork from the collection
 */
$collection = get_field('featured_collection_artworks');
$collectionArtists = get_field('featured_collection_home_artists');
$collectionEssays = get_field('featured_collection_essays');

?>
<section class="container pv5">

  <div class="row">
  <div class="c12">
      <h2 class="mt0 mbneg1">
        <a class="section-title-link" href="<?= esc_url($collection['full_catalogue_link']['url']); ?>"><?= $collection['section_title']; ?></a>
      </h2>
      </div>
  </div>
  <ul class="nav nav-tabs" id="homeTab" role="tablist">
    <li class="nav-item active">
      <a class="nav-link " id="artwork-tab" data-toggle="tab" href="#artwork-content" role="tab" aria-controls="home" aria-selected="true">Artworks</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" id="artist-tab" data-toggle="tab" href="#artist-content" role="tab" aria-controls="profile" aria-selected="false">Artists</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" id="essays-tab" data-toggle="tab" href="#essays-content" role="tab" aria-controls="contact" aria-selected="false">Essays</a>
    </li>
  </ul>
  <div class="tab-content" id="homeTabContent">
    <div class="tab-pane fade active in pa2 bg-lighter-gray" id="artwork-content" role="tabpanel" aria-labelledby="artwork-tab">
    <div class="mb2">
        <?php while( have_rows('featured_collection_artworks_artwork_groups') ) : the_row(); ?>
          <div class="flex-img-grid">
            <?php while( have_rows('artworks') ) : the_row();
              $artwork = get_sub_field('artwork');
              $thumb_id = get_post_thumbnail_id($artwork);
              $layout = get_row_layout('artwork');
              $artworkImageOverride = get_sub_field('artwork_image_override');
              $thumb_crop = $layout == 'landscape' ? 'landscape' : 'portrait';
            ?>
            <a class="flex-img-grid--item overlay layout-<?= get_row_layout('artwork'); ?>" href="<?= get_permalink($artwork); ?>">
              <figure>
                <div class="collection-icon"><?= Extras\icons('plus', 20, 20); ?></div>
                <div class="ratio-container ratio-container--thumb-<?= get_row_layout('artwork'); ?> loading">
                  <picture>
                    <img src="<?= $artworkImageOverride ? $artworkImageOverride['sizes']["thumb-home-{$thumb_crop}"] : wp_get_attachment_image_src($thumb_id, "thumb-home-{$thumb_crop}")[0]; ?>" class="lazyload" data-src="<?= $artworkImageOverride ? $artworkImageOverride['sizes']["thumb-home-{$thumb_crop}"] : wp_get_attachment_image_src($thumb_id, "thumb-home-{$thumb_crop}")[0]; ?>" alt="" data-expand="-10"/>
                  </picture>
                </div>
                <figcaption class="overlay-content">
                  <strong class="db artist-name"><?= helpers\get_artwork_artist($artwork, true); ?></strong>
                  <span class="db artwork-title"><?= get_the_title($artwork); ?></span>
                </figcaption>
              </figure>
            </a>
            <?php endwhile; ?>
          </div>
        <?php endwhile; ?>
      </div>
      <?php helpers\acflink( $collection['full_catalogue_link'], 'btn btn-outline btn-lg btn-caret-right' ); ?>
    </div>
    <div class="tab-pane fade pa2 bg-lighter-gray" id="artist-content" role="tabpanel" aria-labelledby="artist-tab">
      <div class="mb2">
        <?php while( have_rows('featured_collection_home_artists_artist_groups') ) : the_row(); ?>
          <div class="flex-img-grid">
            <?php while( have_rows('artists') ) : the_row();
              $artist = get_sub_field('artist');
              $thumb_id = get_post_thumbnail_id($artist);
              $layout = get_row_layout('artist');
              $artistImageOverride = get_sub_field('artist_image_override');

              $thumb_crop = $layout == 'landscape' ? 'landscape' : 'portrait';
            ?>
            <a class="flex-img-grid--item overlay layout-<?= get_row_layout('artist'); ?>" href="<?= get_permalink($artist); ?>">
              <figure>
                <div class="collection-icon"><?= Extras\icons('plus', 20, 20); ?></div>
                <div class="ratio-container ratio-container--thumb-<?= get_row_layout('artist'); ?> loading">
                  <picture>
                    <img src="<?= $artworkImageOverride ? $artworkImageOverride['sizes']["thumb-home-{$thumb_crop}"] : wp_get_attachment_image_src($thumb_id, "thumb-home-{$thumb_crop}")[0]; ?>" class="lazyload" data-src="<?= $artistImageOverride ? $artistImageOverride['sizes']["thumb-home-{$thumb_crop}"] : wp_get_attachment_image_src($thumb_id, "thumb-home-{$thumb_crop}")[0]; ?>" alt="" data-expand="-10"/>
                  </picture>
                </div>
                <figcaption class="overlay-content">
                  <strong class="db artist-name"><?= $artist->post_title;?></strong>
                </figcaption>
              </figure>
            </a>
            <?php endwhile; ?>
          </div>
        <?php endwhile; ?>
      </div>
      <?php helpers\acflink( $collectionArtists['full_catalogue_link'], 'btn btn-outline btn-lg btn-caret-right' ); ?>
    </div>
    <div class="tab-pane fade pa2 bg-lighter-gray essay-tab-panel" id="essays-content" role="tabpanel" aria-labelledby="essays-tab">
      <div class="mb2">
      <?php if( have_rows('featured_collection_essays') ): ?>
        <?php while( have_rows('featured_collection_essays') ): the_row(); ?>
          <div class="flex-img-grid">
            <?php while( have_rows('essays') ) : the_row();
            $essay = get_sub_field('essay');
              $thumb_id = get_post_thumbnail_id($essay);
              $essayImageOverride = get_sub_field('essay_image_override');

            ?>
            <a class="flex-img-grid--item overlay layout-landscape" href="<?= get_permalink($essay->ID); ?>">
              <figure>
                <div class="collection-icon"><?= Extras\icons('plus', 20, 20); ?></div>
                <div class="ratio-container ratio-container--thumb-landscape loading">
                  <picture>
                    <img src="<?= $artworkImageOverride ? $artworkImageOverride['sizes']["thumb-home-{$thumb_crop}"] : wp_get_attachment_image_src($thumb_id, "thumb-home-{$thumb_crop}")[0]; ?>" class="lazyload" data-src="<?= $essayImageOverride ? $essayImageOverride['sizes']['thumb-home-landscape'] : wp_get_attachment_image_src($thumb_id, "thumb-home-landscape")[0]; ?>" alt="" data-expand="-10"/>
                  </picture>
                </div>
                <figcaption class="overlay-content">
                  <strong class="db artist-name"><?= $essay->post_title;?></strong>
                </figcaption>
              </figure>
            </a>
            <?php endwhile; ?>
          </div>
          <?php endwhile; ?>
        <?php endif; ?>
      </div>
      <?php helpers\acflink( $collectionEssays['full_catalogue_link'], 'btn btn-outline btn-lg btn-caret-right' ); ?>
    </div>
  </div>
</section>

<?php
/**
 * Feature Exhibition
 */
$exhibitions = get_field('exhibitions_section');
$exhibitions_hide = $exhibitions['hide_exhibitions_section'];
$section_title_link = $exhibitions['all_exhibitions_link']['url'];
?>
<?php 
if (!$exhibitions_hide) : ?>
<section class="home--feat-exb-group bg-lighter-gray">
  <div class="container pv5">
    <?php while( have_rows('exhibitions_section') ) : the_row(); ?>
      <?php while( have_rows('featured_exhibition_group') ) : the_row(); ?>
      <div class="row mb4 mt4">
        <div class="cs12">
          <h2 class="mt0" style="text-align:center;">
            <?php
            /*
            Check if we are passing in a link value for the heading title
            */
            if ( isset($section_title_link) ) : ?>
              <a class="section-title-link" href="<?= esc_url($section_title_link); ?>">
                <?php the_sub_field('heading'); ?>
              </a>
            <?php else : ?>
              <?php the_sub_field('heading'); ?>
            <?php endif; ?>
          </h2>
        </div>
        <div class="cs8">
          <?php the_sub_field('heading_text'); ?>
        </div>
      </div>

      <?php
        $exhibition = get_sub_field('featured_exhibition');
        $date_args = [
          'start_field'  => 'start_date',
          'end_field'    => 'end_date',
          'post_id'      => $exhibition->ID,
          'month_format' => 'F'
        ];
        if (get_field('exhibitions_section_featured_exhibition_image_override')){
          $bg = get_field('exhibitions_section_featured_exhibition_image_override');
        } else {
          $bg = get_field('image', $exhibition);
        }
      ?>
      <div class="flex-ns ">
        <article class="flex flex-item featured-item w-100 w-50-ns pv3 overlay">
          <div
            class="bg-img lazyload"
            data-bgset="<?= $bg['sizes']['thumb-home-past-exhibitions']; ?>"
            data-expand="-10"
          ></div>
          <a href="<?= get_permalink($exhibition->ID); ?>" class="w-100 pa4 pb2-ns flex flex-column justify-end">
            <div class="exb-top-wrapper mb2">
              <h3 class="text-white size-h1 b heading-reset"><?= $exhibition->post_title; ?></h3>
            </div>
            <span class="text-white"><?= Exhibitions\the_date_range($date_args); ?></span>
          </a>
        </article>
        <div class="w-100 w-50-ns flex flex-wrap">
        <?php if( have_rows('exhibitions_section_exhibitions') ): ?>
        <?php while( have_rows('exhibitions_section_exhibitions') ): the_row(); ?>
        <?php $item = get_sub_field('exhibition');
        $date_args = [
          'start_field'  => 'start_date',
          'end_field'    => 'end_date',
          'post_id'      => $item->ID,
          'month_format' => 'F'
        ];
        ?>
          <?php
          if (get_sub_field('exhibition_override_image')){
            $thumb = get_sub_field('exhibition_override_image');
          } else {
            $thumb = get_field('image',$item->ID);
          }
            $label = helpers\get_item_label($item);
          ?>
		<article class="flex flex-item w-50 pa2 overlay <?= get_post_type($item);?>" style="padding: 0;">
        <a class="item masonry--item overlay bg-light-gray <?= $item_large; ?>"  href="<?= get_permalink($item->ID); ?>" style="width:100%;margin:0;">
          <div class="ratio-container ratio-container--thumb-square loading" style="height: 100%;width: 100%;">
            <!--<img class="lazyload w-100" data-src="<?= $thumb['sizes']['thumb-home-past-exhibitions']; ?>" alt="" data-expand="-10" style="z-index: 5;">-->
          </div>
		<div class="item--details" style="background-image: url(<?= $thumb['sizes']['thumb-home-past-exhibitions']; ?>);background-size: cover;background-repeat: no-repeat;background-position: center center;">
      <h2 class="heading-reset text-white serif"><?= $item->post_title; ?></h2>
      <div class="db overlay-content--hide mt1">
        <div class="press-source--border">
          <strong class="press-source text-white"><?php echo get_field('press_source', $item->ID); ?></strong>
        </div>
        <span class="db text-white"><?php echo get_field('press_publish_date', $item->ID); ?></span>
      </div>
    </div>	
          <div class="overlay-content">
            <span class="btn btn-outline btn-lg text-white"><?= helpers\get_press_link_text($f); ?></span>
          </div>
        </a>
      </article>
			
			
			<!--
          <article class="flex flex-item w-50 pa2 overlay <?= get_post_type($item);?>">
            <div
              class="lazyload bg-img"
              data-bgset="<?= $thumb['sizes']['thumb-home-past-exhibitions']; ?>" data-expand="-10"
            ></div>
            <a href="<?= get_permalink($item->ID); ?>" class="w-100 pa2 pa3-ns flex flex-column justify-end">
              <div class="exb-top-wrapper mb1">
                <h3 class="text-white size-h4 b heading-reset"><?= $item->post_title; ?></h3>
              </div>
              <span class="text-white"><?= Exhibitions\the_date_range($date_args); ?></span>
            </a>
          </article>-->
          <?php endwhile; ?>
        <?php endif; ?>
        </div>
      </div>
	  <br>
      <a class="btn btn-outline btn-lg btn-caret-right mb4" href="<?php echo get_site_url(); ?>/news-media/exhibition/hermitage-amsterdam/" target="_self">View all News &amp; Media</a>
      <?php endwhile; ?>
    <?php endwhile; ?>
	<?php endif; ?>
    <?php //helpers\acflink( $exhibitions['all_exhibitions_link'], 'mt3 btn btn-outline btn-lg btn-caret-right' ); ?>
  </div>
</section>
