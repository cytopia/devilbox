/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * @fileoverview    Implements the shiftkey + click remove column
 *                  from order by clause funcationality
 * @name            columndelete
 *
 * @requires    jQuery
 */

function captureURL (url) {
    var URL = {};
    url = '' + url;
    // Exclude the url part till HTTP
    url = url.substr(url.search('sql.php'), url.length);
    // The url part between ORDER BY and &session_max_rows needs to be replaced.
    URL.head = url.substr(0, url.indexOf('ORDER+BY') + 9);
    URL.tail = url.substr(url.indexOf('&session_max_rows'), url.length);
    return URL;
}

/**
 * This function is for navigating to the generated URL
 *
 * @param object   target HTMLAnchor element
 * @param object   parent HTMLDom Object
 */

function removeColumnFromMultiSort (target, parent) {
    var URL = captureURL(target);
    var begin = target.indexOf('ORDER+BY') + 8;
    var end = target.indexOf(PMA_commonParams.get('arg_separator') + 'session_max_rows');
    // get the names of the columns involved
    var between_part = target.substr(begin, end - begin);
    var columns = between_part.split('%2C+');
    // If the given column is not part of the order clause exit from this function
    var index = parent.find('small').length ? parent.find('small').text() : '';
    if (index === '') {
        return '';
    }
    // Remove the current clicked column
    columns.splice(index - 1, 1);
    // If all the columns have been removed dont submit a query with nothing
    // After order by clause.
    if (columns.length === 0) {
        var head = URL.head;
        head = head.slice(0,head.indexOf('ORDER+BY'));
        URL.head = head;
        // removing the last sort order should have priority over what
        // is remembered via the RememberSorting directive
        URL.tail += PMA_commonParams.get('arg_separator') + 'discard_remembered_sort=1';
    }
    URL.head = URL.head.substring(URL.head.indexOf('?') + 1);
    var middle_part = columns.join('%2C+');
    params = URL.head + middle_part + URL.tail;
    return params;
}

AJAX.registerOnload('keyhandler.js', function () {
    $('th.draggable.column_heading.pointer.marker a').on('click', function (event) {
        var url = $(this).parent().find('input').val();
        var argsep = PMA_commonParams.get('arg_separator')
        if (event.ctrlKey || event.altKey) {
            event.preventDefault();
            var params = removeColumnFromMultiSort(url, $(this).parent());
            if (params) {
                AJAX.source = $(this);
                PMA_ajaxShowMessage();
                params += argsep + 'ajax_request=true' + argsep + 'ajax_page_request=true';
                $.post('sql.php', params, AJAX.responseHandler);
            }
        } else if (event.shiftKey) {
            event.preventDefault();
            AJAX.source = $(this);
            PMA_ajaxShowMessage();
            var params = url.substring(url.indexOf('?') + 1);
            params += argsep + 'ajax_request=true' + argsep + 'ajax_page_request=true';
            $.post('sql.php', params, AJAX.responseHandler);
        }
    });
});

AJAX.registerTeardown('keyhandler.js', function () {
    $(document).off('click', 'th.draggable.column_heading.pointer.marker a');
});
