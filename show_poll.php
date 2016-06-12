<?php
/**
 * @author Axoford12
 * @see QQ : 847072154
 * @since 2016.6.11
 * @version 1.1.0
 * Start: 2016.6.12 (10:45)
 * End: 2015.6.12   (10:51)
 * Updates : Part (1 -> 2);
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
     $query = 'update poll_resultss '
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