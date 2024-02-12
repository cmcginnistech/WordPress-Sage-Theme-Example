/**
 * Vh fix
 */
function vhfix() {
  // First we get the viewport height and we multiple it by 1% to get a value for a vh unit
  var vh = window.innerHeight * 0.01;
  // Then we set the value in the --vh custom property to the root of the document
  document.documentElement.style.setProperty("--vh", vh + "px");
}

/**
 * returns true or false based on current Bootstrap breakpoint
 */
function isBreakpoint(alias) {
  return $(".device-" + alias).is(":visible");
}

function homeMenu() {
  $("#homeHeroNavbar .dropdown").on("show.bs.dropdown", function () {
    $("#homeHeroNavbar").addClass("navbar-open");
    $("#heroCarousel").addClass("carousel-navbar-open");
  });
  $("#homeHeroNavbar .dropdown").on("hide.bs.dropdown", function () {
    $("#homeHeroNavbar").removeClass("navbar-open");
    $("#heroCarousel").removeClass("carousel-navbar-open");
  });
}

function bsCarousel() {
  $(".carousel").carousel({
    interval: 7000,
  });
}
//Allows bootstrap carousels to display 3 items per page rather than just one
function exhibitionMultiCarousel() {
  var slider = tns({
    container: ".tiny-slider",

    responsive: {
      700: {
        items: 1,
      },
      900: {
        items: 2,
      },
      1600: {
        items: 3,
      },
      5000: {
        items: 3,
      },
    },
    items: 1,
    arrowKeys: true,
    mouseDrag: true,
    speed: 400,
  });
}

/**
 * returns current breakpoint as string based on isBreakpoint
 */
function getBreakpoint() {
  if (isBreakpoint("lg")) {
    return "lg";
  }
  if (isBreakpoint("md")) {
    return "md";
  }
  if (isBreakpoint("sm")) {
    return "sm";
  }
  return "xs";
}

/**
 * Adds 3d translate parallax to elements. Pass an object with key value pairs corresponding to 'selector': relative where relative to a float indicating the relative speed. 0 is same, >0 is slower, <0 is faster.
 */
function parallaxative(targets) {
  var lax = function () {
    return function () {
      var top = $(window).scrollTop();
      for (var target in targets) {
        $(target).css({
          transform: "translate3D(0," + top * targets[target] + "px,0)",
        });
      }
    };
  };
  $(window).on("scroll", lax());
}

/**
 * Explicitly save/update a url parameter using HTML5's replaceState().
 * @link https://gist.github.com/excalq/2961415
 */
function updateQueryStringParam(key, value) {
  var baseUrl = [
      location.protocol,
      "//",
      location.host,
      location.pathname,
    ].join(""),
    urlQueryString = document.location.search,
    newParam = key + "=" + value,
    params = "?" + newParam;

  // If the "search" string exists, then build params from it
  if (urlQueryString) {
    var updateRegex = new RegExp("([?&])" + key + "[^&]*");
    var removeRegex = new RegExp("([?&])" + key + "=[^&;]+[&;]?");

    // if param is empty, remove it
    if (typeof value === "undefined" || value === null || value === "") {
      params = urlQueryString.replace(removeRegex, "$1");
      params = params.replace(/[&;]$/, "");

      // if param already exists, update it
    } else if (urlQueryString.match(updateRegex) !== null) {
      params = urlQueryString.replace(updateRegex, "$1" + newParam);

      // otherwise, add it to the end
    } else {
      params = urlQueryString + "&" + newParam;
    }
  }

  // remove any left over empty params
  params = params.replace(/[^=&]+=(&|$)/g, "").replace(/&$/, "");

  // no parameter was set so we don't need the question mark
  params = params === "?" ? "" : params;

  // update the window history
  window.history.replaceState({}, "", baseUrl + params);
}

/**
 * Initialize the inline link tooltips
 */
