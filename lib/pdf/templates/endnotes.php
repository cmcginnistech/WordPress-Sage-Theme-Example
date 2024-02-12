<?php
$group = helpers\get_the_group($post->ID);

// if part of a group, pull the group comp figs
if ( $group ) {
  $post_id = $group->ID;
} else {
  $post_id = $post->ID;
}
?>

<div class="gen-wrapper container">
  <table>
    <tr>
      <td width="5%">&nbsp;</td>
      <td width="90%">

        <?php if (have_rows('entry_endnotes', $post_id)): ?>
          <br><br>
          <div class="tab-title">Endnotes</div>
          <ol>
              <?php while ( have_rows('entry_endnotes', $post_id) ) : the_row(); ?>
                <li><?php the_sub_field('endnote'); ?></li>
              <?php endwhile; ?>
          </ol>
        <?php endif; ?>

      </td>
    </tr>
  </table>
</div>