$(document).ready(function() {
    $('#add_hash, #add_edit_hash').click(function(e) {
        e.preventDefault();

        var form    = $(e.target).parents('form');
        var key     = form.find('input[name="key"]').val().trim();
        var hashkey = form.find('input[name="hashkey"]').val().trim();
        var str     = form.find('textarea[name="value"]').val().trim();

        if (key != '' && str != '' && hashkey != '') {
            $.ajax({
                url: baseurl+'/hashes/add/' + currentServerDb,
                dataType: 'json',
                type: 'POST',
                data: 'key='+key+'&value='+str+'&hashkey='+hashkey,
                success: function(data) {
                    if (data) {
                        var oldkey = form.find('input[name="oldkey"]');
                        form.find('textarea').val('');
                        
                        if (oldkey.length > 0) {
                            location.reload();
                        } else {
                            if (e.target.id == 'add_edit_hash') {
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
        } else {
            modalPopup.alert('invalid')
        }
    });
});
