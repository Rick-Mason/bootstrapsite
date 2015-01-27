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
$sErrorMsg			= "";

$errors 			= false;
$errorDescription 	= false;

$showDbMessage 		= false;
$dbMessage 			= "";
$dbAlert 			= "";
$dbStrongText 		= "";


if( $_SESSION["user_logged"]) {
	$sql = "SELECT 
				user_description 
			FROM user_description 
			WHERE user_id = {$_SESSION['user_id']}";
	$stmt = $dbh->query( $sql );
	$row = $stmt->fetch( PDO::FETCH_ASSOC );
	if( $row ){
		$sDspDescription = htmlentities( $row['user_description'] );
		$isEdit = true;
	} 
} 

/**
 * PROCESSING - Process the form
 */

if ( isset( $_POST[ 'submit' ] )) {
	if( !empty(  $_POST[ 'inputDescription' ] )) { 
		$sDspDescription = strip_tags( 
					$_POST[ 'inputDescription' ],
					"<strong><em><br><a><hr><p>");
	} else {
		$errors 			= true;
		$errorDescription 	= true;
	}

	if ( !$errors ) {
		try {
			if( $isEdit ) {
				$sql = "UPDATE
							user_description
						SET 
							user_description 	= :user_description
						WHERE
							user_id				= {$_SESSION['user_id']}";
			} else {
				$sql = "INSERT INTO
							user_description
						SET 
							user_description 	= :user_description,
							user_id				= {$_SESSION['user_id']}";	
			}

			
			$stmt = $dbh->prepare( $sql );
			$checkSuccess = 
					$stmt->execute( 
						[ ":user_description" => $sDspDescription ]
								  );
			if( $checkSuccess ){
				$showDbMessage	= true;
				if( $isEdit ){
					$dbMessage 		= "Your description has been changed in the database.";
				} else {
					$dbMessage 		= "Your paragraph was added to the database.";
				}
				
				$dbAlert 		= "success";
				$dbStrongText 	= "CONGRATULATIONS";
			} else {
				$showDbMessage 	= true;
				$dbMessage	 	= "There was a problem with the database.";
				$dbAlert		= "danger";
				$dbStrongText 	= "ERROR";
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
	$inputSubmit = formSubmit( "submit", "Save");
}

if( $showDbMessage ){
	$extraDbMessage = wrapAlert( $dbAlert, $dbStrongText, $dbMessage );
	$extraDbMessage = wrapContainer( $extraDbMessage );
} else {
	$extraDbMessage = "";
}


$sCompleteForm = wrapFormTags( 	$inputDescription 
								. $inputSubmit, 
								"post", 
								$_SERVER['PHP_SELF'] );




$moreContent = wrapContainer($sCompleteForm );
$leftColContent = wrapColumn( $jumboContent . $moreContent, 9 );
$rowContent = wrapRow( $rightColContent . $leftColContent . $extraDbMessage  );




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