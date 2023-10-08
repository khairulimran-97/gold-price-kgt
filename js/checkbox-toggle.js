jQuery(document).ready(function($) {
    // Listen for changes in the checkbox
    $('.variable_checkbox').on('change', function() {
        var checkbox = $(this);
        var cageCodeGroup = checkbox.closest('.options_group').find('.cage_code_options_group');

        // Toggle the Cage Code field based on checkbox status
        if (checkbox.prop('checked')) {
            cageCodeGroup.show();
        } else {
            cageCodeGroup.hide();
        }
    });
});
