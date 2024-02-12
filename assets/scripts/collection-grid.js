/**
 * All of the Isotope/Masonry stuff for the collection pages.
 * Used for both the gallery and grid templates.
 *
 * Note: we are only using isotope/masonry for the layout engine.
 * All of the filtering and sorting is done via lib/wp-api.php.
 * The isotope instance created here will be read by the
 * ajax functions in main.js.
 */
function initCollectionView() {

  var $grid = $('#js-posts-row'),
      filters = {},
      defaultFilter;

  // Intialize isotope
  $grid.isotope({
    layoutMode: 'masonryHorizontal',
    itemSelector: '.item',
    masonryHorizontal: {
      rowHeight: '.grid-sizer',
      gutter: 20
    },
    getSortData: {
      artist: '[data-artist]',
      date: '[data-date]',
      medium: '[data-medium]'
    },
    filter: defaultFilter
  });

  // Re-layout masonry each time an image is lazy-loaded
  $(document).on('lazyloaded', function (e) {

    $grid.isotope('layout');

    $(e.target).each(function() {
      $(this).parents('.item').addClass('loaded');
    });

  });

  // Gallery/Grid view toggle
  // This will change the URL and prepend any selected filters as
  // query params.
  $('.collection-view-select').on('changed.bs.select', function (e) {
    var newUrl = $(this).find('.selected a').data('tokens'),
        $sortBy = $(this).siblings('.sort-filter').find('.selected a'),
        params = {};

    $('.filter').each(function() {
      var key = $(this).data('filter'),
          selected = $(this).find('.selected a').map(function() {
              return $(this).data('tokens');
            }).toArray().join();

      if ( selected !== '' ) {
        params[key] = selected;
      }
    });

    if ( $sortBy.find('.text').text() !== 'None' ) {
      params.sortby = $sortBy.data('tokens');
    }

    window.location = newUrl + '?' + $.param(params);
  });

}

/**
 * Gallery sizing for images.
 * Relies on the isotope instance created in initCollectionView()
 */
function initGallerySizing() {

  var $bench = $('#bench'),
      $stage = $('.collection-stage'),
      isoInstance = $('#js-posts-row').data().isotope;

  isoInstance.on('layoutComplete', function () {
    var benchH = $bench.height(),
        stageH = $stage.height(),
        stageMinusBenchH = stageH - benchH,
        correction;

    // img width/height get multiplied by this.
    // this way img will always be proportional to window height.
    // decreasing the denominator will make image larger.
    correction = stageMinusBenchH / 95;

    if ( isBreakpoint('xs') ) {
      correction = stageMinusBenchH / 150;
    }

    // adjust the height of each image so it is proportional.
    $('.collection-gallery .item img').each(function() {
      var $this = $(this),
          w = $this.parents('.item').attr('data-frame-w'),
          h = $this.parents('.item').attr('data-frame-h');

      $this.parent('.item-img-wrap').css({
        width: (w * correction),
        height: (h * correction),
        marginTop: (stageMinusBenchH - (h*correction)) / 2.25
      });

    });
  });
}

/**
 * Init gallery tooltips.
 */
function initGalleryPopovers() {

  $('.item').each(function() {
    var $this = $(this),
        $content = $this.find('.popover-content').html();

    $this.find('.popup-trigger').tooltipster({
      contentAsHTML: true,
      side: ['bottom', 'right', 'left', 'top'],
      trigger: 'click',
      content: $content,
      interactive: true,
      maxWidth: 400
    });

  });

}

/**
 * Size Grid items.
 * This applies a sort of aspect ratio container size to the
 * image's parent div for a smoother effect when lazyloading.
 */
function runGridSizing() {
  $('.collection-grid .item').each(function () {
    var $this = $(this),
      img = $this.find('img'),
      w = img.attr('width'),
      h = img.attr('height'),
      newWidth = (w / h) * $this.height();
    $this.css('width', newWidth);
  });
  $('#js-posts-row').data().isotope.layout();
}

/**
 * Kinetic touch drag support.
 */
function initKinetic() {
  var $wrapper = $('#js-posts-wrapper');

  // bind the wrapper.
  // use filterTarget so hoverIntent events bubble up and
  // are clickable on mobile.
  $wrapper.kinetic({
    maxvelocity: 80,
    slowdown: 0.95,
    filterTarget: function (target, e) {
      console.log(this);
      // zero this.prevXPos mean that there was a click without move
      // if (parseInt(this.prevXPos) === 0 && !/down|start/.test(e.type)) {
      if (!/down|start/.test(e.type)) {
        // if event target is an anchor tag, make sure those events bubble through
        // so the hyperlink works.
        return !(/a/i.test(target.tagName));
      }
    }
  });

  /**
   * Scroll the container using kinetic methods.
   * Basically start the scroll and then stop after .5 seconds.
   *
   * @param {int} vel
   */
  function doScroll( vel ) {
    $wrapper.kinetic('start', { velocity: vel });
    setTimeout( function() {
      $wrapper.kinetic('stop');
    }, 500);
  }

  // ui controls
  $('#collection-nav .nav-right').on('click', function() {
    doScroll(30);
  });
  $('#collection-nav .nav-left').on('click', function() {
    doScroll(-30);
  });

}

/**
 * Initialize hover intent for grid items.
 */
function initGridItemsHoverIntent() {
  $('.collection-grid-item').hoverIntent({
    over: function () {
      $(this).addClass('hover');
    },
    out: function () {
      $(this).removeClass('hover');
    },
    sensitivity: 0.9
  });
}

/**
 * Fire functions on document ready.
 */
(function ($) {
  $(document).on('ready', function() {

    initCollectionView();
    initGallerySizing();
    initAjaxPosts();
    initKinetic();

    $(window).on('resize', debounce(function() {
      runGridSizing();
    }, 300));

    // make sure we always scroll back to the beginning when applying new filters
    $('.collection-grid-filters .sort-filter, .collection-grid-filters .filter').on('changed.bs.select', function (e) {
      $('#js-posts-wrapper').scrollLeft(0);
    });

  });
})(jQuery);