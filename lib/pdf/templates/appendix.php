<?php
$group = helpers\get_the_group($post->ID);

// if part of a group, pull the group comp figs
if ( $group ) {
  $post_id = $group->ID;
} else {
  $post_id = $post->ID;
}
?>

<?php if (have_rows('entry_appendix', $post_id)): ?>
<div class="gen-wrapper container">
  <table>
    <tr>
      <td width="5%">&nbsp;</td>
      <td width="90%">
        <?php
          $appendix_count = count(get_field('entry_appendix', $post_id));
          $appendix_title = $appendix_count > 1 ? 'Appendices' : 'Appendix';
        ?>
        <br><br>
        <div class="tab-title"><?= $appendix_title; ?></div>
        <?php while ( have_rows('entry_appendix', $post_id) ) : the_row(); ?>
          <div class="appendix">
            <?php the_sub_field('appendix'); ?>
          </div>
        <?php endwhile; ?>
      </td>
    </tr>
  </table>
</div>
<?php endif; ?>