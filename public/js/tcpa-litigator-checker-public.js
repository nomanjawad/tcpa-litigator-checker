jQuery(document).ready(function ($) {
  $("#tcpa-checker-form").on("submit", function (event) {
    event.preventDefault();

    if (typeof tcpaChecker === "undefined") {
      console.error("tcpaChecker is not defined. Check wp_localize_script.");
      $("#tcpa-result")
        .removeClass("d-none alert-success")
        .addClass("alert-danger")
        .html("<p>Error: AJAX configuration missing.</p>");
      return;
    }

    var formData = {
      action: "tcpa_checker_ajax",
      security: $("#tcpa_checker_nonce").val(),
      phone: $('input[name="phone"]').val(),
      type: $('select[name="type"]').val(),
    };

    $("#tcpa-result")
      .removeClass("d-none alert-danger alert-success")
      .addClass("alert-info")
      .html("<p>Checking...</p>");

    $.post(tcpaChecker.ajax_url, formData, function (response) {
      console.log("API Response:", response);

      if (
        response.success &&
        response.data.status &&
        response.data.status !== "Unknown"
      ) {
        $("#tcpa-result")
          .removeClass("alert-info alert-danger")
          .addClass("alert-success")
          .html(`<p><strong>Status:</strong> ${response.data.status}</p>`);
      } else {
        $("#tcpa-result")
          .removeClass("alert-info alert-success")
          .addClass("alert-danger")
          .html(`<p><strong>Status:</strong> Not Found</p>`);
      }
    }).fail(function () {
      $("#tcpa-result")
        .removeClass("alert-info alert-success")
        .addClass("alert-danger")
        .html("<p><strong>Error:</strong> API request failed.</p>");
    });
  });
});
