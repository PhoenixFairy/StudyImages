<?php
/**
 * @author Axoford12
 * @see QQ : 847072154
 * @since 2016.6.11
 * @access ------
 * @version 1.0.2
 * Start: 2016.6.11 20:12
 * End: 2016.6.11 
 * @author Axoford12
 * This project used to help me to study php.
 * Class :  Images
 */

// set up image
$height = 200;
$width = 200;
$im = imagecreatetruecolor($width, $height);
$white = imagecolorallocate($im , 255, 255, 255);//RGB
$blue = imagecolorallocate($im, 0, 0, 100);// Blue
$red = imagecolorallocate($im, 120, 0, 0);


// draw on image
imagefill($im, 0, 0, $red);
imageline($im, 0, 0, $width/2, $height/2, $blue);
imagestring($im, 4, 50, 150, 'Squre', $white);

// out put image

Header( 'Content-type: image/png');
imagepng($im);

// clean up
imagedestroy($im);



?>