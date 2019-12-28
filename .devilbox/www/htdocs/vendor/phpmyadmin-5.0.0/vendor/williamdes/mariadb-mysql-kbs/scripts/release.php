#!/usr/bin/env php
<?php
declare(strict_types = 1);
require_once(__DIR__ . '/../vendor/autoload.php');

use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\Builder\CliMenuBuilder;

/**
 * Manage release system
 * @license Unlicense
 */

 /**
  * Validate a version name
  *
  * @param string $version The version in format x.y.z
  * @return bool
  */
function validateVersion(string $version): bool
{
    return preg_match("/([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})/", $version) === 1;
}

/**
 * Version to int
 *
 * @param string $version The version in format x.y.z
 * @return int
 */
function versionToInt(string $version): int
{
    $matchs = array();
    preg_match("/([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})/", $version, $matchs);
    return (int) ( sprintf('%03d', $matchs[1]).sprintf('%03d', $matchs[2]).sprintf('%03d', $matchs[3]) );
}

/**
 * Get the version from a JSON file with attribute version
 *
 * @param string $filename The filename
 * @return int
 */
function getVersion(string $filename): int
{
    $version = json_decode(file_get_contents($filename))->version;
    return versionToInt($version);
}

/**
 * Int to version
 *
 * @param int $version The version in int format
 * @example $version 123456789 for 123.456.789
 * @example $version 1010020 for 1.10.20
 * @return string
 */
function intToVersion(int $version): string
{
    $major = ($version / 1000000) | 0;
    $minor = ( ($version / 1000) - ($major * 1000) ) | 0;
    $patch = $version - (($major * 1000000) + ($minor * 1000));
    return $major.".".$minor.".".$patch;
}

/**
 * Saves files (composer, package)
 *
 * @param string $composerSaveVersion The actual composer version
 * @param string $npmSaveVersion      The actual npm version
 * @return void
 */
function saveFiles(string $composerSaveVersion, string $npmSaveVersion): void
{
    global $composerActualData, $npmActualData;
    $composerActualData->version = $composerSaveVersion;
    $npmActualData->version      = $npmSaveVersion;
    $flags                       = JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES;
    file_put_contents(__DIR__."/../composer.json", json_encode($composerActualData, $flags).PHP_EOL);
    file_put_contents(__DIR__."/../package.json", json_encode($npmActualData, $flags).PHP_EOL);
}

/*
// Test data
echo "#1  ".intToVersion(123456789).PHP_EOL;
echo "#2  ".versionToInt("1.10.20").PHP_EOL;
echo "#3  ".intToVersion(100400100).PHP_EOL;
echo "#4  ".intToVersion(1010020).PHP_EOL;
echo "#5  ".getVersion(__DIR__."/../composer.json").PHP_EOL;
echo "#6  ".intToVersion(getVersion(__DIR__."/../composer.json")).PHP_EOL;
*/
echo "Release a new version".PHP_EOL;
$composerActualData = json_decode(file_get_contents(__DIR__."/../composer.json"));
$npmActualData      = json_decode(file_get_contents(__DIR__."/../package.json"));

$composerActualVersion = $composerActualData->version;
$npmActualVersion      = $npmActualData->version;

$composerSaveVersion = $composerActualVersion;
$npmSaveVersion      = $npmActualVersion;



$itemCallable = function (CliMenu $menu): void {
    global $composerActualVersion, $npmActualVersion, $composerSaveVersion, $npmSaveVersion;
    switch ($menu->getSelectedItem()->getText()) {
        case 'Major':
            $composerSaveVersion = intToVersion(versionToInt($composerSaveVersion) + 1000000);
            $npmSaveVersion      = intToVersion(versionToInt($npmSaveVersion) + 1000000);
            break;
        case 'Minor':
            $composerSaveVersion = intToVersion(versionToInt($composerSaveVersion) + 1000);
            $npmSaveVersion      = intToVersion(versionToInt($npmSaveVersion) + 1000);
            break;
        case 'Patch':
            $composerSaveVersion = intToVersion(versionToInt($composerSaveVersion) + 1);
            $npmSaveVersion      = intToVersion(versionToInt($npmSaveVersion) + 1);
            break;
    }

    $menu->close();
    echo "Saved versions, composer: $composerSaveVersion, npm: $npmSaveVersion";
    saveFiles($composerSaveVersion, $npmSaveVersion);
};

$cbManual = function (CliMenu $menu): void {
    global $composerActualVersion, $npmActualVersion, $composerSaveVersion, $npmSaveVersion;
    $result = $menu->askPassword()
        ->setPromptText("Actual composer version: $composerActualVersion")
        ->setPlaceholderText($composerActualVersion)
        ->setValidationFailedText('Invalid version, try again')
        ->setValidator(
            function ($version) {
                return validateVersion($version);
            }
        )->ask();

    $composerSaveVersion = $result->fetch();

    $result         = $menu->askPassword()
        ->setPromptText("Actual npm version: $npmActualVersion")
        ->setPlaceholderText($composerActualVersion)
        ->setValidationFailedText('Invalid version, try again')
        ->setValidator(
            function ($version) {
                                return validateVersion($version);
            }
        )->ask();
    $npmSaveVersion = $result->fetch();
    $menu->close();
    echo "Saved versions, composer: $composerSaveVersion, npm: $npmSaveVersion";
    saveFiles($composerSaveVersion, $npmSaveVersion);
};

$menu = (new CliMenuBuilder())->setTitle('Bump version')
    ->addItem('Major', $itemCallable)
    ->addItem('Minor', $itemCallable)
    ->addItem('Patch', $itemCallable)
    ->addItem('Manual', $cbManual)
    ->addLineBreak('-')
    ->setPadding(2, 4)
    ->setMarginAuto()
    ->build();

$menu->open();
