<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * Common styles for the pmahomme theme
 *
 * @package    PhpMyAdmin-theme
 * @subpackage PMAHomme
 */

// unplanned execution path
if (! defined('PHPMYADMIN') && ! defined('TESTSUITE')) {
    exit();
}
?>
/******************************************************************************/
/* general tags */
html {
    font-size: <?php echo $theme->getFontSize(); ?>
}

input,
select,
textarea {
    font-size: 1em;
}


body {
<?php if (! empty($GLOBALS['cfg']['FontFamily'])) : ?>
    font-family: <?php echo $GLOBALS['cfg']['FontFamily']; ?>;
<?php endif; ?>
    padding: 0;
    margin: 0;
    margin-<?php echo $left; ?>: 240px;
    color: <?php echo $GLOBALS['cfg']['MainColor']; ?>;
    background: <?php echo $GLOBALS['cfg']['MainBackground']; ?>;
}

body#loginform {
    margin: 0;
}

#page_content {
    margin: 0 .5em;
}

.desktop50 {
    width: 50%;
}

.all100 {
    width: 100%;
}

.all85{
    width: 85%;
}

.auth_config_tbl{
    margin: 0 auto;
}

<?php if (! empty($GLOBALS['cfg']['FontFamilyFixed'])) : ?>
    textarea,
    tt,
    pre,
    code {
    font-family: <?php echo $GLOBALS['cfg']['FontFamilyFixed']; ?>;
    }
<?php endif; ?>


h1 {
    font-size: 140%;
    font-weight: bold;
}

h2 {
    font-size: 2em;
    font-weight: normal;
    text-shadow: 0 1px 0 #fff;
    padding: 10px 0 10px;
    padding-<?php echo $left; ?>: 3px;
    color: #777;
}

/* Hiding icons in the page titles */
h2 img {
    display: none;
}

h2 a img {
    display: inline;
}

.data,
.data_full_width {
    margin: 0 0 12px;
}

.data_full_width {
    width: 100%;
}

h3 {
    font-weight: bold;
}

a,
a:link,
a:visited,
a:active,
button.mult_submit,
.checkall_box+label {
    text-decoration: none;
    color: #235a81;
    cursor: pointer;
    outline: none;

}

a:hover,
button.mult_submit:hover,
button.mult_submit:focus,
.checkall_box+label:hover {
    text-decoration: underline;
    color: #235a81;
}

#initials_table {
    background: #f3f3f3;
    border: 1px solid #aaa;
    margin-bottom: 10px;
    -moz-border-radius: 5px;
    -webkit-border-radius: 5px;
    border-radius: 5px;
}

#initials_table td {
    padding: 8px !important;
}

#initials_table a {
    border: 1px solid #aaa;
    background: #fff;
    padding: 4px 8px;
    -moz-border-radius: 5px;
    -webkit-border-radius: 5px;
    border-radius: 5px;
    <?php echo $theme->getCssGradient('ffffff', 'e0e0e0'); ?>
}

#initials_table a.active {
    border: 1px solid #666;
    box-shadow: 0 0 2px #999;
    <?php echo $theme->getCssGradient('bbbbbb', 'ffffff'); ?>
}

dfn {
    font-style: normal;
}

dfn:hover {
    font-style: normal;
    cursor: help;
}

th {
    font-weight: bold;
    color: <?php echo $GLOBALS['cfg']['ThColor']; ?>;
    background: #f3f3f3;
    <?php echo $theme->getCssGradient('ffffff', 'cccccc'); ?>
}

a img {
    border: 0;
}

hr {
    color: <?php echo $GLOBALS['cfg']['MainColor']; ?>;
    background-color: <?php echo $GLOBALS['cfg']['MainColor']; ?>;
    border: 0;
    height: 1px;
}

form {
    padding: 0;
    margin: 0;
    display: inline;
}


input,
select {
    /* Fix outline in Chrome: */
    outline: none;
}

input[type=text],
input[type=password],
input[type=number],
input[type=date] {
    border-radius: 2px;
    -moz-border-radius: 2px;
    -webkit-border-radius: 2px;


    background: white;
    border: 1px solid #aaa;
    color: #555;
    padding: 4px;
}

input[type=text],
input[type=password],
input[type=number],
input[type=date],
input[type=checkbox],
select {
    margin: 6px;
}

input[type=number] {
    width: 50px;
}

input[type=text],
input[type=password],
input[type=number],
input[type=date],
select {
    transition: all 0.2s;
    -ms-transition: all 0.2s;
    -webkit-transition: all 0.2s;
    -moz-transition: all 0.2s;
}

input[type=text][disabled],
input[type=text][disabled]:hover,
input[type=password][disabled],
input[type=password][disabled]:hover,
input[type=number][disabled],
input[type=number][disabled]:hover,
input[type=date][disabled],
input[type=date][disabled]:hover,
select[disabled],
select[disabled]:hover {
    background: #e8e8e8;
    box-shadow: none;
    -webkit-box-shadow: none;
    -moz-box-shadow: none;
}

input[type=text]:hover,
input[type=text]:focus,
input[type=password]:hover,
input[type=password]:focus,
input[type=number]:hover,
input[type=number]:focus,
input[type=date]:hover,
input[type=date]:focus,
select:focus {
    border: 1px solid #7c7c7c;
    background: #fff;
}

input[type=text]:hover,
input[type=password]:hover,
input[type=number]:hover,
input[type=date]:hover {
    box-shadow: 0 1px 3px #aaa;
    -webkit-box-shadow: 0 1px 3px #aaa;
    -moz-box-shadow: 0 1px 3px #aaa;
}

input[type=submit],
input[type=button],
button[type=submit]:not(.mult_submit) {
    font-weight: bold !important;
}

input[type=submit],
input[type=button],
button[type=submit]:not(.mult_submit),
input[type=reset],
input[name=submit_reset],
input.button {
    margin: 6px 14px;
    border: 1px solid #aaa;
    padding: 3px 7px;
    color: #111;
    text-decoration: none;
    background: #ddd;

    border-radius: 12px;
    -webkit-border-radius: 12px;
    -moz-border-radius: 12px;

    text-shadow: 0 1px 0 #fff;

    <?php echo $theme->getCssGradient('f8f8f8', 'd8d8d8'); ?>
}

input[type=submit]:hover,
input[type=button]:hover,
button[type=submit]:not(.mult_submit):hover,
input[type=reset]:hover,
input[name=submit_reset]:hover,
input.button:hover {
    position: relative;
    <?php echo $theme->getCssGradient('fff', 'ddd'); ?>
    cursor: pointer;
}

input[type=submit]:active,
input[type=button]:active,
button[type=submit]:not(.mult_submit):active,
input[type=reset]:active,
input[name=submit_reset]:active,
input.button:active {
    position: relative;
    <?php echo $theme->getCssGradient('eee', 'ddd'); ?>
    box-shadow: 0 1px 6px -2px #333 inset;
    text-shadow: none;
}

input[type=submit]:disabled,
input[type=button]:disabled,
button[type=submit]:not(.mult_submit):disabled,
input[type=reset]:disabled,
input[name=submit_reset]:disabled,
input.button:disabled {
    background: #ccc;
    color: #666;
    text-shadow: none;
}

textarea {
    overflow: visible;
    margin: 6px;
}

textarea.char {
    margin: 6px;
}

fieldset, .preview_sql {
    margin-top: 1em;
    border-radius: 4px 4px 0 0;
    -moz-border-radius: 4px 4px 0 0;
    -webkit-border-radius: 4px 4px 0 0;
    border: #aaa solid 1px;
    padding: 0.5em;
    background: #eee;
    text-shadow: <?php echo $GLOBALS['text_dir'] === 'rtl' ? '-' : ''; ?>1px 1px 2px #fff inset;
    -moz-box-shadow: <?php echo $GLOBALS['text_dir'] === 'rtl' ? '-' : ''; ?>1px 1px 2px #fff inset;
    -webkit-box-shadow: <?php echo $GLOBALS['text_dir'] === 'rtl' ? '-' : ''; ?>1px 1px 2px #fff inset;
    box-shadow: <?php echo $GLOBALS['text_dir'] === 'rtl' ? '-' : ''; ?>1px 1px 2px #fff inset;
}

fieldset fieldset {
    margin: .8em;
    background: #fff;
    border: 1px solid #aaa;
    background: #E8E8E8;

}

fieldset legend {
    font-weight: bold;
    color: #444;
    padding: 5px 10px;
    border-radius: 2px;
    -moz-border-radius: 2px;
    -webkit-border-radius: 2px;
    border: 1px solid #aaa;
    background-color: #fff;
    -moz-box-shadow: <?php echo $GLOBALS['text_dir'] === 'rtl' ? '-' : ''; ?>3px 3px 15px #bbb;
    -webkit-box-shadow: <?php echo $GLOBALS['text_dir'] === 'rtl' ? '-' : ''; ?>3px 3px 15px #bbb;
    box-shadow: <?php echo $GLOBALS['text_dir'] === 'rtl' ? '-' : ''; ?>3px 3px 15px #bbb;
    max-width: 100%;
}

.some-margin {
    margin: 1.5em;
}

/* buttons in some browsers (eg. Konqueror) are block elements, this breaks design */
button {
    display: inline;
}

table caption,
table th,
table td {
    padding: .1em .3em;
    margin: .1em;
    vertical-align: middle;
    text-shadow: 0 1px 0 #fff;
}

/* 3.4 */
.datatable{
    table-layout: fixed;
}

table {
    border-collapse: collapse;
}

thead th {
    border-right: 1px solid #fff;
}

th {
    text-align: left;
}


img,
button {
    vertical-align: middle;
}

input[type="checkbox"],
input[type="radio"] {
    vertical-align: -11%;
}


select {
    -moz-border-radius: 2px;
    -webkit-border-radius: 2px;
    border-radius: 2px;

    border: 1px solid #bbb;
    color: #333;
    padding: 3px;
    background: white;
    margin:6px;
}

select[multiple] {
    <?php echo $theme->getCssGradient('ffffff', 'f2f2f2'); ?>
}

/******************************************************************************/
/* classes */
.clearfloat {
    clear: both;
}

.floatleft {
    float: <?php echo $left; ?>;
    margin-<?php echo $right; ?>: 1em;
}

.floatright {
    float: <?php echo $right; ?>;
}

