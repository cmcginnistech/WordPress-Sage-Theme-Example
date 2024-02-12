<article class="partial partial--bibliography flex-ns row">
  <div class="cs4 first-col">
    <h2 class="size-h4 mt0">
      <?php
      if ( $override_title = get_field('bibli_alt_title', $id) ) {
        echo $override_title;
      } else {
        echo get_the_title($id);
      } ?>
    </h2>
  </div>
  <div class="cs2 tc-ns bibliography-date sans-serif mb2 mb0-ns">
    <?php the_field('bibli_display_date', $id); ?>
  </div>
  <div class="cs6">
    <div class="excerpt">
      <?php the_field('bibliography_text', $id); ?>
    </div>
  </div>
</article>
