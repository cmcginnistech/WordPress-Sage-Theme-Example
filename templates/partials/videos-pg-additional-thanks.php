<div class="container">
  <button type="button" class="mb5 btn btn-default btn-lg btn-outline btn-caret-right" data-toggle="modal" data-target="#additionalThanks">
    <?php _e('Additional Thanks To', 'sage'); ?>
  </button>

  <div class="modal fade" id="additionalThanks" tabindex="-1" role="dialog" aria-labelledby="additionalThanksLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-body">
          <?php the_field('additional_thanks_to_content', 'options'); ?>
        </div>
      </div>
    </div>
  </div>
</div>