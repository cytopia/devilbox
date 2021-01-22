/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * @package PhpMyAdmin-Designer
 */
var _change = 0; // variable to track any change in designer layout.
var _staying = 0; //  variable to check if the user stayed after seeing the confirmation prompt.
var show_relation_lines = true;
var always_show_text = false;

AJAX.registerTeardown('designer/move.js', function () {
    $(document).off('fullscreenchange');
    $('#selflink').show();
});

AJAX.registerOnload('designer/move.js', function () {
    $('#page_content').css({ 'margin-left': '3px' });
    $(document).on('fullscreenchange', function () {
        if (! $.fn.fullScreen()) {
            $('#page_content').removeClass('content_fullscreen')
                .css({ 'width': 'auto', 'height': 'auto' });
            var $img = $('#toggleFullscreen').find('img');
            var $span = $img.siblings('span');
            $span.text($span.data('enter'));
            $img.attr('src', $img.data('enter'))
                .attr('title', $span.data('enter'));
        }
    });

    $('#selflink').hide();
});

function make_zero () {   // Function called if the user stays after seeing the confirmation prompt.
    _staying = 0;
}

function MarkSaved () {
    _change = 0;
    $('#saved_state').text('');
}

function MarkUnsaved () {
    _change = 1;
    $('#saved_state').text('*');
}

var dx;
var dy;
var dy2;
var cur_click = null;
// update in Main()
var sm_x = 2;
var sm_y = 2;
var sm_s           = 0;
var sm_add         = 10;
var s_left         = 0;
var s_right        = 0;
var ON_relation    = 0;
var ON_grid        = 0;
var ON_display_field = 0;
// relation_style: 0 - angular 1 - direct
var ON_angular_direct = 1;
var click_field    = 0;
var link_relation  = '';
var id_hint;
var canvas_width   = 0;
var canvas_height  = 0;
var osn_tab_width  = 0;
var osn_tab_height = 0;
var height_field   = 7;
var Glob_X;
var Glob_Y;
var timeoutID;
var layer_menu_cur_click = 0;
var step = 10;
var from_array = [];
var menu_moved = false;
var grid_size = 10;

// ------------------------------------------------------------------------------
// ------------------------------------------------------------------------------
// ------------------------------------------------------------------------------


// window.captureEvents(Event.MOUSEDOWN | Event.MOUSEUP);
// ---CROSS

var isIE = document.all && !window.opera;

if (isIE) {
    window.onscroll = General_scroll;
    document.onselectstart = function () {
        return false;
    };
}

// document.onmouseup = function (){General_scroll_end();}
function MouseDown (e) {
    Glob_X = dx = isIE ? e.clientX + document.body.scrollLeft : e.pageX;
    Glob_Y = dy = isIE ? e.clientY + document.body.scrollTop : e.pageY;

    if (e.target.tagName === 'SPAN') {
        cur_click = e.target.parentNode.parentNode.parentNode.parentNode;
    } else if (e.target.className === 'tab_zag_2') {
        cur_click = e.target.parentNode.parentNode.parentNode;
    } else if (e.target.className === 'icon') {
        layer_menu_cur_click = 1;
    } else if (e.target.className === 'M_butt') {
        return false;
    }

    if (cur_click !== null) {
        document.getElementById('canvas').style.display = 'none';
        cur_click.style.zIndex = 2;
    }
}

function MouseMove (e) {
    if (e.preventDefault) {
        e.preventDefault();
    }

    var new_dx = isIE ? e.clientX + document.body.scrollLeft : e.pageX;
    var new_dy = isIE ? e.clientY + document.body.scrollTop : e.pageY;

    var delta_x = Glob_X - new_dx;
    var delta_y = Glob_Y - new_dy;

    Glob_X = new_dx;
    Glob_Y = new_dy;

    if (cur_click !== null) {
        MarkUnsaved();

        var $cur_click = $(cur_click);

        var cur_x = parseFloat($cur_click.attr('data-left') || $cur_click.css('left'));
        var cur_y = parseFloat($cur_click.attr('data-top') || $cur_click.css('top'));

        var new_x = cur_x - delta_x;
        var new_y = cur_y - delta_y;

        dx = new_dx;
        dy = new_dy;

        $cur_click.attr('data-left', new_x);
        $cur_click.attr('data-top', new_y);

        if (ON_grid) {
            new_x = parseInt(new_x / grid_size) * grid_size;
            new_y = parseInt(new_y / grid_size) * grid_size;
        }

        $cur_click.css('left', new_x + 'px');
        $cur_click.css('top', new_y + 'px');
    } else if (layer_menu_cur_click) {
        dx = new_dx;
        dy = new_dy;
        if (menu_moved) {
            delta_x = -delta_x;
        }
        var $layer_menu = $('#layer_menu');
        var new_width = $layer_menu.width() + delta_x;
        if (new_width < 150) {
            new_width = 150;
        } else {
            dx = e.pageX;
        }
        $layer_menu.width(new_width);
    }

    if (ON_relation || ON_display_field) {
        document.getElementById('designer_hint').style.left = (Glob_X + 20) + 'px';
        document.getElementById('designer_hint').style.top  = (Glob_Y + 20) + 'px';
    }
}

function MouseUp (e) {
    if (cur_click !== null) {
        document.getElementById('canvas').style.display = 'inline-block';
        Re_load();
        cur_click.style.zIndex = 1;
        cur_click = null;
    }
    layer_menu_cur_click = 0;
    // window.releaseEvents(Event.MOUSEMOVE);
}
// ------------------------------------------------------------------------------
// ------------------------------------------------------------------------------
// ------------------------------------------------------------------------------


// function ToInt(s)
// {
//    return s.substring(0,s.length-2)*1; //re = /(\d+)\w*/; newstr = str.replace(re, "$1");
// }

function Canvas_pos () {
    canvas_width  = document.getElementById('canvas').width  = osn_tab_width  - 3;
    canvas_height = document.getElementById('canvas').height = osn_tab_height - 3;

    if (isIE) {
        document.getElementById('canvas').style.width  = ((osn_tab_width  - 3) ? (osn_tab_width  - 3) : 0) + 'px';
        document.getElementById('canvas').style.height = ((osn_tab_height - 3) ? (osn_tab_height - 3) : 0) + 'px';
    }
}

function Osn_tab_pos () {
    osn_tab_width  = parseInt(document.getElementById('osn_tab').style.width, 10);
    osn_tab_height = parseInt(document.getElementById('osn_tab').style.height, 10);
}

function setDefaultValuesFromSavedState () {
    if ($('#angular_direct_button').attr('class') === 'M_butt') {
        ON_angular_direct = 0;
    } else {
        ON_angular_direct = 1;
    }
    Angular_direct();

    if ($('#grid_button').attr('class') === 'M_butt') {
        ON_grid = 1;
    } else {
        ON_grid = 0;
    }
    Grid();

    var $relLineInvert = $('#relLineInvert');
    if ($relLineInvert.attr('class') === 'M_butt') {
        show_relation_lines = false;
        $relLineInvert.attr('class', 'M_butt');
    } else {
        show_relation_lines = true;
        $relLineInvert.attr('class', 'M_butt_Selected_down');
    }
    Relation_lines_invert();

    if ($('#pin_Text').attr('class') === 'M_butt_Selected_down') {
        always_show_text = true;
        Show_text();
    } else {
        always_show_text = false;
    }

    var $key_SB_all = $('#key_SB_all');
    if ($key_SB_all.attr('class') === 'M_butt_Selected_down') {
        $key_SB_all.click();
        $key_SB_all.toggleClass('M_butt_Selected_down');
        $key_SB_all.toggleClass('M_butt');
    }

    var $key_Left_Right = $('#key_Left_Right');
    if ($key_Left_Right.attr('class') === 'M_butt_Selected_down') {
        $key_Left_Right.click();
    }
}

function Main () {
    // ---CROSS

    document.getElementById('layer_menu').style.top = -1000 + 'px'; // fast scroll
    // sm_x += document.getElementById('osn_tab').offsetLeft;
    // sm_y += document.getElementById('osn_tab').offsetTop;
    Osn_tab_pos();
    Canvas_pos();
    Small_tab_refresh();
    Re_load();
    setDefaultValuesFromSavedState();
    id_hint = document.getElementById('designer_hint');
    if (isIE) {
        General_scroll();
    }
}


// -------------------------------- new -----------------------------------------
function Rezize_osn_tab () {
    var max_X = 0;
    var max_Y = 0;
    for (var key in j_tabs) {
        var k_x = parseInt(document.getElementById(key).style.left, 10) + document.getElementById(key).offsetWidth;
        var k_y = parseInt(document.getElementById(key).style.top, 10) + document.getElementById(key).offsetHeight;
        max_X = max_X < k_x ? k_x : max_X;
        max_Y = max_Y < k_y ? k_y : max_Y;
    }

    osn_tab_width  = max_X + 50;
    osn_tab_height = max_Y + 50;
    Canvas_pos();
    document.getElementById('osn_tab').style.width = osn_tab_width + 'px';
    document.getElementById('osn_tab').style.height = osn_tab_height + 'px';
}
// ------------------------------------------------------------------------------

/**
 * refreshes display, must be called after state changes
 */
