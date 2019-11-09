<?php
/**
 * This page should print 'OK' if everything works,
 * 'FAIL' or nothing if an error occured.
 */
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);

// Create a blank image and add some text
if ( ($im = imagecreatetruecolor(640, 480)) === FALSE ) {
	echo 'FAIL: imagecreatetruecolor()';
	exit(1);
}

// First we create our stamp image manually from GD
if ( ($stamp = imagecreatetruecolor(100, 70)) === FALSE ) {
	echo 'FAIL: imagecreatetruecolor()';
	exit(1);
}

if (!imagefilledrectangle($stamp, 0, 0, 99, 69, 0x0000FF)) {
	echo 'FAIL: imagefilledrectangle()';
	exit(1);
}
if (!imagefilledrectangle($stamp, 9, 9, 90, 60, 0xFFFFFF)) {
	echo 'FAIL: imagefilledrectangle()';
	exit(1);
}

if (!imagestring($stamp, 5, 20, 20, 'libGD', 0x0000FF)) {
	echo 'FAIL: imagestring()';
	exit(1);
}
if (!imagestring($stamp, 3, 20, 40, '(c) 2007-9', 0x0000FF)) {
	echo 'FAIL: imagestring()';
	exit(1);
}


// Set the margins for the stamp and get the height/width of the stamp image
$marge_right = 10;
$marge_bottom = 10;
$sx = imagesx($stamp);
$sy = imagesy($stamp);

// Merge the stamp onto our photo with an opacity of 50%
if (!imagecopymerge($im, $stamp, imagesx($im) - $sx - $marge_right, imagesy($im) - $sy - $marge_bottom, 0, 0, imagesx($stamp), imagesy($stamp), 50)) {
	echo 'FAIL: imagecopymerge()';
	exit(1);
}

// Save the image to file and free memory
if (!imagepng($im, 'image.png')) {
	echo 'FAIL: imagepng()';
	exit(1);
}
if (!imagedestroy($im)) {
	echo 'FAIL: imagedestroy()';
	unlink('image.png');
	exit(1);
}

// Remove image after test
unlink('image.png');

echo 'OK';
