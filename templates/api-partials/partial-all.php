<?php

use Roots\Sage\Extras;

/**
 * @link https://searchwp.com/extensions/term-highlight/
 */
$highlighter = false;
if( class_exists( 'SearchWPHighlighter' ) ) {
  $highlighter = new SearchWPHighlighter();
}

// get search query from $_SERVER global
preg_match("/se=([a-zA-Z0-9_.\+\-%22]+)(&|$)/", $_SERVER['QUERY_STRING'], $output_array);
$query = str_replace('+', ' ', $output_array[1] );

// variables
$the_title  = get_the_title($id);
$the_excerpt = Extras\acf_excerpt($id, $query);
$the_permalink = get_the_permalink($id);
$post_type = get_post_type($id);
$has_thumbnail = has_post_thumbnail($id);

// group post type specific
$group = helpers\get_the_group($id);
if ( $group ) {
  $the_excerpt = Extras\acf_excerpt($group->ID);
}

// highlight query in the title and excerpt
if ( $highlighter ) {
  $the_title =  $highlighter->apply_highlight( $the_title, $query );
  $the_excerpt = $highlighter->apply_highlight( $the_excerpt, $query );
}
?>

<article class="search-item search-item--<?= $post_type; ?> <?php if($has_thumbnail) echo 'search-item--has-thumb'; ?>">

  <?php if ( $has_thumbnail ) : ?>
    <div class="search-item__image">
      <a href="<?= esc_url($the_permalink); ?>" class="overlay dib">

        <?= $post_type == "artwork" ? get_the_post_thumbnail($id, 'medium') :  get_the_post_thumbnail($id, 'thumb-medium'); ?>
      </a>
    </div>
  <?php endif; ?>

  <div class="search-item__content">
    <h2 class="search-item__title"><a href="<?= esc_url($the_permalink); ?>"><?= $the_title; ?></a></h2>
    <div class="search-item__meta">
      <?php include(locate_template('templates/partials/search-meta.php')); ?>
    </div>
  </div>

  <div class="search-item__excerpt">
    <?= $the_excerpt; ?>
    <a href="<?= esc_url($the_permalink); ?>">
      <?php _e('Read More', 'sage'); ?>
    </a>
  </div>

</article>

<hr aria-hidden="true">