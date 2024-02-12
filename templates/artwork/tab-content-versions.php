<?php
use Roots\Sage\Extras;
?>

<div class="container">
  <div class="row">
    <div class="tab-pane-content">
      <?php the_field('versions_text'); ?>
    </div>
  </div>
</div>

<?php if ( have_rows('versions_notes') ) : ?>
<footer class="entry-footer container">
  <div class="entry-footer__section">
    <div class="row">

      <div class="entry-footer__icon-wrapper">
        <button class="btn btn-collapse collapsed" type="button" data-toggle="collapse" data-target="#versionsEndnotes" aria-expanded="false" aria-controls="versionsEndnotes">
          <div class="closed-text">
            <span class="sr-only"><?= __('Expand Version Notes', 'sage'); ?></span>
            <span aria-hidden="true"><?= Extras\icons('plus-circle', 40, 40); ?></span>
          </div>
          <div class="open-text">
            <span class="sr-only"><?= __('Collapse Version Notes', 'sage'); ?></span>
            <span aria-hidden="true"><?= Extras\icons('minus-circle', 40, 40); ?></span>
          </div>
        </button>
      </div>

      <div class="entry-footer__section-header entry-footer__section-header--lg">
        <?= __('Versions Notes', 'sage'); ?>
      </div>

      <div class="entry-footer__section-content">
        <div class="collapse" id="versionsEndnotes">
          <ol class="endnote-list">
            <?php
            $i = 0;
            while ( have_rows('versions_notes') ) : the_row(); ?>
              <li id="ver-note-<?= $i; ?>-content">
                <?php the_sub_field('version_note'); ?>
              </li>
            <?php $i++; endwhile; ?>
          </ol>
        </div>
      </div>

    </div>
  </div>
</footer>
<?php endif; ?>