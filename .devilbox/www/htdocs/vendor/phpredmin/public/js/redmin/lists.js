$(document).ready(function() {
    $('#add_list, #add_edit_list').click(function(e) {
        e.preventDefault();

        var form  = $(e.target).parents('form');
        var key   = form.find('input[name="key"]').val().trim();
        var str   = form.find('textarea[name="value"]').val().trim();
        var type  = form.find('select[name="position"]').val();
        var pivot = form.find('input[name="pivot"]');

        if (key != '' && str != '' && (type == 'append' || type == 'prepend' || type == 'before' || type == 'after')) {
            if ((type == 'before' || type == 'after') && pivot.val().trim() == '') {
                modalPopup.alert('invalid')
            } else {
                if (pivot.length > 0) {
                    pivot = pivot.val().trim();
                }    
                else {
                    pivot = '';
                }    

                $.ajax({
                    url: baseurl+'/lists/add/' + currentServerDb,
                    dataType: 'json',
                    type: 'POST',
                    data: 'key='+key+'&value='+str+'&type='+type+'&pivot='+pivot,
                    success: function(data) {
                        if (data) {
                            var oldkey = form.find('input[name="oldkey"]');
                            form.find('textarea').val('');

                            if (oldkey.length > 0) {
                                location.reload();
                            } else {
                                if (e.target.id == 'add_edit_list') {
                                    location.href = baseurl + '/keys/view/' + currentServerDb + '/' + encodeURIComponent(key);
                                } else {
                                    form.find('input').val('');
                                    modalPopup.alert('saved');
                                } 
                            }
                        } else {
                            modalPopup.alert('error');
                        }
                    }
                });
            }
        } else {
            modalPopup.alert('invalid')
        }
    });

    $('#list_position').change(function(e) {
        var val = $(e.target).val();

        if (val == 'after' || val == 'before') {
            if ($('#list_type').find('input').length <= 0) {
                $('<input type="text" placeholder="Pivot Value" name="pivot" />').appendTo($('#list_type'));
            }
        } else {
            $('#list_type').empty();
        }
    });
});
