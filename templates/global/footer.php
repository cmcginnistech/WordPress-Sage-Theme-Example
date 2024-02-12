<?php
use Roots\Sage\Nav;
use Roots\Sage\Extras;
?>

<footer class="site-footer">
  <div class="container">
    <div class="row mb3">
      <div class="cm9">
        <?php if (get_field('footer_contact_form', 'options')){
          $form_object = get_field('footer_contact_form', 'options');
          gravity_form_enqueue_scripts($form_object['id'], true);
          gravity_form($form_object['id'], true, true, false, '', true, 1);
              }?>
      </div>
      <div class="cm3 mt2">
      <?php if($logo = get_field('footer_logo', 'option')) : ?>

        <a href="<?= esc_url(home_url('/')); ?>">
          <img class="lazyload footer-logo" data-src="<?= esc_url($logo['sizes']['large']); ?>" alt="<?= esc_attr($logo['alt']); ?>" />
        </a>
          <?php endif; ?>

      </div>

    </div>
   <!-- <div class="row mb4">
      <?php
       /* wp_nav_menu([
          'depth'          => 2,
          'menu_class'     => 'nav',
          'theme_location' => 'primary_navigation',
          'walker'         => new Nav\SageNavWalker()
        ]);*/
      ?>

    </div>-->
	  <div class="row mb4 footerspace"></div>
    <div class="row mb0">
      <div class="c8">
    <span class="serif text-periwinkle size-root">
      <a class="text-inherit interact-white" href="<?= esc_url(get_field('privacy_policy_url', 'options')); ?>">Privacy Policy</a>
      - The Leiden Collection &copy; <?= date('Y'); ?>
    </span>
        </div>
    <div class="flex c4" style="justify-content:flex-end;">
    <span class="mr4">
        <?php
          // Check rows exists.
          if( have_rows('footer_social_media','options') ):
              // Loop through rows.
              while( have_rows('footer_social_media','options') ) : the_row();
              $url = get_sub_field('url');
              $img = get_sub_field('icon');
                echo "<a class='mr2' href=\"{$url}\"><img src=\"{$img['sizes']['thumb-xs']}\" alt=\"\" style=\"max-width:30px;height:auto;\" /></a>";

              // End loop.
              endwhile;
          // No value.
          endif;?>
          </span>
          </div>
      </div>
      </div>
</footer>

<div id="get_tooltip_data_nonce" data-nonce="<?= wp_create_nonce( 'get_tooltip_data_nonce' ); ?>" class="hidden"></div>

<a href="#" class="scroll-to-top"><span class="sr-only"><?= __('Scroll back to top', 'sage'); ?></span><?= Extras\icons('chevron-right', 16, 16); ?></a>