.center {
    text-align: center;
}

.displayblock {
    display: block;
}

table.nospacing {
    border-spacing: 0;
}

table.nopadding tr th, table.nopadding tr td {
    padding: 0;
}

th.left, td.left {
    text-align: left;
}

th.center, td.center {
    text-align: center;
}

th.right, td.right {
    text-align: right;
    padding-right: 1em;
}

tr.vtop th, tr.vtop td, th.vtop, td.vtop {
    vertical-align: top;
}

tr.vmiddle th, tr.vmiddle td, th.vmiddle, td.vmiddle {
    vertical-align: middle;
}

tr.vbottom th, tr.vbottom td, th.vbottom, td.vbottom {
    vertical-align: bottom;
}

.paddingtop {
    padding-top: 1em;
}

.separator {
    color: #fff;
    text-shadow: 0 1px 0 #000;
}

div.tools {
    /* border: 1px solid #000; */
    padding: .2em;
}

div.tools a {
    color: #3a7ead !important;
}

div.tools,
fieldset.tblFooters {
    margin-top: 0;
    margin-bottom: .5em;
    /* avoid a thick line since this should be used under another fieldset */
    border-top: 0;
    text-align: <?php echo $right; ?>;
    float: none;
    clear: both;
    -webkit-border-radius: 0 0 4px 4px;
    -moz-border-radius: 0 0 4px 4px;
    border-radius: 0 0 4px 5px;
}

div.null_div {
    height: 20px;
    text-align: center;
    font-style: normal;
    min-width: 50px;
}

fieldset .formelement {
    float: <?php echo $left; ?>;
    margin-<?php echo $right; ?>: .5em;
    /* IE */
    white-space: nowrap;
}

/* revert for Gecko */
fieldset div[class=formelement] {
    white-space: normal;
}

button.mult_submit {
    border: none;
    background-color: transparent;
}

/* odd items 1,3,5,7,... */
table tbody:first-of-type tr:nth-child(odd),
table tbody:first-of-type tr:nth-child(odd) th,
#table_index tbody:nth-of-type(odd) tr,
#table_index tbody:nth-of-type(odd) th {
    background: #fff;
}

/* even items 2,4,6,8,... */
table tbody:first-of-type tr:nth-child(even),
table tbody:first-of-type tr:nth-child(even) th,
#table_index tbody:nth-of-type(even) tr,
#table_index tbody:nth-of-type(even) th {
    background: #DFDFDF;
}

table tr th,
table tr {
    text-align: <?php echo $left; ?>;
}

/* marked table rows */
td.marked:not(.nomarker),
table tr.marked:not(.nomarker) td,
table tbody:first-of-type tr.marked:not(.nomarker) th,
table tr.marked:not(.nomarker) {
    <?php echo $theme->getCssGradient('ced6df', 'b6c6d7'); ?>
    color: <?php echo $GLOBALS['cfg']['BrowseMarkerColor']; ?>;
}

/* hovered items */
table tbody:first-of-type tr:not(.nopointer):hover,
table tbody:first-of-type tr:not(.nopointer):hover th,
.hover:not(.nopointer) {
    <?php echo $theme->getCssGradient('ced6df', 'b6c6d7'); ?>
    color: <?php echo $GLOBALS['cfg']['BrowsePointerColor']; ?>;
}

/* hovered table rows */
#table_index tbody:hover tr,
#table_index tbody:hover th,
table tr.hover:not(.nopointer) th {
    <?php echo $theme->getCssGradient('ced6df', 'b6c6d7'); ?>
    color: <?php echo $GLOBALS['cfg']['BrowsePointerColor']; ?>;
}

/**
 * marks table rows/cells if the db field is in a where condition
 */
.condition {
    border-color: <?php echo $GLOBALS['cfg']['BrowseMarkerBackground']; ?> !important;
}

th.condition {
    border-width: 1px 1px 0 1px;
    border-style: solid;
}

td.condition {
    border-width: 0 1px 0 1px;
    border-style: solid;
}

tr:last-child td.condition {
    border-width: 0 1px 1px 1px;
}

<?php if ($GLOBALS['text_dir'] === 'ltr') : ?>
    /* for first th which must have right border set (ltr only) */
    .before-condition {
    border-right: 1px solid <?php echo $GLOBALS['cfg']['BrowseMarkerBackground']; ?>;
    }
<?php endif; ?>

/**
 * cells with the value NULL
 */
td.null {
    font-style: italic;
    color: #7d7d7d;
}

table .valueHeader {
    text-align: <?php echo $right; ?>;
    white-space: normal;
}
table .value {
    text-align: <?php echo $right; ?>;
    white-space: normal;
}
/* IE doesnt handles 'pre' right */
table [class=value] {
    white-space: normal;
}


<?php if (! empty($GLOBALS['cfg']['FontFamilyFixed'])) : ?>
    .value {
    font-family: <?php echo $GLOBALS['cfg']['FontFamilyFixed']; ?>;
    }
<?php endif; ?>
.attention {
    color: red;
    font-weight: bold;
}
.allfine {
    color: green;
}


img.lightbulb {
    cursor: pointer;
}

.pdflayout {
    overflow: hidden;
    clip: inherit;
    background-color: #fff;
    display: none;
    border: 1px solid #000;
    position: relative;
}

.pdflayout_table {
    background: #D3DCE3;
    color: #000;
    overflow: hidden;
    clip: inherit;
    z-index: 2;
    display: inline;
    visibility: inherit;
    cursor: move;
    position: absolute;
    font-size: 80%;
    border: 1px dashed #000;
}

/* Doc links in SQL */
.cm-sql-doc {
    text-decoration: none;
    border-bottom: 1px dotted #000;
    color: inherit !important;
}

/* no extra space in table cells */
td .icon {
    image-rendering: pixelated;
    margin: 0;
}

.selectallarrow {
    margin-<?php echo $right; ?>: .3em;
    margin-<?php echo $left; ?>: .6em;
}

/* message boxes: error, confirmation */
#pma_errors, #pma_demo, #pma_footer {
    position: relative;
    padding: 0 0.5em;
}

.success h1,
.notice h1,
div.error h1 {
    border-bottom: 2px solid;
    font-weight: bold;
    text-align: <?php echo $left; ?>;
    margin: 0 0 .2em 0;
}

div.success,
div.notice,
div.error {
    margin: .5em 0 0.5em;
    border: 1px solid;
    background-repeat: no-repeat;
    <?php if ($GLOBALS['text_dir'] === 'ltr') : ?>
        background-position: 10px 50%;
        padding: 10px 10px 10px 10px;
    <?php else : ?>
        background-position: 99% 50%;
        padding: 10px 35px 10px 10px;
        <?php
    endif; ?>

    -moz-border-radius: 5px;
    -webkit-border-radius: 5px;
    border-radius: 5px;

    -moz-box-shadow: 0 1px 1px #fff inset;
    -webkit-box-shadow: 0 1px 1px #fff inset;
    box-shadow: 0 1px 1px #fff inset;
}

.success a,
.notice a,
.error a {
    text-decoration: underline;
}

.success {
    color: #000;
    background-color: #ebf8a4;
}

h1.success,
div.success {
    border-color: #a2d246;
}
.success h1 {
    border-color: #00FF00;
}

.notice {
    color: #000;
    background-color: #e8eef1;
}

h1.notice,
div.notice {
    border-color: #3a6c7e;
}

.notice h1 {
    border-color: #ffb10a;
}

.error {
    border: 1px solid maroon !important;
    color: #000;
    background: pink;
}

h1.error,
div.error {
    border-color: #333;
}

div.error h1 {
    border-color: #ff0000;
}

.confirmation {
    color: #000;
    background-color: pink;
}

fieldset.confirmation {
}

fieldset.confirmation legend {
}

/* end messageboxes */

.new_central_col{
    width: 100%;
}

.tblcomment {
    font-size: 70%;
    font-weight: normal;
    color: #000099;
}

.tblHeaders {
    font-weight: bold;
    color: <?php echo $GLOBALS['cfg']['ThColor']; ?>;
    background: <?php echo $GLOBALS['cfg']['ThBackground']; ?>;
}

div.tools,
.tblFooters {
    font-weight: normal;
    color: <?php echo $GLOBALS['cfg']['ThColor']; ?>;
    background: <?php echo $GLOBALS['cfg']['ThBackground']; ?>;
}

.tblHeaders a:link,
.tblHeaders a:active,
.tblHeaders a:visited,
div.tools a:link,
div.tools a:visited,
div.tools a:active,
.tblFooters a:link,
.tblFooters a:active,
.tblFooters a:visited {
    color: #0000FF;
}

.tblHeaders a:hover,
div.tools a:hover,
.tblFooters a:hover {
    color: #FF0000;
}

/* forbidden, no privileges */
.noPrivileges {
    color: #FF0000;
    font-weight: bold;
}

/* disabled text */
.disabled,
.disabled a:link,
.disabled a:active,
.disabled a:visited {
    color: #666;
}

.disabled a:hover {
    color: #666;
    text-decoration: none;
}

tr.disabled td,
td.disabled {
    background-color: #f3f3f3;
    color: #aaa;
}

.nowrap {
    white-space: nowrap;
}

/**
 * login form
 */
body#loginform h1,
body#loginform a.logo {
    display: block;
    text-align: center;
}

body#loginform {
    margin-top: 1em;
    text-align: center;
}

body#loginform div.container {
    text-align: <?php echo $left; ?>;
    width: 30em;
    margin: 0 auto;
}

form.login label {
    float: <?php echo $left; ?>;
    width: 10em;
    font-weight: bolder;
}

form.login input[type=text],
form.login input[type=password],
form.login select {
    box-sizing: border-box;
    width: 14em;
}

.commented_column {
    border-bottom: 1px dashed #000;
}

.column_attribute {
    font-size: 70%;
}

.cfg_dbg_demo{
    margin: 0.5em 1em 0.5em 1em;
}

.central_columns_navigation{
    padding:1.5% 0em !important;
}

.central_columns_add_column{
    display:inline-block;
    margin-left:1%;
    max-width:50%
}

.message_errors_found{
    margin-top: 20px;
}

.repl_gui_skip_err_cnt{
    width: 30px;
}

.font_weight_bold{
    font-weight: bold;
}

.color_gray{
    color: gray;
}

.pma_sliding_message{
    display: inline-block;
}

