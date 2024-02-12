<?php
/**
 * Template Name: Iconclass Page
 */
?>
<?php while (have_posts()) : the_post(); ?>
  <?php get_template_part('templates/headers/header', get_post_type()); ?>
  <?php get_template_part('templates/singles/single', 'iconclass'); ?>
<?php endwhile; ?>
<div class="entry-footer__section container component" style="border-top: 0px solid #f0f0f5; border-bottom: 2px solid #f0f0f5; padding-top: 0px; margin-bottom: 1.5rem;">
<div class="row">
  <div class="entry-footer__icon-wrapper cs12" style="width: 100%; position: relative; top: initial; right: initial;">
      <button class="btn btn-collapse collapsed" type="button" data-toggle="collapse" data-target="#iconclassLearnMore" aria-expanded="false" aria-controls="iconclassLearnMore" style="display:flex;width:100%;justify-content:space-between; padding-left:15px; padding-right:15px; margin-bottom:2rem;">
        <h2 class="text-left"><?= get_field('dropdown_header');?></h2>
        <div class="closed-text">
          <span class="sr-only">Expand</span>
          <span aria-hidden="true"><svg width="40" height="40" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg"><path d="M19 19V8h2v11h11v2H21v11h-2V21H8v-2h11zm1 21C8.954 40 0 31.046 0 20S8.954 0 20 0s20 8.954 20 20-8.954 20-20 20zm0-2c9.941 0 18-8.059 18-18S29.941 2 20 2 2 10.059 2 20s8.059 18 18 18z" fill="#8F92C6" fill-rule="evenodd"></path></svg></span>
        </div>
        <div class="open-text">
          <span class="sr-only">Collapse</span>
          <span aria-hidden="true"><svg width="40" height="40" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg"><path d="M20 40C8.954 40 0 31.046 0 20S8.954 0 20 0s20 8.954 20 20-8.954 20-20 20zm0-2c9.941 0 18-8.059 18-18S29.941 2 20 2 2 10.059 2 20s8.059 18 18 18zM8 19h24v2H8v-2z" fill="#8F92C6" fill-rule="evenodd"></path></svg></span>
        </div>
      </button>

  </div>
  <div class="entry-footer__section-content" style="width: 100%;">
    <div id="iconclassLearnMore" class="collapse component">
      <div class="entry-content">
        <?= get_field('dropdown_content');?>
      </div>
    </div>
  </div>
</div>
</div>
<?php
  $n = htmlentities($_GET['notation']);
    ?>
  <div class="container">
  <div class="row">
    <div class="cs12">
      <div class="embed-responsive embed-responsive-4by3">
        <iframe src="https://www.arkyves.org/HIM/TLC<?php echo strlen($n) > 1 ? '?notation='. $n :'';?>" frameborder="0" class="embed-responsive-item"></iframe>
      </div>
    </div>
  </div>
  </div>
