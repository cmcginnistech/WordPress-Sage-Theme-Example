<?php
/**
 * Template Name: The Leiden Viewer
 */

use Roots\Sage\Extras;

$viewer = new Leiden_Viewer();
$artwork = $viewer->get_artwork_on_view();

// exit early if for some reason we don't have an artwork
if ( !$artwork ) {
  return;
}

// output the viewer markup
$viewer->the_viewer();

?>

<div class="viewer-overlay"></div>

<div class="viewer-bottom-controls">

  <div class="panel-group" id="accordion">
    <div class="panel panel-default">

      <div id="viewerDetails" class="panel-collapse collapse">
        <div class="panel-body">
          <div class="container">
            <div class="row">
              <div class="cs6">
                <h1 class="size-h1 text-italic mb0"><?= $artwork->post_title; ?></h1>
                <span class="artist"><?= helpers\get_artwork_artist($artwork->ID); ?></span>
                <?php
                $id = $artwork->ID;
                include(locate_template('templates/artwork/tombstone.php'));
                ?>
                <a href="<?= esc_url(get_the_permalink($artwork->ID)); ?>">
                  <?= __('View Full Entry', 'sage'); ?>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="panel-heading">
        <div class="container">

          <div class="menu-section">
            <button class="viewer-details-btn btn-collapse collapsed" data-toggle="collapse" data-target="#viewerDetails" aria-expanded="false" aria-controls="viewerDetails">
              <div class="closed-text">
                <span aria-hidden="true"><?= Extras\icons('plus-circle-sm', 24, 24); ?> </span>
                <?= __('Show Details', 'sage'); ?>
              </div>
              <div class="open-text">
                <span aria-hidden="true"><?= Extras\icons('minus-circle-sm', 24, 24); ?> </span>
                <?= __('Hide Details', 'sage'); ?>
              </div>
            </button>
          </div>

          <div id="viewerZoom" class="viewer-zoom-controls menu-section viewer-eligible visible-xs-inline-block">
            <div class="controls-wrapper">
              <span class="control-label upper">Zoom</span>
              <button class="btn" data-type="in" title="Zoom In">
                <?= Extras\icons('plus-circle-sm', 24, 24); ?>
              </button>
              <button class="btn" data-type="out" title="Zoom Out">
                <?= Extras\icons('minus-circle-sm', 24, 24); ?>
              </button>
            </div>
          </div>

        </div>
      </div>

    </div>
  </div>

</div>