/******************************************************************************/
/* specific elements */

/* topmenu */
#topmenu a {
    text-shadow: 0 1px 0 #fff;
}

#topmenu .error {
    background: #eee;border: 0 !important;color: #aaa;
}

ul#topmenu,
ul#topmenu2,
ul.tabs {
    font-weight: bold;
    list-style-type: none;
    margin: 0;
    padding: 0;
}

ul#topmenu2 {
    margin: .25em .5em 0;
    height: 2em;
    clear: both;
}

ul#topmenu li,
ul#topmenu2 li {
    float: <?php echo $left; ?>;
    margin: 0;
    vertical-align: middle;
}

#topmenu img,
#topmenu2 img {
    margin-right: .5em;
    vertical-align: -3px;
}

.menucontainer {
    <?php echo $theme->getCssGradient('ffffff', 'dcdcdc'); ?>
    border-top: 1px solid #aaa;
}

.scrollindicator {
    display: none;
}

/* default tab styles */
.tabactive {
    background: #fff !important;
}

ul#topmenu2 a {
    display: block;
    margin: 7px 6px 7px;
    margin-<?php echo $left; ?>: 0;
    padding: 4px 10px;
    white-space: nowrap;
    border: 1px solid #ddd;
    border-radius: 20px;
    -moz-border-radius: 20px;
    -webkit-border-radius: 20px;
    background: #f2f2f2;

}

span.caution {
    color: #FF0000;
}
fieldset.caution a {
    color: #FF0000;
}
fieldset.caution a:hover {
    color: #fff;
    background-color: #FF0000;
}

#topmenu {
    margin-top: .5em;
    padding: .1em .3em;
}

ul#topmenu ul {
    -moz-box-shadow: <?php echo $GLOBALS['text_dir'] === 'rtl' ? '-' : ''; ?>1px 1px 6px #ddd;
    -webkit-box-shadow: <?php echo $GLOBALS['text_dir'] === 'rtl' ? '-' : ''; ?>2px 2px 3px #666;
    box-shadow: <?php echo $GLOBALS['text_dir'] === 'rtl' ? '-' : ''; ?>2px 2px 3px #666;
}

ul#topmenu ul.only {
    <?php echo $left; ?>: 0;
}

ul#topmenu > li {
    border-right: 1px solid #fff;
    border-left: 1px solid #ccc;
    border-bottom: 1px solid #ccc;
}

ul#topmenu > li:first-child {
    border-left: 0;
}

/* default tab styles */
ul#topmenu a,
ul#topmenu span {
    padding: .6em;
}

ul#topmenu ul a {
    border-width: 1pt 0 0 0;
    -moz-border-radius: 0;
    -webkit-border-radius: 0;
    border-radius: 0;
}

ul#topmenu ul li:first-child a {
    border-width: 0;
}

/* enabled hover/active tabs */
ul#topmenu > li > a:hover,
ul#topmenu > li > .tabactive {
    text-decoration: none;
}

ul#topmenu ul a:hover,
ul#topmenu ul .tabactive {
    text-decoration: none;
}

ul#topmenu a.tab:hover,
ul#topmenu .tabactive {
    /* background-color: <?php echo $GLOBALS['cfg']['MainBackground']; ?>;  */
}

ul#topmenu2 a.tab:hover,
ul#topmenu2 a.tabactive {
    background-color: <?php echo $GLOBALS['cfg']['BgOne']; ?>;
    border-radius: .3em;
    -moz-border-radius: .3em;
    -webkit-border-radius: .3em;
    text-decoration: none;
}

/* to be able to cancel the bottom border, use <li class="active"> */
ul#topmenu > li.active {
    /* border-bottom: 0pt solid <?php echo $GLOBALS['cfg']['MainBackground']; ?>; */
    border-right: 0;
    border-bottom-color: #fff;
}
/* end topmenu */

/* zoom search */
div#dataDisplay input,
div#dataDisplay select {
    margin: 0;
    margin-<?php echo $right; ?>: .5em;
}
div#dataDisplay th {
    line-height: 2em;
}
table#tableFieldsId {
    width: 100%;
}

/* Calendar */
table.calendar {
    width: 100%;
}
table.calendar td {
    text-align: center;
}
table.calendar td a {
    display: block;
}

table.calendar td a:hover {
    background-color: #CCFFCC;
}

table.calendar th {
    background-color: #D3DCE3;
}

table.calendar td.selected {
    background-color: #FFCC99;
}

img.calendar {
    border: none;
}
form.clock {
    text-align: center;
}
/* end Calendar */


/* table stats */
div#tablestatistics table {
    float: <?php echo $left; ?>;
    margin-bottom: .5em;
    margin-<?php echo $right; ?>: 1.5em;
    margin-top: .5em;
    min-width: 16em;
}

/* end table stats */


/* server privileges */
#tableuserrights td,
#tablespecificuserrights td,
#tabledatabases td {
    vertical-align: middle;
}
/* end server privileges */


/* Heading */
#topmenucontainer {
    padding-<?php echo $right; ?>: 1em;
    width: 100%;
}

#serverinfo {
    background: #888;
    padding: .3em .9em;
    padding-<?php echo $left; ?>: 2.2em;
    text-shadow: 0 1px 0 #000;
    max-width: 100%;
    max-height: 16px;
    overflow: hidden;
}

#serverinfo .item {
    white-space: nowrap;
    color: #fff;
}

#page_nav_icons {
    position: fixed;
    top: 0;
    <?php echo $right; ?>: 0;
    z-index: 99;
    padding: .25em 0;
}

#goto_pagetop, #lock_page_icon, #page_settings_icon {
    padding: .25em;
    background: #888;
}

#page_settings_icon {
    cursor: pointer;
    display: none;
}

#page_settings_modal {
    display: none;
}

#pma_navigation_settings {
    display: none;
}

#span_table_comment {
    font-weight: bold;
    font-style: italic;
    white-space: nowrap;
    margin-left: 10px;
    color: #D6D6D6;
    text-shadow: none;
}

#serverinfo img {
    margin: 0 .1em 0;
    margin-<?php echo $left; ?>: .2em;
}


#textSQLDUMP {
    width: 95%;
    height: 95%;
    font-family: Consolas, "Courier New", Courier, mono;
    font-size: 110%;
}

#TooltipContainer {
    position: absolute;
    z-index: 99;
    width: 20em;
    height: auto;
    overflow: visible;
    visibility: hidden;
    background-color: #ffffcc;
    color: #006600;
    border: .1em solid #000;
    padding: .5em;
}

/* user privileges */
#fieldset_add_user_login div.item {
    border-bottom: 1px solid silver;
    padding-bottom: .3em;
    margin-bottom: .3em;
}

#fieldset_add_user_login label {
    float: <?php echo $left; ?>;
    display: block;
    width: 10em;
    max-width: 100%;
    text-align: <?php echo $right; ?>;
    padding-<?php echo $right; ?>: .5em;
}

#fieldset_add_user_login span.options #select_pred_username,
#fieldset_add_user_login span.options #select_pred_hostname,
#fieldset_add_user_login span.options #select_pred_password {
    width: 100%;
    max-width: 100%;
}

#fieldset_add_user_login span.options {
    float: <?php echo $left; ?>;
    display: block;
    width: 12em;
    max-width: 100%;
    padding-<?php echo $right; ?>: .5em;
}

#fieldset_add_user_login input {
    width: 12em;
    clear: <?php echo $right; ?>;
    max-width: 100%;
}

#fieldset_add_user_login span.options input {
    width: auto;
}

#fieldset_user_priv div.item {
    float: <?php echo $left; ?>;
    width: 9em;
    max-width: 100%;
}

#fieldset_user_priv div.item div.item {
    float: none;
}

#fieldset_user_priv div.item label {
    white-space: nowrap;
}

#fieldset_user_priv div.item select {
    width: 100%;
}

#fieldset_user_global_rights fieldset {
    float: <?php echo $left; ?>;
}

#fieldset_user_group_rights fieldset {
    float: <?php echo $left; ?>;
}

#fieldset_user_global_rights>legend input {
    margin-<?php echo $left; ?>: 2em;
}
/* end user privileges */


/* serverstatus */

.linkElem:hover {
    text-decoration: underline;
    color: #235a81;
    cursor: pointer;
}

h3#serverstatusqueries span {
    font-size: 60%;
    display: inline;
}

.buttonlinks {
    float: <?php echo $right; ?>;
    white-space: nowrap;
}

/* Also used for the variables page */
fieldset#tableFilter {
    padding: 0.1em 1em;
}

div#serverStatusTabs {
    margin-top: 1em;
}

caption a.top {
    float: <?php echo $right; ?>;
}

div#serverstatusquerieschart {
    float: <?php echo $left; ?>;
    width: 500px;
    height: 350px;
    margin-<?php echo $right;?>: 50px;
}

table#serverstatusqueriesdetails,
table#serverstatustraffic {
    float: <?php echo $left; ?>;
}

table#serverstatusqueriesdetails th {
    min-width: 35px;
}

table#serverstatusvariables {
    width: 100%;
    margin-bottom: 1em;
}
table#serverstatusvariables .name {
    width: 18em;
    white-space: nowrap;
}
table#serverstatusvariables .value {
    width: 6em;
}
table#serverstatusconnections {
    float: <?php echo $left; ?>;
    margin-<?php echo $left; ?>: 30px;
}

div#serverstatus table tbody td.descr a,
div#serverstatus table .tblFooters a {
    white-space: nowrap;
}

div.liveChart {
    clear: both;
    min-width: 500px;
    height: 400px;
    padding-bottom: 80px;
}

#addChartDialog input[type="text"] {
    margin: 0;
    padding: 3px;
}

div#chartVariableSettings {
    border: 1px solid #ddd;
    background-color: #E6E6E6;
    margin-left: 10px;
}

table#chartGrid td {
    padding: 3px;
    margin: 0;
}

table#chartGrid div.monitorChart {
    background: #EBEBEB;
    overflow: hidden;
    border: none;
}

div.tabLinks {
    margin-left: 0.3em;
    float: <?php echo $left; ?>;
    padding: 5px 0;
}

div.tabLinks a, div.tabLinks label {
    margin-right: 7px;
}

div.tabLinks .icon {
    margin: -0.2em 0.3em 0 0;
}

