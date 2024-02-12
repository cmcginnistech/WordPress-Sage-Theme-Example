<?php

$fallback_gallery = get_field('press_texture_gallery', 'option');

if(has_post_thumbnail($id)) {
  $thumb_id = get_post_thumbnail_id($id);
  $img = wp_get_attachment_image_src($thumb_id, 'thumb-square')[0];
} else {
  $rand = array_rand($fallback_gallery, 1);
  $img = $fallback_gallery[$rand]['sizes']['thumb-square'];
}

?>

<article>
  <a class="item masonry--item overlay bg-light-gray" href="<?= get_the_permalink($id); ?>">
    <div class="ratio-container ratio-container--thumb-square loading">
      <img class="lazyload w-100" data-src="<?= $img; ?>" alt="" data-expand="-10">
    </div>
    <div class="item--details">
      <h2 class="heading-reset text-white serif"><?= mb_strimwidth(get_the_title($id), 0, 60, '&hellip;'); ?></h2>
      <div class="db overlay-content--hide mt1">
        <div class="press-source--border">
          <strong class="press-source text-white"><?= get_field('press_source', $id); ?></strong>
        </div>
        <span class="db text-white"><?= get_field('press_publish_date', $id); ?></span>
      </div>
    </div>
    <div class="overlay-content">
      <span class="btn btn-outline btn-lg text-white"><?= helpers\get_press_link_text($id); ?></span>
  </div>
  </a>
</article>
