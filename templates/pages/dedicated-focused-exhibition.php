<?php
use Roots\Sage\Extras;
use Roots\Sage\Titles;

$hero = get_field('image');

$date_args = [
  'start_field'  => 'start_date',
  'end_field'    => 'end_date',
  'post_id'      => $post->ID,
  'month_format' => 'F'
];

?>

<header class="page-header--hero page-header--exhb bg-black">
  <div class="container">
    <div class="row">
      <div class="hero-content hero-content--exhibition">
        <h1 class="size-h1-plus text-white"><?= Titles\title(); ?></h1>
        <div class="mt2">
          <?php if ( $date_override = get_field('date_override') ) : ?>
            <?= $date_override; ?>
          <?php else : ?>
            <time class="db"><?= Exhibitions\the_date_range($date_args); ?></time>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
  <figure class="loading">
    <div class="lazyload bg-cover bg-cover--artwork bg-cover-overlay--left plax" data-bgset="<?= $hero['sizes']['large']; ?>"></div>
    <?php if($caption = get_field('caption')) : ?>
    <figcaption>
      <div class="container">
        <div class="hero-caption-content">
          <?= $caption; ?>
            <?php if($desc = get_field('description')) {
              echo $desc;
            } ?>
        </div>
      </div>
    </figcaption>
    <?php endif; ?>
  </figure>
</header>


<article class="container">
  <div class="row mt4 mb4 mb5-ns">
    <div class="cs8 entry-content">
      <?php the_field('introductory_content'); ?>
    </div>
    <div class="cs1"></div>
    <aside class="single-exhibition__sidebar cs3">
      <?php get_template_part('templates/exhibitions/sidebar'); ?>
    </aside>
  </div>

  <?php if(have_rows('components')) : ?>
  <div class="row">
    <?php Extras\componify(); ?>
  </div>
  <?php endif; ?>
</article>