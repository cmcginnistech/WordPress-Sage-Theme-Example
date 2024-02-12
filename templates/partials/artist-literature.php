<?php use Roots\Sage\Extras; ?>

<?php if ( $lit = get_field('literature') ) : ?>
<div class="entry-footer__section">
  <div class="row">

    <div class="entry-footer__icon-wrapper">
      <button class="btn btn-collapse collapsed" type="button" data-toggle="collapse" data-target="#artistLit" aria-expanded="false" aria-controls="artistLit">
        <div class="closed-text">
          <span class="sr-only"><?= __('Expand Literature', 'sage'); ?></span>
          <span aria-hidden="true"><?= Extras\icons('plus-circle', 40, 40); ?></span>
        </div>
        <div class="open-text">
          <span class="sr-only"><?= __('Collapse Literature', 'sage'); ?></span>
          <span aria-hidden="true"><?= Extras\icons('minus-circle', 40, 40); ?></span>
        </div>
      </button>
    </div>

    <div class="entry-footer__section-header">
      <?= __('Literature', 'sage'); ?>
    </div>

    <div class="entry-footer__section-content">
      <div class="collapse" id="artistLit">
        <div class="endnote-list">
          <?= $lit; ?>
        </div>
      </div>
    </div>

  </div>
</div>
<?php endif; ?>