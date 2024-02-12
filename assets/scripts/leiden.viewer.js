/* jshint ignore:start */
/**
 * Viewer calls
 */

if (window.Leiden == undefined) {
  var Leiden = {};
}

Leiden.viewer = (function($) {
  return {
    timeoutControl: 0,
    viewerObj: {},
    isMobile: false,
    init: function() {
      console.log("syncVars", leidenViewerVars.syncVars);

      Leiden.viewer.isMobile =
        typeof window.orientation !== "undefined" ||
        navigator.userAgent.indexOf("IEMobile") !== -1;

      if (typeof leidenViewerVars.syncVars.inventory_num !== "undefined") {
        Leiden.viewer.prefixURL = leidenViewerVars.syncVars.imageLoc;
      }

      Leiden.viewer.initializeDetails();
      Leiden.viewer.initializeViewer();
      Leiden.viewer.viewActions();
      Leiden.viewer.modeActions();
      Leiden.viewer.zoomActions();
    },
    initializeDetails: function() {
      var artworkInfo = $("#artwork_info");
      var detailsTrigger = $("#hide_details a");

      detailsTrigger.on("click", function() {
        if (artworkInfo.is(":visible")) {
          artworkInfo.slideUp(700);
          detailsTrigger.text("Show Details");
        } else {
          artworkInfo.slideDown(700);
          detailsTrigger.text("Hide Details");
        }
      });
    },
    hideDetailsOnLoad: function() {
      $("#info-container")
        .css("visibility", "visible")
        .hide()
        .delay(2300)
        .fadeIn(700, function() {
          //on smaller screens collapse these before loading
          if (
            $(".device-sm").is(":visible") ||
            $(".device-xs").is(":visible")
          ) {
            var artworkInfo = $("#artwork_info");
            var detailsTrigger = $("#hide_details a");
            artworkInfo.slideUp(700);
            detailsTrigger.text("Show Details");
          } else {
            var artworkInfo = $("#artwork_info");
            //now re-hide it after showing it
            artworkInfo.delay(1000).animate(
              {
                height: "toggle",
                opacity: 0.21
              },
              700,
              function() {
                var detailsTrigger = $("#hide_details a");
                detailsTrigger.text("Show Details");
                $(this).css("opacity", "1.0");
              }
            );
          }
        });
    },
    initializeViewer: function() {
      var imageHolder = [];
      var count = 0;

      for (var imageType in leidenViewerVars.syncVars.image_files) {
        var currentImage = leidenViewerVars.syncVars.image_files[imageType];
        var dziAttrs = currentImage.dzi;
        var isIIIF = leidenViewerVars.syncVars.iiif;
        var thisTileSource;

        if (isIIIF) {
          thisTileSource = currentImage.url + "info.json";
        } else {
          thisTileSource = {
            Image: {
              xmlns: dziAttrs.xmlns,
              Url: leidenViewerVars.syncVars.imageLoc + currentImage.file + "/",
              Format: dziAttrs.Format,
              Overlap: parseInt(dziAttrs.Overlap),
              TileSize: parseInt(dziAttrs.TileSize),
              Size: {
                Width: parseInt(dziAttrs.sizes.Width),
                Height: parseInt(dziAttrs.sizes.Height)
              }
            }
          };
        }

        imageHolder[count] = {
          key: imageType,
          tileSource: thisTileSource
        };

        if (count === 0) {
          imageHolder[count].shown = true;
        }

        count++;
      }

      Leiden.viewer.viewerObj = new LeidenViewer({
        container: document.querySelector(".viewer-container"),
        images: imageHolder
      });

      // Leiden.viewer.postViewActions();
    },
    postViewActions: function() {
      $("#all_views")
        .off("contextmenu")
        .on("contextmenu", function(e) {
          e.preventDefault();
        });

      //check for URL params
      if (
        typeof url("?") !== "undefined" &&
        url("?") &&
        Object.keys(url("?")).length > 0
      ) {
        //if we have URL params, let's process them
        var incomingParams = url("?");
        console.log("incomingParams", incomingParams);
      } else {
        return false;
      }

      var incomingZoom = 1;
      var incomingPan = {};
      var incomingMode = "curtain";
      var incomingVisibleImages = [];

      for (var paramType in incomingParams) {
        var paramDecoded = decodeURIComponent(paramType);

        switch (paramDecoded) {
          case "x":
          case "y":
            incomingPan[paramDecoded] = parseFloat(incomingParams[paramType]);
            break;
          case "zoom":
            incomingZoom = parseFloat(incomingParams[paramType]);
            break;
          case "mode":
            incomingMode = incomingParams[paramType];
            break;
          default:
            if (paramDecoded.indexOf("visible") !== -1) {
              incomingVisibleImages.push(incomingParams[paramType]);
            }
        }
      }

      console.log(
        "incomingZoom, incomingPan, incomingMode, incomingVisibleImages",
        incomingZoom,
        incomingPan,
        incomingMode,
        incomingVisibleImages
      );

      if (typeof incomingMode === "string") {
        Leiden.viewer.viewerObj.setMode(incomingMode);
      }

      if (incomingVisibleImages.length > 0) {
        for (var count = 0; count < incomingVisibleImages.length; count++) {
          var thisView = $(
            '#viewerViews button.view-trigger[data-type="' +
              incomingVisibleImages[count] +
              '"]'
          );
          Leiden.viewer.initateView(thisView);
        }
      }

      if (typeof incomingZoom === "number") {
        Leiden.viewer.viewerObj.setZoom(incomingZoom);
      }

      if (Object.keys(incomingPan).length === 2) {
        Leiden.viewer.viewerObj.setPan(incomingPan);
      }
    },
    viewActions: function() {
      //updating based on a view click
      $("#viewerViews button.view-trigger").on("click", function(e) {
        e.preventDefault();

        var thisView = $(this);
        Leiden.viewer.initateView(thisView);

        //need this to initiate curtain view on mobile
        if (Leiden.viewer.isMobile) {
          var thisZoom = Leiden.viewer.viewerObj.getZoom();
          Leiden.viewer.viewerObj.setZoom(thisZoom + 0.000000000001);
        }
      });
    },
    initateView: function(thisView) {
      thisView.toggleClass("active");
      Leiden.viewer.viewerObj.setImageShown(
        thisView.data("type"),
        thisView.hasClass("active")
      );
      Leiden.viewer.viewFallback();
    },
    modeActions: function() {
      //updating based on a mode click
      $("#viewerModes button.mode-trigger").on("click", function(e) {
        e.preventDefault();

        if (isBreakpoint("xs") || isBreakpoint("xxs")) {
          swal({
            title: "Viewer Note",
            text:
              "Modes are not available on small screens. Please view this artwork on a larger screen to access modes.",
            button: "Ok",
            closeOnClickOutside: true,
            className: "mode-alert"
          });

          return false;
        }

        var thisMode = $(this);

        $("#viewerModes button.mode-trigger").removeClass("active");
        thisMode.addClass("active");

        Leiden.viewer.viewerObj.setMode(thisMode.data("mode"));
        Leiden.viewer.guaranteedViews();
      });
    },
    changeModeResponsively: function() {
      if (!isBreakpoint("xs") && !isBreakpoint("xxs")) {
        return false;
      }

      $("#viewerModes button.mode-trigger").removeClass("active");
      $("#viewerModes button.mode-trigger.mode-curtain").addClass("active");

      Leiden.viewer.viewerObj.setMode("curtain");
    },
    /**
     * Guarantees that a mode change will have multiple views to show off mode capabilities
     */
    guaranteedViews: function() {},
    zoomActions: function() {
      $("#viewerZoom button").on("click", function() {
        var thisZoom = $(this);

        if (thisZoom.data("type") === "in") {
          Leiden.viewer.viewerObj.zoomIn();
        } else {
          Leiden.viewer.viewerObj.zoomOut();
        }
      });
    },
    viewFallback: function() {
      //we'll check the active views to make sure at least one view is showing (falls back to 'visible')
      var activeViews = $("#viewerViews button.view-trigger.active");
      if (activeViews.length === 0) {
        var visView = $('#viewerViews button.view-trigger[data-type="vis"]');
        visView.toggleClass("active");
        Leiden.viewer.viewerObj.setImageShown("vis", true);
      }
    },
    getShareLink: function() {
      $("#shareThisView").on("click", function() {
        var viewerURL = "";

        //get current configuration
        var currentZoom = Leiden.viewer.viewerObj.getZoom();
        var currentPan = Leiden.viewer.viewerObj.getPan();
        var currentMode = Leiden.viewer.viewerObj.getMode();

        //get current images in view
        var visibleImages = {};
        var count = 0;
        for (var imageType in leidenViewerVars.syncVars.image_files) {
          if (Leiden.viewer.viewerObj.getImageShown(imageType)) {
            visibleImages[count] = imageType;
            count++;
          }
        }

        console.log(
          "currentZoom, currentPan, currentMode, visibleImages",
          currentZoom,
          currentPan,
          currentMode,
          visibleImages
        );

        //var url = jQuery.param(currentPan);
        var urlParams = {
          x: currentPan.x,
          y: currentPan.y,
          zoom: currentZoom,
          mode: currentMode,
          visible: visibleImages
        };

        if (
          typeof leidenViewerVars.postObj.post_type !== "undefined" &&
          leidenViewerVars.postObj.post_type === "artwork"
        ) {
          viewerURL =
            leidenViewerVars.currentURL + "?preview&" + jQuery.param(urlParams);
        } else {
          viewerURL =
            leidenViewerVars.currentURL +
            leidenViewerVars.syncVars.post_slug +
            "/?" +
            jQuery.param(urlParams);
        }

        // Leiden.viewer.goAlert(viewerURL);
        alert("Copy the following URL:\n\n" + viewerURL);
      });
    },
    goAlert: function(content) {
      swal({
        title: "Copy the Below",
        text: content,
        button: "Ok",
        closeOnClickOutside: true,
        className: "share-view"
      });

      //find out when checker is active
      //so we can highlight the url
      Leiden.viewer.timeoutControl = 0;
      Leiden.viewer.alertChecker();
    },
    alertChecker: function() {
      var alertChecking = setInterval(function() {
        if ($(".swal-overlay--show-modal .swal-modal.share-view").length) {
          clearInterval(alertChecking);
          $(".swal-overlay--show-modal .swal-modal.share-view")
            .find(".swal-text")
            .attr("id", "textToSelect");

          var range = document.createRange();
          var selection = window.getSelection();
          range.selectNodeContents(document.getElementById("textToSelect"));

          selection.removeAllRanges();
          selection.addRange(range);
        }

        Leiden.viewer.timeoutControl++;
        if (Leiden.viewer.timeoutControl > 100) {
          clearInterval(alertChecking);
        }
      }, 10);
    }
  };
})(jQuery, Leiden);

(function($) {
  $(document).ready(function() {
    Leiden.viewer.init();
  });

  $(window).load(function() {
    Leiden.viewer.hideDetailsOnLoad();
  });
})(jQuery);