function Re_load () {
    Rezize_osn_tab();
    var n;
    var x1;
    var x2;
    var a = [];
    var K;
    var key;
    var key2;
    var key3;
    Clear();
    for (K in contr) {
        for (key in contr[K]) {
            // contr name
            for (key2 in contr[K][key]) {
                // table name
                for (key3 in contr[K][key][key2]) {
                    // field name
                    if (!document.getElementById('check_vis_' + key2).checked ||
                        !document.getElementById('check_vis_' + contr[K][key][key2][key3][0]).checked) {
                        // if hide
                        continue;
                    }
                    var x1_left  = document.getElementById(key2).offsetLeft + 1;
                    var x1_right = x1_left + document.getElementById(key2).offsetWidth;
                    var x2_left  = document.getElementById(contr[K][key][key2][key3][0]).offsetLeft;
                    var x2_right = x2_left + document.getElementById(contr[K][key][key2][key3][0]).offsetWidth;
                    a[0] = Math.abs(x1_left - x2_left);
                    a[1] = Math.abs(x1_left - x2_right);
                    a[2] = Math.abs(x1_right - x2_left);
                    a[3] = Math.abs(x1_right - x2_right);
                    n = s_left = s_right = 0;
                    for (var i = 1; i < 4; i++) {
                        if (a[n] > a[i]) {
                            n = i;
                        }
                    }
                    if (n === 1) {
                        x1 = x1_left - sm_s;
                        x2 = x2_right + sm_s;
                        if (x1 < x2) {
                            n = 0;
                        }
                    }
                    if (n === 2) {
                        x1 = x1_right + sm_s;
                        x2 = x2_left - sm_s;
                        if (x1 > x2) {
                            n = 0;
                        }
                    }
                    if (n === 3) {
                        x1 = x1_right + sm_s;
                        x2 = x2_right + sm_s;
                        s_right = 1;
                    }
                    if (n === 0) {
                        x1 = x1_left - sm_s;
                        x2 = x2_left - sm_s;
                        s_left = 1;
                    }

                    var row_offset_top = 0;
                    var tab_hide_button = document.getElementById('id_hide_tbody_' + key2);

                    if (tab_hide_button.innerHTML === 'v') {
                        var fromColumn = document.getElementById(key2 + '.' + key3);
                        if (fromColumn) {
                            row_offset_top = fromColumn.offsetTop;
                        } else {
                            continue;
                        }
                    }

                    var y1 = document.getElementById(key2).offsetTop +
                        row_offset_top +
                        height_field;


                    row_offset_top = 0;
                    tab_hide_button = document.getElementById('id_hide_tbody_' + contr[K][key][key2][key3][0]);
                    if (tab_hide_button.innerHTML === 'v') {
                        var toColumn = document.getElementById(contr[K][key][key2][key3][0] +
                            '.' + contr[K][key][key2][key3][1]);
                        if (toColumn) {
                            row_offset_top = toColumn.offsetTop;
                        } else {
                            continue;
                        }
                    }

                    var y2 =
                        document.getElementById(contr[K][key][key2][key3][0]).offsetTop +
                        row_offset_top +
                        height_field;

                    var osn_tab = document.getElementById('osn_tab');

                    Line0(
                        x1 + osn_tab.offsetLeft,
                        y1 - osn_tab.offsetTop,
                        x2 + osn_tab.offsetLeft,
                        y2 - osn_tab.offsetTop,
                        getColorByTarget(contr[K][key][key2][key3][0] + '.' + contr[K][key][key2][key3][1])
                    );
                }
            }
        }
    }
}

/**
 * draws a line from x1:y1 to x2:y2 with color
 */
function Line (x1, y1, x2, y2, color_line) {
    var canvas = document.getElementById('canvas');
    var ctx    = canvas.getContext('2d');
    ctx.strokeStyle = color_line;
    ctx.lineWidth = 1;
    ctx.beginPath();
    ctx.moveTo(x1, y1);
    ctx.lineTo(x2, y2);
    ctx.stroke();
}

/**
 * draws a relation/constraint line, whether angular or not
 */
function Line0 (x1, y1, x2, y2, color_line) {
    if (! show_relation_lines) {
        return;
    }
    Circle(x1, y1, 3, 3, color_line);
    Rect(x2 - 1, y2 - 2, 4, 4, color_line);

    if (ON_angular_direct) {
        Line2(x1, y1, x2, y2, color_line);
    } else {
        Line3(x1, y1, x2, y2, color_line);
    }
}

/**
 * draws a angular relation/constraint line
 */
function Line2 (x1, y1, x2, y2, color_line) {
    var x1_ = x1;
    var x2_ = x2;

    if (s_right) {
        x1_ += sm_add;
        x2_ += sm_add;
    } else if (s_left) {
        x1_ -= sm_add;
        x2_ -= sm_add;
    } else if (x1 < x2) {
        x1_ += sm_add;
        x2_ -= sm_add;
    } else {
        x1_ -= sm_add;
        x2_ += sm_add;
    }

    Line(x1, y1, x1_, y1, color_line);
    Line(x2, y2, x2_, y2, color_line);
    Line(x1_, y1, x2_, y2, color_line);
}

/**
 * draws a relation/constraint line
 */
function Line3 (x1, y1, x2, y2, color_line) {
    var x1_ = x1;
    var x2_ = x2;

    if (s_right) {
        if (x1 < x2) {
            x1_ += x2 - x1 + sm_add;
            x2_ += sm_add;
        } else {
            x2_ += x1 - x2 + sm_add;
            x1_ += sm_add;
        }

        Line(x1, y1, x1_, y1, color_line);
        Line(x2, y2, x2_, y2, color_line);
        Line(x1_, y1, x2_, y2, color_line);
        return;
    }
    if (s_left) {
        if (x1 < x2) {
            x2_ -= x2 - x1 + sm_add;
            x1_ -= sm_add;
        } else {
            x1_ -= x1 - x2 + sm_add;
            x2_ -= sm_add;
        }

        Line(x1, y1, x1_, y1, color_line);
        Line(x2, y2, x2_, y2, color_line);
        Line(x1_, y1, x2_, y2, color_line);
        return;
    }

    var x_s = (x1 + x2) / 2;
    Line(x1, y1, x_s, y1, color_line);
    Line(x_s, y2, x2, y2, color_line);
    Line(x_s, y1, x_s, y2, color_line);
}

function Circle (x, y, r, w, color) {
    var ctx = document.getElementById('canvas').getContext('2d');
    ctx.beginPath();
    ctx.moveTo(x, y);
    ctx.lineWidth = w;
    ctx.strokeStyle = color;
    ctx.arc(x, y, r, 0, 2 * Math.PI, true);
    ctx.stroke();
}

function Clear () {
    var canvas = document.getElementById('canvas');
    var ctx    = canvas.getContext('2d');
    ctx.clearRect(0, 0, canvas_width, canvas_height);
}

function Rect (x1, y1, w, h, color) {
    var ctx = document.getElementById('canvas').getContext('2d');
    ctx.fillStyle = color;
    ctx.fillRect(x1, y1, w, h);
}
// --------------------------- FULLSCREEN -------------------------------------
function Toggle_fullscreen () {
    var value_sent = '';
    var $img = $('#toggleFullscreen').find('img');
    var $span = $img.siblings('span');
    var $content = $('#page_content');
    if (! $content.fullScreen()) {
        $img.attr('src', $img.data('exit'))
            .attr('title', $span.data('exit'));
        $span.text($span.data('exit'));
        $content
            .addClass('content_fullscreen')
            .css({ 'width': screen.width - 5, 'height': screen.height - 5 });
        value_sent = 'on';
        $content.fullScreen(true);
    } else {
        $img.attr('src', $img.data('enter'))
            .attr('title', $span.data('enter'));
        $span.text($span.data('enter'));
        $content.fullScreen(false);
        value_sent = 'off';
    }
    saveValueInConfig('full_screen', value_sent);
}

function addTableToTablesList (index, table_dom) {
    var db = $(table_dom).find('.small_tab_pref').attr('db');
    var table = $(table_dom).find('.small_tab_pref').attr('table_name');
    var db_encoded = $(table_dom).find('.small_tab_pref').attr('db_url');
    var table_encoded = $(table_dom).find('.small_tab_pref').attr('table_name_url');
    var $new_table_line = $('<tr>' +
        '    <td title="' + PMA_messages.strStructure + '"' +
        '        width="1px"' +
        '        class="L_butt2_1">' +
        '        <img alt=""' +
        '            db="' + db_encoded + '"' +
        '            table_name="' + table_encoded + '"' +
        '            class="scroll_tab_struct"' +
        '            src="' + pmaThemeImage + 'designer/exec.png"/>' +
        '    </td>' +
        '    <td width="1px">' +
        '        <input class="scroll_tab_checkbox"' +
        '            title="' + PMA_messages.strHide + '"' +
        '            id="check_vis_' + db_encoded + '.' + table_encoded + '"' +
        '            style="margin:0;"' +
        '            type="checkbox"' +
        '            value="' + db_encoded + '.' + table_encoded + '"' +
        '            checked="checked"' +
        '            />' +
        '    </td>' +
        '    <td class="designer_Tabs"' +
        '        designer_url_table_name="' + db_encoded + '.' + table_encoded + '">' + $('<div/>').text(db + '.' + table).html() + '</td>' +
        '</tr>');
    $('#id_scroll_tab table').first().append($new_table_line);
    $($new_table_line).find('.scroll_tab_struct').click(function () {
        Start_tab_upd(db, table);
    });
    $($new_table_line).on('click', '.designer_Tabs2,.designer_Tabs', function () {
        Select_tab($(this).attr('designer_url_table_name'));
    });
    $($new_table_line).find('.scroll_tab_checkbox').click(function () {
        VisibleTab(this,$(this).val());
    });
    var $tables_counter = $('#tables_counter');
    $tables_counter.text(parseInt($tables_counter.text(), 10) + 1);
}

