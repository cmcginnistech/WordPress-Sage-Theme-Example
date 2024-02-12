<?php
/**
 * Create a custom controller for our own REST API endpoints.
 * See __construct for required parameters when initializing.
 */
class Leiden_REST_Controller {

  public $post_type;

  public $resource_name;

  public $namespace = '/leiden';

  public $version = '/v1';

  /**
   * Here initialize our namespace and resource name.
   *
   * @param $post_type | string
   * @param $resource_name | string
   */
  public function __construct( $post_type, $resource_name ) {
    $this->resource_name = $resource_name;
    $this->post_type     = $post_type;
  }

  /**
   * Register a route.
   * The default callback for the route is get_items(). However, this can be overriden
   * for a post type by creating a new method called get_[my-post-type]_items.
   * If passing an array of post types, the callback will always be get_items().
   */
  public function register_route() {

    if ( !is_array($this->post_type) ) {
      $method_to_check = "get_{$this->post_type}_items";
      $callback = method_exists($this, $method_to_check) ? $method_to_check : 'get_items';
    } else {
      $callback = 'get_items';
    }

    register_rest_route( $this->namespace . $this->version, '/' . $this->resource_name, array(
      array(
        'methods'   => 'GET',
        'callback'  => array( $this, $callback ),
      ),
    ));
  }

  /**
   * Process a partial file to be passed through the endpoint.
   *
   * @param $id | int (id of the post)
   * @param $partial | string (partial file to load)
   */
  public function load_template_part( $id, $partial = null, $template_name ) {
    ob_start();
    include(get_template_directory().'/templates/api-partials/'.$partial.'.php');
    return ob_get_clean();
  }

