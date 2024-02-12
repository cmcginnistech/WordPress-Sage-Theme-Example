<?php
/**
 * Used on archive page template.
 * This template represents one tab panel.
 *
 * @param $tabs      | array (the other tabs)
 * @param $key       | integer (the tab number we are on)
 * @param $selectedTab  | gets selected tab from URL param
 * @param $post_type | string (tab name and post type)
 */

$artists = get_posts([
  'post_type'      => 'artist',
  'posts_per_page' => -1,
  'meta_key'       => 'artist_sort_name',
  'orderby'        => 'meta_value',
  'order'          => 'ASC',
]);

$artists_by_sort_name = [];

// need to create an array sorted by first letter of last name
foreach ( $artists as $post_obj ) {

  $sort_name = get_post_meta($post_obj->ID, 'artist_sort_name', true);
  $first_letter = $sort_name[0];

  $artists_by_sort_name[$first_letter][] = [
    'id' => $post_obj->ID,
    'title' => $post_obj->post_title,
    'sort_name' => $sort_name
  ];
}

?>

<div role="tabpanel" class="tab-pane pv5 <?php if($selectedTab  == 'artist') echo 'active'; ?>" id="<?= $post_type; ?>">

  <?php foreach ( $artists_by_sort_name as $letter_index => $artists ) : ?>
    <article class="row archive-artist">
      <div class="archive-artist__letter-wrapper cs2 tr">
        <h2 class="mr3-ns"><?= $letter_index; ?></h2>
      </div>
      <div class="archive-artist__artists-wrapper cs10 flex">
        <?php
        if ( !empty($artists) ) :
          foreach ( $artists as $artist ) :
            $archives = Archives\get_pdf_archives( $artist['id'] );
            if ( $archives ) :
              ?>
              <div class="archive-artist__single-artist">
              <a  id="<?= $artist['id']; ?>"style="padding-top: 40px; margin-top: -40px; display:block;" name="<?= $artist['id']; ?>"></a>

                <h3><?= $artist['title']; ?></h3>
                <?php
                /**
                 * Get all PDF archives for this artist.
                 * (must pass through $archives variable)
                 */
                include(locate_template('templates/partials/pdf-archive-list.php'));
                ?>
              </div>
            <?php endif; ?>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </article>
    <hr class="visible-xs-block" aria-hidden="true">
  <?php endforeach; ?>

</div>