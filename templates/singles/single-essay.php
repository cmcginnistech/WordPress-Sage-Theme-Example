<?php
use Roots\Sage\Extras;

$videos = get_field('essay_to_video_2way');
?>

<div class="entry-tab-container">

  <!-- Tabs -->
  <nav class="entry-tabnav container">
    <ul class="nav nav-tabs" role="tablist">
      <li role="presentation" class="active">
        <a href="#essay" aria-controls="essay" role="tab" data-toggle="tab">Essay</a>
      </li>
      <?php if ( !empty($videos) ) : ?>
        <li role="presentation">
          <a href="#media" aria-controls="media" role="tab" data-toggle="tab">Media</a>
        </li>
      <?php endif; ?>
    </ul>
  </nav>

  <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="essay">
      <?php Extras\componify(); ?>
      <?php get_template_part('templates/partials/entry-footer'); ?>
    </div>

    <?php if ( !empty($videos) ) : ?>
    <div role="tabpanel" class="tab-pane" id="media">
      <div class="container mb5">
        <div class="row flex flex-wrap">

          <?php
          if ( !empty($videos) ) {
            foreach ( $videos as $video ) {
              $id = $video->ID;
              include(locate_template('templates/api-partials/partial-video.php'));
            }
          } ?>

        </div>
      </div>
    </div>
    <?php endif; ?>
  </div>
</div>