<div class="container staff-single-header">
  <div class="page-header row relative">

    <div class="cs9 cso3 staff-info-wrapper">

      <span class="object-label">Staff</span>
      <h1 class="size-h1 mt0"><?php the_title(); ?></h1>

      <span class="mb3 serif color-stormy size-h4 db text-italic">
        <?php the_field('job_title'); ?>
      </span>

      <figure class="staff-image-wrapper">
        <?php echo get_the_post_thumbnail( $post->id, 'medium_large'); ?>
      </figure>

      <?php the_field('staff_member_bio'); ?>

    </div>
  </div>
</div>