.popupContent {
    display: none;
    position: absolute;
    border: 1px solid #CCC;
    margin: 0;
    padding: 3px;
    -moz-box-shadow: <?php echo $GLOBALS['text_dir'] === 'rtl' ? '-' : ''; ?>2px 2px 3px #666;
    -webkit-box-shadow: <?php echo $GLOBALS['text_dir'] === 'rtl' ? '-' : ''; ?>2px 2px 3px #666;
    box-shadow: <?php echo $GLOBALS['text_dir'] === 'rtl' ? '-' : ''; ?>2px 2px 3px #666;
    background-color: #fff;
    z-index: 2;
}

div#logTable {
    padding-top: 10px;
    clear: both;
}

div#logTable table {
    width: 100%;
}

div#queryAnalyzerDialog {
    min-width: 700px;
}

div#queryAnalyzerDialog div.CodeMirror-scroll {
    height: auto;
}

div#queryAnalyzerDialog div#queryProfiling {
    height: 300px;
}

div#queryAnalyzerDialog td.explain {
    width: 250px;
}

div#queryAnalyzerDialog table.queryNums {
    display: none;
    border: 0;
    text-align: left;
}

.smallIndent {
    padding-<?php echo $left; ?>: 7px;
}

/* end serverstatus */

/* server variables */
#serverVariables {
    width: 100%;
}
#serverVariables .var-row > td {
    line-height: 2em;
}
#serverVariables .var-header {
    color: <?php echo $GLOBALS['cfg']['ThColor']; ?>;
    background: #f3f3f3;
    <?php echo $theme->getCssGradient('ffffff', 'cccccc'); ?>
    font-weight: bold;
    text-align: <?php echo $left; ?>;
}
#serverVariables .var-row {
    padding: 0.5em;
    min-height: 18px;
}
#serverVariables .var-name {
    font-weight: bold;
}
#serverVariables .var-name.session {
    font-weight: normal;
    font-style: italic;
}
#serverVariables .var-value {
    float: <?php echo $right; ?>;
    text-align: <?php echo $right; ?>;
}
#serverVariables .var-doc {
    overflow:visible;
    float: <?php echo $right; ?>;
}

/* server variables editor */
#serverVariables .editLink {
    padding-<?php echo $right; ?>: 1em;
    font-family: sans-serif;
}
#serverVariables .serverVariableEditor {
    width: 100%;
    overflow: hidden;
}
#serverVariables .serverVariableEditor input {
    width: 100%;
    margin: 0 0.5em;
    box-sizing: border-box;
    -ms-box-sizing: border-box;
    -moz-box-sizing: border-box;
    -webkit-box-sizing: border-box;
    height: 2.2em;
}
#serverVariables .serverVariableEditor div {
    display: block;
    overflow: hidden;
    padding-<?php echo $right; ?>: 1em;
}
#serverVariables .serverVariableEditor a {
    margin: 0 0.5em;
    line-height: 2em;
}
/* end server variables */


p.notice {
    margin: 1.5em 0;
    border: 1px solid #000;
    background-repeat: no-repeat;
    <?php if ($GLOBALS['text_dir'] === 'ltr') : ?>
        background-position: 10px 50%;
        padding: 10px 10px 10px 25px;
    <?php else : ?>
        background-position: 99% 50%;
        padding: 25px 10px 10px 10px
    <?php endif; ?>
    -moz-border-radius: 5px;
    -webkit-border-radius: 5px;
    border-radius: 5px;
    -moz-box-shadow: 0 1px 2px #fff inset;
    -webkit-box-shadow: 0 1px 2px #fff inset;
    box-shadow: 0 1px 2px #fff inset;
    background: #555;
    color: #d4fb6a;
}

p.notice a {
    color: #fff;
    text-decoration: underline;
}

/* profiling */

div#profilingchart {
    width: 850px;
    height: 370px;
    float: <?php echo $left; ?>;
}

#profilingchart .jqplot-highlighter-tooltip{
    top: auto !important;
    left: 11px;
    bottom:24px;
}
/* end profiling */

/* table charting */
#resizer {
    border: 1px solid silver;
}
#inner-resizer { /* make room for the resize handle */
    padding: 10px;
}
.chartOption {
    float: <?php echo $left; ?>;
    margin-<?php echo $right;?>: 40px;
}
/* end table charting */

/* querybox */

#togglequerybox {
    margin: 0 10px;
}

#serverstatus h3
{
    margin: 15px 0;
    font-weight: normal;
    color: #999;
    font-size: 1.7em;
}
#sectionlinks {
    margin-bottom: 15px;
    padding: 16px;
    background: #f3f3f3;
    border: 1px solid #aaa;
    border-radius: 5px;
    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    box-shadow: 0 1px 1px #fff inset;
    -webkit-box-shadow: 0 1px 1px #fff inset;
    -moz-box-shadow: 0 1px 1px #fff inset;
}
#sectionlinks a,
.buttonlinks a,
a.button {
    font-weight: bold;
    text-shadow: 0 1px 0 #fff;
    line-height: 35px;
    margin-<?php echo $left; ?>: 7px;
    border: 1px solid #aaa;
    padding: 3px 7px;
    color: #111 !important;
    text-decoration: none;
    background: #ddd;
    white-space: nowrap;
    border-radius: 20px;
    -webkit-border-radius: 20px;
    -moz-border-radius: 20px;
    <?php echo $theme->getCssGradient('f8f8f8', 'd8d8d8'); ?>
}
#sectionlinks a:hover,
.buttonlinks a:hover,
a.button:hover {
    <?php echo $theme->getCssGradient('ffffff', 'dddddd'); ?>
}

div#sqlquerycontainer {
    float: <?php echo $left; ?>;
    width: 69%;
    /* height: 15em; */
}

div#tablefieldscontainer {
    float: <?php echo $right; ?>;
    width: 29%;
    margin-top: -20px;
    /* height: 15em; */
}

div#tablefieldscontainer select {
    width: 100%;
    background: #fff;
    /* height: 12em; */
}

textarea#sqlquery {
    width: 100%;
    /* height: 100%; */
    -moz-border-radius: 4px;
    -webkit-border-radius: 4px;
    border-radius: 4px;
    border: 1px solid #aaa;
    padding: 5px;
    font-family: inherit;
}
textarea#sql_query_edit {
    height: 7em;
    width: 95%;
    display: block;
}
div#queryboxcontainer div#bookmarkoptions {
    margin-top: .5em;
}
/* end querybox */

/* main page */
#maincontainer {
    /* background-image: url(<?php echo $theme->getImgPath('logo_right.png');?>); */
    /* background-position: <?php echo $right; ?> bottom; */
    /* background-repeat: no-repeat; */
}

#mysqlmaininformation,
#pmamaininformation {
    float: <?php echo $left; ?>;
    width: 49%;
}

#maincontainer ul {
    list-style-type: disc;
    vertical-align: middle;
}

#maincontainer li {
    margin-bottom: .3em;
}

#full_name_layer {
    position: absolute;
    padding: 2px;
    margin-top: -3px;
    z-index: 801;

    border-radius: 3px;
    border: solid 1px #888;
    background: #fff;

}
/* end main page */


/* iconic view for ul items */

li.no_bullets {
    list-style-type:none !important;
    margin-left: -25px !important;      //align with other list items which have bullets
}

/* end iconic view for ul items */

#body_browse_foreigners {
    background: <?php echo $GLOBALS['cfg']['NaviBackground']; ?>;
    margin: .5em .5em 0 .5em;
}

#bodythemes {
    width: 500px;
    margin: auto;
    text-align: center;
}

#bodythemes img {
    border: .1em solid #000;
}

#bodythemes a:hover img {
    border: .1em solid red;
}

#fieldset_select_fields {
    float: <?php echo $left; ?>;
}

#selflink {
    clear: both;
    display: block;
    margin-top: 1em;
    margin-bottom: 1em;
    width: 98%;
    margin-<?php echo $left; ?>: 1%;
    border-top: .1em solid silver;
    text-align: <?php echo $right; ?>;
}

#table_innodb_bufferpool_usage,
#table_innodb_bufferpool_activity {
    float: <?php echo $left; ?>;
}

#div_mysql_charset_collations table {
    float: <?php echo $left; ?>;
}

#div_mysql_charset_collations table th,
#div_mysql_charset_collations table td {
    padding: 0.4em;
}

#div_mysql_charset_collations table th#collationHeader {
    width: 35%;
}

#qbe_div_table_list {
    float: <?php echo $left; ?>;
}

#qbe_div_sql_query {
    float: <?php echo $left; ?>;
}

label.desc {
    width: 30em;
    float: <?php echo $left; ?>;
}

label.desc sup {
    position: absolute;
}

code.php {
    display: block;
    padding-left: 1em;
    margin-top: 0;
    margin-bottom: 0;
    max-height: 10em;
    overflow: auto;
    direction: ltr;
}

code.sql,
div.sqlvalidate {
    display: block;
    padding: 1em;
    margin-top: 0;
    margin-bottom: 0;
    max-height: 10em;
    overflow: auto;
    direction: ltr;
}

.result_query div.sqlOuter {
    background: <?php echo $GLOBALS['cfg']['BgOne']; ?>;
    text-align: <?php echo $left; ?>;
}

.result_query .success, .result_query .error {
    margin-bottom: 0;
    border-bottom: none !important;
    border-bottom-left-radius: 0;
    border-bottom-right-radius: 0;
    padding-bottom: 5px;
}

#PMA_slidingMessage code.sql,
div.sqlvalidate {
    background: <?php echo $GLOBALS['cfg']['BgOne']; ?>;
}

#main_pane_left {
    width: 60%;
    min-width: 260px;
    float: <?php echo $left; ?>;
    padding-top: 1em;
}

#main_pane_right {
    overflow: hidden;
    min-width: 160px;
    padding-top: 1em;
    padding-<?php echo $left; ?>: 1em;
    padding-<?php echo $right; ?>: .5em;
}

.group {

    border: 1px solid #999;
    background: #f3f3f3;
    -moz-border-radius: 4px;
    -webkit-border-radius: 4px;
    border-radius: 4px;
    -moz-box-shadow: <?php echo $GLOBALS['text_dir'] === 'rtl' ? '-' : ''; ?>2px 2px 5px #ccc;
    -webkit-box-shadow: <?php echo $GLOBALS['text_dir'] === 'rtl' ? '-' : ''; ?>2px 2px 5px #ccc;
    box-shadow: <?php echo $GLOBALS['text_dir'] === 'rtl' ? '-' : ''; ?>2px 2px 5px #ccc;
    margin-bottom: 1em;
    padding-bottom: 1em;
}

