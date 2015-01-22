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


//right column
$sidebar = sidebar();
$rightColContent = wrapColumn( $sidebar, 3 );

//left column
$jumboContent = wrapJumbotron( "<h2>Welcome to your profile</h2>");
$morecontent = "<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed porttitor, lorem id luctus sodales, purus leo venenatis magna, eu gravida nisi est sed metus. Ut iaculis massa vel tortor fermentum tempus. Donec scelerisque libero quis eros varius, at fringilla magna malesuada. In scelerisque tellus a lectus mollis rutrum. Sed sodales congue imperdiet. Vestibulum eu congue nibh. Sed nec vulputate neque. Curabitur tristique, sapien nec ullamcorper hendrerit, nunc diam varius purus, at vestibulum lacus sem in arcu. Proin luctus elementum porttitor. Phasellus finibus, orci pharetra sagittis fringilla, sem ligula vulputate metus, et congue lectus arcu non enim. Phasellus maximus metus eget dolor lobortis ultricies. Pellentesque porttitor enim tortor, at auctor ipsum sollicitudin nec. Donec imperdiet arcu eu venenatis placerat. Integer accumsan metus justo, eget luctus neque malesuada commodo. Etiam ornare blandit elit vitae consectetur.

Praesent dolor dui, feugiat in erat vel, vehicula blandit tortor. Vivamus ut tortor a magna viverra blandit sit amet a felis. Nullam sit amet sapien lobortis felis ornare auctor. Sed mattis urna eu aliquet dapibus. Ut lacinia tempus libero sit amet tincidunt. Proin quam quam, varius eget suscipit quis, laoreet eget nulla. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nullam nec convallis arcu. Suspendisse efficitur congue lectus, vel vestibulum purus dictum quis.</p>";

$leftColContent = wrapColumn( $jumboContent . $morecontent, 9 );

$rowsContent = wrapRow($rightColContent . $leftColContent);







$sTitle         = "BootStrap Page";
$sTopContent    = outputTop( $sTitle, "My Profile" );
$sBottomContent = outputBottom();
$sBodyContent   = outputNavBarFix();
$sBodyContent  .= wrapContainer($rowsContent);




/**
 * OUTPUT - Make 1 large string and echo the string
 */
$sFinalOutput  = $sTopContent;
$sFinalOutput .= $sBodyContent;
$sFinalOutput .= $sBottomContent;

echo $sFinalOutput;




?>