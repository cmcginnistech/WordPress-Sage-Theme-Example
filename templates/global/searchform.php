<?php

$namespace = uniqid();
use Roots\Sage\Extras;

?>

<form
  role="search"
  method="get"
  class="search-form"
  action="<?= esc_url(home_url('/')); ?>"
  autocomplete="off"
>
  <div class="input-group">
    <label for="<?= "search-{$namespace}"; ?>" class="sr-only"><?= _x( 'Search the Collection', 'label' ); ?>  </label>
    <input
      id="<?= "search-{$namespace}"; ?>"
      type="search"
      class="search-field"
      placeholder="<?= esc_attr_x( 'Search the Collection', 'placeholder' ); ?>" value="<?= get_search_query(); ?>"
      name="s"
	  data-swplive="true"
      required
    />
    <div class="input-group-append">
      <button class="btn btn-search" aria-label="<?= __('Search'); ?>">
        <?= Extras\icons('search', 24, 24); ?>
      </button>
    </div>
  </div>
</form>