.group h2 {
    background-color: #bbb;
    padding: .1em .3em;
    margin-top: 0;
    color: #fff;
    font-size: 1.6em;
    font-weight: normal;
    text-shadow: 0 1px 0 #777;
    -moz-box-shadow: <?php echo $GLOBALS['text_dir'] === 'rtl' ? '-' : ''; ?>1px 1px 15px #999 inset;
    -webkit-box-shadow: <?php echo $GLOBALS['text_dir'] === 'rtl' ? '-' : ''; ?>1px 1px 15px #999 inset;
    box-shadow: <?php echo $GLOBALS['text_dir'] === 'rtl' ? '-' : ''; ?>1px 1px 15px #999 inset;
}

.group-cnt {
    padding: 0;
    padding-<?php echo $left; ?>: .5em;
    display: inline-block;
    width: 98%;
}

textarea#partitiondefinition {
    height: 3em;
}


/* for elements that should be revealed only via js */
.hide {
    display: none;
}

#list_server {
    list-style-type: none;
    padding: 0;
}

/**
  *  Progress bar styles
  */
div.upload_progress
{
    width: 400px;
    margin: 3em auto;
    text-align: center;
}

div.upload_progress_bar_outer
{
    border: 1px solid #000;
    width: 202px;
    position: relative;
    margin: 0 auto 1em;
    color: <?php echo $GLOBALS['cfg']['MainColor']; ?>;
}

div.upload_progress_bar_inner
{
    background-color: <?php echo $GLOBALS['cfg']['NaviPointerBackground']; ?>;
    width: 0;
    height: 12px;
    margin: 1px;
    overflow: hidden;
    color: <?php echo $GLOBALS['cfg']['BrowseMarkerColor']; ?>;
    position: relative;
}

div.upload_progress_bar_outer div.percentage
{
    position: absolute;
    top: 0;
    <?php echo $left; ?>: 0;
    width: 202px;
}

div.upload_progress_bar_inner div.percentage
{
    top: -1px;
    <?php echo $left; ?>: -1px;
}

div#statustext {
    margin-top: .5em;
}

table#serverconnection_src_remote,
table#serverconnection_trg_remote,
table#serverconnection_src_local,
table#serverconnection_trg_local  {
  float: <?php echo $left; ?>;
}
/**
  *  Validation error message styles
  */
input[type=text].invalid_value,
input[type=password].invalid_value,
input[type=number].invalid_value,
input[type=date].invalid_value,
select.invalid_value,
.invalid_value {
    background: #FFCCCC;
}

/**
  *  Ajax notification styling
  */
 .ajax_notification {
    top: 0;           /** The notification needs to be shown on the top of the page */
    position: fixed;
    margin-top: 0;
    margin-right: auto;
    margin-bottom: 0;
    margin-<?php echo $left; ?>: auto;
    padding: 5px;   /** Keep a little space on the sides of the text */
    width: 350px;

    z-index: 1100;      /** If this is not kept at a high z-index, the jQueryUI modal dialogs (z-index: 1000) might hide this */
    text-align: center;
    display: inline;
    left: 0;
    right: 0;
    background-image: url(<?php echo $theme->getImgPath('ajax_clock_small.gif');?>);
    background-repeat: no-repeat;
    background-position: 2%;
    border: 1px solid #e2b709;
 }

/* additional styles */
.ajax_notification {
    margin-top: 200px;
    background: #ffe57e;
    border-radius: 5px;
    -moz-border-radius: 5px;
    -webkit-border-radius: 5px;
    box-shadow: 0 5px 90px #888;
    -moz-box-shadow: 0 5px 90px #888;
    -webkit-box-shadow: 0 5px 90px #888;
}

#loading_parent {
    /** Need this parent to properly center the notification division */
    position: relative;
    width: 100%;
 }
/**
  * Export and Import styles
  */

.export_table_list_container {
    display: inline-block;
    max-height: 20em;
    overflow-y: scroll;
}

.export_table_select th {
    text-align: center;
    vertical-align: middle;
}

.export_table_select .all {
    font-weight: bold;
    border-bottom: 1px solid black;
}

.export_structure, .export_data {
    text-align: center;
}

.export_table_name {
    vertical-align: middle;
}

.exportoptions h2 {
    word-wrap: break-word;
}

.exportoptions h3,
.importoptions h3 {
    border-bottom: 1px #999 solid;
    font-size: 110%;
}

.exportoptions ul,
.importoptions ul,
.format_specific_options ul {
    list-style-type: none;
    margin-bottom: 15px;
}

.exportoptions li,
.importoptions li {
    margin: 7px;
}
.exportoptions label,
.importoptions label,
.exportoptions p,
.importoptions p {
    margin: 5px;
    float: none;
}

#csv_options label.desc,
#ldi_options label.desc,
#latex_options label.desc,
#output label.desc {
    float: <?php echo $left; ?>;
    width: 15em;
}

.exportoptions,
.importoptions {
    margin: 20px 30px 30px;
    margin-<?php echo $left; ?>: 10px;
}

.exportoptions #buttonGo,
.importoptions #buttonGo {
    font-weight: bold;
    margin-<?php echo $left; ?>: 14px;
    border: 1px solid #aaa;
    padding: 5px 12px;
    color: #111;
    text-decoration: none;

    border-radius: 12px;
    -webkit-border-radius: 12px;
    -moz-border-radius: 12px;

    text-shadow: 0 1px 0 #fff;

    <?php echo $theme->getCssGradient('ffffff', 'cccccc'); ?>
    cursor: pointer;
}

.format_specific_options h3 {
    margin: 10px 0 0;
    margin-<?php echo $left; ?>: 10px;
    border: 0;
}

.format_specific_options {
    border: 1px solid #999;
    margin: 7px 0;
    padding: 3px;
}

p.desc {
    margin: 5px;
}

/**
  * Export styles only
  */
select#db_select,
select#table_select {
    width: 400px;
}

.export_sub_options {
    margin: 20px 0 0;
    margin-<?php echo $left; ?>: 30px;
}

.export_sub_options h4 {
    border-bottom: 1px #999 solid;
}

.export_sub_options li.subgroup {
    display: inline-block;
    margin-top: 0;
}

.export_sub_options li {
    margin-bottom: 0;
}
#export_refresh_form {
    margin-left: 20px;
}
#export_back_button {
    display: inline;
}
#output_quick_export {
    display: none;
}
/**
 * Import styles only
 */

.importoptions #import_notification {
    margin: 10px 0;
    font-style: italic;
}

input#input_import_file {
    margin: 5px;
}

.formelementrow {
    margin: 5px 0 5px 0;
}

#filterText {
    vertical-align: baseline;
}

#popup_background {
    display: none;
    position: fixed;
    _position: absolute; /* hack for IE6 */
    width: 100%;
    height: 100%;
    top: 0;
    <?php echo $left; ?>: 0;
    background: #000;
    z-index: 1000;
    overflow: hidden;
}

/**
 * Table structure styles
 */
#fieldsForm ul.table-structure-actions {
    margin: 0;
    padding: 0;
    list-style: none;
}
#fieldsForm ul.table-structure-actions li {
    float: <?php echo $left; ?>;
    margin-<?php echo $right; ?>: 0.3em; /* same as padding of "table td" */
}
#fieldsForm ul.table-structure-actions .submenu li {
    padding: 0;
    margin: 0;
}
#fieldsForm ul.table-structure-actions .submenu li span {
    padding: 0.3em;
    margin: 0.1em;
}
#structure-action-links a {
    margin-<?php echo $right; ?>: 1em;
}
#addColumns input[type="radio"] {
    margin: 3px 0 0;
    margin-<?php echo $left; ?>: 1em;
}
/**
 * Indexes
 */
#index_frm .index_info input[type="text"],
#index_frm .index_info select {
    width: 100%;
    margin: 0;
    box-sizing: border-box;
    -ms-box-sizing: border-box;
    -moz-box-sizing: border-box;
    -webkit-box-sizing: border-box;
}

#index_frm .index_info div {
    padding: .2em 0;
}

#index_frm .index_info .label {
    float: <?php echo $left; ?>;
    min-width: 12em;
}

#index_frm .slider {
    width: 10em;
    margin: .6em;
    float: <?php echo $left; ?>;
}

#index_frm .add_fields {
    float: <?php echo $left; ?>;
}

#index_frm .add_fields input {
    margin-<?php echo $left; ?>: 1em;
}

#index_frm input {
    margin: 0;
}

#index_frm td {
    vertical-align: middle;
}

table#index_columns {
    width: 100%;
}

table#index_columns select {
    width: 85%;
    float: <?php echo $left; ?>;
}

#move_columns_dialog div {
    padding: 1em;
}

#move_columns_dialog ul {
    list-style: none;
    margin: 0;
    padding: 0;
}

#move_columns_dialog li {
    background: <?php echo $GLOBALS['cfg']['ThBackground']; ?>;
    border: 1px solid #aaa;
    color: <?php echo $GLOBALS['cfg']['ThColor']; ?>;
    font-weight: bold;
    margin: .4em;
    padding: .2em;
    -webkit-border-radius: 2px;
    -moz-border-radius: 2px;
    border-radius: 2px;
}

/* config forms */
.config-form ul.tabs {
    margin: 1.1em .2em 0;
    padding: 0 0 .3em 0;
    list-style: none;
    font-weight: bold;
}

.config-form ul.tabs li {
    float: <?php echo $left; ?>;
    margin-bottom: -1px;
}

.config-form ul.tabs li a {
    display: block;
    margin: .1em .2em 0;
    white-space: nowrap;
    text-decoration: none;
    border: 1px solid <?php echo $GLOBALS['cfg']['BgTwo']; ?>;
    border-bottom: 1px solid #aaa;
}

.config-form ul.tabs li a {
    padding: 7px 10px;
    -webkit-border-radius: 5px 5px 0 0;
    -moz-border-radius: 5px 5px 0 0;
    border-radius: 5px 5px 0 0;
    background: #f2f2f2;
    color: #555;
    text-shadow: 0 1px 0 #fff;
}