function initRichInlineLinks() {
  // Add a tooltip to all links in text component
  // where the href includes essays, artwork, artists, or videos anywhere
  // in the url but filter to not include hash links (endnotes & comp figs)
  $(".component--text .entry-content a")
    .filter(function () {
      if (
        !this.href.match(/#/) &&
        $(".introduction-to-the-collection-catalogue").length == 0
      ) {
        return this.href.match(/essays|artwork|artists|videos/);
      }
    })
    .tooltipster({
      content: "Loading...",
      contentAsHTML: true,
      interactive: true,
      maxWidth: 180,
      delay: [200, 600],
      arrow: false,
      position: "bottom",
      functionBefore: function (instance, helper) {
        var $origin = $(helper.origin);

        // we set a variable so the data is only loaded once via Ajax,
        // not every time the tooltip opens
        if ($origin.data("loaded") !== true) {
          $.ajax({
            method: "POST",
            url: window.location.origin + "/wp-admin/admin-ajax.php",
            data: {
              action: "get_tooltip_data",
              _ajax_nonce: $("#get_tooltip_data_nonce").data("nonce"),
              permalink: instance._$origin.context.href,
            },
            success: function (data) {
              // call the 'content' method to update the content of our tooltip with the returned data.
              // note: this content update will trigger an update animation (see the updateAnimation option)
              instance.content(data);

              // to remember that the data has been loaded
              $origin.data("loaded", true);
            },
          });
        }
      },
      functionReady: function (instance, helper) {
        instance._$tooltip.addClass("leiden-side-tip");
      },
      functionPosition: function (instance, helper, position) {
        var $wrapper = $(".component--text"),
          wrapperOffset = $wrapper.offset(),
          wrapperHeight = $wrapper.height(),
          windowScrollTop = $(window).scrollTop(),
          wrapperDistanceFromBtm,
          wrapperDistanceToTop,
          tooltipDistanceToTop;

        // wrapper distance to top of viewport (includes line height allowance)
        wrapperDistanceToTop = wrapperOffset.top - windowScrollTop + 8;
        wrapperDistanceFromBtm = $wrapper[0].getBoundingClientRect().bottom;

        // distance from top of tooltip to top of viewport.
        tooltipDistanceToTop = position.coord.top - position.size.height;
        tooltipDistanceToBtm = position.coord.top + position.size.height;

        // make sure we don't display outside vertical bounds of wrapper.
        // first check if top of tooltip will be past the top of wrapper.
        if (tooltipDistanceToTop < wrapperDistanceToTop) {
          position.coord.top = wrapperDistanceToTop;
        }
        // check if bottom of tooltip will be past bottom of wrapper
        else if (tooltipDistanceToBtm > wrapperDistanceFromBtm) {
          position.coord.top = position.coord.top - position.size.height + 32;
        } else {
          // subtract 32 here so tooltip displays at top of line
          position.coord.top = position.coord.top - 32;
        }

        // set tooltip left hand position to left edge of wrapper
        position.coord.left = wrapperOffset.left;

        return position;
      },
    });
}

/**
 * Inline content that is togglable via shortcodes.
 */
function initInlineContent() {
  $('[data-toggle="inline-ref"]').on("click", function (e) {
    var $this = $(this),
      target = $this.attr("href"),
      src = $this.attr("data-src"),
      content = $(src).html();

    e.preventDefault();

    // check if we are on a comp-fig toggle and if we only
    // have one figure, trigger a click on the corresponding
    // sidebar element which opens a modaal lightbox.
    if (
      $this.hasClass("fig-toggle") &&
      $('[data-fig-ref="' + src + '"]').length
    ) {
      var $modaal = $('[data-fig-ref="' + src + '"]'),
        modaal_image = $modaal.html(),
        modaal_caption = $modaal.data("modaal-desc");

      content =
        '<figure class="single-fig"><a href="#" class="overlay dib">' +
        modaal_image +
        '<div class="overlay-content overlay-content--center tc"><span class="plus-icon" aria-hidden="true"></span></div></a><figcaption>' +
        modaal_caption +
        "</figcaption></figure>";

      $(document).on("click", target + " a.overlay", function (e) {
        e.preventDefault();
        $modaal.trigger("click");
      });
    }

    // prepend a close button
    content =
      '<button class="close inline-ref-close" aria-expanded="true">&times;</button>' +
      content;

    // open the inline content and populate it with the content.
    $(target).find(".inline-ref-content").html(content);
    $(target).toggleClass("open");

    // re-initialize modaal on dynamically populated content
    $(".inline-ref-content .modaal").modaal({ type: "image" });

    // a11y
    if ($(target).attr("aria-expanded") === "true") {
      $this.attr("aria-expanded", "false");
      $(target).attr("aria-expanded", "false");
    } else {
      $this.attr("aria-expanded", "true");
      $(target).attr("aria-expanded", "true");
    }
  });
}

/**
 * Bind click event for inline-ref close button since
 * it is added dynamically.
 */
$(document).on("click", ".inline-ref-close", function () {
  var $this = $(this);
  $this
    .parents(".endnote.open")
    .removeClass("open")
    .attr("aria-expanded", "false");
});

// =====
// Make button open modaal lightbox gallery.
// Button needs to have a data-gallery attr equal to the namespace of the gallery.
// @param button is the class of the button.
// =====
function initViewGallery(elem) {
  elem.each(function () {
    var modaal_gallery = $(this).attr("data-gallery");
    $(this).on("click", function () {
      $(
        "#" + modaal_gallery + " a:first-child[rel=" + modaal_gallery + "]"
      ).click();
    });
  });
}

/**
 * Handy function to debounce functions.
 * Example use in #ajax-search.
 */
function debounce(fn, duration) {
  var timer;

  return function () {
    var context = this,
      args = arguments;

    clearTimeout(timer);
    timer = setTimeout(function () {
      timer = null;
      fn.apply(context, args);
    }, duration);
  };
}

/**
 * Loads posts from REST API and appends them to #js-posts-row via ajax.
 * Options is an object that can have properties.
 */
var totalReturned = 0,
  totalPages,
  totalPosts;
var isCollectionPg =
  $(".page-template-template-collection").length ||
  $(".page-template-template-collection-gallery").length;

function loadPosts(options) {
  // Default variables
  var wrapper = $("#js-posts-wrapper"),
    row = $("#js-posts-row"),
    context = options.context !== "all" ? options.context : "all",
    offset = parseInt(wrapper.attr("data-offset")) || "",
    current = parseInt(wrapper.attr("data-index")) || "",
    isoInstance = row.data().isotope;
  url =
    "/wp-json/leiden/v1/" +
    context +
    "/?posts_per_page=" +
    offset +
    "&paged=" +
    (current + 1);

  // Delete options that we do not want to pass as query params.
  // These options were used above.
  delete options.context;
  delete options.index;
  delete options.offset;

  // Assemble the URL
  url += "&" + $.param(options);

  // console.log(url);

  // begin ajax request
  $.getJSON(url, function (data, status, xhr) {
    totalPages = xhr.getResponseHeader("x-wp-totalpages");
    totalPosts = xhr.getResponseHeader("x-wp-total");

    $(data).each(function () {
      if (isCollectionPg) {
        var $item = $(this.html);
        row.append($item);
        isoInstance.appended($item);
        totalReturned++;
      } else {
        row.append(this.html);
        totalReturned++;
      }
    });
  })
    .done(function () {
      // load more indicator
      $("#js-posts-load-more")
        .removeClass("loading")
        .attr("data-finished", totalReturned >= totalPosts)
        .button("reset");

      // hide loading icon
      $("#ajaxLoading").hide();

      // search page
      $("#js-result-count").html(
        "Showing <span>" +
          totalReturned +
          "</span> of <span>" +
          totalPosts +
          "</span> results"
      );
      $("#js-search-tabs").removeClass("off");

      // no results
      var $noResults = $(
        '<div class="cs12 item no-results" style="min-width:400px"><div class="alert alert-warning">Your selection has returned no results. Please clear a filter and try again.</div></div>'
      );
      if ($("body").hasClass("search")) {
        $noResults = $(
          '<div class="cs12 item no-results" style="min-width:400px"><div class="alert alert-warning">Your search has returned no results. Please try a different search.</div></div>'
        );
      }
      if (totalReturned === 0) {
        row.append($noResults);
      }

      // collection pages (use isotope/masonry library)
      if (isCollectionPg) {
        runGridSizing();
        initGalleryPopovers();
        initGridItemsHoverIntent();
        $(".collection-stage").addClass("loaded");
        if (totalReturned === 0) {
          isoInstance.appended($noResults);
        }
      }
    })
    .fail(function () {
      row.append(
        '<div class="cs12" style="min-width:400px"><div class="alert alert-danger">There was an error loading the posts.</div></div>'
      );
    });

  wrapper.attr("data-index", current + 1);
}

/**
 * Initialize the first ajax load.
 * Also contains a number of event listeners for filters.
 */
function initAjaxPosts() {
  /*
   * Grab all data attrs from wrapper and create options object.
   */
  function getOptions() {
    var wrapper = $("#js-posts-wrapper"),
      row = $("#js-posts-row"),
      options = {};

    $.each(wrapper.data(), function (key, value) {
      if (key !== "kinetic") {
        options[key] = value;
      }
    });

    return options;
  }

  /*
   * Load the initial set of results.
   */
  if ($("#js-posts-wrapper").length) {
    loadPosts(getOptions());
  }

  /*
   * Function to clear the ajax wrapper and reset things.
   */
  function clearPosts() {
    totalReturned = 0;
    $("#ajaxLoading").show();
    $("#js-posts-wrapper").attr("data-index", 0);

    if (isCollectionPg) {
      var isoInstance = $("#js-posts-row").data().isotope,
        allItems = isoInstance.getItemElements();
      isoInstance.remove(allItems);
    } else {
      $("#js-posts-row").empty();
    }
  }

  /*
   * Infinite scrolling for all archive pages (and search).
   * Uses verge lib to detect when indicator is in
   * viewport, then runs loadPosts.
   * Make sure to debounce or you will run ajax requests for as
   * long as the element is in viewport!!
   *
   * Note: collection pg scrolls horizontally.
   */
  var $loadMoreIndicator = $("#js-posts-load-more");
  var $scrollTarget = isCollectionPg ? $(".collection-stage") : $(window);

  $scrollTarget.on("scroll", function () {
    if ($scrollTarget.scrollLeft() > 10) {
      $scrollTarget.addClass("scrolled");
    } else {
      $scrollTarget.removeClass("scrolled");
    }
    if (verge.inViewport($loadMoreIndicator, 100)) {
      if (
        !$loadMoreIndicator.hasClass("loading") &&
        $loadMoreIndicator.attr("data-finished") === "false"
      ) {
        $loadMoreIndicator.addClass("loading");
        debounce(loadPosts(getOptions()), 100);
      }
    }
  });

  /*
   * Combine all the filters for sending to loadPosts().
   */
  function combineFilters() {
    var defaultOptions = getOptions(),
      $wrapper = $("#js-posts-wrapper"),
      newOptions = {},
      search = $("#js-search").val(),
      combinedFilters = {};

    $("[data-filter]").each(function (options) {
      var $filterItem = $(this),
        key = $filterItem.attr("data-filter"),
        values = $filterItem
          .find(".selected a")
          .map(function () {
            return $(this).data("tokens");
          })
          .toArray()
          .join();

      newOptions[key] = values;

      if (values !== "") {
        $wrapper.data(key, values);
      } else {
        $wrapper.removeData(key, null);
      }
    });

    if ($("#js-search").length) {
      if (search.length > 0) {
        newOptions.se = search;
      }
    }

    if (isCollectionPg) {
      var customSortBy = $(".collection-grid-filters .sort-filter")
        .find(".selected a")
        .data("tokens");
      if (
        customSortBy === "artwork_artist_sort_name" ||
        customSortBy === "artwork_medium_sort_name"
      ) {
        newOptions.orderby = "meta_value";
      }
      newOptions.meta_key = customSortBy;
    }

    // combine results and return.
    combinedFilters = $.extend(defaultOptions, newOptions);
    return combinedFilters;
  }

  /*
   * Wrapper function for clear and load posts.
   */
  function doFiltering(options) {
    clearPosts();
    loadPosts(options);
  }

  /**
   * Redirect press archive pages.
   *
   * @param jQuery object | $filterEl (the filter being clicked)
   * @param object | LeidenPressVars (global window variable)
   */
  function doPressRedirection($filterEl) {
    var $this = $filterEl,
      selected = $this.find(".selected a"),
      filterName = $this.data("filter"),
      activeFilters = combineFilters(),
      currentUrl = window.location.origin + window.location.pathname,
      isMainPressPage = currentUrl === LeidenPressVars.base ? true : false,
      newLocation;

    if (selected.length < 1) {
      newLocation = LeidenPressVars.base;
      if (
        filterName === "pe" &&
        "pc" in activeFilters &&
        activeFilters.pc !== ""
      ) {
        newLocation = LeidenPressVars.base + "category/" + activeFilters.pc;
      } else if (
        filterName === "pc" &&
        "pe" in activeFilters &&
        activeFilters.pe !== ""
      ) {
        newLocation = LeidenPressVars.base + "exhibition/" + activeFilters.pe;
      }
      window.location = newLocation;
      return false;
    } else if (selected.length === 1) {
      if (filterName === "pe") {
        newLocation =
          LeidenPressVars.base + "exhibition/" + selected.data("tokens");
        if ("pc" in activeFilters && activeFilters.pc !== "") {
          newLocation += "/?pc=" + activeFilters.pc;
        }
      } else if (filterName === "pc") {
        newLocation =
          LeidenPressVars.base + "category/" + selected.data("tokens");
        if ("pe" in activeFilters && activeFilters.pe !== "") {
          newLocation += "/?pe=" + activeFilters.pe;
        }
      }
      window.location = newLocation;
      return false;
    } else if (selected.length > 1) {
      var params = {};
      if ("pe" in activeFilters && activeFilters.pe !== "") {
        params.pe = activeFilters.pe;
      }
      if ("pc" in activeFilters && activeFilters.pc !== "") {
        params.pc = activeFilters.pc;
      }
      window.location = LeidenPressVars.base + "?" + $.param(params);
      return false;
    }
  }

  /**
   * Add a change event for all multiselect filters.
   *
   * @param object | LeidenPressVars (global window variable)
   */
  $("[data-filter]").on("changed.bs.select", function (e) {
    var $this = $(this),
      filterName = $this.data("filter"),
      currentUrl,
      base,
      isMainPressPage;

    // if LeidenPressVars is defined (defined in lib/archives.php)
    if (typeof LeidenPressVars !== "undefined") {
      currentUrl = window.location.origin + window.location.pathname;
      isMainPressPage = currentUrl === LeidenPressVars.base ? true : false;
      // if on an exhibition page and filter is category, do ajax filtering
      if (
        LeidenPressVars.taxonomy === "press_exhibition" &&
        filterName === "pc"
      ) {
        doFiltering(combineFilters());
        // hide featured press when doing filtering
        $("#featuredPress").hide();
      }
      // if on main press page and filter is category, do ajax filtering
      else if (isMainPressPage && filterName === "pc") {
        doFiltering(combineFilters());
        // hide featured press when doing filtering
        $("#featuredPress").hide();
      }
      // if on any page with LeidenPressVars defined
      else {
        doPressRedirection($this);
      }
    }
    // otherwise  do filtering
    else {
      $this.find(".selectpicker").selectpicker("toggle");
      doFiltering(combineFilters());
    }
  });

  /*
   * Select input for Alphabetical sort filters.
   */
  $(".sortJS").on("change", function (e) {
    var $wrapper = $("#js-posts-wrapper");
    var options = getOptions();
    options.order = "ASC";
    var metakey = $(this).find("option:selected").attr("data-metakey");
    var orderby = $(this).find("option:selected").attr("data-orderby");
    options.meta_key = metakey;
    options.orderby = orderby;
    $wrapper.data("meta_key", metakey);
    $wrapper.data("orderby", orderby);

    doFiltering(options);
  });

  /*
   * Alphabetical sort filters on Bibligraphy.
   */
  $(".bibliography-nav a").on("click", function (e) {
    var $wrapper = $("#js-posts-wrapper");
    var $selected = $(this).data("sort-letter");

    var options = getOptions();
    if ($selected !== "all") {
      options.sl = $selected;
      $wrapper.data("sl", $selected);
    } else {
      options.sl = "";
      $wrapper.data("sl", "");
    }
    doFiltering(options);
  });

  $(".bibliography-select-wrapper").on("changed.bs.select", function (e) {
    var $wrapper = $("#js-posts-wrapper");
    var options = getOptions();

    var $selected = $(this).find(".selected a").data("tokens");
    options.sl = $selected;
    $wrapper.data("sl", $selected);
    doFiltering(options);
  });

  /*
   * Alphabetical sort filters.
   * Make specific to archive headers b/c the collection pg has a .sort-filter
   */
  $(".archive-filter-header .sort-filter").on("click", function (e) {
    var $this = $(this),
      options = getOptions(),
      $wrapper = $("#js-posts-wrapper"),
      val_orderby = $this.attr("data-orderby"),
      val_metakey = $this.attr("data-metakey");
    e.preventDefault();
    options.orderby = val_orderby;
    $wrapper.data("orderby", val_orderby);

    if (
      val_orderby !== "title" &&
      val_orderby !== "date" &&
      val_metakey.length > 0
    ) {
      options.meta_key = val_metakey;
      $wrapper.data("meta_key", val_metakey);
    }

    $(".sort-handle").each(function () {
      $(this).addClass("unsorted").removeClass("sorted up");
    });

    if ($wrapper.attr("data-order") === "ASC") {
      options.order = "DESC";
      $(this)
        .find(".sort-handle")
        .addClass("sorted")
        .removeClass("unsorted up");
      $wrapper.attr("data-order", "DESC").data("order", "DESC");
    } else {
      options.order = "ASC";
      $(this)
        .find(".sort-handle")
        .addClass("sorted up")
        .removeClass("unsorted");
      $wrapper.attr("data-order", "ASC").data("order", "ASC");
    }
    clearPosts();
    doFiltering(options);
  });

  /*
   * Ajax search
   */
  $("#js-search").on(
    "keyup paste",
    debounce(function (e) {
      var pressed = e.which,
        keysToIgnore = [9, 37, 38, 39, 40, 17, 18, 93, 16, 20],
        query,
        options = getOptions(),
        $wrapper = $("#js-posts-wrapper");

      // don't do search if user pressed tab, any arrow, ctrl, apple, alt/opt, shift, caps
      if ($.inArray(pressed, keysToIgnore) >= 0) {
        return;
      }

      query = $(this).val();
      options.se = "";

      // do search only if query is not empty
      if (query.length > 0) {
        options.se = query;
        $wrapper.data("se", options.se);
      } else {
        $wrapper.data("se", "");
      }

      $("#featuredPress").hide();

      clearPosts();
      doFiltering(options);
    }, 800)
  );

  /*
   * On the search page, search nav tabs will run a call
   * to loadPosts
   */
  $("#js-search-tabs li > a").on("click", function (e) {
    var options = getOptions(),
      $this = $(this),
      postType = $this.data("type"),
      $parent = $("#js-search-tabs"),
      $wrapper = $("#js-posts-wrapper");

    e.preventDefault();

    if ($parent.hasClass("off") || $this.parent().hasClass("active")) {
      return;
    }
    $parent.addClass("off");

    if (postType !== "all") {
      options.post_type = postType;
      $wrapper.data("post_type", postType);
    }

    $this.parent().addClass("active");
    $this.parent().siblings().removeClass("active");

    doFiltering(options);
  });

  /*
   * Search page mobile select box to do filtering,
   * rather than tabs.
   */
  $("#searchTabSelect").on("changed.bs.select", function (e) {
    var options = getOptions(),
      postType = $(this).find(".selected a").data("tokens");

    e.preventDefault();

    if (postType !== "all") {
      options.post_type = postType;
    }

    doFiltering(options);
  });

  /**
   * Collection sort filter.
   */
  $(".collection-view-select + .sort-filter").on("changed.bs.select", function (
    e
  ) {
    var selected = $(this).find(".selected a").data("tokens"),
      options = getOptions(),
      $wrapper = $("#js-posts-wrapper");

    if (selected !== "sort_date") {
      options.orderby = "meta_value";
      $wrapper.data("orderby", "meta_value");
    }
    options.meta_key = selected;
    $wrapper.data("meta_key", selected);

    doFiltering(options);
  });
}

/**
 * Masonry
 */
function initMasonry() {
  var $masonry_container = $(".masonry--grid");

  $(window).on("load", function () {
    $masonry_container.masonry({
      itemSelector: ".masonry--item",
      columnWidth: ".masonry--grid-sizer",
      percentPosition: true,
    });
  });

  $masonry_container.on("layoutComplete", function () {
    $(this).addClass("masonry-loaded");
  });

  $(window).on(
    "resize",
    debounce(function (e) {
      $masonry_container.masonry();
    }, 750)
  );
}

/**
 * Back to top arrow.
 */
function initScrollBackToTop() {
  var $button = $(".scroll-to-top");

  //Check to see if the window is top if not then display button
  $(window).scroll(function () {
    if ($(this).scrollTop() > 300) {
      $button.fadeIn();
    } else {
      $button.fadeOut();
    }
  });

  //Click event to scroll to top
  $button.click(function () {
    $("html, body").animate({ scrollTop: 0 }, 800);
    return false;
  });
}

/**
 * Init smooth scroll links.
 */
function initSmoothScroll() {
  $('a[href*="#"]')
    // Remove links that don't actually link to anything
    .not('[href="#"]')
    .not('[href="#0"]')
    .not('[data-toggle="inline-ref"]')
    .not('[data-toggle="tab"]')
    .not('[data-slide="next"]')
    .not('[data-slide="prev"]')

    .click(function (event) {
      // On-page links
      if (
        location.pathname.replace(/^\//, "") ===
          this.pathname.replace(/^\//, "") &&
        location.hostname === this.hostname
      ) {
        // Figure out element to scroll to
        var target = $(this.hash);
        if (target.length) {
          target = target;
        } else {
          target = $("[name=" + this.hash.slice(1) + "]");
        }
        // Does a scroll target exist?
        if (target.length) {
          // Only prevent default if animation is actually gonna happen
          event.preventDefault();
          $("html, body").animate(
            {
              scrollTop: target.offset().top,
            },
            1000,
            function () {
              // Callback after animation
              // Must change focus!
              var $target = $(target);
              $target.focus();
              if ($target.is(":focus")) {
                // Checking if the target was focused
                return false;
              } else {
                $target.attr("tabindex", "-1"); // Adding tabindex for elements not focusable
                $target.focus(); // Set focus again
              }
            }
          );
        }
      }
    });
}

/**
 * Init sticky tabs with bootstrap affix.
 */
function initStickyTabs() {
  var $target = $(".entry-tabnav"),
    $tabContainer = $(".entry-tab-container"),
    tabContainerTop = $tabContainer.offset().top;

  // scroll back to top if the nav is affixed
  $(".nav-tabs > li > a").on("click", function () {
    if ($target.hasClass("affix")) {
      $("html, body").animate({ scrollTop: tabContainerTop }, 200);
    }
  });

  // recalc container offset after webfonts have loaded
  WebFontConfig.active = function () {
    tabContainerTop = $tabContainer.offset().top;
  };

  // do affix stuff inside a window resize to exclude mobile
  // make sure we destroy it if resizing from lg to xs.
  $(window).on(
    "load resize",
    debounce(function () {
      if (!isBreakpoint("xs")) {
        $target.affix({
          offset: {
            top: Math.floor(tabContainerTop),
          },
        });
      } else {
        // affix doesn't have a destroy method so we do it manually!
        $(window).off(".affix");
        $target
          .removeData("bs.affix")
          .removeClass("affix affix-top affix-bottom");
      }
    }, 400)
  );
}

/* ========================================================================
 * DOM-based Routing
 * Based on http://goo.gl/EUTi53 by Paul Irish
 *
 * Only fires on body classes that match. If a body class contains a dash,
 * replace the dash with an underscore when adding it to the object below.
 *
 * .noConflict()
 * The routing is enclosed within an anonymous function so that you can
 * always reference jQuery with $, even when in .noConflict() mode.
 * ======================================================================== */

(function ($) {
  // Use this variable to set up the common and page specific functions. If you
  // rename this variable, you will also need to rename the namespace below.
  var Sage = {
    // All pages
    common: {
      init: function () {
        // JavaScript to be fired on all pages
        vhfix();
      },
      finalize: function () {
        // JavaScript to be fired on all pages, after page specific JS is fired

        // scroll to top
        initScrollBackToTop();
        initSmoothScroll();
        // Reduced motion test.
        if (window.matchMedia("(prefers-reduced-motion)").matches) {
          $("body").addClass("reduced-motion");
        }

        // Only fire parallax when user doesn't have reduced motion preferences enabled.
        if (!$("body").hasClass("reduced-motion")) {
          parallaxative({ ".plax": 0.25 });
        }

        // Remove loading icon class once image is done lazyloading.
        document.addEventListener("lazyloaded", function (e) {
          e.target.parentNode.classList.remove("loading");
        });

        // Inline content in text components.
        initInlineContent();

        // Rich inline links (only for desktop)
        $(window).bind(
          "load resize",
          debounce(function () {
            // if large breakpoint, initialize tooltipster
            if (isBreakpoint("lg")) {
              initRichInlineLinks();

              // otherwise, make sure we destroy the tooltips in case we are resizing.
              // but make sure to destroy only the ones in div.entry-content otherwise,
              // none of the tooltips will work
            } else {
              var instances = $.tooltipster.instances(".entry-content a");
              $.each(instances, function (i, instance) {
                instance.destroy();
              });
            }
          }, 400)
        );

        // Modaal lightboxes.
        $(".modaal-inline").modaal();
        $(".modaal-gallery").modaal({
          type: "image",
        });
        initViewGallery($(".btn-modaal-open"));

        // elements that get collapsed for mobile but not desktop
        $(window).bind(
          "load resize",
          debounce(function () {
            if (isBreakpoint("xs")) {
              $(".collapse-for-small").each(function () {
                $(this).addClass("collapse").attr("aria-expanded", "false");
              });
            } else {
              $(".collapse-for-small").each(function () {
                $(this)
                  .removeClass("collapse")
                  .attr("aria-expanded", "true")
                  .css("height", "auto");
              });
            }
          }, 300)
        );

        // Tooltips via tooltipster
        $(".tooltip").tooltipster({
          interactive: true,
          maxWidth: 300,
        });

        // copy to clipboard buttons
        var clipboard = new Clipboard(".clipboard");
        clipboard.on("success", function (e) {
          alert("The permalink has been copied to the clipboard.");
        });

        // scroll window to top of tabs when they are collapsed
        $(document).on(
          "shown.bs.collapse",
          ".panel-collapse, a[data-toggle='tab']",
          function (e) {
            triggerTop = $(e.target).siblings(".panel-heading").offset().top;
            $("html, body").animate({ scrollTop: triggerTop }, 200);
          }
        );
      },
    },
    home: {
      finalize: function () {
        bsCarousel();
        homeMenu();
      },
    },
    archive: {
      finalize: function () {
        initAjaxPosts();
      },
    },
    search: {
      finalize: function () {
        initAjaxPosts();
      },
    },
    page_template_template_press_landing: {
      finalize: function () {
        initMasonry();
      },
    },
    tax_press_category: {
      finalize: function () {
        initMasonry();
      },
    },
    tax_press_exhibition: {
      finalize: function () {
        initMasonry();
      },
    },
    post_type_archive_press: {
      finalize: function () {
        initMasonry();
      },
    },
    page_template_template_exhibitions: {
      finalize: function () {
        exhibitionMultiCarousel();
      },
    },
    post_type_archive_essay: {
      finalize: function () {
        $(".collapse").on("show.bs.collapse", function (e) {
          var toggle = $(this).siblings(".theme__toggle");
          toggle.text(function () {
            return $(this).text().replace("View", "Close");
          });

          $(this).closest(".theme").toggleClass("active");
        });

        $(".collapse").on("hide.bs.collapse", function (e) {
          var toggle = $(this).siblings(".theme__toggle");
          toggle.text(function () {
            return $(this).text().replace("Close", "View");
          });

          $(this).closest(".theme").toggleClass("active");
        });
      },
    },
    page_template_template_viewer: {
      finalize: function () {
        $("#viewerDetails")
          .on("show.bs.collapse", function () {
            $(".viewer-overlay").addClass("on");
          })
          .on("hide.bs.collapse", function () {
            $(".viewer-overlay").removeClass("on");
          });

        $("#viewerInfoToolTip").tooltipster({
          interactive: true,
          maxWidth: 300,
          trigger: "click",
          functionAfter: function () {
            Leiden.viewer.getShareLink();
          },
        });

        $("#iiifInfoTooltip").tooltipster({
          interactive: true,
          maxWidth: 300,
          trigger: "click",
        });
      },
    },
    post_type_archive_glossary: {
      finalize: function () {
        $("#glossary-select").on("changed.bs.select", function (e) {
          var selected = e.target.selectedIndex;
          var scroll = e.target[selected].value;
          var aTag = $("a[id='" + scroll + "']");
          $("html,body").animate({ scrollTop: aTag.offset().top - 50 }, "slow");
        });
      },
    },
    page_template_template_archive: {
      finalize: function () {
        $("#archive-tab").tabCollapse();
      },
    },
    single_artwork: {
      finalize: function () {
        initStickyTabs();
        $("#artwork-tabs").tabCollapse();
      },
    },
  };

  // The routing fires all common scripts, followed by the page specific scripts.
  // Add additional events for more control over timing e.g. a finalize event
  var UTIL = {
    fire: function (func, funcname, args) {
      var fire;
      var namespace = Sage;
      funcname = funcname === undefined ? "init" : funcname;
      fire = func !== "";
      fire = fire && namespace[func];
      fire = fire && typeof namespace[func][funcname] === "function";

      if (fire) {
        namespace[func][funcname](args);
      }
    },
    loadEvents: function () {
      // Fire common init JS
      UTIL.fire("common");

      // Fire page-specific init JS, and then finalize JS
      $.each(document.body.className.replace(/-/g, "_").split(/\s+/), function (
        i,
        classnm
      ) {
        UTIL.fire(classnm);
        UTIL.fire(classnm, "finalize");
      });

      // Fire common finalize JS
      UTIL.fire("common", "finalize");
    },
  };

  // Load Events
  $(document).ready(UTIL.loadEvents);
})(jQuery); // Fully reference jQuery after this point.
