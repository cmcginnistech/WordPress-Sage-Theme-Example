<?php
use Roots\Sage\Extras;
?>

<div class="container">
  <div class="row">
    <div class="tab-pane-content">
      <?php the_field('provenance_text'); ?>
    </div>
  </div>
</div>

<?php if ( have_rows('provenance_remarks') ) : ?>
<footer class="entry-footer container">
  <div class="entry-footer__section">
    <div class="row">

      <div class="entry-footer__icon-wrapper">
        <button class="btn btn-collapse collapsed" type="button" data-toggle="collapse" data-target="#provRemarks" aria-expanded="false" aria-controls="provRemarks">
          <div class="closed-text">
            <span class="sr-only"><?= __('Expand Provenance Endnotes', 'sage'); ?></span>
            <span aria-hidden="true"><?= Extras\icons('plus-circle', 40, 40); ?></span>
          </div>
          <div class="open-text">
            <span class="sr-only"><?= __('Collapse Provenance Endnotes', 'sage'); ?></span>
            <span aria-hidden="true"><?= Extras\icons('minus-circle', 40, 40); ?></span>
          </div>
        </button>
      </div>

      <div class="entry-footer__section-header">
        <?= __('Provenance Endnotes', 'sage'); ?>
      </div>

      <div class="entry-footer__section-content">
        <div class="collapse" id="provRemarks">
          <ol class="endnote-list">
            <?php
            $i = 0;
            while ( have_rows('provenance_remarks') ) : the_row(); ?>
              <li id="prov-remark-<?= $i; ?>-content">
                <?php the_sub_field('remark'); ?>
              </li>
            <?php $i++; endwhile; ?>
          </ol>
        </div>
      </div>

    </div>
  </div>
</footer>
<?php endif; ?>