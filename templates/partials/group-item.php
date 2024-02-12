<?php
/**
 * @var object | WP_Post
 */

$artist = helpers\get_artwork_artist($artwork->ID, true);
$current_obj_class = '';
$is_current = false;

if ( $artwork->ID === $post->ID ) {
  $current_obj_class = 'current tooltip';
  $is_current = true;
}
?>

<li class="group-item <?= esc_attr($current_obj_class); ?>" <?php if($is_current) echo 'data-tooltip-content="#currentGroupItemTooltip"'; ?>>
  <a href="<?= get_the_permalink($artwork->ID); ?>">
    <?= get_the_post_thumbnail($artwork->ID, 'thumbnail', ['class' => 'fl mr3']); ?>
    <div class="overflow-hidden">
      <span class="title"><?= get_the_title($artwork->ID); ?></span>
      <small class="artist"><?= $artist; ?></small>
    </div>
  </a>
</li>

<?php if ( $is_current ) : ?>
<div class="hidden">
  <div id="currentGroupItemTooltip">
    <?= __('You are currently viewing this work', 'sage'); ?>
  </div>
</div>
<?php endif; ?>