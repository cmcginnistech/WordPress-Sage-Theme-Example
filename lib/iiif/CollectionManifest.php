<?php

namespace Roots\Sage\IIIF;

use Roots\Sage\IIIF\Image;

class CollectionManifest extends Manifest {

  /**
   * Constructor.
   */
  public function __construct() {
    $this->manifestID = esc_url(home_url('iiif\/presentation\/v2\/collection\/manifest\/'));
    $this->manifestTitle = __('The Leiden Collection Manifest', 'sage');
    $this->manifestType = 'sc:Collection';
    $this->manifestRelated = [
      [
        '@id' => esc_url(home_url('collection\/'))
      ]
    ];
    $this->manifests = $this->assemble_manifests();
  }

  /**
   * Get any associated canvases for the manifest
   *
   * @return array|boolean
   */
  public function assemble_manifests() {

    $manifests = [];
    $records = get_posts([
      'post_type'      => 'artwork',
      'posts_per_page' => -1,
      'meta_key'       => 'artwork_artist_sort_name',
      'orderby'        => 'meta_value',
      'order'          => 'ASC',
      'meta_query'     => [
        [
          'key'     => 'iiif_image_types',
          'compare' => 'EXISTS'
        ]
      ]
    ]);

    foreach ( $records as $record ) {
      $images = get_field('iiif_image_types', $record->ID);

      if ( empty($images) ) {
        continue;
      }

      $manifests[] = [
        '@id'    => get_iiif_link($record, 'manifest'),
        '@type'  => 'sc:Manifest',
        'label'  => get_the_title($record->ID)
      ];
    }
    return $manifests;
  }


}