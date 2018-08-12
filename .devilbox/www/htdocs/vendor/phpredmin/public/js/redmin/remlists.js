$(document).ready(function() {
    $('#list_remove_options').change(function(e) {
        var val = $(e.target).val();

        if (val == 'count') {
            if ($('#list_remove_type').find('input').length <= 0) {
                $('<input type="text" placeholder="Count" name="count" />').appendTo($('#list_remove_type'));
            }
        } else {
            $('#list_remove_type').empty();
        }
    });
});
