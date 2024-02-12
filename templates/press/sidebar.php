<?php
$ex = get_the_terms($post->ID, 'press_exhibition');
if( !empty( $ex ) && !is_wp_error($ex) ): ?>
<dl>
  <dt><?= __('Category', 'sage'); ?></dt>
  <?php foreach ($ex as $e) { ?>
  <dd>
    <a href="<?= esc_url(get_term_link($e->term_id, 'press_exhibition')); ?>">
      <?= $e->name; ?>
    </a>
  </dd>
  <?php } ?>
</dl>
<?php endif; ?>

<?php
$cats = get_the_terms($post->ID, 'press_category');
if( !empty( $cats ) && !is_wp_error($cats) ): ?>
<dl>
  <dt><?= __('News Type', 'sage'); ?></dt>
  <?php foreach( $cats as $cat ) { ?>
    <dd>
      <a href="<?= esc_url(get_term_link($cat->term_id, 'press_category')); ?>">
        <?= $cat->name; ?>
      </a>
    </dd>
  <?php } ?>
</dl>
<?php endif; ?>