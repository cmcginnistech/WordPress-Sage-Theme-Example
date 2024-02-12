<?php

use Roots\Sage\{Extras, Titles};

?>
<style>
.exhibition_excerpt p {
    margin-bottom: 0px;
}
.exhibition-description p:first-child {
    margin-bottom: 0px;
}
.line-wrapper {
    position: relative;
    margin-top: 10px;
    margin-bottom: 15px;
}
.line-divider:after {
    position: absolute;
    bottom: 0;
    left: 0;
    max-width: 375px;
    width: 100%;
    height: 1px;
    background-color: #fff;
    opacity: .5;
    content: "";
}
</style>
<header class="page-header">
  <div class="container">
    <div class="row">
      <div class="cs4">
        <h1 class="page-title size-h1-plus-ns"><?= Titles\title(); ?></h1>
      </div>
      <?php if ($intro = get_field('exhibition_introduction', 'option')) : ?>
      <div class="cs8">
        <?= $intro; ?>
      </div>
      <?php endif; ?>
    </div>
  </div>
</header>
<div class="container">
  <?php if (have_rows('exhibition_by_theme', 'options')) : $i = 0; ?>
    <?php while (have_rows('exhibition_by_theme', 'options')) : the_row(); $i++;?>
      <?php
        $theme = get_sub_field('theme');
		$exhibition_title = get_sub_field('exhibition_title');
		$curated_by = get_sub_field('curated_by');
		$date_from = get_sub_field('date_from');
		$date_to = get_sub_field('date_to');
		$exhibition_excerpt = get_sub_field('exhibition_excerpt');
		$exhibition_learn_more_text = get_sub_field('exhibition_learn_more_text');
		$description = get_sub_field('description_of_the_exhibition');
		$information = get_sub_field('information_about_the_catalogue');
        $thumb_id = get_sub_field('background_image');
		$background_position_not_expanded = get_sub_field('background_position_not_expanded');
		$background_position_expanded = get_sub_field('background_position_expanded');
	    $image_caption = get_sub_field('image_caption');
      ?>
	  <style>
	  .theme__bg--underlayer.expanded-<?= $i ?> {
		  background-position: 50% <?php echo $background_position_expanded; ?>%;
	  }
	  .notexpanded-<?= $i ?> {
		  background-position: 50% <?php echo $background_position_not_expanded; ?>%;
	  }
	  </style>
      <div class="theme status-<?= $i; ?> loading">
      <div
          class="theme__bg bg-matrix-purple theme__bg--underlayer lazyload expanded-<?= $i ?>" style=""
          data-bgset="<?= wp_get_attachment_image_src($thumb_id, "large")[0]; ?>"
        ></div>
        <div
          class="theme__bg lazyload bg-cover-overlay bg-cover-overlay--purple notexpanded-<?= $i ?>" style=""
          data-bgset="<?= wp_get_attachment_image_src($thumb_id, "large")[0]; ?>"
          data-bg-matrix
        ></div>
        <div class="theme__content">
          <div style="position: relative;" class="a11y-link-wrap">
            <h2 class="theme__title size-h1" style="margin-bottom:15px;"><?= $exhibition_title ?></h2>
			<?= $date_from; ?> - <?= $date_to; ?>
<!-- 			<div class="spacer" style="height: 25px;"></div> -->
			<div class="exhibition_excerpt"><?= $exhibition_excerpt; ?></div>
			<?php if($curated_by) : ?>
			<?= $curated_by; ?>
			<br />
			<?php endif; ?>
            <?php if ($exhibition_title) : ?>
              <button class="theme__toggle" data-toggle="collapse" href="#collapseTheme-<?= $i; ?>" aria-expanded="false" aria-controls="collapseTheme-<?= $i; ?>" style="margin-top: 1rem;" onclick="event.preventDefault(); document.getElementsByClassName('status-<?= $i; ?>')[0].classList.toggle('active');">
                Learn more about this exhibition
                <?= Extras\icons('caret-down-white', 15, 10); ?>
              </button>
            </div>
            <div class="collapse" id="collapseTheme-<?= $i; ?>">
			<div class="spacer" style="height: 25px;"></div>
			<?= $exhibition_learn_more_text; ?>
			
			<div class="exhibition-description">
			<!--<h2 class="theme__title size-h1" style="max-width: 800px;"><?= $description; ?></h2>-->

			<?php if($information) : ?>
			<div class="line-wrapper"><div class="line-divider"></div></div>
			<div class="spacer" style="height: 25px;"></div>
			<?= $information; ?>
			<?php endif; ?>
			</div>
			
                <?php if( have_rows('links') ): ?>
					<?php while( have_rows('links') ) : the_row(); ?>
					<?php
						$link_url = get_sub_field('url');
						$link_title = get_sub_field('link_title');
						$link_author = get_sub_field('link_author');
					?>
                <div class="essay" style="padding-top: 0;">
                  <a class="btn btn-outline btn-lg btn-caret-right mb4 essay__link" href="<?= $link_url; ?>" target="_blank">
                    <?= $link_title ?>
                    <?php if($link_author ) : ?> <span class="essay__author db mb3 mb0-ns">by <?= $link_author; ?></span><?php endif; ?>
                  </a>
	  
					
					
                </div>
                <?php //wp_reset_postdata(); ?>
              <?php endwhile; ?>
			  <?php endif; ?>
			<?php if($image_caption): ?>
			<div class="image-caption"><?= $image_caption; ?> </div>
			<?php endif; ?>
            </div>
          <?php endif; ?>
        </div>
      </div>
    <?php endwhile; ?>
  <?php endif; ?>
</div>