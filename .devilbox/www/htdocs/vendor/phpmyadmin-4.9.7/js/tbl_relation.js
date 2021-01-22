/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * for tbl_relation.php
 *
 */
function show_hide_clauses ($thisDropdown) {
    if ($thisDropdown.val() === '') {
        $thisDropdown.parent().nextAll('span').hide();
    } else {
        if ($thisDropdown.is('select[name^="destination_foreign_column"]')) {
            $thisDropdown.parent().nextAll('span').show();
        }
    }
}

/**
 * Sets dropdown options to values
 */
function setDropdownValues ($dropdown, values, selectedValue) {
    $dropdown.empty();
    var optionsAsString = '';
    // add an empty string to the beginning for empty selection
    values.unshift('');
    $.each(values, function () {
        optionsAsString += '<option value=\'' + escapeHtml(this) + '\'' + (selectedValue === escapeHtml(this) ? ' selected=\'selected\'' : '') + '>' + escapeHtml(this) + '</option>';
    });
    $dropdown.append($(optionsAsString));
}

/**
 * Retrieves and populates dropdowns to the left based on the selected value
 *
 * @param $dropdown the dropdown whose value got changed
 */
function getDropdownValues ($dropdown) {
    var foreignDb = null;
    var foreignTable = null;
    var $databaseDd;
    var $tableDd;
    var $columnDd;
    var foreign = '';
    // if the changed dropdown is for foreign key constraints
    if ($dropdown.is('select[name^="destination_foreign"]')) {
        $databaseDd = $dropdown.parent().parent().parent().find('select[name^="destination_foreign_db"]');
        $tableDd    = $dropdown.parent().parent().parent().find('select[name^="destination_foreign_table"]');
        $columnDd   = $dropdown.parent().parent().parent().find('select[name^="destination_foreign_column"]');
        foreign = '_foreign';
    } else { // internal relations
        $databaseDd = $dropdown.parent().find('select[name^="destination_db"]');
        $tableDd    = $dropdown.parent().find('select[name^="destination_table"]');
        $columnDd   = $dropdown.parent().find('select[name^="destination_column"]');
    }

    // if the changed dropdown is a database selector
    if ($dropdown.is('select[name^="destination' + foreign + '_db"]')) {
        foreignDb = $dropdown.val();
        // if no database is selected empty table and column dropdowns
        if (foreignDb === '') {
            setDropdownValues($tableDd, []);
            setDropdownValues($columnDd, []);
            return;
        }
    } else { // if a table selector
        foreignDb = $databaseDd.val();
        foreignTable = $dropdown.val();
        // if no table is selected empty the column dropdown
        if (foreignTable === '') {
            setDropdownValues($columnDd, []);
            return;
        }
    }
    var $msgbox = PMA_ajaxShowMessage();
    var $form = $dropdown.parents('form');
    var $db = $form.find('input[name="db"]').val();
    var $table = $form.find('input[name="table"]').val();
    var argsep = PMA_commonParams.get('arg_separator');
    var params = 'getDropdownValues=true' + argsep + 'ajax_request=true' +
        argsep + 'db=' + encodeURIComponent($db) +
        argsep + 'table=' + encodeURIComponent($table) +
        argsep + 'foreign=' + (foreign !== '') +
        argsep + 'foreignDb=' + encodeURIComponent(foreignDb) +
        (foreignTable !== null ?
            argsep + 'foreignTable=' + encodeURIComponent(foreignTable) : ''
        );
    var $server = $form.find('input[name="server"]');
    if ($server.length > 0) {
        params += argsep + 'server=' + $form.find('input[name="server"]').val();
    }
    $.ajax({
        type: 'POST',
        url: 'tbl_relation.php',
        data: params,
        dataType: 'json',
        success: function (data) {
            PMA_ajaxRemoveMessage($msgbox);
            if (typeof data !== 'undefined' && data.success) {
                // if the changed dropdown is a database selector
                if (foreignTable === null) {
                    // set values for table and column dropdowns
                    setDropdownValues($tableDd, data.tables);
                    setDropdownValues($columnDd, []);
                } else { // if a table selector
                    // set values for the column dropdown
                    var primary = null;
                    if (typeof data.primary !== 'undefined'
                        && 1 === data.primary.length
                    ) {
                        primary = data.primary[0];
                    }
                    setDropdownValues($columnDd.first(), data.columns, primary);
                    setDropdownValues($columnDd.slice(1), data.columns);
                }
            } else {
                PMA_ajaxShowMessage(data.error, false);
            }
        }
    });
}

