$(document).ready(function() {
    $('.disabled').click(function(e) {
        e.preventDefault();
    });

    $('#reset_stats').click(function(e) {
        e.preventDefault();
        modalPopup.confirm(
            function() {
                $.ajax({
                    url: baseurl + '/actions/reset/' + currentServerDb,
                    dataType: 'json',
                    success: function(data) {
                        location.href = baseurl + '/welcome/index/' + currentServerDb;
                    }
                });
            }    
        );
    });

    $('#flush_db').click(function(e) {
        e.preventDefault();

        modalPopup.confirm(
            function() {
                $.ajax({
                    url: baseurl + '/actions/fdb/' + currentServerDb,
                    dataType: 'json',
                    success: function(data) {
                        location.href = baseurl + '/welcome/index/' + currentServer + '/0';
                    }
                });
            }    
        );
    });

    $('#flush_all').click(function(e) {
        e.preventDefault();
        modalPopup.confirm(
            function() {
                $.ajax({
                    url: baseurl + '/actions/fall/' + currentServerDb,
                    dataType: 'json',
                    success: function(data) {
                        location.href = baseurl + '/welcome/index/' + currentServer + '/0';
                    }
                });
            }    
        );
    });
    
    function executeSave(async) {
        $.ajax({
            url: baseurl + '/welcome/save/' + currentServerDb + '/' + async,
            dataType: 'json',
            success: function(data) {
                modalPopup.hide('confirm');
                if (data.status) {
                    modalPopup.alert('saved', 'All DBs are saved to file ' + data.filename);
                }
                else {
                    modalPopup.alert('error', 'Problem while saving DBs to file ' + data.filename);
                }    
            }
        });
    }

    $('#save_sync').click(function(e) {
        e.preventDefault();

        modalPopup.confirm(
            function() {
                executeSave(0);
            },
            'You almost never want to call synchronous save in production environments where it will block all the other clients. Instead usually asynchronous save is used'
        );
    });

    $('#save_async').click(function(e) {
        e.preventDefault();

        executeSave(1);
    });

    $('#add_db').click(function(e) {
        e.preventDefault();

        modalPopup.addDb(
            function() {
                var dbIdx = parseInt($('#dbIdx').val());
                location.href = baseurl + '/welcome/index/' + currentServer + '/' + dbIdx;
            }
        );
    });
});