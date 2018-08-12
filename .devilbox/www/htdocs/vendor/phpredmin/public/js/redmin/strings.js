$(document).ready(function() {
    $('#add_string').click(function(e) {
        e.preventDefault();

        var form = $(e.target).parents('form');
        var key  = form.find('input[name="key"]').val().trim();
        var str  = form.find('textarea[name="value"]').val().trim();

        if (key != '' && str != '') {
            $.ajax({
                url: baseurl+'/strings/add/' + currentServerDb,
                dataType: 'json',
                type: 'POST',
                data: 'key='+key+'&value='+str,
                success: function(data) {
                    form.find('input').val('');
                    form.find('textarea').val('');

                    if (data)
                        modalPopup.alert('saved');
                    else
                        modalPopup.alert('error');
                }
            });
        } else {
            modalPopup.alert('invalid')
        }
    });
});
