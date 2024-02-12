<div class="gen-wrapper container">
  <table>
    <tr>
      <td width="5%">&nbsp;</td>
      <td width="90%">

        <?php if (get_field('provenance_text')): ?>
          <br><br>
          <div class="tab-title">Provenance</div>
          <div><?php the_field('provenance_text'); ?></div>
          <?php if ( have_rows('provenance_remarks') ) : ?>
            <div class="tab-sub-title">Provenance Notes</div>
          <?php endif; ?>
          <ol>
            <?php while ( have_rows('provenance_remarks') ) : the_row(); ?>
              <li><?php the_sub_field('remark'); ?></li>
            <?php endwhile; ?>
          </ol>
        <?php endif; ?>


        <?php if (get_field('exhibitions_text')): ?>
          <br><br>
          <div class="tab-title">Exhibition History</div>
          <div><?php the_field('exhibitions_text'); ?></div>
        <?php endif; ?>


        <?php if (get_field('references_text')): ?>
          <br><br>
          <div class="tab-title">References</div>
          <div><?php the_field('references_text'); ?></div>
        <?php endif; ?>


        <?php if (get_field('versions_text')): ?>
          <br><br>
          <div class="tab-title">Versions</div>
          <div><?php the_field('versions_text'); ?></div>
          <?php if ( have_rows('versions_notes') ) : ?>
            <div class="tab-sub-title">Versions Notes</div>
          <?php endif; ?>
          <ol>
            <?php while ( have_rows('versions_notes') ) : the_row(); ?>
              <li><?php the_sub_field('version_note'); ?></li>
            <?php endwhile; ?>
          </ol>
        <?php endif; ?>


        <?php if (get_field('technical_summary')): ?>
          <br><br>
          <div class="tab-title">Technical Summary</div>
          <div><?php the_field('technical_summary'); ?></div>
          <?php if ( have_rows('tech_endnotes') ) : ?>
            <div class="tab-sub-title">Technical Summary Endnotes</div>
          <?php endif; ?>
          <ol>
            <?php while ( have_rows('tech_endnotes') ) : the_row(); ?>
              <li><?php the_sub_field('endnote'); ?></li>
            <?php endwhile; ?>
          </ol>
        <?php endif; ?>

      </td>
    </tr>
  </table>

</div>