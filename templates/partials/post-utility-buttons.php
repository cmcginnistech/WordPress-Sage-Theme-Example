<?php

use Roots\Sage\Extras;

$permalink = get_the_permalink();
$title = get_the_title();
$image_url = get_the_post_thumbnail();
?>

<div class="post-utility-buttons">

  <?php
  /*
  The share button
  */
  $fb_share = 'http://www.facebook.com/sharer.php?u='. $permalink;
  $tw_share = 'https://twitter.com/share?url='. $permalink .'&text='. wp_trim_words( $title, 18 );
  $pint_share = 'https://pinterest.com/pin/create/bookmarklet/?media='. $image_url .'&url='. $permalink .'&description='. $title;
  ?>
  <div class="share-post-module mr2">
    <button id="pageShareBtn" class="btn btn-lg btn-outline" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <?php _e('Share', 'sage'); ?>
      <span class="icon" aria-hidden="true"><?= Extras\icons('share', 15, 15); ?></span>
    </button>
    <ul class="dropdown-menu" aria-labelledby="pageShareBtn">
      <li>
        <a href="<?= esc_url($fb_share); ?>" target="_blank">
          <span class="icon" aria-hidden="true"><?= Extras\icons('facebook', 20, 20); ?></span>
          <span class="sr-only">share on </span>
          <?php _e('Facebook', 'sage'); ?>
        </a>
      </li>
      <li>
        <a href="<?= esc_url($tw_share); ?>" target="_blank">
          <span class="icon" aria-hidden="true"><?= Extras\icons('twitter', 20, 20) ; ?></span>
          <span class="sr-only">share on </span>
          <?php _e('Twitter', 'sage'); ?>
        </a>
      </li>
      <li>
        <a href="<?= esc_url($pint_share); ?>" target="_blank">
          <span class="icon" aria-hidden="true"><?= Extras\icons('pinterest', 18, 18) ; ?></span>
          <span class="sr-only">share on </span>
          <?php _e('Pinterest', 'sage'); ?>
        </a>
      </li>
      <li>
        <button data-clipboard-text="<?= get_the_permalink(); ?>" class="clipboard">
          <span class="icon" aria-hidden="true"><?= Extras\icons('link', 18, 16) ; ?></span>
          <span class="sr-only">copy the </span>
          <?php _e('Permalink', 'sage'); ?>
        </button>
      </li>
    </ul>
  </div>

  <?php
  /*
  The download button
  */
  if ( get_post_type() == 'artwork' ) :
    $lowres_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
    $hires = get_field('high_resolution_image');

    // check wp-config and load remote assets if path is defined.
    if ( defined('WP_DEBUG') && defined('LC_HIRES_PATH') ) {
      $hires_url = LC_HIRES_PATH . $hires .'.jpg';
    } else {
      $hires_url = get_site_url(null, '/wp-content/uploads/downloadable/') . $hires .'.jpg';
    }
    ?>
    <div class="download-post-module mr2">
      <button id="artDownloadBtn" class="btn btn-lg btn-outline" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <?php _e('Download Image', 'sage'); ?>
        <span class="icon" aria-hidden="true"><?= Extras\icons('download', 16, 16); ?></span>
      </button>
      <ul class="dropdown-menu" aria-labelledby="artDownloadBtn">

        <?php if ( $hires !== '' && $hires !== null ) : ?>
          <li>
            <a href="<?= esc_url($hires_url); ?>" target="_blank">
              <span class="sr-only">download</span>
              <?php _e('High Resolution', 'sage'); ?>
            </a>
          </li>
        <?php endif; ?>

        <?php if ( $lowres_url ) : ?>
          <li>
            <a href="<?= esc_url($lowres_url); ?>" target="_blank">
              <span class="sr-only">download</span>
              <?php _e('Low Resolution', 'sage'); ?>
            </a>
          </li>
        <?php endif; ?>

      </ul>
    </div>
  <?php endif; ?>

  <?php
  /*
  The print button.
  (see function redirect_to_PDF_download() for url param)
  */
  $printable_types = ['artwork', 'artist', 'essay', 'group', 'page'];
  $download_only = is_page_template('template-portrait-in-oil.php');
  if ( in_array( get_post_type(), $printable_types ) ) : ?>
    <div class="print-post-module dib">
      <a href="<?= esc_url($permalink) .'?pdf=1'; ?>" target="_blank" class="btn btn-lg btn-outline" <?php if($download_only) echo 'download'; ?>>
        <?php _e('Print', 'sage'); ?>
        <span class="icon" aria-hidden="true"><?= Extras\icons('print', 18, 18); ?></span>
      </a>
    </div>
  <?php endif; ?>

</div>