<?php
use Roots\Sage\Extras;

$the_id = get_the_ID();
$group = helpers\get_the_group( $the_id );

// if the artwork is part of a group, we will display the group's components
if ( $group ) {
  $the_id = $group->ID;
}
?>

<?php Extras\componify( null, $the_id ); ?>

<?php get_template_part('templates/partials/entry-footer'); ?>