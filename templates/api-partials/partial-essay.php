<?php use Roots\Sage\Extras; ?>

<article class="partial partial--essay row flex-ns">
  <div class="cs3 essay-row-image">
    <figure class="overlay dib">
      <?= get_the_post_thumbnail($id, 'thumb-medium'); ?>
    </figure>
  </div>
  <div class="cs4 essay-row-header">
    <h2 class="size-root-2x-ns size-h3 mt0">
      <a href="<?= get_the_permalink($id); ?>"><?= get_the_title($id) ?></a>
    </h2>
    <em class="serif color-stormy size-h4">by <?= get_field('entry_author', $id); ?></em>
  </div>
  <div class="cs5 essay-row-excerpt">
    <div class="excerpt">
      <?= Extras\acf_excerpt($id); ?>
    </div>
  </div>
</article>
