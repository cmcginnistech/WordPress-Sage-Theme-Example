<article class="row partial partial--entry_author flex-ns">
  <div class="cso1 cs3 first-col mb3 mb0-ns">
    <h2 class="mt0">
      <a href="<?= get_the_permalink($id); ?>"><?= get_the_title($id) ?></a>
    </h2>
    <span class="thin"><?= get_field('job_title', $id) ? get_field('job_title', $id).'<br>' : ''; ?></span>
    <em class="serif color-stormy"><?= get_field('company_organization', $id); ?></em>
  </div>
  <div class="cs7">
    <div class="excerpt">
      <p><?= wp_trim_words(get_field('intro_text', $id), 45); ?></p>
    </div>
  </div>
</article>