function Add_Other_db_tables () {
    var button_options = {};
    button_options[PMA_messages.strGo] = function () {
        var db = $('#add_table_from').val();
        var table = $('#add_table').val();

        // Check if table already imported or not.
        var $table = $('[id="' + encodeURIComponent(db) + '.' + encodeURIComponent(table) + '"]');
        if ($table.length !== 0) {
            PMA_ajaxShowMessage(
                PMA_sprintf(PMA_messages.strTableAlreadyExists, db + '.' + table),
                undefined,
                'error'
            );
            return;
        }

        $.post('db_designer.php', {
            'ajax_request' : true,
            'dialog' : 'add_table',
            'db' : db,
            'table' : table,
            'server': PMA_commonParams.get('server')
        }, function (data) {
            var $newTableDom = $(data.message);
            $newTableDom.find('a').first().remove();
            var dbEncoded = $($newTableDom).find('.small_tab_pref').attr('db_url');
            var tableEncoded = $($newTableDom).find('.small_tab_pref').attr('table_name_url');
            if (typeof dbEncoded === 'string' && typeof tableEncoded === 'string' ) { // Do not try to add if attr not found !
                $('#container-form').append($newTableDom);
                enableTableEvents(null, $newTableDom);
                addTableToTablesList(null, $newTableDom);
                j_tabs[dbEncoded + '.' + tableEncoded] = 1;
                MarkUnsaved();
            }
        });
        $(this).dialog('close');
    };
    button_options[PMA_messages.strCancel] = function () {
        $(this).dialog('close');
    };

    var $select_db = $('<select id="add_table_from"></select>');
    $select_db.append('<option value="">' + PMA_messages.strNone + '</option>');

    var $select_table = $('<select id="add_table"></select>');
    $select_table.append('<option value="">' + PMA_messages.strNone + '</option>');

    $.post('sql.php', {
        'ajax_request' : true,
        'sql_query' : 'SHOW databases;',
        'server': PMA_commonParams.get('server')
    }, function (data) {
        $(data.message).find('table.table_results.data.ajax').find('td.data').each(function () {
            var val = $(this)[0].innerText;
            $select_db.append($('<option></option>').val(val).text(val));
        });
    });

    var $form = $('<form action="" class="ajax"></form>')
        .append($select_db).append($select_table);
    $('<div id="page_add_tables_dialog"></div>')
        .append($form)
        .dialog({
            appendTo: '#page_content',
            title: PMA_messages.strAddTables,
            width: 500,
            modal: true,
            buttons: button_options,
            close: function () {
                $(this).remove();
            }
        });

    $('#add_table_from').change(function () {
        if ($(this).val()) {
            var db_name = $(this).val();
            var sql_query = 'SHOW tables;';
            $.post('sql.php', {
                'ajax_request' : true,
                'sql_query': sql_query,
                'db' : db_name,
                'server': PMA_commonParams.get('server')
            }, function (data) {
                $select_table.html('');
                var rows = $(data.message).find('table.table_results.data.ajax').find('td.data');
                if (rows.length === 0) {
                    $select_table.append('<option value="">' + PMA_messages.strNone + '</option>');
                }
                rows.each(function () {
                    var val = $(this)[0].innerText;
                    $select_table.append($('<option></option>').val(val).text(val));
                });
            });
        }
    });
}


// ------------------------------ NEW ------------------------------------------

function New () {
    Prompt_to_save_current_page(function () {
        Load_page(-1);
    });
}

// ------------------------------ SAVE ------------------------------------------
// (del?) no for pdf
function Save (url) {
    for (var key in j_tabs) {
        document.getElementById('t_x_' + key + '_').value = parseInt(document.getElementById(key).style.left, 10);
        document.getElementById('t_y_' + key + '_').value = parseInt(document.getElementById(key).style.top, 10);
        document.getElementById('t_v_' + key + '_').value = document.getElementById('id_tbody_' + key).style.display === 'none' ? 0 : 1;
        document.getElementById('t_h_' + key + '_').value = document.getElementById('check_vis_' + key).checked ? 1 : 0;
    }
    document.getElementById('container-form').action = url;
    $('#container-form').submit();
}

function Get_url_pos (forceString) {
    if (designer_tables_enabled || forceString) {
        var poststr = '';
        var argsep = PMA_commonParams.get('arg_separator');
        var i = 1;
        for (var key in j_tabs) {
            poststr += argsep + 't_x[' + i + ']=' + parseInt(document.getElementById(key).style.left, 10);
            poststr += argsep + 't_y[' + i + ']=' + parseInt(document.getElementById(key).style.top, 10);
            poststr += argsep + 't_v[' + i + ']=' + (document.getElementById('id_tbody_' + key).style.display === 'none' ? 0 : 1);
            poststr += argsep + 't_h[' + i + ']=' + (document.getElementById('check_vis_' + key).checked ? 1 : 0);
            poststr += argsep + 't_db[' + i + ']=' + $(document.getElementById(key)).attr('db_url');
            poststr += argsep + 't_tbl[' + i + ']=' + $(document.getElementById(key)).attr('table_name_url');
            i++;
        }
        return poststr;
    } else {
        var coords = [];
        for (var key in j_tabs) {
            if (document.getElementById('check_vis_' + key).checked) {
                var x = parseInt(document.getElementById(key).style.left, 10);
                var y = parseInt(document.getElementById(key).style.top, 10);
                var tbCoords = new TableCoordinate(
                    $(document.getElementById(key)).attr('db_url'),
                    $(document.getElementById(key)).attr('table_name_url'),
                    -1, x, y);
                coords.push(tbCoords);
            }
        }
        return coords;
    }
}

function Save2 (callback) {
    if (designer_tables_enabled) {
        var argsep = PMA_commonParams.get('arg_separator');
        var poststr = 'operation=savePage' + argsep + 'save_page=same' + argsep + 'ajax_request=true';
        poststr += argsep + 'server=' + server + argsep + 'db=' + encodeURIComponent(db) + argsep + 'selected_page=' + selected_page;
        poststr += Get_url_pos();

        var $msgbox = PMA_ajaxShowMessage(PMA_messages.strProcessingRequest);
        $.post('db_designer.php', poststr, function (data) {
            if (data.success === false) {
                PMA_ajaxShowMessage(data.error, false);
            } else {
                PMA_ajaxRemoveMessage($msgbox);
                PMA_ajaxShowMessage(PMA_messages.strModificationSaved);
                MarkSaved();
                if (typeof callback !== 'undefined') {
                    callback();
                }
            }
        });
    } else {
        var name = $('#page_name').html().trim();
        Save_to_selected_page(db, selected_page, name, Get_url_pos(), function (page) {
            MarkSaved();
            if (typeof callback !== 'undefined') {
                callback();
            }
        });
    }
}


function submitSaveDialogAndClose (callback) {
    var $form = $('#save_page');
    var name = $form.find('input[name="selected_value"]').val().trim();
    if (name === '') {
        PMA_ajaxShowMessage(PMA_messages.strEnterValidPageName, false);
        return;
    }
    $('#page_save_dialog').dialog('close');

    if (designer_tables_enabled) {
        var $msgbox = PMA_ajaxShowMessage(PMA_messages.strProcessingRequest);
        PMA_prepareForAjaxRequest($form);
        $.post($form.attr('action'), $form.serialize() + Get_url_pos(), function (data) {
            if (data.success === false) {
                PMA_ajaxShowMessage(data.error, false);
            } else {
                PMA_ajaxRemoveMessage($msgbox);
                MarkSaved();
                if (data.id) {
                    selected_page = data.id;
                }
                $('#page_name').text(name);
                if (typeof callback !== 'undefined') {
                    callback();
                }
            }
        });
    } else {
        Save_to_new_page(db, name, Get_url_pos(), function (page) {
            MarkSaved();
            if (page.pg_nr) {
                selected_page = page.pg_nr;
            }
            $('#page_name').text(page.page_descr);
            if (typeof callback !== 'undefined') {
                callback();
            }
        });
    }
}

