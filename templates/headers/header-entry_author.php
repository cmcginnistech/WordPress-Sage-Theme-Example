<div class="container-full bg-purple">
  <div class="container">
    <div class="row">

      <div class="page-header author-single-header">
        <div class="cs4">
          <span><?php echo get_field('author_label_override') ? get_field('author_label_override') : 'author';?></span>
          <h1><?php the_title(); ?></h1>
        </div>
        <div class="cs8">
          <div class="pl5-ns author-single-header-right-column">
            <em><?= get_field('job_title', $id) ? get_field('job_title', $id).', ' : ''; ?></em>
            <?= get_field('company_organization', $id); ?>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>