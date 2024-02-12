<div class="gen-wrapper container">
  <table>
    <tr>
      <td width="5%">&nbsp;</td>
      <td width="90%">

        <?php if (get_field('literature')): ?>
          <br><br>
          <div class="tab-title">Literature</div>
          <div><?php the_field('literature'); ?></div>
        <?php endif; ?>

      </td>
    </tr>
  </table>

</div>