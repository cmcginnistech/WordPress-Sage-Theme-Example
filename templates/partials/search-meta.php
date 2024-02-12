<?php

use Roots\Sage\Extras;

/**
 * @var string | $post_type
 * @var string | $the_permalink
 */
?>

<?php if ( $post_type == 'artwork' ) : ?>
  <span class="size-h4 artist-name db serif"><?= helpers\get_artwork_artist($id); ?></span>
  <?php include(locate_template('templates/artwork/tombstone.php')); ?>


<?php elseif ( $post_type == 'group' ) :
  $group_artists = helpers\get_group_artists($id);
  ?>
  <span class="size-h4 mb3 db serif">
    <?php foreach ( $group_artists as $artist ) {
      echo $artist->post_title .'<br>';
    } ?>
  </span>


<?php elseif ( $post_type == 'artist' ) : ?>
  <?php if ( $dates = helpers\get_artist_display_dates($id) ) : ?>
    <em class="size-h4 serif">(<?= $dates['birth']; ?> – <?= $dates['death']; ?>)</em>
  <?php endif; ?>


<?php elseif ( $post_type == 'video' ) : ?>
  <?= get_field('description', $id); ?>
  <a href="<?= esc_url($the_permalink); ?>">
    Watch Video <?= Extras\icons('video', 10, 10); ?>
  </a>


<?php elseif ( $post_type == 'essay' ) : ?>
  <?php if ( $author = get_field('entry_author', $id) ) : ?>
    <em class="size-h4 serif text-italic">by <?= $author; ?></em>
  <?php endif; ?>


<?php endif; ?>