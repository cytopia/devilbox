/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * general function, usually for data manipulation pages
 *
 */

var ErrorReport = {
    /**
     * @var object stores the last exception info
     */
    _last_exception: null,
    /**
     * handles thrown error exceptions based on user preferences
     *
     * @return void
     */
    error_handler: function (exception) {
        // issue: 14359
        if (JSON.stringify(ErrorReport._last_exception) === JSON.stringify(exception)) {
            return;
        }
        if (exception.name === null || typeof(exception.name) === 'undefined') {
            exception.name = ErrorReport._extractExceptionName(exception);
        }
        ErrorReport._last_exception = exception;
        $.post('error_report.php', {
            ajax_request: true,
            server: PMA_commonParams.get('server'),
            get_settings: true,
            exception_type: 'js'
        }, function (data) {
            if (data.success !== true) {
                PMA_ajaxShowMessage(data.error, false);
                return;
            }
            if (data.report_setting === 'ask') {
                ErrorReport._showErrorNotification();
            } else if (data.report_setting === 'always') {
                report_data = ErrorReport._get_report_data(exception);
                post_data = $.extend(report_data, {
                    send_error_report: true,
                    automatic: true
                });
                $.post('error_report.php', post_data, function (data) {
                    if (data.success === false) {
                        // in the case of an error, show the error message returned.
                        PMA_ajaxShowMessage(data.error, false);
                    } else {
                        PMA_ajaxShowMessage(data.message, false);
                    }
                });
            }
        });
    },
    /**
     * Shows the modal dialog previewing the report
     *
     * @param exception object error report info
     *
     * @return void
     */
    _showReportDialog: function (exception) {
        var report_data = ErrorReport._get_report_data(exception);

        /* Remove the hidden dialogs if there are*/
        if ($('#error_report_dialog').length !== 0) {
            $('#error_report_dialog').remove();
        }
        var $div = $('<div id="error_report_dialog"></div>');
        $div.css('z-index', '1000');

        var button_options = {};

        button_options[PMA_messages.strSendErrorReport] = function () {
            var $dialog = $(this);
            var post_data = $.extend(report_data, {
                send_error_report: true,
                description: $('#report_description').val(),
                always_send: $('#always_send_checkbox')[0].checked
            });
            $.post('error_report.php', post_data, function (data) {
                $dialog.dialog('close');
                if (data.success === false) {
                    // in the case of an error, show the error message returned.
                    PMA_ajaxShowMessage(data.error, false);
                } else {
                    PMA_ajaxShowMessage(data.message, 3000);
                }
            });
        };

        button_options[PMA_messages.strCancel] = function () {
            $(this).dialog('close');
        };

        $.post('error_report.php', report_data, function (data) {
            if (data.success === false) {
                // in the case of an error, show the error message returned.
                PMA_ajaxShowMessage(data.error, false);
            } else {
                // Show dialog if the request was successful
                $div
                    .append(data.message)
                    .dialog({
                        title: PMA_messages.strSubmitErrorReport,
                        width: 650,
                        modal: true,
                        buttons: button_options,
                        close: function () {
                            $(this).remove();
                        }
                    });
            }
        });
    },
    /**
     * Shows the small notification that asks for user permission
     *
     * @return void
     */
    _showErrorNotification: function () {
        ErrorReport._removeErrorNotification();

        var $div = $(
            '<div style="position:fixed;bottom:0;left:0;right:0;margin:0;' +
            'z-index:1000" class="error" id="error_notification"></div>'
        ).append(
            PMA_getImage('s_error') + PMA_messages.strErrorOccurred
        );

        var $buttons = $('<div class="floatright"></div>');

        var button_html  = '<button id="show_error_report">';
        button_html += PMA_messages.strShowReportDetails;
        button_html += '</button>';

        button_html += '<a id="change_error_settings">';
        button_html += PMA_getImage('s_cog', PMA_messages.strChangeReportSettings);
        button_html += '</a>';

        button_html += '<a href="#" id="ignore_error">';
        button_html += PMA_getImage('b_close', PMA_messages.strIgnore);
        button_html += '</a>';

        $buttons.html(button_html);

        $div.append($buttons);
        $div.appendTo(document.body);
        $(document).on('click', '#change_error_settings', ErrorReport._redirect_to_settings);
        $(document).on('click', '#show_error_report', ErrorReport._createReportDialog);
        $(document).on('click', '#ignore_error', ErrorReport._removeErrorNotification);
    },
    /**
     * Removes the notification if it was displayed before
     *
     * @return void
     */
    _removeErrorNotification: function (e) {
        if (e) {
            // don't remove the hash fragment by navigating to #
            e.preventDefault();
        }
        $('#error_notification').fadeOut(function () {
            $(this).remove();
        });
    },
    /**
     * Extracts Exception name from message if it exists
     *
     * @return String
     */
    _extractExceptionName: function (exception) {
        if (exception.message === null || typeof(exception.message) === 'undefined') {
            return '';
        }

        var reg = /([a-zA-Z]+):/;
        var regex_result = reg.exec(exception.message);
        if (regex_result && regex_result.length === 2) {
            return regex_result[1];
        }

        return '';
    },
    /**
     * Shows the modal dialog previewing the report
     *
     * @return void
     */
    _createReportDialog: function () {
        ErrorReport._removeErrorNotification();
        ErrorReport._showReportDialog(ErrorReport._last_exception);
    },
    /**
     * Redirects to the settings page containing error report
     * preferences
     *
     * @return void
     */
    _redirect_to_settings: function () {
        window.location.href = 'prefs_forms.php';
    },
    /**
     * Returns the report data to send to the server
     *
     * @param exception object exception info
     *
     * @return object
     */
    _get_report_data: function (exception) {
        if (exception && exception.stack && exception.stack.length) {
            for (var i = 0; i < exception.stack.length; i++) {
                var stack = exception.stack[i];
                if (stack.context && stack.context.length) {
                    for (var j = 0; j < stack.context.length; j++) {
                        if (stack.context[j].length >  80) {
                            stack.context[j] = stack.context[j].substring(-1, 75) + '//...';
                        }
                    }
                }
            }
        }
        var report_data = {
            'server': PMA_commonParams.get('server'),
            'ajax_request': true,
            'exception': exception,
            'url': window.location.href,
            'exception_type': 'js'
        };
        if (AJAX.scriptHandler._scripts.length > 0) {
            report_data.scripts = AJAX.scriptHandler._scripts.map(
                function (script) {
                    return script;
                }
            );
        }
        return report_data;
    },
    /**
     * Wraps all global functions that start with PMA_
     *
     * @return void
     */
    wrap_global_functions: function () {
        for (var key in window) {
            if (key.indexOf('PMA_') === 0) {
                var global = window[key];
                if (typeof(global) === 'function') {
                    window[key] = ErrorReport.wrap_function(global);
                }
            }
        }
    },
    /**
     * Wraps given function in error reporting code and returns wrapped function
     *
     * @param func function to be wrapped
     *
     * @return function
     */
    wrap_function: function (func) {
        if (!func.wrapped) {
            var new_func = function () {
                try {
                    return func.apply(this, arguments);
                } catch (x) {
                    TraceKit.report(x);
                }
            };
            new_func.wrapped = true;
            // Set guid of wrapped function same as original function, so it can be removed
            // See bug#4146 (problem with jquery draggable and sortable)
            new_func.guid = func.guid = func.guid || new_func.guid || jQuery.guid++;
            return new_func;
        } else {
            return func;
        }
    },
    /**
     * Automatically wraps the callback in AJAX.registerOnload
     *
     * @return void
     */
    _wrap_ajax_onload_callback: function () {
        var oldOnload = AJAX.registerOnload;
        AJAX.registerOnload = function (file, func) {
            func = ErrorReport.wrap_function(func);
            oldOnload.call(this, file, func);
        };
    },
    /**
     * Automatically wraps the callback in $.fn.on
     *
     * @return void
     */
    _wrap_$_on_callback: function () {
        var oldOn = $.fn.on;
        $.fn.on = function () {
            for (var i = 1; i <= 3; i++) {
                if (typeof(arguments[i]) === 'function') {
                    arguments[i] = ErrorReport.wrap_function(arguments[i]);
                    break;
                }
            }
            return oldOn.apply(this, arguments);
        };
    },
    /**
     * Wraps all global functions that start with PMA_
     * also automatically wraps the callback in AJAX.registerOnload
     *
     * @return void
     */
    set_up_error_reporting: function () {
        ErrorReport.wrap_global_functions();
        ErrorReport._wrap_ajax_onload_callback();
        ErrorReport._wrap_$_on_callback();
    }

};

AJAX.registerOnload('error_report.js', function () {
    TraceKit.report.subscribe(ErrorReport.error_handler);
    ErrorReport.set_up_error_reporting();
    ErrorReport.wrap_global_functions();
});
