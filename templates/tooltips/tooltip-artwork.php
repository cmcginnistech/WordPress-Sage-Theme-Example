<?php
$artist = get_field('artwork_to_artist_2way', $id);
?>

<aside class="rich-tooltip rich-tooltip--artwork">

  <header class="rich-tooltip__header">
    <?php if ( has_post_thumbnail($id) ) : ?>
      <?= get_the_post_thumbnail($id, 'thumb-xs', ['class' => 'rich-tooltip__image']); ?>
    <?php endif; ?>
    <div class="overflow-hidden">
      <h4 class="rich-tooltip__title"><?= get_the_title($id); ?></h4>
      <span class="meta">
        <?= helpers\get_artwork_artist($id); ?>
      </span>
    </div>
  </header>

  <div class="rich-tooltip__content">
    <div class="excerpt">
      <?php include(locate_template('templates/artwork/tombstone.php')); ?>
    </div>

    <a href="<?= get_the_permalink($id); ?>" class="db" target="_blank">
      <?= __('View Artwork', 'sage'); ?>
    </a>
    <a href="<?= get_the_permalink($artist->ID); ?>" class="db" target="_blank">
      <?= __('Read Artist Bio', 'sage'); ?>
    </a>
  </div>

</aside>