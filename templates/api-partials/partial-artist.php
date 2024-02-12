<article class="partial partial--artist row flex-ns items-center">
  <div class="cs5 first-col">
    <h2 class="size-h4-ns size-h3 mt0 mb0-ns mb2">
      <a href="<?= get_the_permalink($id); ?>"><?= get_the_title($id) ?></a>
    </h2>
  </div>
  <div class="birth-death cs5">
    <?php
    $dates =  helpers\get_artist_display_dates($id);
    if ( $dates ) : ?>
      <span class="birth-death__bpy"><?= $dates['birth']; ?></span>
      <span class="birth-death__sep">–</span>
      <span class="birth-death__dpy"><?= $dates['death']; ?></span>
    <?php endif; ?>
  </div>
  <div class="cs2 works">
    <?php
    $works = get_post_meta($id, 'artist_collection_items_count', true);
    $works_label = $works == 1 ? 'work' : 'works';
    ?>
    <strong><?= $works; ?></strong>
    <?= $works_label; ?>
  </div>
</article>