<?php

use Roots\Sage\Extras;
use Roots\Sage\IIIF;

$viewer = new Leiden_Viewer();
$views = $viewer->get_views();
$artwork = $viewer->get_artwork_on_view();

?>

<nav class="viewer-nav">

  <div id="viewerZoom" class="menu-section viewer-eligible">
    <div class="controls-wrapper">
      <span class="control-label upper">Zoom</span>
      <button class="btn" data-type="in" title="Zoom In">
        <span class="sr-only">Zoom In</span>
        <?= Extras\icons('plus-circle-sm', 24, 24); ?>
      </button>
      <button class="btn" data-type="out" title="Zoom Out">
        <span class="sr-only">Zoom Out</span>
        <?= Extras\icons('minus-circle-sm', 24, 24); ?>
      </button>
    </div>
  </div>

  <?php if ( count($views) > 1 ): ?>

    <div id="viewerViews" class="menu-section viewer-eligible viewer-control">
      <form class="form-inline" role="viewer" id="viewerViewsForm">
        <div class="form-group">
          <label class="control-label upper">Views</label>

          <div class="btn-group" role="group" aria-label="available views">
            <?php if ( array_key_exists( 'vis', $views ) ) : ?>
              <button class="btn active view-trigger" data-type="vis" title="Visible">V<span class="sr-only">Visible spectrum</span></button>
            <?php endif; ?>

            <?php if ( array_key_exists( 'xra', $views ) ) : ?>
              <button class="btn view-trigger" data-type="xra" title="X-ray">XR<span class="sr-only">X-ray</span></button>
            <?php endif; ?>

            <?php if ( array_key_exists( 'irr', $views ) ) : ?>
              <button class="btn view-trigger" data-type="irr" title="Infrared">IR<span class="sr-only">infrared</span></button>
            <?php endif; ?>
          </div>

        </div>
      </form>
    </div>

  <?php endif; ?>

  <?php if ( count($views) > 1 ) : ?>

    <div id="viewerModes" class="menu-section viewer-eligible viewer-control">
      <form class="form-inline" role="viewer" id="viewerModesForm">
        <div class="form-group">
          <label class="control-label upper" for="search">Modes</label>

          <div class="btn-group" role="group" aria-label="available views">
            <button class="btn active uppercase mode-trigger mode-curtain" data-mode="curtain" data-toggle="tooltip" data-placement="bottom" title="Curtain View">
              <span class="sr-only">Curtain view</span>
              <?= Extras\icons('mode-curtain', 24, 24); ?>
            </button>

            <button class="btn mode-trigger mode-syncs" data-mode="sync" data-toggle="tooltip" data-placement="bottom" title="Sync View">
              <span class="sr-only">Sync View</span>
              <?= Extras\icons('mode-sync', 24, 24); ?>
            </button>
          </div>

        </div>
      </form>
    </div>

  <?php endif; ?>

  <div id="viewerInfo" class="menu-section viewer-info">
    <button id="viewerInfoToolTip" class="btn" data-tooltip-content="#viewerInfoToolTip-content">
      <span class="sr-only">Veiwer Info</span>
      <?= Extras\icons('info-circle', 24, 24); ?>
    </button>
  </div>

  <?php if ( !empty( get_field('iiif_image_types', $artwork->ID ) ) ) : ?>
    <div id="iiifInfo" class="menu-section iiif-info">
      <button id="iiifInfoTooltip" class="btn" data-tooltip-content="#iiifInfoTooltip-content">
        <span class="sr-only">IIIF Info</span>
        <img src="<?= get_template_directory_uri(); ?>/dist/images/logos/iiif-logo-white.png" width="28" height="28" alt="" />
      </button>
    </div>
  <?php endif; ?>

</nav>


<div class="hidden">
  <div id="viewerInfoToolTip-content">
    <p>
      <?= __('Leiden Viewer', 'sage'); ?></br>
      <?= __('Created by Ian Gilman', 'sage'); ?>.</br>
    </p>
    <!-- <p id="get_url_wrapper">
      <button id="shareThisView" class="get-url-trigger">
        <?= __('Share this view', 'sage'); ?>
      </button>
    </p> -->
  </div>

  <div id="iiifInfoTooltip-content" class="viewer-iiif-tooltip">
    <?php the_field('about_iiif_text', 'options'); ?>
    <p>
      <label class="db">IIIF Manifest Link
        <input
          type="text"
          id="iiifManifestLinkInput"
          class="form-control"
          value="<?= IIIF\get_iiif_link($viewer->get_artwork_on_view()); ?>"
          readonly
          onClick="javascript:document.getElementById('iiifManifestLinkInput').select()"
        />
      </label>
    </p>
  </div>
</div>