.config-form ul.tabs li a:hover,
.config-form ul.tabs li a:active {
    background: #e5e5e5;
}

.config-form ul.tabs li.active a {
    background-color: #fff;
    margin-top: 1px;
    color: #000;
    text-shadow: none;
    border-color: #aaa;
    border-bottom: 1px solid #fff;
}

.config-form fieldset {
    margin-top: 0;
    padding: 0;
    clear: both;
    -webkit-border-radius: 0;
    -moz-border-radius: 0;
    border-radius: 0;
}

.config-form legend {
    display: none;
}

.config-form fieldset p {
    margin: 0;
    padding: .5em;
    background: #fff;
    border-top: 0;
}

.config-form fieldset .errors { /* form error list */
    margin: 0 -2px 1em;
    padding: .5em 1.5em;
    background: #FBEAD9;
    border: 0 #C83838 solid;
    border-width: 1px 0;
    list-style: none;
    font-family: sans-serif;
    font-size: small;
}

.config-form fieldset .inline_errors { /* field error list */
    margin: .3em .3em .3em;
    margin-<?php echo $left; ?>: 0;
    padding: 0;
    list-style: none;
    color: #9A0000;
    font-size: small;
}

.config-form fieldset th {
    padding: .3em .3em .3em;
    padding-<?php echo $left; ?>: .5em;
    text-align: <?php echo $left; ?>;
    vertical-align: top;
    width: 40%;
    background: transparent;
    filter: none;
}

.config-form fieldset .doc,
.config-form fieldset .disabled-notice {
    margin-<?php echo $left; ?>: 1em;
}

.config-form fieldset .disabled-notice {
    font-size: 80%;
    text-transform: uppercase;
    color: #E00;
    cursor: help;
}

.config-form fieldset td {
    padding-top: .3em;
    padding-bottom: .3em;
    vertical-align: top;
}

.config-form fieldset th small {
    display: block;
    font-weight: normal;
    font-family: sans-serif;
    font-size: x-small;
    color: #444;
}

.config-form fieldset th,
.config-form fieldset td {
    border-top: 1px <?php echo $GLOBALS['cfg']['BgTwo']; ?> solid;
    border-<?php echo $right; ?>: none;
}

fieldset .group-header th {
    background: <?php echo $GLOBALS['cfg']['BgTwo']; ?>;
}

fieldset .group-header + tr th {
    padding-top: .6em;
}

fieldset .group-field-1 th,
fieldset .group-header-2 th {
    padding-<?php echo $left; ?>: 1.5em;
}

fieldset .group-field-2 th,
fieldset .group-header-3 th {
    padding-<?php echo $left; ?>: 3em;
}

fieldset .group-field-3 th {
    padding-<?php echo $left; ?>: 4.5em;
}

fieldset .disabled-field th,
fieldset .disabled-field th small,
fieldset .disabled-field td {
    color: #666;
    background-color: #ddd;
}

.config-form .lastrow {
    border-top: 1px #000 solid;
}

.config-form .lastrow {
    background: <?php echo $GLOBALS['cfg']['ThBackground']; ?>;
    padding: .5em;
    text-align: center;
}

.config-form .lastrow input {
    font-weight: bold;
}

/* form elements */

.config-form span.checkbox {
    padding: 2px;
    display: inline-block;
}

.config-form .custom { /* customized field */
    background: #FFC;
}

.config-form span.checkbox.custom {
    padding: 1px;
    border: 1px #EDEC90 solid;
    background: #FFC;
}

.config-form .field-error {
    border-color: #A11 !important;
}

.config-form input[type="text"],
.config-form input[type="password"],
.config-form input[type="number"],
.config-form select,
.config-form textarea {
    border: 1px #A7A6AA solid;
    height: auto;
}

.config-form input[type="text"]:focus,
.config-form input[type="password"]:focus,
.config-form input[type="number"]:focus,
.config-form select:focus,
.config-form textarea:focus {
    border: 1px #6676FF solid;
    background: #F7FBFF;
}

.config-form .field-comment-mark {
    font-family: serif;
    color: #007;
    cursor: help;
    padding: 0 .2em;
    font-weight: bold;
    font-style: italic;
}

.config-form .field-comment-warning {
    color: #A00;
}

/* error list */
.config-form dd {
    margin-<?php echo $left; ?>: .5em;
}

.config-form dd:before {
    content: "\25B8  ";
}

.click-hide-message {
    cursor: pointer;
}

.prefsmanage_opts {
    margin-<?php echo $left; ?>: 2em;
}

#prefs_autoload {
    margin-bottom: .5em;
    margin-left: .5em;
}

#placeholder .button {
    position: absolute;
    cursor: pointer;
}

#placeholder div.button {
    font-size: smaller;
    color: #999;
    background-color: #eee;
    padding: 2px;
}

.wrapper {
    float: <?php echo $left; ?>;
    margin-bottom: 1.5em;
}
.toggleButton {
    position: relative;
    cursor: pointer;
    font-size: .8em;
    text-align: center;
    line-height: 1.4em;
    height: 1.55em;
    overflow: hidden;
    border-right: .1em solid #888;
    border-left: .1em solid #888;
    -webkit-border-radius: .3em;
    -moz-border-radius: .3em;
    border-radius: .3em;
}
.toggleButton table,
.toggleButton td,
.toggleButton img {
    padding: 0;
    position: relative;
}
.toggleButton .container {
    position: absolute;
}
.toggleButton .container td,
.toggleButton .container tr {
    background-image: none;
    background: none !important;
}
.toggleButton .toggleOn {
    color: #fff;
    padding: 0 1em;
    text-shadow: 0 0 .2em #000;
}
.toggleButton .toggleOff {
    padding: 0 1em;
}

.doubleFieldset fieldset {
    width: 48%;
    float: <?php echo $left; ?>;
    padding: 0;
}
.doubleFieldset fieldset.left {
    margin-<?php echo $right; ?>: 1%;
}
.doubleFieldset fieldset.right {
    margin-<?php echo $left; ?>: 1%;
}
.doubleFieldset legend {
    margin-<?php echo $left; ?>: 1.5em;
}
.doubleFieldset div.wrap {
    padding: 1.5em;
}

#table_name_col_no_outer {
    margin-top: 45px;
}

#table_name_col_no {
    position: fixed;
    top: 55px;
    width: 100%;
    background: #ffffff;
}

#table_columns input[type="text"],
#table_columns input[type="password"],
#table_columns input[type="number"],
#table_columns select {
    width: 10em;
    box-sizing: border-box;
    -ms-box-sizing: border-box;
    -moz-box-sizing: border-box;
    -webkit-box-sizing: border-box;
}

#placeholder {
    position: relative;
    border: 1px solid #aaa;
    float: <?php echo $right; ?>;
    overflow: hidden;
    width: 450px;
    height: 300px;
}

#openlayersmap{
    width: 450px;
    height: 300px;
}

.placeholderDrag {
    cursor: move;
}

#placeholder .button {
    position: absolute;
}

#left_arrow {
    left: 8px;
    top: 26px;
}

#right_arrow {
    left: 26px;
    top: 26px;
}

#up_arrow {
    left: 17px;
    top: 8px;
}

#down_arrow {
    left: 17px;
    top: 44px;
}

#zoom_in {
    left: 17px;
    top: 67px;
}

#zoom_world {
    left: 17px;
    top: 85px;
}

#zoom_out {
    left: 17px;
    top: 103px;
}

.colborder {
    cursor: col-resize;
    height: 100%;
    margin-<?php echo $left; ?>: -6px;
    position: absolute;
    width: 5px;
}

.colborder_active {
    border-<?php echo $right; ?>: 2px solid #a44;
}

.pma_table td {
    position: static;
}

.pma_table th.draggable span,
.pma_table tbody td span {
    display: block;
    overflow: hidden;
}

.pma_table tbody td span code span {
    display: inline;
}

.pma_table th.draggable.right span {
    margin-<?php echo $right; ?>: 0px;
}

.pma_table th.draggable span {
    margin-<?php echo $right; ?>: 10px;
}

.modal-copy input {
    display: block;
    width: 100%;
    margin-top: 1.5em;
    padding: .3em 0;
}

.cRsz {
    position: absolute;
}

.cCpy {
    background: #333;
    color: #FFF;
    font-weight: bold;
    margin: .1em;
    padding: .3em;
    position: absolute;
    text-shadow: -1px -1px #000;

    -moz-box-shadow: 0 0 .7em #000;
    -webkit-box-shadow: 0 0 .7em #000;
    box-shadow: 0 0 .7em #000;
    -moz-border-radius: .3em;
    -webkit-border-radius: .3em;
    border-radius: .3em;
}

.cPointer {
    background: url(<?php echo $theme->getImgPath('col_pointer.png');?>);
    height: 20px;
    margin-<?php echo $left; ?>: -5px;  /* must be minus half of its width */
    margin-top: -10px;
    position: absolute;
    width: 10px;
}

.tooltip {
    background: #333 !important;
    opacity: .8 !important;
    border: 1px solid #000 !important;
    -moz-border-radius: .3em !important;
    -webkit-border-radius: .3em !important;
    border-radius: .3em !important;
    text-shadow: -1px -1px #000 !important;
    font-size: .8em !important;
    font-weight: bold !important;
    padding: 1px 3px !important;
}

.tooltip * {
    background: none !important;
    color: #FFF !important;
}

.cDrop {
    left: 0;
    position: absolute;
    top: 0;
}

.coldrop {
    background: url(<?php echo $theme->getImgPath('col_drop.png');?>);
    cursor: pointer;
    height: 16px;
    margin-<?php echo $left; ?>: .3em;
    margin-top: .3em;
    position: absolute;
    width: 16px;
}

.coldrop:hover,
.coldrop-hover {
    background-color: #999;
}

.cList {
    background: #EEE;
    border: solid 1px #999;
    position: absolute;
    -moz-box-shadow: 0 .2em .5em #333;
    -webkit-box-shadow: 0 .2em .5em #333;
    box-shadow: 0 .2em .5em #333;
}

.cList .lDiv div {
    padding: .2em .5em .2em;
    padding-<?php echo $left; ?>: .2em;
}

.cList .lDiv div:hover {
    background: #DDD;
    cursor: pointer;
}

.cList .lDiv div input {
    cursor: pointer;
}

