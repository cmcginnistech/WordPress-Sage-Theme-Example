/**
 * Ajax call to kickoff pdf generation on post save.
 */
(function ($) {
  function leiden_async_generate_pdf(archive) {
    var is_archive = false;

    if (archive === "archive") {
      is_archive = true;
    }

    var data = {
      action: "leiden_generate_pdf",
      _ajax_nonce: utilityVars.requestNonce,
      pdf: 1,
      postID: utilityVars.postID,
      archive: is_archive ? is_archive : null
    };

    $.ajax({
      method: "POST",
      url: utilityVars.ajaxURL,
      data: data,
      beforeSend: function () {
        // do stuff before sending...
      }
    })
      .success(function (message) {
        var html = "<p>" + message + "</p>";
        $("#ajax-pdf-notice")
          .html(html)
          .removeClass("notice-warning")
          .addClass("notice-success");
      })
      .error(function (err) {
        var html =
          "<p>Apologies, there was an error generating the PDF. (" +
          err.statusText +
          ")</p>";
        $("#ajax-pdf-notice")
          .html(html)
          .removeClass("notice-warning")
          .addClass("notice-error");
        console.log(err);
      });
  }

  /**
   * Detect when a post has been udpated. This occurs after the page is refreshed.
   */
  function save_post_listener() {
    if (
      utilityVars.screen !== "undefined" &&
      utilityVars.screen.base !== "post"
    ) {
      return false;
    }

    var postTypes = ["artist", "artwork", "essay", "group"],
      pageNames = ["a-portrait-in-oil"];

    if (
      $.inArray(utilityVars.screen.post_type, postTypes) === -1 &&
      $.inArray(utilityVars.postName, pageNames) === -1
    ) {
      return false;
    }

    // use the WP "udpated" message to determine if we just saved this post
    if (
      parseInt(utilityVars.params.message) === 1 &&
      $("#message").hasClass("updated")
    ) {
      // inital PDF generating notice
      $("#message").after(
        '<div id="ajax-pdf-notice" class="notice notice-warning is-dismissible"><span class="spinner" style="visibility:visible;float:left;margin:8px 5px 0 0;"></span><p>Printable PDF is generating. Do not refresh the page until this is complete.</p></div>'
      );

      // ajax call
      leiden_async_generate_pdf();
    }
  }

  /**
   * Detect when a post has been udpated. This occurs after the page is refreshed.
   */
  function archive_post_listener() {
    if (
      utilityVars.screen !== "undefined" &&
      utilityVars.screen.base !== "post"
    ) {
      return false;
    }

    $("#doArchivePDF").on("click", function (e) {
      e.preventDefault();
      $("html, body").animate({scrollTop: 0}, 400);

      $(".wp-header-end").after(
        '<div id="ajax-pdf-notice" class="notice notice-warning is-dismissible"><span class="spinner" style="visibility:visible;float:left;margin:8px 5px 0 0;"></span><p>Archive PDF is generating. Do not refresh the page until this is complete.</p></div>'
      );

      leiden_async_generate_pdf("archive");
    });
  }

  /**
   * Clear location cache.
   * Use no conflict jQuery for admin list pages.
   */
  function leiden_async_clear_location_cache() {
    var data,
      $button = jQuery("#wp-admin-bar-js-clear-location-cache a");

    data = {
      action: "clear_location_cache",
      _ajax_nonce: utilityVars.requestNonce
    };

    jQuery
      .ajax({
        method: "POST",
        url: utilityVars.ajaxURL,
        data: data,
        beforeSend: function () {
          jQuery("#wp-admin-bar-js-clear-location-cache").addClass("disabled");
          $button.text("Clearing...");
        }
      })
      .success(function (message) {
        if (message === "success") {
          $button.text("Cache cleared successfully!").addClass("success");
        } else {
          $button.text(message).addClass("error");
          alert(
            "Apologies, it looks like we are having trouble connecting to Collector Systems right now. Their API may be down. Please try again in 15 minutes."
          );
        }
      })
      .error(function (err) {
        $button.text("Error Clearing Cache!").addClass("error");
      });
  }

  /**
   * Detect when the clear location cache button is clicked.
   * Use no conflict jQuery for admin list pages.
   */
  function clear_location_cache_listener() {
    jQuery("#wp-admin-bar-js-clear-location-cache a:not(.disabled)").on(
      "click",
      function () {
        leiden_async_clear_location_cache();
      }
    );
  }

  /**
   * Fire events on document ready.
   */
  $(document).ready(function () {
    save_post_listener();
    archive_post_listener();
    clear_location_cache_listener();
  });
})(jQuery);