/**
 * Unbind all event handlers before tearing down a page
 */
AJAX.registerTeardown('tbl_relation.js', function () {
    $('body').off('change',
        'select[name^="destination_db"], ' +
        'select[name^="destination_table"], ' +
        'select[name^="destination_foreign_db"], ' +
        'select[name^="destination_foreign_table"]'
    );
    $('body').off('click', 'a.add_foreign_key_field');
    $('body').off('click', 'a.add_foreign_key');
    $('a.drop_foreign_key_anchor.ajax').off('click');
});

AJAX.registerOnload('tbl_relation.js', function () {
    /**
     * Ajax event handler to fetch table/column dropdown values.
     */
    $('body').on('change',
        'select[name^="destination_db"], ' +
        'select[name^="destination_table"], ' +
        'select[name^="destination_foreign_db"], ' +
        'select[name^="destination_foreign_table"]',
        function () {
            getDropdownValues($(this));
        }
    );

    /**
     * Ajax event handler to add a column to a foreign key constraint.
     */
    $('body').on('click', 'a.add_foreign_key_field', function (event) {
        event.preventDefault();
        event.stopPropagation();

        // Add field.
        $(this)
            .prev('span')
            .clone(true, true)
            .insertBefore($(this))
            .find('select')
            .val('');

        // Add foreign field.
        var $source_elem = $('select[name^="destination_foreign_column[' +
            $(this).attr('data-index') + ']"]:last').parent();
        $source_elem
            .clone(true, true)
            .insertAfter($source_elem)
            .find('select')
            .val('');
    });

    /**
     * Ajax event handler to add a foreign key constraint.
     */
    $('body').on('click', 'a.add_foreign_key', function (event) {
        event.preventDefault();
        event.stopPropagation();

        var $prev_row = $(this).closest('tr').prev('tr');
        var $newRow = $prev_row.clone(true, true);

        // Update serial number.
        var curr_index = $newRow
            .find('a.add_foreign_key_field')
            .attr('data-index');
        var new_index = parseInt(curr_index) + 1;
        $newRow.find('a.add_foreign_key_field').attr('data-index', new_index);

        // Update form parameter names.
        $newRow.find('select[name^="foreign_key_fields_name"]:not(:first), ' +
            'select[name^="destination_foreign_column"]:not(:first)'
        ).each(function () {
            $(this).parent().remove();
        });
        $newRow.find('input, select').each(function () {
            $(this).attr('name',
                $(this).attr('name').replace(/\d/, new_index)
            );
        });
        $newRow.find('input[type="text"]').each(function () {
            $(this).val('');
        });
        // Finally add the row.
        $newRow.insertAfter($prev_row);
    });

    /**
     * Ajax Event handler for 'Drop Foreign key'
     */
    $('a.drop_foreign_key_anchor.ajax').on('click', function (event) {
        event.preventDefault();
        var $anchor = $(this);

        // Object containing reference to the current field's row
        var $curr_row = $anchor.parents('tr');

        var drop_query = escapeHtml(
            $curr_row.children('td')
                .children('.drop_foreign_key_msg')
                .val()
        );

        var question = PMA_sprintf(PMA_messages.strDoYouReally, drop_query);

        $anchor.PMA_confirm(question, $anchor.attr('href'), function (url) {
            var $msg = PMA_ajaxShowMessage(PMA_messages.strDroppingForeignKey, false);
            var params = getJSConfirmCommonParam(this, $anchor.getPostData());
            $.post(url, params, function (data) {
                if (data.success === true) {
                    PMA_ajaxRemoveMessage($msg);
                    PMA_commonActions.refreshMain(false, function () {
                        // Do nothing
                    });
                } else {
                    PMA_ajaxShowMessage(PMA_messages.strErrorProcessingRequest + ' : ' + data.error, false);
                }
            }); // end $.post()
        }); // end $.PMA_confirm()
    }); // end Drop Foreign key

    var windowwidth = $(window).width();
    $('.jsresponsive').css('max-width', (windowwidth - 35) + 'px');
});