function Save3 (callback) {
    if (selected_page !== -1) {
        Save2(callback);
    } else {
        var button_options = {};
        button_options[PMA_messages.strGo] = function () {
            var $form = $('#save_page');
            $form.submit();
        };
        button_options[PMA_messages.strCancel] = function () {
            $(this).dialog('close');
        };

        var $form = $('<form action="db_designer.php" method="post" name="save_page" id="save_page" class="ajax"></form>')
            .append('<input type="hidden" name="server" value="' + server + '" />')
            .append($('<input type="hidden" name="db" />').val(db))
            .append('<input type="hidden" name="operation" value="savePage" />')
            .append('<input type="hidden" name="save_page" value="new" />')
            .append('<label for="selected_value">' + PMA_messages.strPageName +
                '</label>:<input type="text" name="selected_value" />');
        $form.on('submit', function (e) {
            e.preventDefault();
            submitSaveDialogAndClose(callback);
        });
        $('<div id="page_save_dialog"></div>')
            .append($form)
            .dialog({
                appendTo: '#page_content',
                title: PMA_messages.strSavePage,
                width: 300,
                modal: true,
                buttons: button_options,
                close: function () {
                    $(this).remove();
                }
            });
    }
}

// ------------------------------ EDIT PAGES ------------------------------------------
function Edit_pages () {
    Prompt_to_save_current_page(function () {
        var button_options = {};
        button_options[PMA_messages.strGo] = function () {
            var $form = $('#edit_delete_pages');
            var selected = $form.find('select[name="selected_page"]').val();
            if (selected === '0') {
                PMA_ajaxShowMessage(PMA_messages.strSelectPage, 2000);
                return;
            }
            $(this).dialog('close');
            Load_page(selected);
        };
        button_options[PMA_messages.strCancel] = function () {
            $(this).dialog('close');
        };

        var $msgbox = PMA_ajaxShowMessage();
        $.post('db_designer.php', {
            'ajax_request': true,
            'server': server,
            'db': db,
            'dialog': 'edit'
        }, function (data) {
            if (data.success === false) {
                PMA_ajaxShowMessage(data.error, false);
            } else {
                PMA_ajaxRemoveMessage($msgbox);

                if (! designer_tables_enabled) {
                    Create_page_list(db, function (options) {
                        $('#selected_page').append(options);
                    });
                }
                $('<div id="page_edit_dialog"></div>')
                    .append(data.message)
                    .dialog({
                        appendTo: '#page_content',
                        title: PMA_messages.strOpenPage,
                        width: 350,
                        modal: true,
                        buttons: button_options,
                        close: function () {
                            $(this).remove();
                        }
                    });
            }
        }); // end $.post()
    });
}

// -----------------------------  DELETE PAGES ---------------------------------------
function Delete_pages () {
    var button_options = {};
    button_options[PMA_messages.strGo] = function () {
        var $form = $('#edit_delete_pages');
        var selected = $form.find('select[name="selected_page"]').val();
        if (selected === '0') {
            PMA_ajaxShowMessage(PMA_messages.strSelectPage, 2000);
            return;
        }

        var $msgbox = PMA_ajaxShowMessage(PMA_messages.strProcessingRequest);
        var deleting_current_page = selected === selected_page;
        PMA_prepareForAjaxRequest($form);

        if (designer_tables_enabled) {
            $.post($form.attr('action'), $form.serialize(), function (data) {
                if (data.success === false) {
                    PMA_ajaxShowMessage(data.error, false);
                } else {
                    PMA_ajaxRemoveMessage($msgbox);
                    if (deleting_current_page) {
                        Load_page(null);
                    } else {
                        PMA_ajaxShowMessage(PMA_messages.strSuccessfulPageDelete);
                    }
                }
            }); // end $.post()
        } else {
            Delete_page(selected, function (success) {
                if (! success) {
                    PMA_ajaxShowMessage('Error', false);
                } else {
                    PMA_ajaxRemoveMessage($msgbox);
                    if (deleting_current_page) {
                        Load_page(null);
                    } else {
                        PMA_ajaxShowMessage(PMA_messages.strSuccessfulPageDelete);
                    }
                }
            });
        }

        $(this).dialog('close');
    };
    button_options[PMA_messages.strCancel] = function () {
        $(this).dialog('close');
    };

    var $msgbox = PMA_ajaxShowMessage();
    $.post('db_designer.php', {
        'ajax_request': true,
        'server': server,
        'db': db,
        'dialog': 'delete'
    }, function (data) {
        if (data.success === false) {
            PMA_ajaxShowMessage(data.error, false);
        } else {
            PMA_ajaxRemoveMessage($msgbox);

            if (! designer_tables_enabled) {
                Create_page_list(db, function (options) {
                    $('#selected_page').append(options);
                });
            }

            $('<div id="page_delete_dialog"></div>')
                .append(data.message)
                .dialog({
                    appendTo: '#page_content',
                    title: PMA_messages.strDeletePage,
                    width: 350,
                    modal: true,
                    buttons: button_options,
                    close: function () {
                        $(this).remove();
                    }
                });
        }
    }); // end $.post()
}

// ------------------------------ SAVE AS PAGES ---------------------------------------
function Save_as () {
    var button_options = {};
    button_options[PMA_messages.strGo] = function () {
        var $form           = $('#save_as_pages');
        var selected_value  = $form.find('input[name="selected_value"]').val().trim();
        var $selected_page  = $form.find('select[name="selected_page"]');
        var choice          = $form.find('input[name="save_page"]:checked').val();
        var name            = '';

        if (choice === 'same') {
            if ($selected_page.val() === '0') {
                PMA_ajaxShowMessage(PMA_messages.strSelectPage, 2000);
                return;
            }
            name = $selected_page.find('option:selected').text();
        } else if (choice === 'new') {
            if (selected_value === '') {
                PMA_ajaxShowMessage(PMA_messages.strEnterValidPageName, 2000);
                return;
            }
            name = selected_value;
        }

        var $msgbox = PMA_ajaxShowMessage(PMA_messages.strProcessingRequest);
        if (designer_tables_enabled) {
            PMA_prepareForAjaxRequest($form);
            $.post($form.attr('action'), $form.serialize() + Get_url_pos(), function (data) {
                if (data.success === false) {
                    PMA_ajaxShowMessage(data.error, false);
                } else {
                    PMA_ajaxRemoveMessage($msgbox);
                    MarkSaved();
                    if (data.id) {
                        selected_page = data.id;
                    }
                    Load_page(selected_page);
                }
            }); // end $.post()
        } else {
            if (choice === 'same') {
                var selected_page_id = $selected_page.find('option:selected').val();
                Save_to_selected_page(db, selected_page_id, name, Get_url_pos(), function (page) {
                    PMA_ajaxRemoveMessage($msgbox);
                    MarkSaved();
                    if (page.pg_nr) {
                        selected_page = page.pg_nr;
                    }
                    Load_page(selected_page);
                });
            } else if (choice === 'new') {
                Save_to_new_page(db, name, Get_url_pos(), function (page) {
                    PMA_ajaxRemoveMessage($msgbox);
                    MarkSaved();
                    if (page.pg_nr) {
                        selected_page = page.pg_nr;
                    }
                    Load_page(selected_page);
                });
            }
        }

        $(this).dialog('close');
    };
    button_options[PMA_messages.strCancel] = function () {
        $(this).dialog('close');
    };

    var $msgbox = PMA_ajaxShowMessage();
    $.post('db_designer.php', {
        'ajax_request': true,
        'server': server,
        'db': db,
        'dialog': 'save_as'
    }, function (data) {
        if (data.success === false) {
            PMA_ajaxShowMessage(data.error, false);
        } else {
            PMA_ajaxRemoveMessage($msgbox);

            if (! designer_tables_enabled) {
                Create_page_list(db, function (options) {
                    $('#selected_page').append(options);
                });
            }

            $('<div id="page_save_as_dialog"></div>')
                .append(data.message)
                .dialog({
                    appendTo: '#page_content',
                    title: PMA_messages.strSavePageAs,
                    width: 450,
                    modal: true,
                    buttons: button_options,
                    close: function () {
                        $(this).remove();
                    }
                });
            // select current page by default
            if (selected_page !== -1) {
                $('select[name="selected_page"]').val(selected_page);
            }
        }
    }); // end $.post()
}

function Prompt_to_save_current_page (callback) {
    if (_change === 1 || selected_page === -1) {
        var button_options = {};
        button_options[PMA_messages.strYes] = function () {
            $(this).dialog('close');
            Save3(callback);
        };
        button_options[PMA_messages.strNo] = function () {
            $(this).dialog('close');
            callback();
        };
        button_options[PMA_messages.strCancel] = function () {
            $(this).dialog('close');
        };
        $('<div id="prompt_save_dialog"></div>')
            .append('<div>' + PMA_messages.strLeavingPage + '</div>')
            .dialog({
                appendTo: '#page_content',
                title: PMA_messages.strSavePage,
                width: 300,
                modal: true,
                buttons: button_options,
                close: function () {
                    $(this).remove();
                }
            });
    } else {
        callback();
    }
}

