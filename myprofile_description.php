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

$isEdit 			= false;
$sDspDescription 	= '';
$sErrorMsg 			= "";

$dbShowMessage 		= false;
$dbMessage			= "";
$dbAlert			= "";
$dbStrongText 		= "";
$extraDbMessage 	= "";

$errors 			= false;
$errorDescription 	= false;



if( $_SESSION["user_logged"]) {
	$sql = "SELECT 
				user_description 
			FROM user_description 
			WHERE user_id = {$_SESSION['user_id']}";
	$stmt = $dbh->query( $sql );
	$row = $stmt->fetch( PDO::FETCH_ASSOC );
	if( $row ){
		$sDspDescription = $row['user_description'];
		$isEdit = true;
	}
} 


/**
 * PROCESSING - Process the form
 */

if ( isset( $_POST['submit'] )) {
	if ( !empty( $_POST['inputDescription'] )) {
		$sDspDescription = 
			strip_tags( 
				$_POST['inputDescription'],
				"<strong><em><br><hr><p><a>");
		$sDatabaseDescription = htmlentities( $sDspDescription );
	} else {
		$errros 			= true;
		$errorDescription 	= true;
	}

	if( !$errors ) {
		// do the database insert
		if ( $isEdit ) {
			//update query
			$sql = "UPDATE 
						user_description 
					SET 
						user_description 	= :user_description
					WHERE
						user_id 			= {$_SESSION['user_id']}";
		} else {
			//insert query
			$sql = "INSERT INTO 
						user_description 
					SET 
						user_description 	= :user_description,
						user_id 			= {$_SESSION['user_id']}";
		}
		try {
			$stmt = $dbh->prepare( $sql );
			$checkSuccess = $stmt->execute( 
						[ ":user_description" => $sDatabaseDescription ] );

			if( $checkSuccess ){
				$dbShowMessage 	= true;
				$dbAlert		= "success";
				$dbStrongText	= "CONGRATULATIONS";
				$dbMessage = ( $isEdit ) ? 
						  "Your description changes have been saved." 
						: "Your description has been saved to the database.";
				
				$isEdit			= true;

			} else {
				$dbShowMessage 	= true;
				$dbAlert 		= "danger";
				$dbStrongText 	= "ERROR";
				$dbMessage 		= "There was a database error.";
				$isEdit			= true;

			}

		} catch ( PDOException $e ) {
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
$jumboContent = wrapJumbotron( "<h2>Add/Edit Profile Description</h2>");
if( $errorDescription ){
	$sErrorMsg = wrapAlert( "danger", 
							"ERROR!", 
							"Please enter a paragraph about yourself.");
}
$inputDescription = formTextarea ( 5, 
							"inputDescription", 
							$sDspDescription, 
							"Tell us about youreself.", 
							$sErrorMsg);
$sErrorMsg = "";
if ( $isEdit ){
	$inputSubmit = formSubmit( "submit", "Edit Description");
} else {
	$inputSubmit = formSubmit( "submit", "Add Description");
}

$sCompleteForm = wrapFormTags( $inputDescription . $inputSubmit, 
								"post", 
								$_SERVER['PHP_SELF'] );
 
$moreContent = wrapContainer($sCompleteForm);


if( $dbShowMessage ) {
	$extraDbMessage = wrapAlert( $dbAlert, $dbStrongText, $dbMessage );
	$extraDbMessage = wrapContainer( $extraDbMessage );
}


$leftColContent = wrapColumn( $jumboContent . $moreContent, 9 );
$rowContent = wrapRow( $rightColContent . $leftColContent . $extraDbMessage );




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