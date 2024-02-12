<?php
use Roots\Sage\Extras;
?>

<div class="container">
  <div class="row">
    <div class="tab-pane-content">
      <?php the_field('technical_summary'); ?>
    </div>
  </div>
</div>

<?php if ( have_rows('tech_endnotes') ) : ?>
<footer class="entry-footer container">
  <div class="entry-footer__section">
    <div class="row">

      <div class="entry-footer__icon-wrapper">
        <button class="btn btn-collapse collapsed" type="button" data-toggle="collapse" data-target="#techEndnotes" aria-expanded="false" aria-controls="techEndnotes">
          <div class="closed-text">
            <span class="sr-only"><?= __('Expand Tech Endnotes', 'sage'); ?></span>
            <span aria-hidden="true"><?= Extras\icons('plus-circle', 40, 40); ?></span>
          </div>
          <div class="open-text">
            <span class="sr-only"><?= __('Collapse Tech Endnotes', 'sage'); ?></span>
            <span aria-hidden="true"><?= Extras\icons('minus-circle', 40, 40); ?></span>
          </div>
        </button>
      </div>

      <div class="entry-footer__section-header entry-footer__section-header--lg">
        <?= __('Technical Summary Endnotes', 'sage'); ?>
      </div>

      <div class="entry-footer__section-content">
        <div class="collapse" id="techEndnotes">
          <ol class="endnote-list">
            <?php
            $i = 0;
            while ( have_rows('tech_endnotes') ) : the_row(); ?>
              <li id="tech-endnote-<?= $i; ?>-content">
                <?php the_sub_field('endnote'); ?>
              </li>
            <?php $i++; endwhile; ?>
          </ol>
        </div>
      </div>

    </div>
  </div>
</footer>
<?php endif; ?>