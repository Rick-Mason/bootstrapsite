<?php
session_start();
include_once( "lib/error_functions.php" );
include_once( "lib/config.php" );
include_once( "lib/wrapper_functions.php" );
include_once( "lib/db_connect.php" );
include_once( "lib/scripts.php" );


if ( isset( $_GET ['user_id'] )) {
	$iUserId = (int) $_GET ['user_id'];
}

if ( 0 === $iUserId ) {
	header( "Location: profiles.php" );
}


// data from user and user_image in one joined query
$sql = "SELECT 
  			user.user_name,
   			user_image.image_name 
		FROM 
			user 
		LEFT JOIN 
			user_image 
		ON 
			user.user_id = user_image.user_id
		WHERE 
			user.user_id = $iUserId";

$stmt = $dbh->query( $sql );
$row = $stmt->fetch( PDO::FETCH_ASSOC );
$sUserName = $row ['user_name'];

if ( $row ['image_name'] ) {
	$sImagePath = IMAGE_BASE_PATH . $row ['image_name'];
	$sImageString = wrapImageTag( $sImagePath );
} else {
	$sImageString = "<h3>No Image on File</h3>";
}


//data from user_description
$sql = "SELECT 
			user_description
		FROM 
			user_description
		WHERE
			user_id = $iUserId";
$stmt = $dbh->query( $sql );
$row  = $stmt->fetch( PDO::FETCH_ASSOC );
if ( $row ['user_description'] ) {
	$sUserDescription = html_entity_decode( $row ['user_description'] );
} else {
	$sUserDescription = "<h3>No paragraph on file</h3>";
}


//data from user_personal_info
$sql = "SELECT 
			user_first,
			user_last
		FROM	
			user_personal_info
		WHERE
			user_id = $iUserId";
$stmt = $dbh->query( $sql );
$row  = $stmt->fetch( PDO::FETCH_ASSOC );

if ( $row ['user_first'] ) {
	$sUserFirstAndLast = ucfirst( $row ['user_first'] ) 
						 . " "
						 . ucfirst ( $row ['user_last'] );
} else {
	$sUserFirstAndLast = "No First and Last name on file.";
}

/**
 * HTML - Contstruct the page
 */

$jumboContent = wrapJumbotron( "<h1>$sUserName</h1>" );

//right column
$rightColContent = wrapColumn( $sImageString , 6 );

//left column
$morecontent  = "<h2>$sUserFirstAndLast</h2>";
$morecontent .= $sUserDescription;
$leftColContent = wrapColumn( $morecontent, 6 );

$rowsContent = wrapRow( $rightColContent . $leftColContent );







$sTitle         = "View Profile";
$sTopContent    = outputTop( $sTitle, "Profiles" );
$sBottomContent = outputBottom();
$sBodyContent   = outputNavBarFix();
$sBodyContent  .= wrapContainer( $jumboContent );
$sBodyContent  .= wrapContainer( $rowsContent );




/**
 * OUTPUT - Make 1 large string and echo the string
 */
$sFinalOutput  = $sTopContent;
$sFinalOutput .= $sBodyContent;
$sFinalOutput .= $sBottomContent;

echo $sFinalOutput;




?>