<?php
use Roots\Sage\Extras;
if ($_GET['tab'] === 'artist'){
  $selectedTab = $_GET['tab'];
} elseif($_GET['tab'] === 'essay'){
  $selectedTab = $_GET['tab'];
} else {
$selectedTab = 'artwork';
}
?>
<div class="bg-purple archive-background-wrapper">
  <div class="container mb4">
    <div class="page-header row">
      <div class="cs4">
        <h1 class="size-h1"><?php _e('Archive', 'sage'); ?></h1>
      </div>
      <div class="cs8 archive-header__column-right">
        <div class=" pl5-ns">
            <span class="thin db mb4"><?php the_field('introduction'); ?></span>
            <a class="btn btn-collapse collapsed" role="button" data-toggle="collapse" data-target="#howToCite" aria-expanded="false" aria-controls="howToCite">
              <div class=""><?= __('How to cite', 'sage'); ?></div>
              <div class="closed-text">
                <span class="sr-only"><?= __('Expand', 'sage'); ?></span>
                <span aria-hidden="true"><?= Extras\icons('plus-circle-sm', 24, 24); ?></span>
              </div>
              <div class="open-text">
                <span class="sr-only"><?= __('Collapse', 'sage'); ?></span>
                <span aria-hidden="true"><?= Extras\icons('minus-circle-sm', 24, 24); ?></span>
              </div>
            </a>
            <div class="collapse-for-small archive-pg-cite" id="howToCite">
            <div class="pa3 pa0-ns mb4">
                <p class="thin size-root-xs">
                  <?php
                  $cite = [
                    '<span class="cite-label">To cite the 2017 archived edition of the collection catalogue:</span></br>',
                    'Wheelock, Arthur K., Jr., ed. <em>The Leiden Collection Catalogue</em>. New York. 2017.',
                    get_site_url(). '/archive/',
                    '(archived January 2017)'
                  ];
                  echo implode(' ', $cite);
                  ?>
                </p>
              </div>
              <div class="pa3 pa0-ns mb4">
                <p class="thin size-root-xs">
                  <?php
                  $cite = [
                    '<span class="cite-label">To cite the 2020 archived edition of the collection catalogue:</span></br>',
                    'Wheelock, Arthur K., Jr., ed. <em>The Leiden Collection Catalogue</em>, 2nd ed. New York. 2017–20.',
                    get_site_url(). '/archive/',
                    '(archived May 2020)'
                  ];
                  echo implode(' ', $cite);
                  ?>
                </p>
              </div>
              <div class="pa3 pa0-ns">
                <p class="thin size-root-xs">
                  <?php
                  $cite = [
                    '<span class="cite-label">To cite the third ongoing edition of the collection catalogue:</span></br>',
                    get_field('how_to_cite_text'),
                    get_site_url(). '/archive/',
                    '(accessed '. date('F d, Y') .')'
                  ];
                  echo implode(' ', $cite);
                  ?>
                </p>
              </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>


<div class="container">
  <nav>
    <ul class="nav nav-tabs" role="tablist" id="archive-tab">
      <li role="presentation" <?php echo $selectedTab === 'artwork' ? 'class="active"' : '';?>><a href="#artwork" aria-controls="artwork" role="tab" data-toggle="tab"><?php _e('Artwork Entries', 'sage'); ?></a></li>
      <li role="presentation" <?php echo $selectedTab === 'artist' ? 'class="active"' : '';?>><a href="#artist" aria-controls="artist" role="tab" data-toggle="tab"><?php _e('Artist Biographies', 'sage'); ?></a></li>
      <li role="presentation" <?php echo $selectedTab === 'essay' ? 'class="active"' : '';?>><a href="#essay" aria-controls="essay" role="tab" data-toggle="tab"><?php _e('Scholarly Essays', 'sage'); ?></a></li>
    </ul>
  </nav>

  <div class="tab-content">
    <?php
    // tabs (should match post type slug)
    $tabs = ['artwork', 'artist', 'essay'];

    foreach ( $tabs as $key => $post_type ) {
      $template = "templates/partials/archive-page-{$post_type}.php";
      include(locate_template($template));
    }
    ?>
  </div>

</div>