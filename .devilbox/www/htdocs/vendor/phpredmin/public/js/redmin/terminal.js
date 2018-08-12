$(document).ready(function() {

    function getCookie(name) {
        var value = "; " + document.cookie;
        var parts = value.split("; " + name + "=");
        if (parts.length == 2) return parts.pop().split(";").shift();
    }

    var prompt = "redis " + currentHost + ":" + currentPort + ">";

    if (getCookie('term_theme') == 'dark') { $("#mainContainer").addClass('dark_theme'); }

    $("#term_light").on("click", function(e) {
        e.preventDefault();
        $("#mainContainer").removeClass('dark_theme');
        document.cookie = "term_theme=light; expires=Fri, 31 Dec 9999 23:59:59 GMT; path=/";
    })

    $("#term_dark").on("click", function(e) {
        e.preventDefault();
        $("#mainContainer").addClass('dark_theme');
        document.cookie = "term_theme=dark; expires=Fri, 31 Dec 9999 23:59:59 GMT; path=/";
    })

    $(".terminal-clear").tooltip({title: "Clear", placement: "right"});

    $(".terminal-clear").on("click", function(e) {
        e.preventDefault();
        $(".terminal-console").html(prompt);
        $('#terminal-input').focus();
    })
    $('#terminal-input').focus();

    $('.terminal-prompt').ajaxStart(function() {
        $(this).html("");
        $(this).addClass('ajax-loading');
    });

    $('.terminal-prompt').ajaxStop(function() {
        $(this).removeClass('ajax-loading');
        $(this).html(">");
    });

    $('#terminal-input').on('keypress', function(e) {
        var key     = e.keyCode;
        var command = $('#terminal-input').val().trim();

        if (key == '38' || key == '40') {
            if (key == '40' && command == '')
                return false;
            else if (key == '38')
                var navigation = 'up';
            else
                var navigation = 'down';

            var url = baseurl+'/terminal/history/' + currentServerDb + '?navigation=' + navigation;

            if (key == '38' && command == '')
                url += '&start=true';

            $.ajax({
                url: url,
                dataType: 'json',
                type: 'GET',
                success: function(data) {
                    if (data) {
                        if (data.reset)
                            $('#terminal-input').val('');
                        else
                            $('#terminal-input').val(data.command);
                    } else {
                        modalPopup.alert('error', 'Could not run the command');
                    }
                }
            });
        }

        if (key == '13') {
            if (command != '') {
                $.ajax({
                    url: baseurl+'/terminal/run/' + currentServerDb,
                    dataType: 'json',
                    type: 'POST',
                    data: 'command='+command,
                    success: function(data) {
                        if (data) {
                            terminal  = $(".terminal-console").html();
                            terminal += " " + command + "<br/>";

                            $.each(data.result, function(key, value) {
                                if (value.trim() != "")
                                    terminal += value + "<br />";
                            });

                            terminal += prompt;
                            $(".terminal-console").html(terminal);
                            $(".terminal-console").animate({ scrollTop: $('.terminal-console')[0].scrollHeight}, 1000);
                        } else {
                            modalPopup.alert('error', 'Could not run the command');
                        }

                        $('#terminal-input').val('');
                    }
                });
            } else
                $(".terminal-console").animate({ scrollTop: $('.terminal-console')[0].scrollHeight}, 1000);
        }
    });
});
