<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * Displays list of themes.
 *
 * @package PhpMyAdmin
 */

use PhpMyAdmin\Core;
use PhpMyAdmin\ThemeManager;
use PhpMyAdmin\Response;

/**
 * get some globals
 */
require './libraries/common.inc.php';

$response = Response::getInstance();
$response->getFooter()->setMinimal();
$header = $response->getHeader();
$header->setBodyId('bodythemes');
$header->setTitle('phpMyAdmin - ' . __('Theme'));
$header->disableMenuAndConsole();

$hash    = '#pma_' . preg_replace('/([0-9]*)\.([0-9]*)\..*/', '\1_\2', PMA_VERSION);
$url     = Core::linkURL('https://www.phpmyadmin.net/themes/') . $hash;
$output  = '<h1>phpMyAdmin - ' . __('Theme') . '</h1>';
$output .= '<p>';
$output .= '<a href="' . $url . '" rel="noopener noreferrer" target="_blank">';
$output .= __('Get more themes!');
$output .= '</a>';
$output .= '</p>';
$output .= ThemeManager::getInstance()->getPrintPreviews();

$response->addHTML($output);
