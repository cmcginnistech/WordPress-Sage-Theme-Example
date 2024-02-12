<?php

$query = get_queried_object();
$offset = get_option('posts_per_page');

?>

<?php get_template_part('templates/headers/header'); ?>

<nav class="search-pg-nav container">
  <ul id="js-search-tabs" class="nav nav-tabs hidden-xs" role="tablist">

    <li role="presentation" class="active">
      <a href="#" aria-controls="all" role="tab" data-type="all">All</a>
    </li>

    <?php
    // Build the tabs using DB query.
    // $tabs['post_type'] => ['label', 'disabled]
    $tabs = [
      'artist' => [
        'label' => 'Artists'
      ],
      'artwork' => [
        'label' => 'Artworks'
      ],
      'group' => [
        'label' => 'Group Entries'
      ],
      'essay' => [
        'label' => 'Essays'
      ],
      'entry_author' => [
        'label' => 'Authors'
      ],
      'video' => [
        'label' => 'Videos'
      ]
    ];

    foreach ( $tabs as $post_type => $values ) {
      $tab_query = new SWP_Query([
        'post_type' => $post_type,
        'posts_per_page' => -1,
        's' => get_search_query()
      ]);
      $values['found'] = count($tab_query->posts);
      $values['disabled'] = false;

      if ( empty($tab_query->posts) ) {
        $values['disabled'] = true;
      }

      // override original array
      $tabs[$post_type] = $values;
    }

    ?>

    <?php foreach ( $tabs as $post_type => $values ) : ?>
      <li role="presentation" class="<?php if($values['disabled']) echo 'disabled'; ?>">
        <a href="#" aria-controls="<?= $post_type; ?>" role="tab" data-type="<?= $post_type; ?>">
          <?= $values['label']; ?>
        </a>
      </li>

    <?php endforeach; ?>

  </ul>

  <?php
  /*
   * Show a select box instead of tabs for mobile
   */
  ?>
  <div class="search-pg-mobile-tab-select visible-xs-block" id="searchTabSelect">
    <label>
      <small class="db"><?= __('Show results narrowed by:', 'sage'); ?></small>
      <select class="selectpicker">
        <option data-tokens="all" selected>All Results</option>
        <?php foreach ( $tabs as $post_type => $values ) : ?>
          <option data-tokens="<?= $post_type; ?>" <?php if($values['disabled']) echo 'disabled'; ?>>
            <?= $values['label']; ?>
            <?php if($values['found']) echo "({$values['found']})"; ?>
          </option>
        <?php endforeach; ?>
      </select>
    </label>
  </div>

</nav>

<div class="container">

  <div id="js-result-count" class="result-count"></div>

  <div class="content" aria-live="assertive">

    <div id="ajaxLoading" class="loading tc">
      <img src="<?= get_template_directory_uri(); ?>/dist/images/loading.svg" width="60" alt="" />
    </div>

    <div id="js-posts-wrapper" class="posts-wrapper" data-context="all" data-index="0" data-offset="<?= $offset; ?>" <?= Filters\get_data_atts(); ?>>
      <div id="js-posts-row" class="posts-row">
        <!-- ajax posts in here -->
      </div>
    </div>

    <div class="mb5">
      <div id="js-posts-load-more" data-appear-top-offset="300"></div>
    </div>

  </div>
</div>