// ------------------------------ EXPORT PAGES ---------------------------------------
function Export_pages () {
    var button_options = {};
    button_options[PMA_messages.strGo] = function () {
        $('#id_export_pages').submit();
        $(this).dialog('close');
    };
    button_options[PMA_messages.strCancel] = function () {
        $(this).dialog('close');
    };
    var $msgbox = PMA_ajaxShowMessage();
    var argsep = PMA_commonParams.get('arg_separator');

    $.post('db_designer.php', {
        'ajax_request': true,
        'server': server,
        'db': db,
        'dialog': 'export',
        'selected_page': selected_page
    }, function (data) {
        if (data.success === false) {
            PMA_ajaxShowMessage(data.error, false);
        } else {
            PMA_ajaxRemoveMessage($msgbox);

            var $form = $(data.message);
            if (!designer_tables_enabled) {
                $form.append('<input type="hidden" name="offline_export" value="true" />');
            }
            $.each(Get_url_pos(true).substring(1).split(argsep), function () {
                var pair = this.split('=');
                var input = $('<input type="hidden" />');
                input.attr('name', pair[0]);
                input.attr('value', pair[1]);
                $form.append(input);
            });
            var $formatDropDown = $form.find('#plugins');
            $formatDropDown.change(function () {
                var format = $formatDropDown.val();
                $form.find('.format_specific_options').hide();
                $form.find('#' + format + '_options').show();
            }).trigger('change');

            $('<div id="page_export_dialog"></div>')
                .append($form)
                .dialog({
                    appendTo: '#page_content',
                    title: PMA_messages.strExportRelationalSchema,
                    width: 550,
                    modal: true,
                    buttons: button_options,
                    close: function () {
                        $(this).remove();
                    }
                });
        }
    }); // end $.post()
}// end export pages

function Load_page (page) {
    if (designer_tables_enabled) {
        var param_page = '';
        var argsep = PMA_commonParams.get('arg_separator');
        if (page !== null) {
            param_page = argsep + 'page=' + page;
        }
        $('<a href="db_designer.php?server=' + server + argsep + 'db=' + encodeURIComponent(db) + param_page + '"></a>')
            .appendTo($('#page_content'))
            .click();
    } else {
        if (page === null) {
            Show_tables_in_landing_page(db);
        } else if (page > -1) {
            Load_HTML_for_page(page);
        } else if (page === -1) {
            Show_new_page_tables(true);
        }
    }
    MarkSaved();
}

function Grid () {
    var value_sent = '';
    if (!ON_grid) {
        ON_grid = 1;
        value_sent = 'on';
        document.getElementById('grid_button').className = 'M_butt_Selected_down';
    } else {
        document.getElementById('grid_button').className = 'M_butt';
        ON_grid = 0;
        value_sent = 'off';
    }
    saveValueInConfig('snap_to_grid', value_sent);
}

function Angular_direct () {
    var value_sent = '';
    if (ON_angular_direct) {
        ON_angular_direct = 0;
        value_sent = 'angular';
        document.getElementById('angular_direct_button').className = 'M_butt_Selected_down';
    } else {
        ON_angular_direct = 1;
        value_sent = 'direct';
        document.getElementById('angular_direct_button').className = 'M_butt';
    }
    saveValueInConfig('angular_direct', value_sent);
    Re_load();
}

function saveValueInConfig (index_sent, value_sent) {
    $.post('db_designer.php',
        { operation: 'save_setting_value', index: index_sent, ajax_request: true, server: server, value: value_sent },
        function (data) {
            if (data.success === false) {
                PMA_ajaxShowMessage(data.error, false);
            }
        });
}

// ++++++++++++++++++++++++++++++ RELATION ++++++++++++++++++++++++++++++++++++++
function Start_relation () {
    if (ON_display_field) {
        return;
    }

    if (!ON_relation) {
        document.getElementById('foreign_relation').style.display = '';
        ON_relation = 1;
        document.getElementById('designer_hint').innerHTML = PMA_messages.strSelectReferencedKey;
        document.getElementById('designer_hint').style.display = 'block';
        document.getElementById('rel_button').className = 'M_butt_Selected_down';
    } else {
        document.getElementById('designer_hint').innerHTML = '';
        document.getElementById('designer_hint').style.display = 'none';
        document.getElementById('rel_button').className = 'M_butt';
        click_field = 0;
        ON_relation = 0;
    }
}

// table field
function Click_field (db, T, f, PK) {
    PK = parseInt(PK);
    var argsep = PMA_commonParams.get('arg_separator');
    if (ON_relation) {
        if (!click_field) {
            // .style.display=='none'        .style.display = 'none'
            if (!PK) {
                alert(PMA_messages.strPleaseSelectPrimaryOrUniqueKey);
                return;// 0;
            }// PK
            if (j_tabs[db + '.' + T] !== 1) {
                document.getElementById('foreign_relation').style.display = 'none';
            }
            click_field = 1;
            link_relation = 'DB1=' + db + argsep + 'T1=' + T + argsep + 'F1=' + f;
            document.getElementById('designer_hint').innerHTML = PMA_messages.strSelectForeignKey;
        } else {
            Start_relation(); // hidden hint...
            if (j_tabs[db + '.' + T] !== 1 || !PK) {
                document.getElementById('foreign_relation').style.display = 'none';
            }
            var left = Glob_X - (document.getElementById('layer_new_relation').offsetWidth >> 1);
            document.getElementById('layer_new_relation').style.left = left + 'px';
            var top = Glob_Y - document.getElementById('layer_new_relation').offsetHeight;
            document.getElementById('layer_new_relation').style.top  = top + 'px';
            document.getElementById('layer_new_relation').style.display = 'block';
            link_relation += argsep + 'DB2=' + db + argsep + 'T2=' + T + argsep + 'F2=' + f;
        }
    }

    if (ON_display_field) {
        var fieldNameToSend = decodeURIComponent(f);
        var newDisplayFieldClass = 'tab_field';
        var oldTabField = document.getElementById('id_tr_' + T + '.' + display_field[T]);
        // if is display field
        if (display_field[T] === f) {// The display field is already the one defined, user wants to remove it
            newDisplayFieldClass = 'tab_field';
            delete display_field[T];
            if (oldTabField) {// Clear the style
                // Set display field class on old item
                oldTabField.className = 'tab_field';
            }
            fieldNameToSend = '';
        } else {
            newDisplayFieldClass = 'tab_field_3';
            if (display_field[T]) { // Had a previous one, clear it
                if (oldTabField) {
                    // Set display field class on old item
                    oldTabField.className = 'tab_field';
                }
                delete display_field[T];
            }
            display_field[T] = f;

            var tabField = document.getElementById('id_tr_' + T + '.' + display_field[T]);
            if (tabField) {
                // Set new display field class
                tabField.className = newDisplayFieldClass;
            }
        }
        ON_display_field = 0;
        document.getElementById('designer_hint').innerHTML = '';
        document.getElementById('designer_hint').style.display = 'none';
        document.getElementById('display_field_button').className = 'M_butt';

        var $msgbox = PMA_ajaxShowMessage(PMA_messages.strProcessingRequest);
        $.post('db_designer.php',
            { operation: 'setDisplayField', ajax_request: true, server: server, db: db, table: T, field: fieldNameToSend },
            function (data) {
                if (data.success === false) {
                    PMA_ajaxShowMessage(data.error, false);
                } else {
                    PMA_ajaxRemoveMessage($msgbox);
                    PMA_ajaxShowMessage(PMA_messages.strModificationSaved);
                }
            });
    }
}

function New_relation () {
    document.getElementById('layer_new_relation').style.display = 'none';
    var argsep = PMA_commonParams.get('arg_separator');
    link_relation += argsep + 'server=' + server + argsep + 'db=' + db + argsep + 'db2=p';
    link_relation += argsep + 'on_delete=' + document.getElementById('on_delete').value + argsep + 'on_update=' + document.getElementById('on_update').value;
    link_relation += argsep + 'operation=addNewRelation' + argsep + 'ajax_request=true';

    var $msgbox = PMA_ajaxShowMessage(PMA_messages.strProcessingRequest);
    $.post('db_designer.php', link_relation, function (data) {
        if (data.success === false) {
            PMA_ajaxShowMessage(data.error, false);
        } else {
            PMA_ajaxRemoveMessage($msgbox);
            Load_page(selected_page);
        }
    }); // end $.post()
}

// -------------------------- create tables -------------------------------------

function Start_table_new () {
    PMA_commonParams.set('table', '');
    PMA_commonActions.refreshMain('tbl_create.php');
}

function Start_tab_upd (db, table) {
    PMA_commonParams.set('db', db);
    PMA_commonParams.set('table', table);
    PMA_commonActions.refreshMain('tbl_structure.php');
}
// --------------------------- hide tables --------------------------------------

// max/min all tables
function Small_tab_all (id_this) {
    var icon = id_this.children[0];
    var value_sent = '';

    if (icon.alt === 'v') {
        $('.designer_tab .small_tab,.small_tab2').each(function(index, element) {
            if ($(element).text() === 'v') {
                Small_tab($(element).attr('table_name'), 0);
            }
        });
        icon.alt = '>';
        icon.src = icon.dataset.right;
        value_sent = 'v';
    } else {
        $('.designer_tab .small_tab,.small_tab2').each(function(index, element) {
            if ($(element).text() !== 'v') {
                Small_tab($(element).attr('table_name'), 0);
            }
        });
        icon.alt = 'v';
        icon.src = icon.dataset.down;
        value_sent = '>';
    }
    saveValueInConfig('small_big_all', value_sent);
    $('#key_SB_all').toggleClass('M_butt_Selected_down');
    $('#key_SB_all').toggleClass('M_butt');
    Re_load();
}

// invert max/min all tables
function Small_tab_invert () {
    $('.designer_tab .small_tab,.small_tab2').each(function(index, element) {
        Small_tab($(element).attr('table_name'), 0);
    });
    Re_load();
}

