<?php

use Roots\Sage\Titles;

$date_args = [
  'start_field'  => 'start_date',
  'end_field'    => 'end_date',
  'post_id'      => $post->ID,
  'month_format' => 'F'
];

if ( $acf_header = get_field('image') ) {
  $hero_img_url = $acf_header['sizes']['large'];
  $hero_img_alt = $acf_header['alt'];
  $header_class = 'has-banner-img';
}
elseif ( has_post_thumbnail() ) {
  $thumb_id = get_post_thumbnail_id();
  $hero_img_url = wp_get_attachment_image_src($thumb_id, 'large')[0];
  $hero_img_alt = get_post_meta($thumb_id, '_wp_attachment_image_alt', true);
  $header_class = 'has-thumb';
}
else {
  $hero_img_url = '';
  $hero_img_alt = '';
  $header_class = 'no-image';
}

?>

<header class="page-header--hero bg-black <?= esc_attr($header_class); ?>">
  <div class="container">
    <div class="row">
      <div class="hero-content hero-content--press">
        <h1 class="size-h1 text-white"><?= Titles\title(); ?></h1>
        <strong class="text-white"><?php the_field('press_source'); ?></strong>
        – <?php the_field('press_publish_date'); ?>
        <?php if ( get_field('press_author') ) : ?>
          <span class="db"><?php the_field('press_author'); ?></span>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <?php
  /**
   * Hero image
   */
  ?>
  <figure class="loading">
    <div class="lazyload bg-cover bg-cover--artwork bg-cover-overlay--left plax" data-bgset="<?= $hero_img_url; ?>"></div>
    <?php if($hero_img_alt) : ?>
      <figcaption class="sr-only"><?= $hero_img_alt; ?></figcaption>
    <?php endif; ?>
  </figure>

</header>
