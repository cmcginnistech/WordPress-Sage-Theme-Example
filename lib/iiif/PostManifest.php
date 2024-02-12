<?php

namespace Roots\Sage\IIIF;

use Roots\Sage\IIIF\Image;
use helpers;

class PostManifest extends Manifest {

  /**
   * The record we are building the manifest for.
   * @var WP_Post object
   */
  public $record;

  /**
   * Constructor.
   */
  public function __construct( $id ) {
    $this->record = get_post($id);
    $this->manifestID = get_iiif_link($this->record, 'manifest');
    $this->manifestType = 'sc:Manifest';
    $this->manifestTitle = get_the_title($this->record);
    $this->manifestThumbnail = get_the_post_thumbnail_url( $this->record, 'thumb-xs' );
    $this->manifestRelated = [
      [
        '@id' => get_permalink( $this->record->ID )
      ]
    ];
    $this->metadata = $this->get_meta_data();
    $this->canvases = $this->get_canvases();
    $this->services = $this->get_services();
  }

  /**
   * Gets the meta data for the manifest.
   *
   * @return array
   */
  public function get_meta_data() {

    $record = $this->record;
    $record_id = $this->record->ID;

    return [
      [
        'label' => 'Artist',
        'value' => helpers\get_artwork_artist($record_id, true)
      ],
      [
        'label' => 'Date',
        'value' => get_field('tombstone_date', $record_id)
      ],
      [
        'label' => 'Medium',
        'value' => helpers\get_artwork_medium($record_id)
      ],
      [
        'label' => 'Dimensions',
        'value' => get_field('artwork_height_cm', $record_id) .' x '. get_field('artwork_width_cm', $record_id) .' cm'
      ],
      [
        'label' => 'Credit Line',
        'value' => __('Image courtesy of The Leiden Collection, New York', 'sage')
      ]
    ];
  }

  /**
   * Get the image type label.
   *
   * @param  string $type
   * @return string|boolean
   */
  public function get_image_type_label( $type ) {
    $types = [
      'vis' => 'Visual Spectrum',
      'xra' => 'X-Ray',
      'irr' => 'Infrared'
    ];

    if ( array_key_exists($type, $types) ) {
      return $types[$type];
    }
    return false;
  }

  /**
   * Get any associated canvases for the manifest
   *
   * @return array|boolean
   */
  public function get_canvases() {

    $images = get_field('iiif_image_types', $this->record->ID);
    $canvases = [];

    if ( empty($images) ) {
      return false;
    }

    foreach ( $images as $img ) {
      $image = new Image( $img['image_url']);
      $type = $this->get_image_type_label( $img['type'] );
      $canvas_uuid = $this->uuid('http://');
      $canvases[] = [
        '@id'    => $canvas_uuid,
        '@type'  => 'sc:Canvas',
        'label'  => $type ? $type : 'Normal Canvas',
        'width'  => $image->get_width(),
        'height' => $image->get_height(),
        'images' => [
          [
            '@context'   => 'http://iiif.io/api/presentation/2/context.json',
            '@id'        => $this->uuid('http://'),
            '@type'      => 'oa:Annotation',
            'motivation' => 'sc:painting',
            'resource'   => [
              '@id'      => $image->get_id() .'full/full/0/default.jpg',
              '@type'    => 'dctypes:Image',
              'format'   => 'image/jpeg',
              'width'  => $image->get_width(),
              'height' => $image->get_height(),
              'service'  => $image->get_service_obj()
            ],
            'on' => $canvas_uuid
          ]
        ]
      ];
    }
    return $canvases;
  }

  /**
   * Get services for the manifest.
   *
   * @link https://iiif.io/api/annex/services/
   * @return array
   */
  public function get_services() {
    return [
      [
        '@context' => 'https://www.arkyves.org/help/ArkyvesIntro2.0.pdf',
        '@id'      => $this->uuid('http://'),
        'label'    => 'IconClass Terms',
        'terms'    => get_field('iconclass_terms_v2', $this->record->ID),
        'inventoryid'    => get_field('inventory_number',  $this->record->ID)

      ]
    ];
  }
}