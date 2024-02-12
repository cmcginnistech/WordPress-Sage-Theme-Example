<?php use Roots\Sage\PDF_Functions; ?>

<div class="row">
  <div class="entry-content">
    <?= apply_filters('the_content', get_sub_field('text')); ?>

    <?php
    /*
    the entry author is hidden by default and shown
    with CSS on the last component of supported pages.
    */
    $author = helpers\get_the_entry_author();
	$translator = get_field('translator_creditor');
    if ( $author && !PDF_Functions\doing_pdf_gen() ) : ?>
      <div class="entry-attribution">
          <?= $author; ?>
      </div>
    <?php endif; ?>
	
	<?php if($translator) : ?>
	<div class="">
          <?= $translator; ?>
    </div>
	<?php endif; ?>

    <?php
    /*
    the entry essays is hidden by default and shown
    with CSS on the last component of supported pages.
    */
    $related_essays = helpers\get_related_essay_links();
    if ( $related_essays && !PDF_Functions\doing_pdf_gen() ) : ?>
      <div class="entry-related-essays">
        <?= $related_essays; ?>
      </div>
    <?php endif; ?>
  </div>
  <?php if ( !PDF_Functions\doing_pdf_gen() ) : ?>
    <?php get_template_part('templates/partials/comp-figs'); ?>
  <?php endif; ?>
</div>