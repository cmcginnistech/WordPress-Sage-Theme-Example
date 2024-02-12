<?php
/**
 * Featured posts for current taxonomy.
 * Only show if we are on a tax page.
 */
if ( is_page_template('template-press-landing.php') || is_post_type_archive('press') ) {

  $featured_posts = get_field('press_featured_posts', 'options');
  $fallback_gallery = get_field('press_texture_gallery', 'options');

} elseif ( is_tax() ) {

  $query = get_queried_object();
  $taxonomy = $query->taxonomy;
  $term_id = $query->term_id;
  $featured_posts = get_field('featured_posts', $taxonomy . '_' . $term_id);
  $fallback_gallery = get_field('press_texture_gallery', 'option');

} else {
  return;
}

if ( !empty($featured_posts) && empty($_GET) ) : ?>

<div id="featuredPress" class="featured-press-section container">

  <?php if ( is_tax() || is_post_type_archive('press') ) : ?>
    <h2 class="tc size-h2 mb3 mt0"><?= __('Featured News & Media', 'sage'); ?></h2>
  <?php endif; ?>

  <div class="masonry masonry--grid">
    <div class="masonry--grid-sizer"></div>
    <?php foreach($featured_posts as $f) : ?>

      <?php
        $item_large = get_field('press_large_thumbnail', $f) ? 'masonry--item-2x' : '';

        if(has_post_thumbnail($f)) {
          $thumb_id = get_post_thumbnail_id($f);
          $img = wp_get_attachment_image_src($thumb_id, 'thumb-square')[0];
        } else {
          $rand = array_rand($fallback_gallery, 1);
          $img = $fallback_gallery[$rand]['sizes']['thumb-square'];
        }
      ?>
      <article>
        <a class="item masonry--item overlay bg-light-gray <?= $item_large; ?>"  href="<?= get_the_permalink($f); ?>">
          <div class="ratio-container ratio-container--thumb-square loading">
            <img class="lazyload w-100" data-src="<?= $img; ?>" alt="" data-expand="-10">
          </div>
          <div class="item--details">
            <h2 class="heading-reset text-white serif"><?= $item_large ? get_the_title($f) : mb_strimwidth(get_the_title($f), 0, 60, '&hellip;'); ?></h2>
            <div class="db mt1 overlay-content--hide">
              <div class="press-source--border">
                <strong class="press-source text-white"><?= get_field('press_source', $f); ?></strong>
              </div>
              <span class="db text-white"><?= get_field('press_publish_date', $f); ?></span>
            </div>
          </div>
          <div class="overlay-content">
            <span class="btn btn-outline btn-lg text-white"><?= helpers\get_press_link_text($f); ?></span>
          </div>
        </a>
      </article>
    <?php endforeach; ?>
  </div>
</div>

<?php endif; ?>