function Relation_lines_invert () {
    show_relation_lines = ! show_relation_lines;
    saveValueInConfig('relation_lines', show_relation_lines);
    $('#relLineInvert').toggleClass('M_butt_Selected_down');
    $('#relLineInvert').toggleClass('M_butt');
    Re_load();
}

function Small_tab_refresh () {
    for (var key in j_tabs) {
        if (document.getElementById('id_hide_tbody_' + key).innerHTML !== 'v') {
            Small_tab(key, 0);
        }
    }
}

function Small_tab (t, re_load) {
    var id      = document.getElementById('id_tbody_' + t);
    var id_this = document.getElementById('id_hide_tbody_' + t);
    var id_t    = document.getElementById(t);
    if (id_this.innerHTML === 'v') {
        // ---CROSS
        id.style.display = 'none';
        id_this.innerHTML = '>';
    } else {
        id.style.display = '';
        id_this.innerHTML = 'v';
    }
    if (re_load) {
        Re_load();
    }
}
// ------------------------------------------------------------------------------
function Select_tab (t) {
    var id_zag = document.getElementById('id_zag_' + t);
    if (id_zag.className !== 'tab_zag_3') {
        document.getElementById('id_zag_' + t).className = 'tab_zag_2';
    } else {
        document.getElementById('id_zag_' + t).className = 'tab_zag';
    }
    // ----------
    var id_t = document.getElementById(t);
    window.scrollTo(parseInt(id_t.style.left, 10) - 300, parseInt(id_t.style.top, 10) - 300);
    setTimeout(
        function () {
            document.getElementById('id_zag_' + t).className = 'tab_zag';
        },
        800
    );
}
// ------------------------------------------------------------------------------

function Canvas_click (id, event) {
    var n = 0;
    var relation_name = 0;
    var selected = 0;
    var a = [];
    var Key0;
    var Key1;
    var Key2;
    var Key3;
    var Key;
    var x1;
    var x2;
    var K;
    var key;
    var key2;
    var key3;
    var Local_X = isIE ? event.clientX + document.body.scrollLeft : event.pageX;
    var Local_Y = isIE ? event.clientY + document.body.scrollTop : event.pageY;
    Local_X -= $('#osn_tab').offset().left;
    Local_Y -= $('#osn_tab').offset().top;
    Clear();
    for (K in contr) {
        for (key in contr[K]) {
            for (key2 in contr[K][key]) {
                for (key3 in contr[K][key][key2]) {
                    if (! document.getElementById('check_vis_' + key2).checked ||
                        ! document.getElementById('check_vis_' + contr[K][key][key2][key3][0]).checked) {
                        continue; // if hide
                    }
                    var x1_left  = document.getElementById(key2).offsetLeft + 1;// document.getElementById(key2+"."+key3).offsetLeft;
                    var x1_right = x1_left + document.getElementById(key2).offsetWidth;
                    var x2_left  = document.getElementById(contr[K][key][key2][key3][0]).offsetLeft;// +document.getElementById(contr[K][key2][key3][0]+"."+contr[K][key2][key3][1]).offsetLeft
                    var x2_right = x2_left + document.getElementById(contr[K][key][key2][key3][0]).offsetWidth;
                    a[0] = Math.abs(x1_left - x2_left);
                    a[1] = Math.abs(x1_left - x2_right);
                    a[2] = Math.abs(x1_right - x2_left);
                    a[3] = Math.abs(x1_right - x2_right);
                    n = s_left = s_right = 0;
                    for (var i = 1; i < 4; i++) {
                        if (a[n] > a[i]) {
                            n = i;
                        }
                    }
                    if (n === 1) {
                        x1 = x1_left - sm_s;
                        x2 = x2_right + sm_s;
                        if (x1 < x2) {
                            n = 0;
                        }
                    }
                    if (n === 2) {
                        x1 = x1_right + sm_s;
                        x2 = x2_left - sm_s;
                        if (x1 > x2) {
                            n = 0;
                        }
                    }
                    if (n === 3) {
                        x1 = x1_right + sm_s;
                        x2 = x2_right + sm_s;
                        s_right = 1;
                    }
                    if (n === 0) {
                        x1 = x1_left - sm_s;
                        x2 = x2_left - sm_s;
                        s_left    = 1;
                    }

                    var y1 = document.getElementById(key2).offsetTop + document.getElementById(key2 + '.' + key3).offsetTop + height_field;
                    var y2 = document.getElementById(contr[K][key][key2][key3][0]).offsetTop +
                                     document.getElementById(contr[K][key][key2][key3][0] + '.' + contr[K][key][key2][key3][1]).offsetTop + height_field;

                    if (!selected && Local_X > x1 - 10 && Local_X < x1 + 10 && Local_Y > y1 - 7 && Local_Y < y1 + 7) {
                        Line0(
                            x1 + osn_tab.offsetLeft,
                            y1 - osn_tab.offsetTop,
                            x2 + osn_tab.offsetLeft,
                            y2 - osn_tab.offsetTop,
                            'rgba(255,0,0,1)');

                        selected = 1; // Rect(x1-sm_x,y1-sm_y,10,10,"rgba(0,255,0,1)");
                        relation_name = key; //
                        Key0 = contr[K][key][key2][key3][0];
                        Key1 = contr[K][key][key2][key3][1];
                        Key2 = key2;
                        Key3 = key3;
                        Key = K;
                    } else {
                        Line0(
                            x1 + osn_tab.offsetLeft,
                            y1 - osn_tab.offsetTop,
                            x2 + osn_tab.offsetLeft,
                            y2 - osn_tab.offsetTop,
                            getColorByTarget(contr[K][key][key2][key3][0] + '.' + contr[K][key][key2][key3][1])
                        );
                    }
                }
            }
        }
    }
    if (selected) {
        // select relations
        var left = Glob_X - (document.getElementById('layer_upd_relation').offsetWidth >> 1);
        document.getElementById('layer_upd_relation').style.left = left + 'px';
        var top = Glob_Y - document.getElementById('layer_upd_relation').offsetHeight - 10;
        document.getElementById('layer_upd_relation').style.top = top + 'px';
        document.getElementById('layer_upd_relation').style.display = 'block';
        var argsep = PMA_commonParams.get('arg_separator');
        link_relation = 'T1=' + Key0 + argsep + 'F1=' + Key1 + argsep + 'T2=' + Key2 + argsep + 'F2=' + Key3 + argsep + 'K=' + Key;
    }
}

function Upd_relation () {
    document.getElementById('layer_upd_relation').style.display = 'none';
    var argsep = PMA_commonParams.get('arg_separator');
    link_relation += argsep + 'server=' + server + argsep + 'db=' + db;
    link_relation += argsep + 'operation=removeRelation' + argsep + 'ajax_request=true';

    var $msgbox = PMA_ajaxShowMessage(PMA_messages.strProcessingRequest);
    $.post('db_designer.php', link_relation, function (data) {
        if (data.success === false) {
            PMA_ajaxShowMessage(data.error, false);
        } else {
            PMA_ajaxRemoveMessage($msgbox);
            Load_page(selected_page);
        }
    }); // end $.post()
}

function VisibleTab (id, t_n) {
    if (id.checked) {
        document.getElementById(t_n).style.display = 'block';
    } else {
        document.getElementById(t_n).style.display = 'none';
    }
    Re_load();
}

// max/min all tables
function Hide_tab_all (id_this) {
    if (id_this.alt === 'v') {
        id_this.alt = '>';
        id_this.src = id_this.dataset.right;
    } else {
        id_this.alt = 'v';
        id_this.src = id_this.dataset.down;
    }
    var E = document.getElementById('container-form');
    for (var i = 0; i < E.elements.length; i++) {
        if (E.elements[i].type === 'checkbox' && E.elements[i].id.substring(0, 10) === 'check_vis_') {
            if (id_this.alt === 'v') {
                E.elements[i].checked = true;
                document.getElementById(E.elements[i].value).style.display = '';
            } else {
                E.elements[i].checked = false;
                document.getElementById(E.elements[i].value).style.display = 'none';
            }
        }
    }
    Re_load();
}

function in_array_k (x, m) {
    var b = 0;
    for (var u in m) {
        if (x === u) {
            b = 1;
            break;
        }
    }
    return b;
}

function No_have_constr (id_this) {
    var a = [];
    var K;
    var key;
    var key2;
    var key3;
    for (K in contr) {
        for (key in contr[K]) {
            // contr name
            for (key2 in contr[K][key]) {
                // table name
                for (key3 in contr[K][key][key2]) {
                    // field name
                    a[key2] = a[contr[K][key][key2][key3][0]] = 1; // exist constr
                }
            }
        }
    }

    if (id_this.alt === 'v') {
        id_this.alt = '>';
        id_this.src = id_this.dataset.right;
    } else {
        id_this.alt = 'v';
        id_this.src = id_this.dataset.down;
    }
    var E = document.getElementById('container-form');
    for (var i = 0; i < E.elements.length; i++) {
        if (E.elements[i].type === 'checkbox' && E.elements[i].id.substring(0, 10) === 'check_vis_') {
            if (!in_array_k(E.elements[i].value, a)) {
                if (id_this.alt === 'v') {
                    E.elements[i].checked = true;
                    document.getElementById(E.elements[i].value).style.display = '';
                } else {
                    E.elements[i].checked = false;
                    document.getElementById(E.elements[i].value).style.display = 'none';
                }
            }
        }
    }
}

