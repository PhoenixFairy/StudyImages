<?php
/**
 * @author Axoford12
 * @see QQ : 847072154
 * @since 2016.6.11
 * @access ------
 * @version 1.0.0 
 * Date: 201.6.11 19:48
 * @author Axoford12
 * This project used to help me to study php.
 * Class :  Images
 * 
 */

// set up image
$height = 200;
$width = 200;
$im = imagecreatetruecolor($width, $height);
$white = imagecolorallocate($im , 255, 255, 255);//RGB
$blue = imagecolorallocate($im, 0, 0, 64);// Blue


// draw on image
imagefill($im, 0, 0, $blue);
imageline($im, 0, 0, $width, $height, $white);
imagestring($im, 4, 50, 150, 'Sales', $white);

// out put image

Header( 'Content-type: image/png');
imagepng($im);

// clean up
imagedestroy($im);



?>