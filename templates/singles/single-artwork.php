<?php

use Roots\Sage\Extras;

?>

<header class="entry-header container">
  <div class="row">
    <div class="entry-header__main">
      <h1 class="entry-header__title"><?php the_title(); ?></h1>

      <div class="single-artwork-artist-info">
        <span class="artist db">
          <?php
          $artist = get_field('artwork_to_artist_2way');

          // if we have an artist name variant, output that first
          if ( $artist_variant = get_field('artist_name_variant') ) {
            echo $artist_variant;
          // otherwise, fallback to the artist field
          } else {
            if (is_object($artist)) {
              $artist_link = get_the_permalink($artist->ID);
              echo "<a href=\"{$artist_link}\" target=\"_blank\">{$artist->post_title}</a>";
            }
          } ?>
        </span>

        <?php if ($artist) : ?>
        <span class="artist-dates db">
          <?php
          $dates =  helpers\get_artist_display_dates($artist->ID);
          if ( $dates ) {
            echo "({$dates['birth']} – {$dates['death']})";
          } ?>
        </span>
        <?php endif; ?>
      </div>

      <?php get_template_part('templates/artwork/tombstone'); ?>
    </div>

    <div class="entry-header__sidebar">
      <?php get_template_part('templates/partials/post-utility-buttons'); ?>
      <?php Extras\how_to_cite(); ?>
    </div>
  </div>
</header>

<?php
/*
Check if this artwork is part of a group.
*/
$group = helpers\get_the_group($post->ID);
if ( $group ) : ?>
<div class="container">
  <div class="group-module group-module--artwork">
    <div class="row flex-ns">
      <header class="cs4">
        <strong><?= __('This work is part of a group', 'sage'); ?></strong>
        <a href="<?= get_permalink($group->ID); ?>" class="size-h4 mb4 mb0-ns db"><?= $group->post_title; ?></a>
      </header>
      <div class="cs8 flex-ns items-center">
        <ul class="list-unstyled flex-ns flex-wrap">
          <?php
          $all_works_in_group = get_field('artwork_to_group_2way', $group->ID);
          foreach ( $all_works_in_group as $artwork ) {
            include(locate_template('templates/partials/group-item.php'));
          } ?>
        </ul>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

<?php
/*
The main tabbed content.
*/
?>
<div class="entry-tab-container artwork-main">

  <nav class="entry-tabnav container">
    <?php get_template_part('templates/artwork/tabs'); ?>
  </nav>

  <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="entry">
      <?php get_template_part('templates/artwork/tab-content', 'entry'); ?>
    </div>
    <div role="tabpanel" class="tab-pane tab-pane--basic" id="provenance">
      <?php get_template_part('templates/artwork/tab-content', 'provenance'); ?>
    </div>
    <div role="tabpanel" class="tab-pane tab-pane--basic" id="exhibitions">
      <?php get_template_part('templates/artwork/tab-content', 'exhibitions'); ?>
    </div>
    <div role="tabpanel" class="tab-pane tab-pane--basic" id="references">
      <?php get_template_part('templates/artwork/tab-content', 'references'); ?>
    </div>
    <div role="tabpanel" class="tab-pane tab-pane--basic" id="tech-summary">
      <?php get_template_part('templates/artwork/tab-content', 'tech_summary'); ?>
    </div>
    <div role="tabpanel" class="tab-pane tab-pane--basic" id="versions">
      <?php get_template_part('templates/artwork/tab-content', 'versions'); ?>
    </div>
    <div role="tabpanel" class="tab-pane tab-pane--basic" id="media">
      <?php get_template_part('templates/artwork/tab-content', 'media'); ?>
    </div>
  </div>

</div>
