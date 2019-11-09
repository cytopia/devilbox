<?php
/**
 * This page should print 'OK' if everything works,
 * 'FAIL' or nothing if an error occured.
 */
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);

// Create a blank image and add some text
if ( ($im = imagecreatetruecolor(120, 20)) === FALSE ) {
	echo 'FAIL: imagecreatetruecolor()';
	exit(1);
}

if ( ($text_color = imagecolorallocate($im, 233, 14, 91)) === FALSE ) {
	echo 'FAIL: imagecolorallocate()';
	exit(1);
}

if (!imagestring($im, 1, 5, 5,  'WebP with PHP', $text_color)) {
	echo 'FAIL: imagestring()';
	exit(1);
}

// Save the image
if (!imagewebp($im, 'image.webp')) {
	echo 'FAIL: imagewebp()';
	exit(1);
}

// Free up memory
if (!imagedestroy($im)) {
	echo 'FAIL: imagedestroy()';
	unlink('image.webp');
	exit(1);
}

// Remove image after test
unlink('image.webp');

echo 'OK';
