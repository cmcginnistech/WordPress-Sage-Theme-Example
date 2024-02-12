<?php
use Roots\Sage\Extras;
?>

<header class="entry-header container">
  <div class="row">
    <div class="entry-header__main">
      <h1 class="entry-header__title"><?php the_title(); ?></h1>

      <div class="single-artwork-artist-info">
        <?php
        $group_artists = helpers\get_group_artists($id);
        foreach ( $group_artists as $artist ) : ?>
          <span class="artist db">
            <a href="<?= esc_url(get_permalink($artist->ID)); ?>"><?= $artist->post_title; ?></a>
          </span>
        <?php endforeach; ?>
      </div>

      <?php
      $artworks = get_field('artwork_to_group_2way');

      if ( !empty($artworks) ) : ?>
        <div class="group-module group-module--group">
          <strong><?= __('This entry pertains to the following artworks:', 'sage'); ?></strong>
          <ul class="list-unstyled">
            <?php
            foreach ( $artworks as $artwork ) {
              include(locate_template('templates/partials/group-item.php'));
            } ?>
          </ul>
        </div>
      <?php endif; ?>
    </div>

    <div class="entry-header__sidebar">
      <?php get_template_part('templates/partials/post-utility-buttons'); ?>
      <?php Extras\how_to_cite(); ?>
    </div>
  </div>

</header>

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
    <div role="tabpanel" class="tab-pane tab-pane--basic" id="media">
      <?php get_template_part('templates/artwork/tab-content', 'media'); ?>
    </div>
  </div>

</div>