var deleteRow = function(e) {
    e.preventDefault();

    var tr      = $(e.target).parents('tr');
    var type    = $(e.target).attr('keytype');
    var keyinfo = $(e.target).attr('keyinfo');
    var id      = $(e.target).attr('id');

    if (typeof(keyinfo) == 'undefined') {
        var url = baseurl+'/'+type+'/delete/'+currentServerDb+'/'+encodeURIComponent(id);
    } else {
        var url = baseurl+'/'+type+'/delete/'+currentServerDb+'/'+encodeURIComponent(keyinfo)+'/'+encodeURIComponent(id);
    }

    modalPopup.confirm(
        function() {
            $.ajax({
                url: url,
                dataType: 'json',
                success: function(data) {
                    modalPopup.hide('confirm');

                    if (data == 1) {
                        tr.remove();
                    }
                }
            });
        }    
    );
}

$(document).ready(function() {
    $('.del').click(function(e) {
        deleteRow(e);
    });

    $('.keys-table td').click(function(e) {
        if ($(e.target).hasClass('icon') || $(e.target).hasClass('key-checkbox'))
            return;

        var input = $($(e.target).parents('tr')[0]).find("input[name=keys\\[\\]]");

        if (!input.hasClass('all-key-checkbox'))
            input.attr('checked', !input.is(':checked'));
    });

    $('#checkall').click(function(e) {
        $("input[name=keys\\[\\]]").attr('checked', $(e.target).is(':checked'));
    });

    $('.moveall').click(function(e) {
        e.preventDefault();
        var checkboxes = $("input[name=keys\\[\\]]:checked");

        if (checkboxes.length > 0) {
            modalPopup.moveKeys(
                function() {
                    var destination = $('.modal-body input').val().trim();
                    var type       = $(e.target).attr('keytype');
                    var keyinfo    = $(e.target).attr('keyinfo');

                    if (destination != '') {
                        var values = [];
                        checkboxes.each(function() {
                            values.push($(this).val());
                        });

                        if (typeof(keyinfo) == 'undefined') {
                            var postdata = {'values[]': values, 'destination': destination};
                        } else {
                            var postdata = {'values[]': values, 'destination': destination, 'keyinfo': keyinfo};
                        }

                        $.post(
                            baseurl+'/'+type+'/moveall/'+currentServerDb,
                            postdata,
                            function(data) {
                                modalPopup.hide('moveKeys');

                                checkboxes.each(function() {
                                    if (data[$(this).val()]) {
                                        $(this).parents('tr').remove();
                                    }
                                });
                            }
                        );
                    };
                },
                $(e.target).attr('modaltitle'),
                $(e.target).attr('modaltip')
            );
        } else {
            modalPopup.alert('invalid', 'Please select key(s) to move');
        }
    });


    $('.delall').click(function(e) {
        e.preventDefault();
        var checkboxes = $("input[name=keys\\[\\]]:checked");
        var type       = $(e.target).attr('keytype');
        var keyinfo    = $(e.target).attr('keyinfo');

        if (checkboxes.length > 0) {
            modalPopup.confirm(
                function() {
                    var values = [];
                    checkboxes.each(function() {
                        values.push($(this).val());
                    });

                    if (typeof(keyinfo) == 'undefined') {
                        var postdata = {'values[]': values};
                    } else {
                        var postdata = {'values[]': values, 'keyinfo': keyinfo};
                    }

                    $.post(
                        baseurl+'/'+type+'/delall/'+currentServerDb,
                        postdata,
                        function(data) {
                            modalPopup.hide('confirm');

                            checkboxes.each(function() {
                                if (data[$(this).val()] == 1) {
                                    $(this).parents('tr').remove();
                                }
                            });
                        }
                    );
                }    
            );
        } else {
            modalPopup.alert('invalid', 'Please select key(s) to delete');
        }
    });
});
