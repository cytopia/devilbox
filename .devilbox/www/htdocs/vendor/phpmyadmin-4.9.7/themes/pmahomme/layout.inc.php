<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * configures general layout
 * for detailed layout configuration please refer to the css files
 *
 * @package    PhpMyAdmin-theme
 * @subpackage PMAHomme
 */

/**
 * navi frame
 */
// navi frame width
$GLOBALS['cfg']['NaviWidth']                = 240;

// foreground (text) color for the navi frame
$GLOBALS['cfg']['NaviColor']                = '#000';

// background for the navi frame
$GLOBALS['cfg']['NaviBackground']           = '#f3f3f3';

// foreground (text) color of the pointer in navi frame
$GLOBALS['cfg']['NaviPointerColor']         = '#000';

// background of the pointer in navi frame
$GLOBALS['cfg']['NaviPointerBackground']    = '#ddd';

/**
 * main frame
 */
// foreground (text) color for the main frame
$GLOBALS['cfg']['MainColor']                = '#444';

// background for the main frame
$GLOBALS['cfg']['MainBackground']           = '#fff';

// foreground (text) color of the pointer in browse mode
$GLOBALS['cfg']['BrowsePointerColor']       = '#000';

// background of the pointer in browse mode
$GLOBALS['cfg']['BrowsePointerBackground']  = '#cfc';

// foreground (text) color of the marker (visually marks row by clicking on it)
// in browse mode
$GLOBALS['cfg']['BrowseMarkerColor']        = '#000';

// background of the marker (visually marks row by clicking on it) in browse mode
$GLOBALS['cfg']['BrowseMarkerBackground']   = '#fc9';

/**
 * fonts
 */
/**
 * the font family as a valid css font family value,
 * if not set the browser default will be used
 * (depending on browser, DTD and system settings)
 */
$GLOBALS['cfg']['FontFamily']           = 'sans-serif';
/**
 * fixed width font family, used in textarea
 */
$GLOBALS['cfg']['FontFamilyFixed']      = 'monospace';

/**
 * tables
 */
// border
$GLOBALS['cfg']['Border']               = 0;
// table header and footer color
$GLOBALS['cfg']['ThBackground']         = '#D3DCE3';
// table header and footer background
$GLOBALS['cfg']['ThColor']              = '#000';
// table data row background
$GLOBALS['cfg']['BgOne']                = '#E5E5E5';
// table data row background, alternate
$GLOBALS['cfg']['BgTwo']                = '#D5D5D5';
