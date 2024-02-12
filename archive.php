<?php

$query = get_queried_object();
$offset = get_option('posts_per_page');

if ( $query instanceof WP_Term && helpers\is_press_archive() ) {
  $context = 'press';
}
elseif ( $query instanceof WP_Post_Type ) {
  $post_type = $query->name;
  $context = $query->rest_base;
  if ( $query->name !== 'press' ) {
    $offset = $offset * 2;
  }
}
?>

<?php get_template_part('templates/headers/header', 'archive-'.$context); ?>

<?php if (get_queried_object()->name === 'bibliography_entry'){
  ?>
  <div class="container mb5 bibliography-nav">
    <div class="expanded-nav hidden-xs">
      <strong class="upper bibliography-nav__title"><?php _e('Filter:',"sage") ?></strong>
      <ul class="list-inline dib ">
     <?php echo "<li><a href=\"#any\" data-sort-letter=\"all\">All</a></li>|";?>
        <?php
        $all_letters = range('A', 'Z');
        foreach ( $all_letters as $letter ) {
          $post_letter = get_posts([
            'post_type'   => 'bibliography_entry',
            'numberposts' => 1,
            'meta_key' => 'sort_letter',
            'meta_value' => $letter
          ]);
          if ( $post_letter ) {
            echo "<li><a href=\"#{$letter}\" data-sort-letter=\"{$letter}\">{$letter}</a></li>";
          } else {
            echo "<li>{$letter}</li>";
          }
        }
        ?>
      </ul>
    </div>
    <div class="visible-xs bibliography-select-wrapper">
      <label class='sort-filter-select-label' for='bibliography-select' class=''><?php _e('Filter:',"sage") ?></label>
      <select class='sort-filter-select selectpicker' title='Select Letter' id="bibliography-select" >";
      <option class="" data-tokens="">All</option>
        <?php
        foreach ( $all_letters as $letter) {
          $post_letter = get_posts([
            'post_type'   => 'bibliography_entry',
            'numberposts' => 1,
            'meta_key' => 'sort_letter',
            'meta_value' => $letter
          ]);
          if ( $post_letter ) {?>
            <option class="" data-tokens="<?= $letter;?>"><?= $letter;?></option>
          <?php } else { ?>
            <option class="" disabled><?= $letter;?></option>
          <?php }?>
        <?php } ?>
      </select>
    </div>
  </div>
<?php } ?>

<?php if ( !helpers\is_press_archive() ) : ?>
<div class="container archive-filter-header <?= get_queried_object()->name;?>">
  <div class="row">
    <?php Archives\get_archive_table_head() ?>
  </div>
</div>
<?php endif; ?>



<div class="container pv3 test">
  <div class="content" aria-live="assertive">
    <div id="ajaxLoading" class="tc"><img src="<?= get_template_directory_uri(); ?>/dist/images/loading.svg" width="60" alt="" /></div>
    <div
      id="js-posts-wrapper"
      class="posts-wrapper"
      data-context="<?= $context; ?>"
      data-index="0"
      data-offset="<?= $offset; ?>"
      <?= Filters\get_data_atts(); ?>
    >
      <div id="js-posts-row" class="row masonry-row">
        <!-- ajax posts in here -->
      </div>
    </div>
    <div class="mb5">
      <div id="js-posts-load-more" data-appear-top-offset="300"></div>
    </div>
  </div>
</div>

<?php if ( is_post_type_archive('video') ) : ?>
  <?php get_template_part('templates/partials/videos-pg-additional-thanks'); ?>
<?php endif; ?>