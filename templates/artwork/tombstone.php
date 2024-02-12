<?php

use Roots\Sage\PDF_Functions;

if ( !isset($id) ) {
  $id = $post->ID;
}
?>

<div class="tombstone">

  <dl>
    <?php
    /**
     * The date.
     */
    if ( $tomb_date = get_field('tombstone_date', $id) ) : ?>
      <dt class="sr-only"><?php _e('date', 'sage'); ?></dt>
      <dd><?= $tomb_date; ?></dd>
    <?php endif; ?>

    <?php
    /**
     * The medium.
     */
    $tomb_medium = helpers\get_artwork_medium($id);

    if ( $tomb_medium ) : ?>
      <dt class="sr-only"><?php _e('medium', 'sage'); ?></dt>
      <dd><?= $tomb_medium; ?></dd>
    <?php endif; ?>

    <?php
    /**
     * The dimensions.
     */
    ?>
    <dt class="sr-only"><?php _e('dimensions', 'sage'); ?></dt>
    <dd><?= get_field('artwork_height_cm', $id) .' x '. get_field('artwork_width_cm', $id) .' cm'; ?> <?php echo get_field('artwork_additional_dimension_info', $id) ?></dd>

    <?php
    /**
     * The signed data.
     */
    $tomb_is_signed = get_field('is_signed', $id);
    $tomb_signed_data = get_field('signed_text', $id);
    if ( $tomb_is_signed == 'yes' && $tomb_signed_data && !is_page_template(['template-collection.php', 'template-collection-gallery.php']) ) : ?>
      <dt class="sr-only"><?php _e('signed information', 'sage'); ?></dt>
      <dd><?= $tomb_signed_data; ?></dd>
    <?php endif; ?>

    <?php
    /**
     * The inventory number.
     */
    ?>
    <dt class="sr-only"><?php _e('inventory number', 'sage'); ?></dt>
    <dd><?php the_field('inventory_number', $id); ?></dd>

    <?php
    /**
     * The current on view location (from Collector Systems).
     * Saved in options table.
     * Don't ouput for PDFs.
     */
    if ( !PDF_Functions\doing_pdf_gen() ) :
      $location = helpers\get_artwork_location($id);
      if ( $location ) : ?>
        <dd class="currently-on-view">
          <strong>Currently on view: </strong><?= $location; ?>
        </dd>
      <?php endif;
    endif; ?>

  </dl>

</div>