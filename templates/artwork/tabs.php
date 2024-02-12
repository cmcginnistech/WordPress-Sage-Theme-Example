<ul class="nav nav-tabs" role="tablist" id="artwork-tabs">

  <li role="presentation" class="active">
    <a href="#entry" aria-controls="entry" role="tab" data-toggle="tab">Entry</a>
  </li>

  <?php if ( get_field('references_text') ) : ?>
  <li role="presentation">
    <a href="#references" aria-controls="references" role="tab" data-toggle="tab">References</a>
  </li>
  <?php endif; ?>

  <?php if ( get_field('exhibitions_text') ) : ?>
  <li role="presentation">
    <a href="#exhibitions" aria-controls="exhibitions" role="tab" data-toggle="tab">Exhibition History</a>
  </li>
  <?php endif; ?>

  <?php if ( get_field('provenance_text') ) : ?>
  <li role="presentation">
    <a href="#provenance" aria-controls="provenance" role="tab" data-toggle="tab">Provenance</a>
  </li>
  <?php endif; ?>

  <?php if ( get_field('technical_summary') ) : ?>
  <li role="presentation">
    <a href="#tech-summary" aria-controls="tech-summary" role="tab" data-toggle="tab">Technical Summary</a>
  </li>
  <?php endif; ?>

  <?php if ( get_field('versions_text') ) : ?>
  <li role="presentation">
    <a href="#versions" aria-controls="versions" role="tab" data-toggle="tab">Versions</a>
  </li>
  <?php endif; ?>

  <?php
  $videos = (get_post_type() == 'group') ? get_field('group_to_video_2way') : get_field('artwork_to_video_2way');
  if ( !empty($videos) ) : ?>
  <li role="presentation">
    <a href="#media" aria-controls="media" role="tab" data-toggle="tab">Media</a>
  </li>
  <?php endif; ?>

</ul>