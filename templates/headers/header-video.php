<?php
use Roots\Sage\Extras;
?>

<div class="page-header--hero video-stage">
  <div class="container">
    <div class="embed-responsive embed-responsive-16by9">
      <?php the_field('video_url'); ?>
    </div>
  </div>
</div>

<div class="bg-gray-light pv4 mb5">
  <div class="container">

    <div class="row">
      <div class="cs8">
        <h1>
          <?php if ( $override_title = get_field('video_title_display') ) {
            echo $override_title;
          } else {
            echo get_the_title();
          } ?>
        </h1>
        <?php the_field('description'); ?>
      </div>
      <div class="cs4 video__related-content">
        <?php
        $related_artwork = get_field('artwork_to_video_2way');
        $related_groups = get_field('group_to_video_2way');
        $related_essays = get_field('essay_to_video_2way');

        if ( $related_groups ) {
          $related = $related_groups;
          $heading = __('Related Groups', 'sage');
        } elseif ( $related_essays ) {
          $related = $related_essays;
          $heading = __('Related Essays', 'sage');
        } else {
          $related = $related_artwork;
          $heading = __('Related Artwork', 'sage');
        }
        if (!empty($related)) :
          ?>
          <strong><?= $heading; ?></strong>
          <ul class="mb5">
            <?php foreach ( $related as $item ) : ?>
              <li>
                <a class="serif" href="<?= get_the_permalink($item->ID); ?>"><?= get_the_title($item->ID); ?></a>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
        <?php get_template_part('templates/partials/post-utility-buttons'); ?>
      </div>
    </div>

  </div>
</div>