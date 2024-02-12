<?php
// get posts of current WP_Query
$glossary_terms = $wp_query->posts;

// check for search query
if ( isset($_GET['search']) && $_GET['search'] !== '' ) {
  $swp_query = new WP_Query([
    's'              => $_GET['search'],
    'post_type'      => 'glossary',
    'posts_per_page' => -1,
    'orderby'        => 'title',
    'order'          => 'ASC'
  ]);
  $glossary_terms = $swp_query->posts;
}

// create an array sorted by first letter of glossary term
$main_list = [];
if ( !empty($glossary_terms) ) {
  foreach ( $glossary_terms as $post_obj ) {

    $post_title = strip_tags($post_obj->post_title);
    $first_letter = $post_title[0];

    $main_list[$first_letter][] = [
      'id' => $post_obj->ID,
      'title' => $post_obj->post_title,
      'content' => $post_obj->post_content
    ];
  }
}
?>

<?php get_template_part('templates/headers/header'); ?>

<div class="container mb5 glossary-nav">
  <div class="expanded-nav hidden-xs">
    <strong class="upper glossary-nav__title">Jump to:</strong>
    <ul class="list-inline dib ">
      <?php
      $all_letters = range('A', 'Z');
      foreach ( $all_letters as $letter ) {
        if ( array_key_exists($letter, $main_list) ) {
          echo "<li><a href=\"#{$letter}\">{$letter}</a></li>";
        } else {
          echo "<li>{$letter}</li>";
        }
      }
      ?>
    </ul>
  </div>
  <div class="visible-xs">
    <label class='sort-filter-select-label' for='sort-select' class=''><?php _e('Jump to:',"sage") ?></label>
    <select class='sort-filter-select selectpicker' title='Select Letter' id="glossary-select" >";
      <?php
      foreach ( $all_letters as $letter) {
        if ( array_key_exists($letter, $main_list) ) {?>
          <option class=""><?= $letter;?></option>
        <?php } else { ?>
          <option class="" disabled><?= $letter;?></option>
        <?php }?>
      <?php } ?>
    </select>
  </div>
</div>

<div class="container">

  <?php if ( empty($glossary_terms) ) : ?>
    <div class="alert alert-warning">
      <?= __('No glossary terms found.', 'sage'); ?>
    </div>
  <?php endif; ?>

  <?php foreach ( $main_list as $letter_index => $items ) : ?>
    <div class="row row--single-letter-wrapper">
      <div class="glossary-left-column">
        <a id="<?= $letter_index; ?>"></a>
        <h2 class="mt0"><?= $letter_index; ?></h2>
      </div>
      <div class="glossary-right-column">
        <?php if ( !empty($items) ) : ?>
          <?php foreach ( $items as $item ) : ?>
            <div class="row mb4">
              <div class="cs4">
                <h3 class="tr-ns"><?= $item['title']; ?></h3>
              </div>
              <div class="cs7">
                <p>
                  <?php
                  $permalink = get_the_permalink($item['id']);
                  $read_more = '&hellip; <a href="'. $permalink .'">Read More</a>';
                  echo wp_trim_words( $item['content'], 100, $read_more );
                  ?>
                </p>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  <?php endforeach; ?>
  <div class="row cso2 cs8 row--glossary-credit">
    <?php the_field('glossary_credit_content', 'options'); ?>
  </div>

</div>
