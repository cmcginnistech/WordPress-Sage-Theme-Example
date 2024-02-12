<?php

namespace Roots\Sage\IIIF;

/**
 * An abstract class for creating a IIIF manifest.
 */
abstract class Manifest {

  public $manifestID;

  public $manifestTitle;

  public $manifestType;

  public $manifestRelated;

  public $manifestThumbnail;

  public $metadata;

  public $canvases;

  public $manifests;

  /**
   * Empty Constructor method.
   */
  public function __construct() {}

  /**
   * Create a UUID for @id's
   *
   * @link   http://ajaxray.com/blog/php-uuid-generator-function/
   * @param  string $prefix
   * @return string
   */
  public function uuid( $prefix = '' ) {
    $chars = md5(uniqid(mt_rand(), true));
    $parts = [substr($chars,0,8), substr($chars,8,4), substr($chars,12,4), substr($chars,16,4), substr($chars,20,12)];
    return $prefix . implode($parts, '-');;
  }

  /**
   * Group any available canvases into sequences.
   *
   * @return array|boolean
   */
  private function get_sequences() {

    if ( ! method_exists($this, 'get_canvases') ) {
      return false;
    }

    if ( ! $canvases = $this->get_canvases() ) {
      return false;
    }

    return [
      [
        '@id' => $this->uuid('http://'),
        '@type' => 'sc:Sequence',
        'label' => 'Normal Sequence',
        'canvases' => $canvases
      ]
    ];
  }

  /**
   * Build the completed manifest.
   *
   * @return array
   */
  private function build() {

    $manifest = [
      '@context'         => 'http://iiif.io/api/presentation/2/context.json',
      '@id'              => $this->manifestID,
      '@type'            => $this->manifestType,
      'label'            => $this->manifestTitle,
      'attribution'      => 'The Leiden Collection',
      'description'      => get_field('iiif_manifest_description_text', 'options'),
      'logo'             => get_template_directory_uri().'/dist/images/logos/leiden-logo-dark.jpg',
      'viewingDirection' => 'left-to-right',
    ];

    if ( !empty($this->metadata) ) {
      $manifest['metadata'] = $this->metadata;
    }

    if ( !empty($this->manifestThumbail) ) {
      $manifest['thumbnail'] = [
        '@id' => $this->manifestThumbail
      ];
    }

    if ( !empty($this->manifestRelated) ) {
      $manifest['related'] = $this->manifestRelated;
    }

    if ( $sequences = $this->get_sequences() ) {
      $manifest['sequences'] = $sequences;
    }

    if ( property_exists($this, 'manifests') ) {
      $manifest['manifests'] = $this->manifests;
    }

    if ( property_exists($this, 'services') ) {
      $manifest['service'] = $this->services;
    }

    return $manifest;
  }

  /**
   * Output the JSON then die.
   *
   * Access-Control-Allow-Origin header is required by 3rd
   * party services that read this file.
   */
  public function output() {
    header("Access-Control-Allow-Origin: *");
    wp_send_json( $this->build() );
  }
}