  /**
   * Get items for our collection. This is the callback for the
   * register_rest_route function above
   *
   * @param $request | WP_REST_Request $request Full details about the request.
   * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
   */
  public function get_items( $request ) {
    $type = $this->post_type;
    $params = $request->get_query_params();
    $args['posts_per_page'] = '-1';
    $args['post_type'] = $type;

    // Loop through each of our url params and add them to our $args array.
    if( $params ) {
      foreach( $params as $param => $value ) {

        // transform some custom params to their tax equivalent.
        if ( $param === 'pe' ) { $param = 'press_exhibition'; }
        if ( $param === 'pc' ) { $param = 'press_category'; }

        // make sure taxonomies always use a tax_query
        if( taxonomy_exists( $param ) && $value !== '' ) {
          $args['tax_query'][] = [
            'taxonomy' => $param,
            'field' => 'slug',
            'terms' => explode(',', $value)
          ];

        // for collection grid/gallery pages
        } elseif ( $param === 'c_artist' && $value !== '' ) {
          $args['meta_query'][] = [
            'key'     => 'artwork_to_artist_2way',
            'value'   => $value,
            'compare' => 'IN'
          ];

        // otherwise, just add params to $args
        } else {
          $args[$param] = htmlspecialchars($value);
        }
      }
    }

    /**
     * Meta query for artists. Check to see if we should hide
     * the artist based on field settings.
     */
    if ( $type === 'artist' ) {
      $args['meta_query'] = [
        'relation' => 'OR',
        [
          'key' => 'hide_on_list_view',
          'value' => 1,
          'compare' => '!='
        ],
        [
          'key' => 'hide_on_list_view',
          'compare' => 'NOT EXISTS'
        ]
      ];
    }

    /**
     * Bibliographies.
     */
    if ( $type === 'bibliography_entry' ) {
      /**
       * Order by title AND sort date (by most recent) if it's a default return.
       */
      if (!isset($params['meta_key'])) {
        $args['meta_key'] = 'bibli_sort_date';
        $args['orderby'] = [
          'title'          => 'ASC',
          'meta_value_num' => 'DESC',
        ];
        unset($args['order']);
      }

      /**
       * Revert to a normal query to allow for multidimensional orderby.
       */
      if (isset($params['se']) && $params['se'] !== '') {
        $args['s'] = $params['se'];
        unset($params['se']);
      }

      /**
       * Sort letters.
       */
      if (isset($params['sl']) && $params['sl'] !== '') {
        $args['meta_query'] = [
          [
            'key' => 'sort_letter',
            'value' => htmlspecialchars($params['sl']),
            'compare' => '='
          ]
        ];
      }
    }

    /**
     * On view now filter for collection pages.
     * @param &show
     */
    if ( isset($params['show']) && $params['show'] == 'on-view-now' ) {
      $ids = [];
      $csdata = get_option('leiden_cs_data');
      $q = get_posts([
        'post_type' => 'artwork',
        'posts_per_page' => -1,
        'meta_query' => array(
          array(
              'key' => 'location_name',
              'value' => '', //The value of the field.
              'compare' => '!=', //Conditional statement used on the value.
          )
      )
      ]);
      if ( !empty($q) ) {
        foreach ( $q as $p ) {
          // $inv_num = get_field('inventory_number', $p->ID);
          // if ( array_key_exists($inv_num, $csdata) ) {
            $ids[] = $p->ID;
          // }
        }
      }
      $args['post__in'] = $ids;

      // if we are not already sorting, set the sort order
      if ( $params['meta_key'] !== 'artwork_artist_sort_name' && $params['meta_key'] !== 'sort_date' && $params['meta_key'] !== 'artwork_medium_sort_name' ) {
        $args['meta_key'] = 'artwork_location_sort_name';
        $args['orderby'] = 'meta_value';
      }
    }

    /*
     * The query.
     * Use searchWP for search queries, otherwise, use a
     * regular wp_query.
     */
    if( isset($params['se']) && $params['se'] ) {
      $args['s'] = $params['se'];
      // $args['engine'] = 'site_search';
      $results = new SWP_Query( $args );
      unset($args['s']);
    }
    else {
      $results = new WP_Query( $args );
    }

    $total_posts = $results->found_posts;
    $max_pages = ceil( $total_posts / (int) $args['posts_per_page'] );

    /*
     * Loop through each post and return it to our json response.
     * Pass $id and $type to Api_Result_Html to format the response.
     */
    $filtered = [];
    $partial = !is_array($type) ? 'partial-'.$type : 'partial-all';
    $partial_template = isset($args['template']) ? $args['template'] : null;
    if( !empty($results->posts) ) {
      foreach( $results->posts as $result => $value ) {
        if( file_exists( get_template_directory().'/templates/api-partials/'. $partial .'.php' ) ) {
          $filtered[] = array(
            'id' => $value->ID,
            'title' => get_the_title( $value->ID ),
            'html'  => $this->load_template_part( $value->ID, $partial, $partial_template )
          );
        } else {
          $filtered[] = array( 'Error' => $partial );
        }
      }
    }

    // Prepare the response.
    $response = rest_ensure_response( $filtered );
    $response->header( 'X-WP-Total', (int) $total_posts );
    $response->header( 'X-WP-TotalPages', (int) $max_pages );

    return $response;
  }
}

/**
 * Register all new routes from the controller.
 * Note: rest controler expects these args - ( post_type(s), endpoint_name )
 */
function register_rest_routes() {

  // define the routes
  $routes = [
    [
      'post_type'     => 'artist',
      'resource_name' => 'artists'
    ],
    [
      'post_type'     => 'essay',
      'resource_name' => 'essays'
    ],
    [
      'post_type'     => 'bibliography_entry',
      'resource_name' => 'bibliography'
    ],
    [
      'post_type'     => 'video',
      'resource_name' => 'videos'
    ],
    [
      'post_type'     => 'entry_author',
      'resource_name' => 'authors'
    ],
    [
      'post_type'     => 'press',
      'resource_name' => 'press'
    ],
    [
      'post_type'     => 'staff_member',
      'resource_name' => 'staff'
    ],
    [
      'post_type'     => ['artist', 'artwork', 'group', 'essay', 'video', 'entry_author'],
      'resource_name' => 'all'
    ],
    [
      'post_type'     => 'artwork',
      'resource_name' => 'artworks'
    ]
  ];

  // register each route
  foreach( $routes as $route ) {
    $posts_controller = new Leiden_REST_Controller( $route['post_type'], $route['resource_name']);
    $posts_controller->register_route();
  }

}
add_action( 'rest_api_init', 'register_rest_routes' );