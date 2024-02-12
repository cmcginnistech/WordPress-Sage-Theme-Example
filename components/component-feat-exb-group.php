<?php
use Roots\Sage\Extras;
 ?>

<div class="row mb4">
  <div class="cs3">
    <h2 class="mt0">
      <?php
      /*
      Check if we are passing in a link value for the heading title
      */
      if ( isset($section_title_link) ) : ?>
        <a class="section-title-link" href="<?= esc_url($section_title_link); ?>">
          <?php the_sub_field('heading'); ?>
        </a>
      <?php else : ?>
        <?php the_sub_field('heading'); ?>
      <?php endif; ?>
    </h2>
  </div>
  <div class="cs8 cso1">
    <?php the_sub_field('heading_text'); ?>
  </div>
</div>

<?php
  $exhibition = get_sub_field('featured_exhibition');
  $date_args = [
    'start_field'  => 'start_date',
    'end_field'    => 'end_date',
    'post_id'      => $exhibition->ID,
    'month_format' => 'F'
  ];
  $related = get_sub_field('related_content');
  $bg = get_field('image', $exhibition);
?>
<div class="flex-ns">
  <article class="flex flex-item w-100 w-50-ns pv3 overlay">
    <div
      class="bg-img lazyload"
      data-bgset="<?= $bg['sizes']['thumb-landscape']; ?>"
      data-expand="-10"
    ></div>
    <a href="<?= get_permalink($exhibition->ID); ?>" class="w-100 pa4 flex flex-column justify-between">
      <div class="exb-top-wrapper">
        <small class="upper bold db mb2"><?= __('Exhibition', 'sage'); ?></small>
        <h3 class="text-white size-h1 b heading-reset"><?= $exhibition->post_title; ?></h3>
      </div>
      <span class="text-white"><?= Exhibitions\the_date_range($date_args); ?></span>
    </a>
  </article>
  <div class="w-100 w-50-ns flex flex-wrap">
  <?php if(!empty($related)) : ?>
    <?php foreach($related as $item) : ?>
    <?php
      $thumb_id = get_post_thumbnail_id($item);
      $label = helpers\get_item_label($item);
    ?>
    <article class="flex flex-item w-50 pa2 overlay <?= get_post_type($item);?>">
      <div
        class="lazyload bg-img"
        data-bgset="<?= wp_get_attachment_image_src($thumb_id, "thumb-small")[0]; ?>" data-expand="-10"
      ></div>
      <a href="<?= get_permalink($item->ID); ?>" class="w-100 pa2 pa3-ns flex flex-column justify-between">
        <?php if(get_post_type($item) != 'artwork') : ?>
        <div class="exb-top-wrapper">
          <small class="upper bold dib mb2"><?= helpers\get_item_label($item); ?><?php if (get_post_type($item) == "video"){ echo Extras\icons('video', 10, 10); };?></small>
          <h3 class="text-white mt0 mb4 b "><?= $item->post_title; ?></h3>
        </div>
          <?php if(get_post_type($item) == 'press') { ?>
            <div class="exb-bottom-wrapper">
              <small class="upper bold db"><?php the_field('press_source', $item); ?></small>
              <small class="db exb-date"><?php the_field('press_publish_date', $item); ?></small>
            </div>
          <?php } ?>
        <?php else : ?>
          <div class="exb-icon dn db-ns"><?= Extras\icons('plus', 20, 20); ?></div>
          <div class="overlay-content">
            <strong class="db artist-name"><?= helpers\get_artwork_artist($item, true); ?></strong>
            <span class="db artwork-title"><?= $item->post_title; ?></span>
          </div>
        <?php endif; ?>
      </a>
    </article>
    <?php endforeach; ?>
  <?php endif; ?>
  </div>
</div>