function General_scroll () {
    /*
    if (!document.getElementById('show_relation_olways').checked) {
        document.getElementById("canvas").style.display = 'none';
        clearTimeout(timeoutID);
        timeoutID = setTimeout(General_scroll_end, 500);
    }
    */
    // if (timeoutID)
    clearTimeout(timeoutID);
    timeoutID = setTimeout(
        function () {
            document.getElementById('top_menu').style.left = document.body.scrollLeft + 'px';
            document.getElementById('top_menu').style.top  = document.body.scrollTop + 'px';
        },
        200
    );
}

// max/min all tables
function Show_left_menu (id_this) {
    var icon = id_this.children[0];
    $('#key_Show_left_menu').toggleClass('M_butt_Selected_down');
    if (icon.alt === 'v') {
        document.getElementById('layer_menu').style.top = '0px';
        document.getElementById('layer_menu').style.display = 'block';
        icon.alt = '>';
        icon.src = icon.dataset.up;
        if (isIE) {
            General_scroll();
        }
    } else {
        document.getElementById('layer_menu').style.top = -1000 + 'px'; // fast scroll
        document.getElementById('layer_menu').style.display = 'none';
        icon.alt = 'v';
        icon.src = icon.dataset.down;
    }
}
// ------------------------------------------------------------------------------
function Side_menu_right (id_this) {
    $('#side_menu').toggleClass('right');
    $('#layer_menu').toggleClass('left');
    var icon = $(id_this.childNodes[0]);
    var current = icon.attr('src');
    icon.attr('src', icon.attr('data-right')).attr('data-right', current);

    icon = $(document.getElementById('layer_menu_sizer').childNodes[0])
        .toggleClass('floatleft')
        .toggleClass('floatright')
        .children();
    current = icon.attr('src');
    icon.attr('src', icon.attr('data-right'));
    icon.attr('data-right', current);
    menu_moved = !menu_moved;
    saveValueInConfig('side_menu', $('#side_menu').hasClass('right'));
    $('#key_Left_Right').toggleClass('M_butt_Selected_down');
    $('#key_Left_Right').toggleClass('M_butt');
}
// ------------------------------------------------------------------------------
function Show_text () {
    $('#side_menu').find('.hidable').show();
}
function Hide_text () {
    if (!always_show_text) {
        $('#side_menu').find('.hidable').hide();
    }
}
function Pin_text () {
    always_show_text = !always_show_text;
    $('#pin_Text').toggleClass('M_butt_Selected_down');
    $('#pin_Text').toggleClass('M_butt');
    saveValueInConfig('pin_text', always_show_text);
}
// ------------------------------------------------------------------------------
function Start_display_field () {
    if (ON_relation) {
        return;
    }
    if (!ON_display_field) {
        ON_display_field = 1;
        document.getElementById('designer_hint').innerHTML = PMA_messages.strChangeDisplay;
        document.getElementById('designer_hint').style.display = 'block';
        document.getElementById('display_field_button').className = 'M_butt_Selected_down';// '#FFEE99';gray #AAAAAA

        if (isIE) { // correct for IE
            document.getElementById('display_field_button').className = 'M_butt_Selected_down_IE';
        }
    } else {
        document.getElementById('designer_hint').innerHTML = '';
        document.getElementById('designer_hint').style.display = 'none';
        document.getElementById('display_field_button').className = 'M_butt';
        ON_display_field = 0;
    }
}
// ------------------------------------------------------------------------------
var TargetColors = [];
function getColorByTarget (target) {
    var color = '';  // "rgba(0,100,150,1)";

    for (var a in TargetColors) {
        if (TargetColors[a][0] === target) {
            color = TargetColors[a][1];
            break;
        }
    }

    if (color.length === 0) {
        var i = TargetColors.length + 1;
        var d = i % 6;
        var j = (i - d) / 6;
        j = j % 4;
        j++;
        var color_case = [
            [1, 0, 0],
            [0, 1, 0],
            [0, 0, 1],
            [1, 1, 0],
            [1, 0, 1],
            [0, 1, 1]
        ];
        var a = color_case[d][0];
        var b = color_case[d][1];
        var c = color_case[d][2];
        var e = (1 - (j - 1) / 6);

        var r = Math.round(a * 200 * e);
        var g = Math.round(b * 200 * e);
        b = Math.round(c * 200 * e);
        color = 'rgba(' + r + ',' + g + ',' + b + ',1)';

        TargetColors.push([target, color]);
    }

    return color;
}

function Click_option (dbName, tableName, columnName, tableDbNameUrl, optionColNameString) {
    var designerOptions = document.getElementById('designer_optionse');
    var left = Glob_X - (designerOptions.offsetWidth >> 1);
    designerOptions.style.left = left + 'px';
    // var top = Glob_Y - designerOptions.offsetHeight - 10;
    designerOptions.style.top  = (screen.height / 4) + 'px';
    designerOptions.style.display = 'block';
    document.getElementById('ok_add_object_db_and_table_name_url').value = tableDbNameUrl;
    document.getElementById('ok_add_object_db_name').value = dbName;
    document.getElementById('ok_add_object_table_name').value = tableName;
    document.getElementById('ok_add_object_col_name').value = columnName;
    document.getElementById('option_col_name').innerHTML = optionColNameString;
}

function Close_option () {
    document.getElementById('designer_optionse').style.display = 'none';
    document.getElementById('rel_opt').value = '--';
    document.getElementById('Query').value = '';
    document.getElementById('new_name').value = '';
    document.getElementById('operator').value = '---';
    document.getElementById('groupby').checked = false;
    document.getElementById('h_rel_opt').value = '--';
    document.getElementById('h_operator').value = '---';
    document.getElementById('having').value = '';
    document.getElementById('orderby').value = '---';

}

function Select_all (tableName, dbName, idSelectAll) {
    var parentIsChecked = $('#' + idSelectAll).is(':checked');
    var checkboxAll = $('#container-form input[id_check_all=\'' + idSelectAll + '\']:checkbox');

    checkboxAll.each(function () {
        // already checked and then check parent
        if (parentIsChecked === true && this.checked) {
            // was checked, removing column from selected fields
            // trigger unchecked event
            this.click();
        }
        this.checked = parentIsChecked;
        this.disabled = parentIsChecked;
    });
    if (parentIsChecked) {
        select_field.push('`' + tableName + '`.*');
        from_array.push(tableName);
    } else {
        var i;
        for (i = 0; i < select_field.length; i++) {
            if (select_field[i] === ('`' + tableName + '`.*')) {
                select_field.splice(i, 1);
            }
        }
        var k;
        for (k = 0; k < from_array.length; k++) {
            if (from_array[k] === tableName) {
                from_array.splice(k, 1);
                break;
            }
        }
    }
    Re_load();
}

function Table_onover (id_this, val, buil) {
    buil = parseInt(buil);
    if (!val) {
        document.getElementById('id_zag_' + id_this).className = 'tab_zag_2';
        if (buil) {
            document.getElementById('id_zag_' + id_this + '_2').className = 'tab_zag_2';
        }
    } else {
        document.getElementById('id_zag_' + id_this).className = 'tab_zag';
        if (buil) {
            document.getElementById('id_zag_' + id_this + '_2').className = 'tab_zag';
        }
    }
}

/* This function stores selected column information in select_field[]
 * In case column is checked it add else it deletes
 *
 */
function store_column (tableName, colName, checkboxId) {
    var i;
    var k;
    var selectKeyField = '`' + tableName + '`.`' + colName + '`';
    if (document.getElementById(checkboxId).checked === true) {
        select_field.push(selectKeyField);
        from_array.push(tableName);
    } else {
        for (i = 0; i < select_field.length; i++) {
            if (select_field[i] === selectKeyField) {
                select_field.splice(i, 1);
                break;
            }
        }
        for (k = 0; k < from_array.length; k++) {
            if (from_array[k] === tableName) {
                from_array.splice(k, 1);
                break;
            }
        }
    }
}

/**
 * This function builds object and adds them to history_array
 * first it does a few checks on each object, then makes an object(where,rename,groupby,aggregate,orderby)
 * then a new history object is made and finally all these history objects are added to history_array[]
 *
**/

