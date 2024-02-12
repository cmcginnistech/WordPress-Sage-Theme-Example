<div class="container">
  <hr class="hr--2px mv4 mb5-ns" aria-hidden="true" />
</div>

<div class="components-wrapper container">
  <section class="component component--text">
    <div class="row">
      <div class="entry-content">
        <?php while ( have_rows('memoir_sections') ) : the_row(); ?>
          <?= apply_filters('the_content', get_sub_field('content')); ?>
        <?php endwhile; ?>
      </div>
      <?php get_template_part('templates/partials/comp-figs'); ?>
    </div>
    <div class="entry-attribution row">
      <div class="entry-attribution__author">
        - <?= the_field('entry_author'); ?>
      </div>
    </div>
  </div>
</div>