.showAllColBtn {
    border-bottom: solid 1px #999;
    border-top: solid 1px #999;
    cursor: pointer;
    font-size: .9em;
    font-weight: bold;
    padding: .35em 1em;
    text-align: center;
}

.showAllColBtn:hover {
    background: #DDD;
}

.turnOffSelect {
  -moz-user-select: none;
  -khtml-user-select: none;
  -webkit-user-select: none;
  user-select: none;
}

.navigation {
    margin: .8em 0;

    border-radius: 5px;
    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;

    <?php echo $theme->getCssGradient('eeeeee', 'cccccc'); ?>
}

.navigation td {
    margin: 0;
    padding: 0;
    vertical-align: middle;
    white-space: nowrap;
}

.navigation_separator {
    color: #999;
    display: inline-block;
    font-size: 1.5em;
    text-align: center;
    height: 1.4em;
    width: 1.2em;
    text-shadow: 1px 0 #FFF;
}

.navigation input[type=submit] {
    background: none;
    border: 0;
    filter: none;
    margin: 0;
    padding: .8em .5em;

    border-radius: 0;
    -webkit-border-radius: 0;
    -moz-border-radius: 0;
}

.navigation input[type=submit]:hover,
.navigation input.edit_mode_active {
    color: #fff;
    cursor: pointer;
    text-shadow: none;

    <?php echo $theme->getCssGradient('333333', '555555'); ?>
}

.navigation select {
    margin: 0 .8em;
}

.cEdit {
    margin: 0;
    padding: 0;
    position: absolute;
}

.cEdit input[type=text] {
    background: #FFF;
    height: 100%;
    margin: 0;
    padding: 0;
}

.cEdit .edit_area {
    background: #FFF;
    border: 1px solid #999;
    min-width: 10em;
    padding: .3em .5em;
}

.cEdit .edit_area select,
.cEdit .edit_area textarea {
    width: 97%;
}

.cEdit .cell_edit_hint {
    color: #555;
    font-size: .8em;
    margin: .3em .2em;
}

.cEdit .edit_box {
    overflow-x: hidden;
    overflow-y: scroll;
    padding: 0;
    margin: 0;
}

.cEdit .edit_box_posting {
    background: #FFF url(<?php echo $theme->getImgPath('ajax_clock_small.gif');?>) no-repeat right center;
    padding-<?php echo $right; ?>: 1.5em;
}

.cEdit .edit_area_loading {
    background: #FFF url(<?php echo $theme->getImgPath('ajax_clock_small.gif');?>) no-repeat center;
    height: 10em;
}

.cEdit .goto_link {
    background: #EEE;
    color: #555;
    padding: .2em .3em;
}

.saving_edited_data {
    background: url(<?php echo $theme->getImgPath('ajax_clock_small.gif');?>) no-repeat left;
    padding-<?php echo $left; ?>: 20px;
}

.relationalTable td {
    vertical-align: top;
}

.relationalTable select {
    width: 125px;
    margin-right: 5px;
}

/* css for timepicker */
.ui-timepicker-div .ui-widget-header { margin-bottom: 8px; }
.ui-timepicker-div dl { text-align: <?php echo $left; ?>; }
.ui-timepicker-div dl dt { height: 25px; margin-bottom: -25px; }
.ui-timepicker-div dl dd { margin: 0 10px 10px 85px; }
.ui-timepicker-div td { font-size: 90%; }
.ui-tpicker-grid-label { background: none; border: none; margin: 0; padding: 0; }
.ui-timepicker-rtl { direction: rtl; }
.ui-timepicker-rtl dl { text-align: right; }
.ui-timepicker-rtl dl dd { margin: 0 65px 10px 10px; }

input.btn {
    color: #333;
    background-color: #D0DCE0;
}

body .ui-widget {
    font-size: 1em;
}

.ui-dialog fieldset legend a {
    color: #235A81;
}

.ui-draggable {
    z-index: 801;
}

/* over-riding jqplot-yaxis class */
.jqplot-yaxis {
    left:0 !important;
    min-width:25px;
    width:auto;
}
.jqplot-axis {
    overflow:hidden;
}

.report-data {
    height:13em;
    overflow:scroll;
    width:570px;
    border: solid 1px;
    background: white;
    padding: 2px;
}

.report-description {
    height:10em;
    width:570px;
}

div#page_content div#tableslistcontainer table.data {
    border-top: 0.1px solid #EEEEEE;
}

div#page_content div#tableslistcontainer, div#page_content div.notice, div#page_content div.result_query {
    margin-top: 1em;
}

table.show_create {
    margin-top: 1em;
}

table.show_create td {
    border-right: 1px solid #bbb;
}

#alias_modal table {
    width: 100%;
}

#alias_modal label {
    font-weight: bold;
}

.ui-dialog {
    position: fixed;
}


.small_font {
    font-size: smaller;
}

/* Console styles */
#pma_console_container {
    width: 100%;
    position: fixed;
    bottom: 0;
    <?php echo $left; ?>: 0;
    z-index: 100;
}
#pma_console {
    position: relative;
    margin-<?php echo $left; ?>: 240px;
}
#pma_console .templates {
    display: none;
}
#pma_console .mid_text,
#pma_console .toolbar span {
    vertical-align: middle;
}
#pma_console .toolbar {
    position: relative;
    background: #ccc;
    border-top: solid 1px #aaa;
    cursor: n-resize;
}
#pma_console .toolbar.collapsed:not(:hover) {
    display: inline-block;
    border-top-<?php echo $right; ?>-radius: 3px;
    border-<?php echo $right; ?>: solid 1px #aaa;
}
#pma_console .toolbar.collapsed {
    cursor: default;
}
#pma_console .toolbar.collapsed>.button {
    display: none;
}
#pma_console .message span.text,
#pma_console .message span.action,
#pma_console .toolbar .button,
#pma_console .toolbar .text,
#pma_console .switch_button {
    padding: 0 3px;
    display: inline-block;
}
#pma_console .message span.action,
#pma_console .toolbar .button,
#pma_console .switch_button {
    cursor: pointer;
}
#pma_console .message span.action:hover,
#pma_console .toolbar .button:hover,
#pma_console .switch_button:hover,
#pma_console .toolbar .button.active {
    background: #ddd;
}
#pma_console .toolbar .text {
    font-weight: bold;
}
#pma_console .toolbar .button,
#pma_console .toolbar .text {
    margin-<?php echo $right; ?>: .4em;
}
#pma_console .toolbar .button,
#pma_console .toolbar .text {
    float: <?php echo $right; ?>;
}
#pma_console .content {
    overflow-x: hidden;
    overflow-y: auto;
    margin-bottom: -65px;
    border-top: solid 1px #aaa;
    background: #fff;
    padding-top: .4em;
}
#pma_console .content.console_dark_theme {
    background: #000;
    color: #fff;
}
#pma_console .content.console_dark_theme .CodeMirror-wrap {
    background: #000;
    color: #fff;
}
#pma_console .content.console_dark_theme .action_content {
    color: #000;
}
#pma_console .content.console_dark_theme .message {
    border-color: #373B41;
}
#pma_console .content.console_dark_theme .CodeMirror-cursor {
    border-color: #fff;
}
#pma_console .content.console_dark_theme .cm-keyword {
    color: #de935f;
}
#pma_console .message,
#pma_console .query_input {
    position: relative;
    font-family: Monaco, Consolas, monospace;
    cursor: text;
    margin: 0 10px .2em 1.4em;
}
#pma_console .message {
    border-bottom: solid 1px #ccc;
    padding-bottom: .2em;
}
#pma_console .message.expanded>.action_content {
    position: relative;
}
#pma_console .message:before,
#pma_console .query_input:before {
    left: -0.7em;
    position: absolute;
    content: ">";
}
#pma_console .query_input:before {
    top: -2px;
}
#pma_console .query_input textarea {
    width: 100%;
    height: 4em;
    resize: vertical;
}
#pma_console .message:hover:before {
    color: #7cf;
    font-weight: bold;
}
#pma_console .message.expanded:before {
    content: "]";
}
#pma_console .message.welcome:before {
    display: none;
}
#pma_console .message.failed:before,
#pma_console .message.failed.expanded:before,
#pma_console .message.failed:hover:before {
    content: "=";
    color: #944;
}
#pma_console .message.pending:before {
    opacity: .3;
}
#pma_console .message.collapsed>.query {
    white-space: nowrap;
    text-overflow: ellipsis;
    overflow: hidden;
}
#pma_console .message.expanded>.query {
    display: block;
    white-space: pre;
    word-wrap: break-word;
}
#pma_console .message .text.targetdb,
#pma_console .message.collapsed .action.collapse,
#pma_console .message.expanded .action.expand,
#pma_console .message .action.requery,
#pma_console .message .action.profiling,
#pma_console .message .action.explain,
#pma_console .message .action.bookmark {
    display: none;
}
#pma_console .message.select .action.profiling,
#pma_console .message.select .action.explain,
#pma_console .message.history .text.targetdb,
#pma_console .message.successed .text.targetdb,
#pma_console .message.history .action.requery,
#pma_console .message.history .action.bookmark,
#pma_console .message.bookmark .action.requery,
#pma_console .message.bookmark .action.bookmark,
#pma_console .message.successed .action.requery,
#pma_console .message.successed .action.bookmark {
    display: inline-block;
}
#pma_console .message .action_content {
    position: absolute;
    bottom: 100%;
    background: #ccc;
    border: solid 1px #aaa;
    border-top-<?php echo $left; ?>-radius: 3px;
}
html.ie8 #pma_console .message .action_content {
    position: relative!important;
}
#pma_console .message.bookmark .text.targetdb,
#pma_console .message .text.query_time {
    margin: 0;
    display: inline-block;
}
#pma_console .message.failed .text.query_time,
#pma_console .message .text.failed {
    display: none;
}
#pma_console .message.failed .text.failed {
    display: inline-block;
}
#pma_console .message .text {
    background: #fff;
}
#pma_console .message.collapsed>.action_content {
    display: none;
}
#pma_console .message.collapsed:hover>.action_content {
    display: block;
}
#pma_console .message .bookmark_label {
    padding: 0 4px;
    top: 0;
    background: #369;
    color: #fff;
    border-radius: 3px;
}
#pma_console .message .bookmark_label.shared {
    background: #396;
}
#pma_console .message.expanded .bookmark_label {
    border-top-left-radius: 0;
    border-top-right-radius: 0;
}
#pma_console .query_input {
    position: relative;
}
#pma_console .mid_layer {
    height: 100%;
    width: 100%;
    position: absolute;
    top: 0;
    /* For support IE8, this layer doesn't use filter:opacity or opacity,
    js code will fade this layer opacity to 0.18(using animation) */
    background: #666;
    display: none;
    cursor: pointer;
    z-index: 200;
}
#pma_console .card {
    position: absolute;
    width: 94%;
    height: 100%;
    min-height: 48px;
    <?php echo $left; ?>: 100%;
    top: 0;
    border-<?php echo $left; ?>: solid 1px #999;
    z-index: 300;
    transition: <?php echo $left; ?> 0.2s;
    -ms-transition: <?php echo $left; ?> 0.2s;
    -webkit-transition: <?php echo $left; ?> 0.2s;
    -moz-transition: <?php echo $left; ?> 0.2s;
}
#pma_console .card.show {
    <?php echo $left; ?>: 6%;
    box-shadow: -2px 1px 4px -1px #999;
}

