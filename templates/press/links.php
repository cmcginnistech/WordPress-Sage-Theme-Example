<?php
use Roots\Sage\Extras;
?>

<?php if(have_rows('press_links')) : ?>
  <div class="entry-links flex-ns mb4">
  <?php while(have_rows('press_links')) : the_row(); ?>

    <?php
    if( get_row_layout() == 'external_link' ) {
      $href = get_sub_field('page_url');
      $text = get_sub_field('text');
    }
    elseif( get_row_layout() == 'pdf_download' ) {
      $href = get_sub_field('pdf_download')['url'];
      $text = get_sub_field('text');
    } ?>

    <a class="cta-press btn btn-outline btn-caret-right <?php echo $text ? 'top-text-btn' : ''?>" href="<?= esc_url($href); ?>" target="_blank" rel="noopener">
        <?php if ( get_row_layout() == 'pdf_download' ) : ?>
          <strong class="db"><?= __('View full article', 'sage'); ?></strong>
        <?php endif; ?>
        <span class="top-text"><?= $text; ?></span>
        <?php if ( get_row_layout() == 'external_link' ) : ?>
          <strong class="db"><?= get_field('press_source'); ?></strong>
        <?php endif; ?>
    </a>

  <?php endwhile; ?>
  </div>
<?php endif; ?>