<?php

use Roots\Sage\Extras;

$author = helpers\get_the_entry_author($id);
?>

<aside class="rich-tooltip rich-tooltip--essay">

  <header class="rich-tooltip__header">
    <?php if ( has_post_thumbnail($id) ) : ?>
      <?= get_the_post_thumbnail($id, 'thumb-xs', ['class' => 'mb3']); ?>
    <?php endif; ?>
    <h4 class="rich-tooltip__title"><?= get_the_title($id); ?></h4>
    <span class="meta"><?php if ( $author ) echo 'by '.$author; ?></span>
  </header>

  <div class="rich-tooltip__content">
    <a href="<?= get_the_permalink($id); ?>" class="db" target="_blank">
      <?= __('Read Essay', 'sage'); ?>
    </a>
  </div>

</aside>