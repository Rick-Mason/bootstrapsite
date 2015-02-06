<?php
session_start();
if ( !$_SESSION ['user_logged'] ) {
	header("Location: login.php");
}

include_once( "lib/error_functions.php" );
include_once( "lib/config.php" );
include_once( "lib/wrapper_functions.php" );
include_once( "lib/db_connect.php" );
include_once( "lib/scripts.php");



/**
 * VARS - Initialize variables for later use
 */

$useUpdate 			= false;
$sDspDescription 	= '';
$sErrorMsg 			= "";

$showMessage 		= false;
$message			= "";
$allRowsMessage		= "";

$errors 			= false;
$errorDescription 	= false;




$sql = "SELECT 
			user_description 
		FROM 
			user_description 
		WHERE 
			user_id = {$_SESSION ['user_id']}";
$stmt = $dbh->query( $sql );
$row = $stmt->fetch( PDO::FETCH_ASSOC );
if ( $row ) {
	/*
		NOTICE:
			We found USER DATA, so we save it into a local variable,
			AND set the flag to show we need to UPDATE, not INSERT.
	*/
	$sDspDescription = $row ['user_description'];
	$useUpdate = true;
}


/**
 * PROCESSING - Process the form
 */

/*
	NOTICE:
		We only process POST data if we received the appropriate SUBMIT.

		Each SUBMIT button has a NAME. The actual NAME of the SUBMIT is 
		something we set when we construct the FORM. Because the FORM
		we are working with only has one submit button, we can use a simple
		name like: "submit".

		Some pages have multiple forms, and some forms have multiple buttons
		that do different things. In that case, we can use the NAME of the
		submit button to determine what has been POSTED.
*/
if ( isset( $_POST ['submit'] )) {
	if ( !empty( $_POST ['inputDescription'] )) {
		/*
			SECURITY:
				Security matters. One thing we can do is prevent the
				USER from adding HTML (or other types of) tags into the
				data they submit.

				The function "strip_tags()" does exactly that.
				It takes the string you want stripped, and a list
				of ALLOWED tags, returning the stripped string.

				In addition, it's important to make sure you ENCODE
				HTML strings when you store them in an SQL database.
				It prevents them from being processed.

				The function "htmlentities()" QUOTES the strings to
				prepare it to be stored in the database.
		*/
		$sDspDescription = 
			strip_tags( 
				$_POST ['inputDescription'],
				"<strong><em><br><hr><p><a>" );
		$sDatabaseDescription = htmlentities( $sDspDescription );
	} else {
		$errors 			= true;
		$errorDescription 	= true;
	}

	/*
		NOTICE:
			We are checking for ERRORS before we continue.

		QUESTION:
			What ERRORS can we have at this point?

			The error we are interested in is that the USER didn't
			input a value for the user_description.

			No point in attempting an INSERT if we don't have a value.

		QUESTION:
			What if it's an UPDATE?

			In the DESIGN of our application, we have decided that the USER
			can ADD (INSERT) data, and EDIT (UPDATE) data, but we have not
			provided a means for them to DELETE data.

			If the USER tries to input an EMPTY STRING (which is different than
			a NULL VALUE) we give them an ERROR.

		SEE:
			myprofile_image.php for an example that includes DELETE along with
			ADD and UPDATE.
	*/
	if ( !$errors ) {
		// do the database insert or update
		if ( $useUpdate ) {
			//update query
			$sql = "UPDATE 
						user_description 
					SET 
						user_description 	= :user_description
					WHERE
						user_id 			= {$_SESSION ['user_id']}";
		} else {
			//insert query
			$sql = "INSERT INTO 
						user_description 
					SET 
						user_description 	= :user_description,
						user_id 			= {$_SESSION ['user_id']}";
		}
		try {
			$stmt = $dbh->prepare( $sql );
			$stmt->execute( [":user_description" => $sDatabaseDescription] );

			$showMessage 	= true;
			$message = ( $useUpdate ) ? 
					  "Your description changes have been saved." 
					: "Your description has been saved to the database.";
			// Now that a user_description exists, future processing will
			// be EDIT / UPDATE.
			$useUpdate	= true;

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
$jumboContent = wrapJumbotron( "<h2>Add/Edit Profile Description</h2>" );
if ( $errorDescription ) {
	$sErrorMsg = wrapAlert( "danger", 
							"ERROR!", 
							"Please enter a paragraph about yourself." );
}
$inputDescription = formTextarea ( 5, 
							"inputDescription", 
							$sDspDescription, 
							"Tell us about youreself.", 
							$sErrorMsg );
$sErrorMsg = "";
if ( $useUpdate ) {
	$inputSubmit = formSubmit( "submit", "Edit Description" );
} else {
	$inputSubmit = formSubmit( "submit", "Add Description" );
}

$sCompleteForm = wrapFormTags( $inputDescription . $inputSubmit, 
								"post", 
								$_SERVER ['PHP_SELF'] );
 
$moreContent = wrapContainer( $sCompleteForm );


if ( $showMessage ) {
	$allRowsMessage = wrapAlert( "success", "CONGRATULATIONS", $message );
	$allRowsMessage = wrapContainer( $allRowsMessage );
}


$leftColContent = wrapColumn( $jumboContent . $moreContent, 9 );
$rowContent = wrapRow( $rightColContent . $leftColContent . $allRowsMessage );




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