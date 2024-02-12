<article class="row partial partial--staff flex-ns">
  <div class="cso1 cs3 first-col">
    <h2 class="mt0">
      <a href="<?= get_the_permalink($id); ?>"><?= get_the_title($id) ?></a>
    </h2>
    <span class="color-stormy text-italic serif"><?= get_field('job_title', $id); ?></span>
  </div>
  <div class="cs7">
    <div class="excerpt">
      <p><?= wp_trim_words(get_field('staff_member_bio', $id), 45); ?></p>
    </div>
  </div>
</article>