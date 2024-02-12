<?php

$exhibitions = get_posts([
  'post_type' => 'exhibition',
  'order' => 'ASC',
  'meta_query' => [
    [
      'key'     => 'end_date',
      'compare' => '<',
      'value'   => date('Ymd')
    ]
  ],
  'orderby' => 'meta_value'
]);

// Build array of past exhibitons by year.
$years = Exhibitions\group_past_exhibitions($exhibitions);

?>

<section class="bg-light-gray pv4">
<?php foreach($years as $year => $exhibitions) : ?>
  <div class="container pv4">
    <h2 class="section-title-underline size-h1"><em><?= $year; ?></em></h2>
    <div class="cards">
      <?php foreach($exhibitions as $id => $date) : ?>
        <?php
          $date_args = [
            'start_field'  => 'start_date',
            'end_field'    => 'end_date',
            'post_id'      => $id,
            'month_format' => 'F'
          ];
        ?>
        <a class="card card--exhibition card--half card-overlay" href="<?= get_the_permalink($id); ?>">
          <?php
            $light_img = get_field('exhibition_graphic_light', $id);
            $dark_img = get_field('exhibition_graphic_dark', $id);
          ?>
          <?php if($light_img) { ?>
            <div class="card-overlay--light" style="background-image:url('<?= $light_img['sizes']['large']; ?>')"></div>
          <?php } ?>
          <?php if($dark_img) { ?>
            <div class="card-overlay--dark" style="background-image:url('<?= $dark_img['sizes']['large']; ?>')"></div>
          <?php } ?>
          <div class="card-center-content">
            <h3 class="heading-reset sans-serif text-inherit text-uppercase"><?= get_the_title($id); ?></h3>
            <span class="block"><?= get_field('exhibition_short_location', $id); ?></span>
            <time class="block serif fw-400"><em><?= Exhibitions\the_date_range($date_args); ?></em></time>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
<?php endforeach; ?>
</section>