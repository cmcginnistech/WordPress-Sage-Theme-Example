<?php

use Roots\Sage\Extras;

$dates =  helpers\get_artist_display_dates($id);
$num_works = (integer) get_post_meta($id, 'artist_collection_items_count', true);
$works_label = $num_works > 1 ? 'works' : 'work';
?>

<aside class="rich-tooltip rich-tooltip--artist">

  <header class="rich-tooltip__header">
    <?php if ( has_post_thumbnail($id) ) : ?>
      <?= get_the_post_thumbnail($id, 'thumb-xs', ['class' => 'rich-tooltip__image']); ?>
    <?php endif; ?>
    <div class="overflow-hidden">
      <h4 class="rich-tooltip__title"><?= get_the_title($id); ?></h4>
      <span class="meta">
        <?php
        if ( $dates ) {
          echo "({$dates['birth']} – {$dates['death']})";
        } ?>
      </span>
    </div>
  </header>

  <div class="rich-tooltip__content">
    <div class="excerpt">
      <?= Extras\acf_excerpt($id, 25); ?>
    </div>

    <a href="<?= get_the_permalink($id); ?>" class="db" target="_blank">
      <?= __('Read Biography', 'sage'); ?>
    </a>

    <?php if ( $num_works > 0 ) : ?>
      <a href="/collection/?c_artist=<?= esc_attr($id); ?>" class="db" target="_blank">
        <?= sprintf( __( 'View %u %s', 'sage' ), $num_works, $works_label); ?>
      </a>
    <?php endif; ?>
  </div>

</aside>