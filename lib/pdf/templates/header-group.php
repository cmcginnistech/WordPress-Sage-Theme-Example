<?php use Roots\Sage\Extras; ?>

<div class="gen-wrapper container">
  <div class="gen-header" >
    <table class="top-image">
        <tr>
            <td width="7%">&nbsp;</td>
            <td width="86%">
                <?php
                // can't use the_post_thumbnail here for some reason... possibly
                // bc of the responsive img markup?
                $feat_img_url = get_the_post_thumbnail_url( $post->ID, 'large' );
                ?>
                <img src="<?= $feat_img_url; ?>" />
            </td>
            <td width="7%">&nbsp;</td>
        </tr>
    </table>
    <br /><br />
    <table class="two-column" nobr="true" style="page-break-inside:avoid;">
        <tr><td><hr></td></tr>
        <tr>
            <td width="45%">
                <div class="title"><?php the_title(); ?></div>
                <br>
                <div><?php
                  $group_artists = helpers\get_group_artists($post->ID);
                  foreach ( $group_artists as $artist ) {
                    echo $artist->post_title .'<br>';
                  } ?>
                </div>
            </td>
            <td width="5%">&nbsp;</td>
            <td width="5%">&nbsp;</td>
            <td width="45%"></td>
        </tr>
    </table>

    <table nobr="true" style="page-break-inside:avoid;">
        <tr><td><hr></td></tr>
        <tr><td><?php Extras\how_to_cite(); ?></td></tr>
        <tr><td><hr></td></tr>
    </table>

  </div>

</div>