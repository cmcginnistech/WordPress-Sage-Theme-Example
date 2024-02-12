<?php

use Roots\Sage\Extras;

$images = get_sub_field('gallery_images');
$namespace = uniqid();
$i = 0;

// images in a row before "view all" button is shown
$max_default_imgs = 6;

?>

<div class="row">
  <div class="content-text tc">
  <?php if($heading = get_sub_field('heading')) : ?>
    <h2><?= $heading; ?></h2>
  <?php endif; ?>
  <div class="excerpt">
    <?= get_sub_field('intro_text'); ?>
  </div>
  </div>
</div>

<?php if($images) : ?>
  <div class="flex-gallery" id="modaal-gallery-<?= $namespace; ?>">
    <?php foreach($images as $image) : ?>
      <a
        href="<?= $image['sizes']['medium_large']; ?>"
        rel="modaal-gallery-<?= $namespace; ?>"
        class="gallery modaal-gallery image-wrapper"
        data-modaal-desc="<?= strip_tags($image['caption']); ?>"
        <?= $i >= $max_default_imgs ? 'hidden' : ''; ?>
      >
        <div class="ratio-container ratio-container--thumb-square loading">
          <img
            class="lazyload"
            data-src="<?= $image['sizes']['thumb-square']; ?>"
            alt="<?= sprintf('View image %s', $i); ?>"
            data-expand="-10"
          />
        </div>
      </a>
      <?php $i++; ?>
    <?php endforeach; ?>
  </div>
  <?php if(count($images) >= $max_default_imgs ) : ?>
  <div class="row">
    <div class="content-text tc">
      <button
        class="btn btn-outline btn-modaal-open mt3"
        data-gallery="modaal-gallery-<?= $namespace; ?>">
          <?= __('View All'); ?>
      </button>
    </div>
  </div>
  <?php endif; ?>
<?php endif; ?>