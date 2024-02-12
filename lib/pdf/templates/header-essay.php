<?php use Roots\Sage\Extras; ?>

<div class="gen-wrapper container">
  <div class="gen-header" >
    <table class="top-image">
        <tr>
            <td>
                <?php
                // if this is the portrait and oil page, use full size.
                // otherwise use a crop size
                if ( get_post_type() === 'page' ) {
                    $crop_size = 'full';
                    $below_image = '<br><br>';
                } else {
                    $crop_size = 'hero';
                    $below_image = '';
                }
                // can't use the_post_thumbnail here for some reason... possibly
                // bc of the responsive img markup?
                $feat_img_url = get_the_post_thumbnail_url( $post->ID, $crop_size );
                ?>
                <div><img src="<?= $feat_img_url; ?>" /></div>
                <?= $below_image; ?>
            </td>
        </tr>
    </table>
    <br /><br />
    <table class="two-column" nobr="true" style="page-break-inside:avoid;">
        <tr>
            <td>
                <h3 class="essay-title"><?php the_title(); ?></h3>
            </td>
        </tr>
        <tr><td>&nbsp;</td></tr>
    </table>

    <table nobr="true" style="page-break-inside:avoid;">
        <tr><td><hr></td></tr>
        <tr><td><?php Extras\how_to_cite(); ?></td></tr>
        <tr><td><hr></td></tr>
    </table>
  </div>

</div>