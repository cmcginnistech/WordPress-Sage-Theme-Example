<?php

use Roots\Sage\Extras;

$date_args = [
  'start_field'  => 'start_date',
  'end_field'    => 'end_date',
  'post_id'      => $post->ID,
  'month_format' => 'F'
];

$press_exhibition_term = get_field('news_media_link');
$view_remarks = get_field('view_remarks');
?>

<?php if ($press_exhibition_term) : ?>
  <a class="btn btn-outline btn-lg btn-caret-right mb4" href="<?= get_term_link($press_exhibition_term, 'press_exhibition'); ?>" target="_blank"><?= __('View News & Media'); ?></a>
<?php endif; ?>

<?php if ($view_remarks) : ?>
  <a class="btn btn-outline btn-lg btn-caret-right mb4" href="<?php echo $view_remarks; ?>" target="_blank"><?= __('View Remarks'); ?></a>
<?php endif; ?>

<dl>
  <dt class="text-uppercase"><?= __('Dates', 'sage'); ?></dt>
  <dd>
    <?php if ( $date_override = get_field('date_override') ) : ?>
      <?= $date_override; ?>
    <?php else : ?>
      <time class="db"><?= Exhibitions\the_date_range($date_args); ?></time>
    <?php endif; ?>
  </dd>
</dl>

<?php if(get_field('location')) { ?>
<dl>
  <dt class="text-uppercase"><?= __('Location', 'sage'); ?></dt>
  <dd><?= get_field('location'); ?></dd>
</dl>
<?php } ?>

<?php if(get_field('opening_hours')) { ?>
<dl>
  <dt class="text-uppercase"><?= __('Opening Hours', 'sage'); ?></dt>
  <dd><?= get_field('opening_hours'); ?></dd>
</dl>
<?php } ?>

<?php if(get_field('admission')) { ?>
<dl>
  <dt class="text-uppercase"><?= __('Admission', 'sage'); ?></dt>
  <dd><?= get_field('admission'); ?></dd>
</dl>
<?php } ?>

<?php if($cta = get_field('more_information_button')) {
  helpers\acflink($cta, 'btn btn-lg btn-outline btn-caret-right');
} ?>
