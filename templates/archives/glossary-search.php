<?php
use Roots\Sage\Extras;

$query = '';
if ( isset($_GET['search']) && $_GET['search'] !== '' ) {
  $query = htmlentities($_GET['search']);
}
?>

<form role="search" method="get" action="<?= htmlentities(get_post_type_archive_link('glossary')); ?>" class="form-inline glossary-search">
  <label><span class="sr-only">Search</span></label>
  <input type="search" id="search" name="search" class="form-control" value="<?= esc_attr($query); ?>" placeholder="Search Glossary&hellip;" />
  <div class="input-group-append">
    <button class="btn btn-search" type="submit">
      <span class="sr-only">Search</span>
      <?= Extras\icons('search', 24, 24); ?>
    </button>
  </div>
</form>