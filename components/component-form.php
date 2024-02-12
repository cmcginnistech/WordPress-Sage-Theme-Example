<?php

$form_obj = get_sub_field('gravity_form');

?>


<div class="row">
  <div class="form-wrapper">
    <?php if ( get_sub_field('heading') ) : ?>
      <h2><?php the_sub_field('heading'); ?></h2>
    <?php endif; ?>
    <?php the_sub_field('text'); ?>
    <?php gravity_form($form_obj['id'], false, false, false, '', true, 0); ?>
  </div>
</div>