<?php
/**
 * @author Axoford12
 * @see QQ : 847072154
 * @since 2016.6.11
 * -------------------------------
 * @version 1.1.0
 * -------------------------------
 * Start: 2016.6.12 (10:45)
 * End: 2016.6.12   (10:51)
 * Updates : Part (1 -> 2);
 * -------------------------------
 * @version 1.1.1
 * -------------------------------
 * Start: 2016.6.13 (10:46)
 * End: 2016.6.13   (11:44)
 * Updates : Part(3 -> 4);
 */
/*********************************************
 * Database query to get poll into  <Part 1> *                        
 *********************************************/
 
/**
 * @method Get vote from form
 */
 $vote = $_REQUEST['vote'];
 
 
 
 /**
  * @method Login in to database
  */
 if (!$db_conn = new mysqli('localhost','poll','poll','poll')){
     echo 'Could not connect to database<br />';
     exit();
 }
 
 
 /**
  * @method
  * If they filled the form out, add their vote
  */
 
 if (!empty($vote)){
     $vote = addslashes($vote);
     $query = 'update poll_results '
             .'set num_votes = num_votes + 1 '
             ."where candidate = '$vote'";
     if(!$result = @$db_conn->query($query)){
         echo 'Could not connect to database<br />';
         exit();
     }
 }
 
 
 /**
  * @method
  * Get current results of poll, regardless of whether they voted
  * 
  */
$query = 'select * from poll_results';
if(!$result = @$db_conn->query($query)){
    echo 'Could not connect to database<br />';
    exit();
}

$num_candidates = $result->num_rows;

/**
 * @method
 * Calculate total number of votes so far
 */
$total_votes = 0;
while(@$row = $result->fetch_object()){
    $total_votes += $row->num_votes;
}

/**
 * @method
 * Reset result pointer
 */
$result->data_seek(0);

/*****************************************
 * Inital calculations for graph <Part 2>*
 *****************************************/



/**
 * @method
 * Set up some constants
 */
putenv('GDFONTPATH=C:\WINDOWS\Fonts');

/**
 * @var width
 * width of image in pixels - this will fit in 640*480.
 * @var left_margin
 * Space to leave on left of graph
 * @var right_margin
 * Ditto right
 */
$width = 40;
$left_margin = 50;
$right_margin = 40;

$bar_height = 40;
$bar_spacing = $bar_height / 2;
$font = 'arial';
/**
 * @var tittle_size
 * @var main_size
 * @var small_size
 * Points
 * 
 * @var text_inden
 * Position for text lables from edge of image
 */
$title_size = 16;# Point
$main_size = 12;# Point
$small_size = 12;# Point
$text_indent = 10;# Position for text lables from edge of image

/**
 * @method
 * Set up initial point to draw form
 * @var x
 * Place t draw baseline of the graph
 * @var y
 * Ditto
 * @var bar_unit
 * One Point on the graph
 */
$x = $left_margin + 60;# Place t draw baseline of the graph
$y = 50;# Ditto
$bar_unit = ($width - ($x + $right_margin)) / 100;# One Point on the graph


/**
 * @method
 * Calculate height of graph - bars plus some margin
 */
$height = $num_candidates * ($bar_height + $bar_spacing) + 50;


/************************************
 * Set up base image  <Part 3>      *
 ************************************/

/**
 * @todo Create a blank canvas
 */
 $im = imagecreatetruecolor($width, $height);
 
 
 /**
  * @todo Allocate colors
  * 
  */
 
 $white = imagecolorallocate($im, 255, 255, 255);
 $blue = imagecolorallocate($im, 0, 64, 128);
 $black = imagecolorallocate($im, 0, 0, 0);
 $pink = imagecolorallocate($im, 255, 78, 243);
 
 $text_color = $black;
 $percent_color = $black;
 $bg_color = $white;
 $line_color = $black;
 $bar_color = $blue;
 $number_color = $pink;
 
 
 /**
  * @todo Create 'canvas' to draw on.
  */
 imagefilledrectangle($im, 0, 0, $width, $height, $bg_color);
 
 /**
  * @todo Draw outline around canvas.
  */
 
imagerectangle($im, 0, 0, $width-1, $height-1, $line_color);

/**
 * @todo Add title
 */
$title = 'Poll Results';
$title_dimensions = imagettfbbox($title_size, 0, $font, $title);
$title_length = $title_dimensions[2] - $title_dimensions[1];
$title_height = abs($title_dimensions[7] - $title_dimensions[1]);
$title_above_line = abs($title_dimensions[7]);
$title_x = ($width - $title_length) / 2;#  Center in x
$title_y = ($y - $title_height) / 2 + $title_above_line;# Center in y graph 
imagettftext($im, $title_size, 0, $title_x, $title_y, $text_color, $font, $title);


/** 
 * @todo Draw a base line from a little above first bar location
 * To a little below last
 */

imageline($im,$x,$y-5,$x,$height-15,$line_color);


/*********************************************
 *        Draw data into graph    <Part 4>   *    
 *********************************************/


/**
 * @todo Get each line of database date and draw corresponding bars
 */

while (@$row = $result->fetch_object()){
    if ($total_votes > 0) {
        $percent = intval(($row->num_votes / $total_votes) * 100);
        
    } else {
        $percent = 0;
    }
    # Display percent for this value.
    $percent_dimensions = imagettfbbox($main_size, 0, $font, $percent.'%');
    $percent_length = $percent_dimensions[2] - $percent_dimensions[0];
    imagettftext($im, $main_size, 0, $width-$percent_length-$text_indent, $y+($bar_height / 2), $percent_color, $font, $percent.'%');
    
    # Length for bar for this value
    $bar_length = $x + ($percent * $bar_unit);
    
    # Draw bar for this value
    imagefilledrectangle($im, $x, $y-2, $bar_length, $y+$bar_height, $bar_color);
    
    # Draw title for this value
    imagerectangle($im, $bar_length+1,
        $y-2, ($x+(100*$bar_unit)), 
        $y+$bar_height, $line_color);
    
    # Display numbers
    
    imagettftext($im, $small_size, 0, $x+(100*$bar_unit)-50, $y+($bar_height/2),
         $number_color, $font, $row->num_votes.'/'.$total_votes);
    
    # Move down to next bar
    $y = $y + ($bar_height+$bar_spacing);
    
}



/*************************************
 *   Display image                   *
 *************************************/
header('Content-type: image/png');
imagepng($im);



/*************************************
 *   Clean up                        *
 *************************************/

imagedestroy($im);
?>