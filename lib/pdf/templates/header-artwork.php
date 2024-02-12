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
                    $artist = get_field('artwork_to_artist_2way');
                    $dates =  helpers\get_artist_display_dates($artist->ID);

                    if ( $artist_variant = get_field('artist_name_variant', $post->ID, false) ) {
                        echo $artist_variant;
                    } else {
                        echo $artist->post_title;
                    } ?>
                </div>
                <div><?php
                if ($dates){
                    echo '(';
                 echo $dates['birth'] .' – '. $dates['death'];
                 echo ')';
                }
                 ?></div>
            </td>
            <td width="5%">&nbsp;</td>
            <td width="5%">&nbsp;</td>
            <td width="45%">
                <?php
                ob_start();
                include(locate_template('templates/artwork/tombstone.php'));
                $tombstone = ob_get_clean();
                $tombstone = strip_tags($tombstone, '<br><span><div><dd><dt>');
                preg_replace("/<dt(.*?)<\/dt>/", "", $tombstone);
                $tombstone = str_replace('<dd>', '<div>', $tombstone);
                $tombstone_out = str_replace('</dd>', '</div>', $tombstone);
                echo $tombstone_out;
                ?>
            </td>
        </tr>
        <tr><td>&nbsp;</td></tr>
    </table>

    <?php if ( $post_type !== 'group' ) : ?>
        <table nobr="true" style="page-break-inside:avoid;">
            <tr><td><hr></td></tr>
            <tr><td><?php Extras\how_to_cite(); ?></td></tr>
            <tr><td><hr></td></tr>
        </table>
    <?php endif; ?>

  </div>

</div>