html.ie7 #pma_console .query_input {
    display: none;
}

#pma_bookmarks .content.add_bookmark,
#pma_console_options .content {
    padding: 4px 6px;
}
#pma_bookmarks .content.add_bookmark .options {
    margin-<?php echo $left; ?>: 1.4em;
    padding-bottom: .4em;
    margin-bottom: .4em;
    border-bottom: solid 1px #ccc;
}
#pma_bookmarks .content.add_bookmark .options button {
    margin: 0 7px;
    vertical-align: bottom;
}
#pma_bookmarks .content.add_bookmark input[type=text] {
    margin: 0;
    padding: 2px 4px;
}
#pma_console .button.hide,
#pma_console .message span.text.hide {
    display: none;
}
#debug_console.grouped .ungroup_queries,
#debug_console.ungrouped .group_queries {
    display: inline-block;
}
#debug_console.ungrouped .ungroup_queries,
#debug_console.ungrouped .sort_count,
#debug_console.grouped .group_queries {
    display: none;
}
#debug_console .count {
    margin-right: 8px;
}
#debug_console .show_trace .trace,
#debug_console .show_args .args {
    display: block;
}
#debug_console .hide_trace .trace,
#debug_console .hide_args .args,
#debug_console .show_trace .action.dbg_show_trace,
#debug_console .hide_trace .action.dbg_hide_trace,
#debug_console .traceStep.hide_args .action.dbg_hide_args,
#debug_console .traceStep.show_args .action.dbg_show_args {
    display: none;
}

#debug_console .traceStep:after,
#debug_console .trace.welcome:after,
#debug_console .debug>.welcome:after {
    content: "";
    display: table;
    clear: both;
}
#debug_console .debug_summary {
    float: left;
}
#debug_console .trace.welcome .time {
    float: right;
}
#debug_console .traceStep .file,
#debug_console .script_name {
    float: right;
}
#debug_console .traceStep .args pre {
    margin: 0;
}

/* Code mirror console style*/

.cm-s-pma .CodeMirror-code pre,
.cm-s-pma .CodeMirror-code {
    font-family: Monaco, Consolas, monospace;
}
.cm-s-pma .CodeMirror-measure>pre,
.cm-s-pma .CodeMirror-code>pre,
.cm-s-pma .CodeMirror-lines {
    padding: 0;
}
.cm-s-pma.CodeMirror {
    resize: none;
    height: auto;
    width: 100%;
    min-height: initial;
    max-height: initial;
}
.cm-s-pma .CodeMirror-scroll {
    cursor: text;
}

/* PMA drop-improt style */

.pma_drop_handler {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    background: rgba(0, 0, 0, 0.6);
    height: 100%;
    z-index: 999;
    color: white;
    font-size: 30pt;
    text-align: center;
    padding-top: 20%;
}

.pma_sql_import_status {
    display: none;
    position: fixed;
    bottom: 0;
    right: 25px;
    width: 400px;
    border: 1px solid #999;
    background: #f3f3f3;
    -moz-border-radius: 4px;
    -webkit-border-radius: 4px;
    border-radius: 4px;
    -moz-box-shadow: <?php echo $GLOBALS['text_dir'] === 'rtl' ? '-' : ''; ?>2px 2px 5px #ccc;
    -webkit-box-shadow: <?php echo $GLOBALS['text_dir'] === 'rtl' ? '-' : ''; ?>2px 2px 5px #ccc;
    box-shadow: <?php echo $GLOBALS['text_dir'] === 'rtl' ? '-' : ''; ?>2px 2px 5px #ccc;
}

.pma_sql_import_status h2,
.pma_drop_result h2 {
    background-color: #bbb;
    padding: .1em .3em;
    margin-top: 0;
    margin-bottom: 0;
    color: #fff;
    font-size: 1.6em;
    font-weight: normal;
    text-shadow: 0 1px 0 #777;
    -moz-box-shadow: <?php echo $GLOBALS['text_dir'] === 'rtl' ? '-' : ''; ?>1px 1px 15px #999 inset;
    -webkit-box-shadow: <?php echo $GLOBALS['text_dir'] === 'rtl' ? '-' : ''; ?>1px 1px 15px #999 inset;
    box-shadow: <?php echo $GLOBALS['text_dir'] === 'rtl' ? '-' : ''; ?>1px 1px 15px #999 inset;
}

.pma_sql_import_status div {
    height: 270px;
    overflow-y:auto;
    overflow-x:hidden;
    list-style-type: none;
}

.pma_sql_import_status div li {
    padding: 8px 10px;
    border-bottom: 1px solid #bbb;
    color: rgb(148, 14, 14);
    background: white;
}

.pma_sql_import_status div li .filesize {
    float: right;
}

.pma_sql_import_status h2 .minimize {
    float: right;
    margin-right: 5px;
    padding: 0 10px;
}

.pma_sql_import_status h2 .close {
    float: right;
    margin-right: 5px;
    padding: 0 10px;
    display: none;
}

.pma_sql_import_status h2 .minimize:hover,
.pma_sql_import_status h2 .close:hover,
.pma_drop_result h2 .close:hover {
    background: rgba(155, 149, 149, 0.78);
    cursor: pointer;
}

.pma_drop_file_status {
    color: #235a81;
}

.pma_drop_file_status span.underline:hover {
    cursor: pointer;
    text-decoration: underline;
}

.pma_drop_result {
    position: fixed;
    top: 10%;
    left: 20%;
    width: 60%;
    background: white;
    min-height: 300px;
    z-index: 800;
    -webkit-box-shadow: 0 0 15px #999;
    border-radius: 10px;
    cursor: move;
}

.pma_drop_result h2 .close {
    float: right;
    margin-right: 5px;
    padding: 0 10px;
}

.dependencies_box {
    background-color: white;
    border: 3px ridge black;
}

#composite_index_list {
    list-style-type: none;
    list-style-position: inside;
}

span.drag_icon {
    display: inline-block;
    background-image: url('<?php echo $theme->getImgPath('s_sortable.png');?>');
    background-position: center center;
    background-repeat: no-repeat;
    width: 1em;
    height: 3em;
    cursor: move;
}

.topmargin {
    margin-top: 1em;
}

meter[value="1"]::-webkit-meter-optimum-value {
    background: linear-gradient(white 3%, #E32929 5%, transparent 10%, #E32929);
}
meter[value="2"]::-webkit-meter-optimum-value {
    background: linear-gradient(white 3%, #FF6600 5%, transparent 10%, #FF6600);
}
meter[value="3"]::-webkit-meter-optimum-value {
    background: linear-gradient(white 3%, #FFD700 5%, transparent 10%, #FFD700);
}

/* styles for sortable tables created with tablesorter jquery plugin */
th.header {
    cursor: pointer;
    color: #235a81;
}

th.header:hover {
    text-decoration: underline;
}

th.header .sorticon {
    width: 16px;
    height: 16px;
    background-repeat: no-repeat;
    background-position: right center;
    display: inline-table;
    vertical-align: middle;
    float: right;
}

th.headerSortUp .sorticon, th.headerSortDown:hover .sorticon {
    background-image: url(<?php echo $theme->getImgPath('s_desc.png');?>);
}

th.headerSortDown .sorticon, th.headerSortUp:hover .sorticon {
    background-image: url(<?php echo $theme->getImgPath('s_asc.png');?>);
}
/* end of styles of sortable tables */

/* styles for jQuery-ui to support rtl languages */
body .ui-dialog .ui-dialog-titlebar-close {
    <?php echo $right; ?>: .3em;
    <?php echo $left; ?>: initial;
}

body .ui-dialog .ui-dialog-title {
    float: <?php echo $left; ?>;
}

body .ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset {
    float: <?php echo $right; ?>;
}
/* end of styles for jQuery-ui to support rtl languages */

@media only screen and (max-width: 768px) {
    /* For mobile phones: */
    #main_pane_left {
        width: 100%;
    }

    #main_pane_right {
        padding-top: 0;
        padding-<?php echo $left; ?>: 1px;
        padding-<?php echo $right; ?>: 1px;
    }

    ul#topmenu,
    ul.tabs {
        display: flex;
    }

    .navigationbar {
        display: inline-flex;
        margin: 0 !important;
        border-radius: 0 !important;
        overflow: auto;
    }

    .scrollindicator {
        padding: 5px;
        cursor: pointer;
        display: inline;
    }

    .responsivetable {
        overflow-x: auto;
    }

    body#loginform div.container {
        width: 100%;
    }

    .largescreenonly {
        display: none;
    }

    .width100, .desktop50 {
        width: 100%;
    }

    .width96 {
        width: 96% !important;
    }

    #page_nav_icons {
        display: none;
    }

    table#serverstatusconnections {
        margin-left: 0;
    }

    #table_name_col_no {
        top: 62px
    }

    .tdblock tr td {
        display: block;
    }

    #table_columns {
        margin-top: 60px;
    }

    #table_columns .tablesorter {
        min-width: 100%;
    }

    .doubleFieldset fieldset {
        width: 98%;
    }

    div#serverstatusquerieschart {
        width: 100%;
        height: 450px;
    }

    .ui-dialog {
        margin: 1%;
        width: 95% !important;
    }
}
/* templates/database/designer */
/* side menu */
#name-panel {
    overflow:hidden;
}
