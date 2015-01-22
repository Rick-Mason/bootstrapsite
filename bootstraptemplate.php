<?php
session_start();
include_once( "lib/error_functions.php" );
include_once( "lib/config.php" );
include_once( "lib/wrapper_functions.php" );
include_once( "lib/db_connect.php" );
include_once( "lib/scripts.php");


/**
 * VARS - Initialize variables for later use
 */


/**
 * PROCESSING - Process the form
 */



/**
 * HTML - Contstruct the page
 */

$sTitle         = "BootStrap Page";
$sTopContent    = outputTop( $sTitle, "" );
$sBottomContent = outputBottom();
$sBodyContent   = outputNavBarFix();



/**
 * OUTPUT - Make 1 large string and echo the string
 */
$sFinalOutput  = $sTopContent;
$sFinalOutput .= $sBodyContent;
$sFinalOutput .= $sBottomContent;

echo $sFinalOutput;




?>