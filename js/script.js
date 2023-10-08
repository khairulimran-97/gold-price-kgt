jQuery(document).ready(function($) {
    var popup = $("#gpw-delete-popup");
    var closeBtn = $(".gpw-popup-close");

    // Show popup when Delete button is clicked
    popup.show();

    // Center the popup
    var windowHeight = $(window).height();
    var popupHeight = popup.outerHeight();
    var topMargin = (windowHeight - popupHeight) / 2;
    popup.css("margin-top", topMargin);

    // Hide popup when Close button is clicked
    closeBtn.click(function() {
        popup.hide();
    });

    // Hide popup when user clicks outside of it
    $(window).click(function(event) {
        if (event.target == popup[0]) {
            popup.hide();
        }
    });
});
