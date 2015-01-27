<?php
session_start();
include_once( "lib/error_functions.php" );
include_once( "lib/config.php" );
include_once( "lib/wrapper_functions.php" );
include_once( "lib/db_connect.php" );
include_once( "lib/scripts.php");

var_dump( $_FILES );

/**
 * VARS - Initialize variables for later use
 */
$sErrorMsg = "";

$errorImage = false;

/**
 * PROCESSING - Process the form
 */

if ( isset( $_POST[ 'submit' ]) ) {
	
	$sNewImageName = uniqid( time() ) . ".png";

	// list takes and array and puts each value into the variables
	// in the list. Its a nice shortcut for converting array contents
    // into individual variables
	list( 	$iOriginalWidth, 
			$iOriginalHeight,
			$iOriginalType,
			$sAttributes 
		)  = getimagesize( $_FILES[ 'inputImage' ][ 'tmp_name' ] );

	// scale is used to keep the aspect ratio constant as you 
	// reduce the size of an image.
	$fScale = $iOriginalWidth / $iOriginalHeight;

	// this function returns mime type "image/jpeg"
	$sMimeType = image_type_to_mime_type( $iOriginalType );

	$rSourceImage = imagecreatefrompng( $_FILES[ 'inputImage' ][ 'tmp_name'] );

	$iNewWidth = ( $iOriginalWidth > IMAGE_MAX_WIDTH ) 
					? IMAGE_MAX_WIDTH : $iOriginalWidth;

	$iNewHeigth = intval( $iNewWidth / $fScale );

	$rDstImage = imagecreatetruecolor( $iNewWidth, $iNewHeigth );

	imagecopyresampled( $rDstImage, 
						$rSourceImage, 
						0, 
						0, 
						0, 
						0, 
						$iNewWidth, 
						$iNewHeigth,
						$iOriginalWidth,
						$iOriginalHeight
						);
	imagepng( $rDstImage, "images/large/" . $sNewImageName );


}

/**
 * HTML - Contstruct the page
 */


//right column
$sidebar = sidebar();
$rightColContent = wrapColumn( $sidebar, 3 );

//left column
$jumboContent = wrapJumbotron( "<h2>Add/Edit Profile Image</h2>");
if( $errorImage ){
	$sErrorMsg = wrapAlert( "danger", 
							"ERROR!", 
							"Please choose an image.");
}
$inputImage = formFiles (	"inputImage", 
							"Choose an image.");

$inputSubmit = formSubmit( "submit", "Add Image");

$sCompleteForm = wrapFormTags( 
								$inputImage . $inputSubmit, 
								"post", 
								$_SERVER['PHP_SELF'],
								true
							  );

 
$moreContent = wrapContainer($sCompleteForm);



$leftColContent = wrapColumn( $jumboContent . $moreContent, 9 );
$rowContent = wrapRow( $rightColContent . $leftColContent  );




$sTitle         = "BootStrap Page";
$sTopContent    = outputTop( $sTitle, "Add Edit Profile Description" );
$sBottomContent = outputBottom();
$sBodyContent   = outputNavBarFix();
$sBodyContent  .= wrapContainer( $rowContent );




/**
 * OUTPUT - Make 1 large string and echo the string
 */
$sFinalOutput  = $sTopContent;
$sFinalOutput .= $sBodyContent;
$sFinalOutput .= $sBottomContent;

echo $sFinalOutput;




?>