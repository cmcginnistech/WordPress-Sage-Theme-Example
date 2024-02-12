<?php

use Roots\Sage\Extras;

?>

<?php if ( have_rows('entry_appendix') ) : ?>
  <?php
    $appendix_count = count(get_field('entry_appendix'));
    $title = $appendix_count > 1 ? 'Appendices' : 'Appendix';
  ?>
<div class="entry-footer__section">
  <div class="row">

    <div class="entry-footer__icon-wrapper">
      <button class="btn btn-collapse collapsed" type="button" data-toggle="collapse" data-target="#entryAppendix" aria-expanded="false" aria-controls="entryAppendix">
        <div class="closed-text">
          <span class="sr-only"><?= __("Expand {$title}", 'sage'); ?></span>
          <span aria-hidden="true"><?= Extras\icons('plus-circle', 40, 40); ?></span>
        </div>
        <div class="open-text">
          <span class="sr-only"><?= __("Collapse {$title}", 'sage'); ?></span>
          <span aria-hidden="true"><?= Extras\icons('minus-circle', 40, 40); ?></span>
        </div>
      </button>
    </div>

    <div class="entry-footer__section-header">
      <?= __("{$title}", 'sage'); ?>
    </div>

    <div class="entry-footer__section-content">
      <div class="collapse" id="entryAppendix">
        <?php while ( have_rows('entry_appendix') ) : the_row(); ?>
          <div class="appendix">
            <?php the_sub_field('appendix'); ?>
          </div>
        <?php endwhile; ?>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>
