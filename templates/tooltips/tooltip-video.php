<?php

use Roots\Sage\Extras;

?>

<aside class="rich-tooltip rich-tooltip--video">

  <header class="rich-tooltip__header">
    <?php if ( has_post_thumbnail($id) ) : ?>
      <?= get_the_post_thumbnail($id, 'thumb-xs', ['class' => 'rich-tooltip__image']); ?>
    <?php endif; ?>
    <div class="overflow-hidden">
      <h4 class="rich-tooltip__title"><?= get_the_title($id); ?></h4>
    </div>
  </header>

  <div class="rich-tooltip__content">
    <a href="<?= get_the_permalink($id); ?>" class="db" target="_blank">
      <?= __('Watch Video', 'sage'); ?>
      <span aria-hidden="true"> <?= Extras\icons('video', 16, 16); ?></span>
    </a>
  </div>

</aside>