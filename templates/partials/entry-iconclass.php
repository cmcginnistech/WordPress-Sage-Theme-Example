<?php

use Roots\Sage\Extras;

$iconclass_terms = [];

if ( get_post_type() == 'artwork' ) {
  $iconclass_terms = get_the_terms($post->ID, 'iconclass');
  $iconClass_termsv2 = get_field('iconclass_terms_v2', $post->ID);
  $iconHide = get_field('hide_iconclass', $post->ID);
}
?>
<?php if(!$iconHide) : ?>
<?php if ( !empty($iconclass_terms) || !empty($iconClass_termsv2) ) : ?>
<div class="entry-footer__section">
  <div class="row">

    <div class="entry-footer__icon-wrapper">
      <button class="btn btn-collapse collapsed" type="button" data-toggle="collapse" data-target="#entryIconclass" aria-expanded="false" aria-controls="entryIconclass">
        <div class="closed-text">
          <span class="sr-only"><?= __('Expand Iconclass', 'sage'); ?></span>
          <span aria-hidden="true"><?= Extras\icons('plus-circle', 40, 40); ?></span>
        </div>
        <div class="open-text">
          <span class="sr-only"><?= __('Collapse Iconclass', 'sage'); ?></span>
          <span aria-hidden="true"><?= Extras\icons('minus-circle', 40, 40); ?></span>
        </div>
      </button>
    </div>

    <div class="entry-footer__section-header">
      <?= __('Iconclass Terms', 'sage'); ?>
      <sup class="tooltip tooltip--info" data-tooltip-content="#iconClassDef">
        <?= Extras\icons('info-circle', 18, 18); ?>
      </sup>
    </div>

    <div class="entry-footer__section-content">
      <div class="collapse" id="entryIconclass">
      <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="embed-responsive embed-responsive-4by3">
          <?php if($post->ID === 1957) { ?>
            <ul class="iconclass-term-list">
              <?php foreach( $iconclass_terms as $term ) : ?>
                <li class="iconclass-term"><?= $term->name; ?></li>
              <?php endforeach; ?>
            </ul>
          <?php } else {?>
            <iframe src="https://www.arkyves.org/HIM/TLC/viewic?id=<?php the_field('inventory_number', $post->ID); ?>" frameborder="0" class="embed-responsive-item"></iframe>
          <?php }?>
        </div>
      </div>
    </div>
   </div>
      </div>
    </div>

  </div>
</div>

<div class="hidden">
  <div id="iconClassDef">
    <?php
    $link = '<a href="/iconclass">https://theleidencollection.com/iconclass</a>';
    echo sprintf( __('Iconclass is a classification system pertaining to art and iconography, designed to describe and classify subjects, themes, and motifs represented in images. Learn more at %s.'), $link );
    ?>
  </div>
</div>
<?php endif; ?>
<?php endif; ?>