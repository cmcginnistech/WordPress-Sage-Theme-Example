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

$essays = get_posts([
  'post_type' => 'essay',
  'posts_per_page' => -1,
  'orderby' => 'title',
  'order' => 'ASC'
]);
?>

<div role="tabpanel" class="tab-pane pv5 <?php if($selectedTab  == 'essay') echo 'active'; ?>" id="<?= $post_type; ?>">

  <?php foreach ($essays as $essay) : ?>
    <article class="row">
    <a  id="<?= $essay->ID; ?>"style="padding-top: 40px; margin-top: -40px; display:block;" name="<?= $essay->ID; ?>"></a>
      <div class="cs9 archive-essay">
        <h2 class="size-h4 size-h3-ns"><?= $essay->post_title; ?></h2>
        <em class="db serif archive-essay__author mb3 mb0-ns">by <?= get_field('entry_author', $essay->ID); ?></em>
      </div>
      <div class="cs3">
        <?php
        /**
         * Get all PDF archives for this artwork.
         */
        $archives = Archives\get_pdf_archives( $essay->ID );
        include(locate_template('templates/partials/pdf-archive-list.php'));
        ?>
      </div>
    </article>
    <hr aria-hidden="true">
  <?php endforeach; ?>

</div>