function add_object (dbName, tableName, colName, dbTableNameUrl) {
    var p;
    var where_obj;
    var rel = document.getElementById('rel_opt');
    var sum = 0;
    var init = history_array.length;
    if (rel.value !== '--') {
        if (document.getElementById('Query').value === '') {
            PMA_ajaxShowMessage(PMA_sprintf(PMA_messages.strQueryEmpty));
            return;
        }
        p = document.getElementById('Query');
        where_obj = new where(rel.value, p.value);// make where object
        history_array.push(new history_obj(colName, where_obj, tableName, h_tabs[dbTableNameUrl], 'Where'));
        sum = sum + 1;
    }
    if (document.getElementById('new_name').value !== '') {
        var rename_obj = new rename(document.getElementById('new_name').value);// make Rename object
        history_array.push(new history_obj(colName, rename_obj, tableName, h_tabs[dbTableNameUrl], 'Rename'));
        sum = sum + 1;
    }
    if (document.getElementById('operator').value !== '---') {
        var aggregate_obj = new aggregate(document.getElementById('operator').value);
        history_array.push(new history_obj(colName, aggregate_obj, tableName, h_tabs[dbTableNameUrl], 'Aggregate'));
        sum = sum + 1;
        // make aggregate operator
    }
    if (document.getElementById('groupby').checked === true) {
        history_array.push(new history_obj(colName, 'GroupBy', tableName, h_tabs[dbTableNameUrl], 'GroupBy'));
        sum = sum + 1;
        // make groupby
    }
    if (document.getElementById('h_rel_opt').value !== '--') {
        if (document.getElementById('having').value === '') {
            return;
        }
        where_obj = new having(
            document.getElementById('h_rel_opt').value,
            document.getElementById('having').value,
            document.getElementById('h_operator').value
        );// make where object
        history_array.push(new history_obj(col_name, where_obj, tableName, h_tabs[dbTableNameUrl], 'Having'));
        sum = sum + 1;
        // make having
    }
    if (document.getElementById('orderby').value !== '---') {
        var oderby_obj = new orderby(document.getElementById('orderby').value);
        history_array.push(new history_obj(col_name, oderby_obj, tableName, h_tabs[dbTableNameUrl], 'OrderBy'));
        sum = sum + 1;
        // make orderby
    }
    PMA_ajaxShowMessage(PMA_sprintf(PMA_messages.strObjectsCreated, sum));
    // output sum new objects created
    var existingDiv = document.getElementById('ab');
    existingDiv.innerHTML = display(init, history_array.length);
    Close_option();
    $('#ab').accordion('refresh');
}

function enablePageContentEvents() {
    $('#page_content').off('mousedown', MouseDown);
    $('#page_content').off('mouseup', MouseUp);
    $('#page_content').off('mousemove', MouseMove);
    $('#page_content').on('mousedown', MouseDown);
    $('#page_content').on('mouseup', MouseUp);
    $('#page_content').on('mousemove', MouseMove);
}

/**
 * This function enables the events on table items.
 * It helps to enable them on page loading and when a table is added on the fly.
 */
function enableTableEvents(index, element) {
    $(element).on('click', '.select_all_1', function () {
        Select_all($(this).attr('table_name'), $(this).attr('db_name'), $(this).attr('id'));
    });
    $(element).on('click', '.small_tab,.small_tab2', function () {
        Small_tab($(this).attr('table_name'), 1);
    });
    $(element).on('click', '.small_tab_pref_1', function () {
        Start_tab_upd($(this).attr('db_url'), $(this).attr('table_name_url'));
    });
    $(element).on('click', '.select_all_store_col', function () {
        store_column($(this).attr('table_name'), $(this).attr('col_name'), $(this).attr('id'));
    });
    $(element).on('click', '.small_tab_pref_click_opt', function () {
        Click_option(
            $(this).attr('db_name'),
            $(this).attr('table_name'),
            $(this).attr('col_name'),
            $(this).attr('db_table_name_url'),
            $(this).attr('option_col_name_modal')
        );
    });
    $(element).on('click', '.tab_field_2,.tab_field_3,.tab_field', function () {
        var params = ($(this).attr('click_field_param')).split(',');
        Click_field(params[3], params[0], params[1], params[2]);
    });

    $(element).find('.tab_zag_noquery').mouseover(function () {
        Table_onover($(this).attr('table_name'),0, $(this).attr('query_set'));
    });
    $(element).find('.tab_zag_noquery').mouseout(function () {
        Table_onover($(this).attr('table_name'),1, $(this).attr('query_set'));
    });
    $(element).find('.tab_zag_query').mouseover(function () {
        Table_onover($(this).attr('table_name'),0, 1);
    });
    $(element).find('.tab_zag_query').mouseout(function () {
        Table_onover($(this).attr('table_name'),1, 1);
    });

    enablePageContentEvents();
}

AJAX.registerTeardown('designer/move.js', function () {
    $('#side_menu').off('mouseenter mouseleave');
    $('#key_Show_left_menu').off('click');
    $('#toggleFullscreen').off('click');
    $('#newPage').off('click');
    $('#editPage').off('click');
    $('#savePos').off('click');
    $('#SaveAs').off('click');
    $('#delPages').off('click');
    $('#StartTableNew').off('click');
    $('#rel_button').off('click');
    $('#StartTableNew').off('click');
    $('#display_field_button').off('click');
    $('#reloadPage').off('click');
    $('#angular_direct_button').off('click');
    $('#grid_button').off('click');
    $('#key_SB_all').off('click');
    $('#SmallTabInvert').off('click');
    $('#relLineInvert').off('click');
    $('#exportPages').off('click');
    $('#query_builder').off('click');
    $('#key_Left_Right').off('click');
    $('#pin_Text').off('click');
    $('#canvas').off('click');
    $('#key_HS_all').off('click');
    $('#key_HS').off('click');
    $('.scroll_tab_struct').off('click');
    $('.scroll_tab_checkbox').off('click');
    $('#id_scroll_tab').find('tr').off('click', '.designer_Tabs2,.designer_Tabs');
    $('.designer_tab').off('click', '.select_all_1');
    $('.designer_tab').off('click', '.small_tab,.small_tab2');
    $('.designer_tab').off('click', '.small_tab_pref_1');
    $('.tab_zag_noquery').off('mouseover');
    $('.tab_zag_noquery').off('mouseout');
    $('.tab_zag_query').off('mouseover');
    $('.tab_zag_query').off('mouseout');
    $('.designer_tab').off('click','.tab_field_2,.tab_field_3,.tab_field');
    $('.designer_tab').off('click', '.select_all_store_col');
    $('.designer_tab').off('click', '.small_tab_pref_click_opt');
    $('#del_button').off('click');
    $('#cancel_button').off('click');
    $('#ok_add_object').off('click');
    $('#cancel_close_option').off('click');
    $('#ok_new_rel_panel').off('click');
    $('#cancel_new_rel_panel').off('click');
    $('#page_content').off('mouseup');
    $('#page_content').off('mousedown');
    $('#page_content').off('mousemove');
});

AJAX.registerOnload('designer/move.js', function () {
    $('#key_Show_left_menu').click(function () {
        Show_left_menu(this);
        return false;
    });
    $('#toggleFullscreen').click(function () {
        Toggle_fullscreen();
        return false;
    });
    $('#addOtherDbTables').click(function () {
        Add_Other_db_tables();
        return false;
    });
    $('#newPage').click(function () {
        New();
        return false;
    });
    $('#editPage').click(function () {
        Edit_pages();
        return false;
    });
    $('#savePos').click(function () {
        Save3();
        return false;
    });
    $('#SaveAs').click(function () {
        Save_as();
        return false;
    });
    $('#delPages').click(function () {
        Delete_pages();
        return false;
    });
    $('#StartTableNew').click(function () {
        Start_table_new();
        return false;
    });
    $('#rel_button').click(function () {
        Start_relation();
        return false;
    });
    $('#display_field_button').click(function () {
        Start_display_field();
        return false;
    });
    $('#reloadPage').click(function () {
        Load_page(selected_page);
    });
    $('#angular_direct_button').click(function () {
        Angular_direct();
        return false;
    });
    $('#grid_button').click(function () {
        Grid();
        return false;
    });
    $('#key_SB_all').click(function () {
        Small_tab_all(this);
        return false;
    });
    $('#SmallTabInvert').click(function () {
        Small_tab_invert();
        return false;
    });
    $('#relLineInvert').click(function () {
        Relation_lines_invert();
        return false;
    });
    $('#exportPages').click(function () {
        Export_pages();
        return false;
    });
    $('#query_builder').click(function () {
        build_query('SQL Query on Database', 0);
    });
    $('#key_Left_Right').click(function () {
        Side_menu_right(this);
        return false;
    });
    $('#side_menu').hover(function () {
        Show_text();
        return false;
    }, function () {
        Hide_text();
        return false;
    });
    $('#pin_Text').click(function () {
        Pin_text(this);
        return false;
    });
    $('#canvas').click(function (event) {
        Canvas_click(this, event);
    });
    $('#key_HS_all').click(function () {
        Hide_tab_all(this);
        return false;
    });
    $('#key_HS').click(function () {
        No_have_constr(this);
        return false;
    });

    $('.designer_tab').each(enableTableEvents);
    $('.designer_tab').each(addTableToTablesList);

    $('input#del_button').click(function () {
        Upd_relation();
    });
    $('input#cancel_button').click(function () {
        document.getElementById('layer_upd_relation').style.display = 'none';
        Re_load();
    });
    $('input#ok_add_object').click(function () {
        add_object(
            $('#ok_add_object_db_name').val(),
            $('#ok_add_object_table_name').val(),
            $('#ok_add_object_col_name').val(),
            $('#ok_add_object_db_and_table_name_url').val()
        );
    });
    $('input#cancel_close_option').click(function () {
        Close_option();
    });
    $('input#ok_new_rel_panel').click(function () {
        New_relation();
    });
    $('input#cancel_new_rel_panel').click(function () {
        document.getElementById('layer_new_relation').style.display = 'none';
    });
    enablePageContentEvents();
});
