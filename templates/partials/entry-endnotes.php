<?php

use Roots\Sage\Extras;

$the_id = get_the_ID();
$group = helpers\get_the_group( $the_id );

// if an artwork is part of a group, display the group's endnotes
if ( $group ) {
  $the_id = $group->ID;
}
?>

<?php if ( have_rows('entry_endnotes', $the_id) ) : ?>
<div class="entry-footer__section">
  <div class="row">

    <div class="entry-footer__icon-wrapper">
      <button class="btn btn-collapse collapsed" type="button" data-toggle="collapse" data-target="#entryEndnotes" aria-expanded="false" aria-controls="entryEndnotes">
        <div class="closed-text">
          <span class="sr-only"><?= __('Expand Endnotes', 'sage'); ?></span>
          <span aria-hidden="true"><?= Extras\icons('plus-circle', 40, 40); ?></span>
        </div>
        <div class="open-text">
          <span class="sr-only"><?= __('Collapse Endnotes', 'sage'); ?></span>
          <span aria-hidden="true"><?= Extras\icons('minus-circle', 40, 40); ?></span>
        </div>
      </button>
    </div>

    <div class="entry-footer__section-header">
      <?= __('Endnotes', 'sage'); ?>
    </div>

    <div class="entry-footer__section-content">
      <div class="collapse" id="entryEndnotes">
        <ol class="endnote-list">
          <?php
          $i = 0;
          while ( have_rows('entry_endnotes', $the_id) ) : the_row(); ?>
            <li id="endnote-<?= $i; ?>-content">
              <?php the_sub_field('endnote'); ?>
            </li>
          <?php $i++; endwhile; ?>
        </ol>
      </div>
    </div>

  </div>
</div>
<?php endif; ?>