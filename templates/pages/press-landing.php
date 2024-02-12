<?php get_template_part('templates/press/archive-featured'); ?>

<div class="bg-gray-light pv4 tc mt4">
  <div class="container">
    <h2 class="size-h2"><?= __('Exhibitions', 'sage'); ?></h2>
    <div class="cards justify-center pb3">
      <?php
      $press_exbs = get_field('landing_pg_exhibitions', 'options');

      foreach ( $press_exbs as $e ) :
        $graphic = get_field('landing_page_graphic', $e);
        ?>
        <a href="<?= get_term_link($e->term_id); ?>" class="card card--exhibition">
          <div class="pad-inner">
            <?php if( $graphic ) { ?>
              <img src="<?= $graphic['sizes']['medium']; ?>" alt="">
            <?php } ?>
            <strong class="db upper mt3"><?= $e->name; ?></strong>
            <span class="thin"><?= __('Media Coverage'); ?></span>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</div>

