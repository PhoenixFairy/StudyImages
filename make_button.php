<?php
/**
 * @author Axoford12
 * @see QQ : 847072154
 * @since 2016.6.11
 * @version 1.0.1
 * Start: 2016.6.11 20:12
 * End: 2016.6.11 20:46
 * @version 1.0.2
 * Start: 2016.6.11 21:21
 * End: 2016.6.11 21:35
 * 
 */

// Check we have the appropriate variable data
// Variables are button-text and color

/**
 * 
 * @var button_text
 * @todo buton-text
 * @example 'Submit'
 */
$button_text = $_REQUEST[ 'button_text'];

/**
 * 
 * @var color
 * @todo button's color
 * 
 */
$color = $_REQUEST[ 'color'];

/**
 * @todo Check Params exits.
 * If Param not correctly , print error message.
 */
if(empty($button_text) || empty($color)){
    echo 'Could not create image - form not filled out correctly';
    exit();
}


/**
 * Create an image of right backround and check size.
 * @var im
 */
$im = imagecreatefrompng($color.'-button.png');


/** 
 * @var width_image
 * @var height_image
 * 
 * get Image size.
 */
$width_image = imagesx($im);
$height_image = imagesy($im);

/**
 * @todo Our images need an 18 pixel margin in from the edge of the image
 * @var width_image_wo_margins
 * @var height_image_wo_margins
 * Image we need's size.
 * 
 */

$width_image_wo_margins = $width_image - (2 * 18);
$height_image_wo_margins = $height_image - (2 * 18);

/**
 * @todo
 * Work out if the font will fit and make it smaller until does
 * Start out with the biggesy size that will resonably fit on our buttons
 * @var font_size
 * Variable used to verify font size
 */
$font_size = 33;


/**
 * @todo
 * You need to tell GD2 where your fonts reside
 * putenv();
 * @var font_name
 * This Vairiable used to work out font_name
 */
putenv('GDFONTPATH=C:\WINDOWS\Fonts');
$fontname = 'arial';


/**
 * @todo
 * Find out the size of the text at that font size
 */
do {
    $font_size--;
    
    $bbox = imagettfbbox($font_size, 0, $fontname, $button_text);
    /**
     * 
     * @var right_text
     * @var left_text
     * right and left co-ordinate
     */
    $right_text = $bbox[2];
    $left_text = $bbox[0];

    
    
    /**
     * 
     * @var width_text
     * How wide is it?
     * @var height_text
     * How tall is it?
     */
    $width_text = $right_text - $left_text;
    $height_text = abs($bbox[7]) - $bbox[1];

    
}
while( $font_size>8 &&
        ( $height_text>$height_image_wo_margins ||
          $width_text>$width_image_wo_margins )
    );
/**
 * @see End : 2016.6.11  20:46
 * @see Start : 2016.6.11 21:21
 */
if ( $height_text>$height_image_wo_margins ||
     $width_text>$width_image_wo_margins ){
    /**
     * This means "No readable font size will fit on button"
     */
    echo 'Text given will not fit on button.<br />';
} else {
     
    /**
     * @todo
     * We have found a font size that will fit.
     * Now work out where to put it.
     */
    
    $text_x = $width_image/2.0 - $width_text/2.0 ;
    $text_y = $height_image/2.0 - $height_text ;
    
    if ($left_text < 0){
        $text_x += abs($left_text);
        /**
         * Add factor for left overhang
         */
    }
    
    
    /**
     * @var above_line_text
     * How far above the baseline?
     * @todo
     * Add baseline factor
     */
    $above_line_text = abs($bbox[7]);
    $text_y += $above_line_text;
    
    /**
     * @todo 
     * adjustment factor for shape of our template.
     */
    
    $text_y -= 2;
    
    $white = imagecolorallocate($im, 255, 255, 255);
    
    imagettftext($im, $font_size, 0, $text_x, $text_y, $white, $fontname, $button_text);
    
    header('Content-type: image/png');
    imagepng ($im);
}
imagedestroy($im);
/**
 * @see End 2016.6.11 21:35
 */

?>