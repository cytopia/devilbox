/**
 * @fileoverview    Javascript functions used in server replication page
 * @name            Server Replication
 *
 * @requires    jQuery
 * @requires    jQueryUI
 * @requires    js/functions.js
 */

var randomServerId = Math.floor(Math.random() * 10000000);
var confPrefix = 'server-id=' + randomServerId + '\nlog_bin=mysql-bin\nlog_error=mysql-bin.err\n';

function updateConfig () {
    var confIgnore = 'binlog_ignore_db=';
    var confDo = 'binlog_do_db=';
    var databaseList = '';

    if ($('#db_select option:selected').length === 0) {
        $('#rep').text(confPrefix);
    } else if ($('#db_type option:selected').val() === 'all') {
        $('#db_select option:selected').each(function () {
            databaseList += confIgnore + $(this).val() + '\n';
        });
        $('#rep').text(confPrefix + databaseList);
    } else {
        $('#db_select option:selected').each(function () {
            databaseList += confDo + $(this).val() + '\n';
        });
        $('#rep').text(confPrefix + databaseList);
    }
}

/**
 * Unbind all event handlers before tearing down a page
 */
AJAX.registerTeardown('replication.js', function () {
    $('#db_type').off('change');
    $('#db_select').off('change');
    $('#master_status_href').off('click');
    $('#master_slaves_href').off('click');
    $('#slave_status_href').off('click');
    $('#slave_control_href').off('click');
    $('#slave_errormanagement_href').off('click');
    $('#slave_synchronization_href').off('click');
    $('#db_reset_href').off('click');
    $('#db_select_href').off('click');
    $('#reset_slave').off('click');
});

AJAX.registerOnload('replication.js', function () {
    $('#rep').text(confPrefix);
    $('#db_type').on('change', updateConfig);
    $('#db_select').on('change', updateConfig);

    $('#master_status_href').on('click', function () {
        $('#replication_master_section').toggle();
    });
    $('#master_slaves_href').on('click', function () {
        $('#replication_slaves_section').toggle();
    });
    $('#slave_status_href').on('click', function () {
        $('#replication_slave_section').toggle();
    });
    $('#slave_control_href').on('click', function () {
        $('#slave_control_gui').toggle();
    });
    $('#slave_errormanagement_href').on('click', function () {
        $('#slave_errormanagement_gui').toggle();
    });
    $('#slave_synchronization_href').on('click', function () {
        $('#slave_synchronization_gui').toggle();
    });
    $('#db_reset_href').on('click', function () {
        $('#db_select option:selected').prop('selected', false);
        $('#db_select').trigger('change');
    });
    $('#db_select_href').on('click', function () {
        $('#db_select option').prop('selected', true);
        $('#db_select').trigger('change');
    });
    $('#reset_slave').on('click', function (e) {
        e.preventDefault();
        var $anchor = $(this);
        var question = Messages.strResetSlaveWarning;
        $anchor.confirm(question, $anchor.attr('href'), function (url) {
            Functions.ajaxShowMessage();
            AJAX.source = $anchor;
            var params = Functions.getJsConfirmCommonParam({
                'ajax_page_request': true,
                'ajax_request': true
            }, $anchor.getPostData());
            $.post(url, params, AJAX.responseHandler);
        });
    });
    $('#button_generate_password').on('click', function () {
        Functions.suggestPassword(this.form);
    });
    $('#nopass_1').on('click', function () {
        this.form.pma_pw.value = '';
        this.form.pma_pw2.value = '';
        this.checked = true;
    });
    $('#nopass_0').on('click', function () {
        document.getElementById('text_pma_change_pw').focus();
    });
});
