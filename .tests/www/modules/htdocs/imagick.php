<?php
/**
 * This page should print 'OK' if everything works,
 * 'FAIL' or nothing if an error occured.
 */
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);

/* Set width and height in proportion of genuine PHP logo */
$width = 400;
$height = 210;

/* Create an Imagick object with transparent canvas */
$img = new Imagick();

if ($img->newImage($width, $height, new ImagickPixel('transparent')) !== TRUE) {
	echo 'FAIL: imagecreatetruecolor()';
	exit(1);
}

/* New ImagickDraw instance for ellipse draw */
$draw = new ImagickDraw();
/* Set purple fill color for ellipse */
$draw->setFillColor('#777bb4');
/* Set ellipse dimensions */
$draw->ellipse($width / 2, $height / 2, $width / 2, $height / 2, 0, 360);
/* Draw ellipse onto the canvas */
$img->drawImage($draw);

/* Reset fill color from purple to black for text (note: we are reusing ImagickDraw object) */
$draw->setFillColor('black');

if ($img->setImageFormat('png') !== TRUE) {
	echo 'FAIL: imagecreatetruecolor()';
	exit(1);
}


echo 'OK';
