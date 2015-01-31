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

try { 
	$sql = "SELECT
				image_name
			FROM 
				user_image
			WHERE 
				user_id = {$_SESSION[ 'user_id' ]}";
	$stmt = $dbh->query( $sql );
	$row = $stmt->fetch( PDO::FETCH_ASSOC );
	if ( $row ) {	
		$sImageName = $row[ 'image_name' ];
		$isEdit = true;
	} else {
		$isEdit = false;
	}
} catch ( PDOException $e ) {
	echo $e;
}

$sErrorMsg = "";
$errorImage = false;


/**
 * PROCESSING - Process the form and delete image link
 */

if ( isset( $_GET[ 'formaction' ] )
		&& "delete_image" == $_GET[ 'formaction' ] ) {
	$sql = "DELETE FROM 
				user_image 
			WHERE 
				user_id = {$_SESSION[ 'user_id' ]}";
	$stmt = $dbh->query( $sql );
	$checkUnlink = unlink( IMAGE_BASE_PATH . $sImageName );
	$isEdit = false;
}



if ( isset( $_POST[ 'submit' ] )) {
	

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

	// this function returns mime type ex. "image/jpeg"
	$sMimeType = image_type_to_mime_type( $iOriginalType );


	//we create a new "layer" from the image currently residing
	//in the temp directory
	switch ( $sMimeType ) {
		case 'image/gif':
			$rSourceImage = imagecreatefromgif( $_FILES[ 'inputImage' ][ 'tmp_name'] );
			$sNewImageName = uniqid() . ".gif";
			break;
		case 'image/jpeg':
			$rSourceImage = imagecreatefromjpeg( $_FILES[ 'inputImage' ][ 'tmp_name'] );
			$sNewImageName = uniqid() . ".jpg";
			break;
		case 'image/png':
			$rSourceImage = imagecreatefrompng( $_FILES[ 'inputImage' ][ 'tmp_name'] );
			$sNewImageName = uniqid() . ".png";
			break;
		default :
			$errorImage = true;
			$sErrorMsg = "Image type not supported. Use .gif/.png/.jpg/.jpeg";
			break;
	}
	
	if ( !$errorImage ) { 
		$iNewWidth = ( $iOriginalWidth > IMAGE_MAX_WIDTH ) 
						? IMAGE_MAX_WIDTH : $iOriginalWidth;

		$iNewHeigth = intval( $iNewWidth / $fScale );


		//create a new "blank canvas" that we can put our image on
		//imagecreatetruecolor() returns a resource that can 
		//be acted upon or used in conjuction with other functions
		//in our example we will be drawing the "source image"
		//on top of this one and resizing along the way
		$rDstImage = imagecreatetruecolor( $iNewWidth, $iNewHeigth );

		// This function "resamples" from the "source" image
		// onto the "destination" image. It smoothly interpolates 
		// pixel values so that we may easily reduce the size of
		// the image. The resource that the image is resampled to
		// is specifically the image resource represented in the first
		// argument. For the purpose of the example below we are starting
		// at the top left of the source image [0,0] and mapping it
		// pixel by pixel to the "Destination" image starting at coordinates
		// [0,0] or top left. 
		imagecopyresampled( $rDstImage, 
							$rSourceImage, 
							0, //destination img x coordinate
							0, //destination img y coordinate
							0, //source img x coordinate
							0, //source img y coord
							$iNewWidth, //new width for resource in first arg
							$iNewHeigth, //new height for resource in first arg
							$iOriginalWidth,
							$iOriginalHeight
							);


		// In the appropriate function below we are telling PHP to take
		// the new "Destination Image" that we created above and place it
		// in our file system. The function creates the final image
		// and places it in the directory and path according to the second
		// argument given.
		switch ( $sMimeType ) {
			case 'image/gif':
				imagegif( $rDstImage, IMAGE_BASE_PATH . $sNewImageName );
				break;
			case 'image/jpeg':
				imagejpeg( $rDstImage, IMAGE_BASE_PATH . $sNewImageName );
				break;
			case 'image/png':
				imagepng( $rDstImage, IMAGE_BASE_PATH . $sNewImageName );
				break;
		}

		try {
			$sql = "INSERT INTO 
						user_image
					SET 
						user_id 	= {$_SESSION[ 'user_id' ]},
						image_name 	= '$sNewImageName'";
			$stmt = $dbh->query( $sql );
			$isEdit = true;
			$sImageName = $sNewImageName;
		} catch ( PDOException $e ){
			echo $e;
		}
		
	}
	


}

/**
 * HTML - Contstruct the page
 */


//right column
$sidebar = sidebar();
$rightColContent = wrapColumn( $sidebar, 3 );

//left column
$jumboContent = wrapJumbotron( "<h2>Add/Edit Profile Image</h2>" );
if ( $errorImage ){
	$sErrorMsg = wrapAlert( "danger", 
							"ERROR!", 
							"Please choose an image." );
}
$inputImage = formFiles (	"inputImage", 
							"Choose an image." );

$inputSubmit = formSubmit( "submit", "Add Image" );

$sCompleteForm = wrapFormTags( 
								$inputImage . $inputSubmit, 
								"post", 
								$_SERVER[ 'PHP_SELF' ],
								true
							  );

if ( $isEdit ) {
 	//build the image from the database.
 	$imagePath = IMAGE_BASE_PATH . $sImageName;
 	$moreContent = wrapImageTag( $imagePath );
 	$moreContent .= "<br />";

 	//create a delete image button
 	$sDeleteUrl = $_SERVER['PHP_SELF'] . "?formaction=delete_image";
 	$moreContent .= wrapLinkButton( "btn btn-warning btn-lg", $sDeleteUrl, "Delete Image" );
 } else {
 	$moreContent = wrapContainer($sCompleteForm);
 }




$leftColContent = wrapColumn( $jumboContent . $moreContent, 9 );
$rowContent = wrapRow( $rightColContent . $leftColContent  );


$sTitle         = "Profile Image";
$sTopContent    = outputTop( $sTitle, "Add/Edit Profile Image" );
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