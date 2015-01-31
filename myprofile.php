<?php
session_start();
include_once( "lib/error_functions.php" );
include_once( "lib/config.php" );
include_once( "lib/wrapper_functions.php" );
include_once( "lib/db_connect.php" );
include_once( "lib/scripts.php" );



/**
 * Pull all relavent data from the database in one query
 */

 
	




/*$sql = "SELECT 	
			user.user_name,
			user.user_email,
			user_description.user_description,
			user_personal_info.user_phone,
			user_personal_info.user_first,
			user_personal_info.user_last,
			user_image.image_name
		FROM 
			user, 
			user_description, 
			user_personal_info,
			user_image
		WHERE 
			user.user_id = {$_SESSION[ 'user_id' ]}
		AND
			user.user_id = user_description.user_id
		AND 
			user.user_id = user_personal_info.user_id
		AND 
			user.user_id = user_image.user_id";*/

// data from user and user_image in one joined query
$sql = "SELECT 
			user.user_id, 
  			user.user_name,
  			user.user_email, 
   			user_image.image_name 
		FROM 
			user 
		LEFT JOIN 
			user_image 
		ON 
			user.user_id = user_image.user_id
		WHERE 
			user.user_id = {$_SESSION[ 'user_id' ]}";

$stmt = $dbh->query( $sql );
$row = $stmt->fetch( PDO::FETCH_ASSOC );
$sUserEmail = $row[ 'user_email' ];
$sUserName = $row[ 'user_name' ];

if( $row[ 'image_name' ] ) {
	$sImagePath = IMAGE_BASE_PATH . $row[ 'image_name' ];
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
			user_id = {$_SESSION[ 'user_id' ]}";
$stmt = $dbh->query( $sql );
$row  = $stmt->fetch( PDO::FETCH_ASSOC );
if ( $row[ 'user_description' ] ) {
	$sUserDescription = html_entity_decode( $row[ 'user_description' ] );
} else {
	$sUserDescription = "<h3>No paragraph on file</h3>";
}


//data from user_personal_info
$sql = "SELECT 
			user_first,
			user_last,
			user_phone
		FROM	
			user_personal_info
		WHERE
			user_id = {$_SESSION[ 'user_id' ]}";
$stmt = $dbh->query( $sql );
$row  = $stmt->fetch( PDO::FETCH_ASSOC );

if ( $row[ 'user_first' ] ) {
	$sUserFirstAndLast = ucfirst( $row[ 'user_first' ] ) 
						 . " "
						 . ucfirst ( $row[ 'user_last' ] );
} else {
	$sUserFirstAndLast = "No First and Last name on file.";
}
if( $row[ 'user_phone' ] ) {
	$sUserPhone = formatPhone( $row[ 'user_phone' ] );
} else {
	$sUserPhone = "No Phone number on file.";
}









/**
 * HTML - Contstruct the page
 */


//right column
$sidebar = sidebar();
$rightColContent = wrapColumn( $sidebar, 3 );

//left column



$jumboContent = wrapJumbotron( "<h1>$sUserName</h1>" );
$morecontent =  $sImageString;
$morecontent .= "<h2>$sUserFirstAndLast</h2>";
$morecontent .= "<h4>$sUserPhone</h4>";
$morecontent .= "<p>$sUserEmail</p>";
$morecontent .= $sUserDescription;

$leftColContent = wrapColumn( $jumboContent . $morecontent, 9 );

$rowsContent = wrapRow( $rightColContent . $leftColContent );







$sTitle         = "BootStrap Page";
$sTopContent    = outputTop( $sTitle, "My Profile" );
$sBottomContent = outputBottom();
$sBodyContent   = outputNavBarFix();
$sBodyContent  .= wrapContainer( $rowsContent );




/**
 * OUTPUT - Make 1 large string and echo the string
 */
$sFinalOutput  = $sTopContent;
$sFinalOutput .= $sBodyContent;
$sFinalOutput .= $sBottomContent;

echo $sFinalOutput;




?>