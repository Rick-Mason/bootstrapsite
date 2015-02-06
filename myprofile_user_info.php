<?php
session_start();
/*
	CODING TIP:
		In order to add information to a user profile, OR
		update user information, the user must first be logged in.

		We check for that in any file where the code needs to
		access data associated with THIS specific user.

		If they are NOT logged in, we don't know who they are
		(No current USER_ID), so we BOUNCE them to the LOGIN PAGE.
*/
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
$useUpdate			= false;

$showMessage 		= false;
$message			= "";
$allRowsMessage		= "";

$errors 			= false;
$errorPhone 		= false;
$errorFirst 		= false;
$errorLast 			= false;

/*
	When you design your application, you have to decide if
	the process of CREATING data and UPDATING data are
	essentially the SAME, or if they are DIFFERENT.

	If those two processes are different, or different enough,
	then having two separate files might be the right choice.

	In our application, the process of CREATING and UPDATING
	are virtually identical. We have written code which is
	sufficently general to handle both cases in one file.

	If they ARE, then the MIGHT have already created
*/

$sql = "SELECT 	
			user_phone,
			user_first,
			user_last
		FROM 
			user_personal_info
		WHERE 
		user_id = {$_SESSION ['user_id']}";
$stmt = $dbh->query( $sql );
$row = $stmt->fetch( PDO::FETCH_ASSOC );
if ( $row ){
	$sDspPhone 		= formatPhone( $row ['user_phone'] );
	$sUserFirst 	= $row ['user_first'];
	$sUserLast 		= $row ['user_last'];
	$useUpdate		= true;
	$sDspSubmitText = "Save Info";

} else {
	$sUserPhone 	= "";
	$sUserFirst 	= "";
	$sUserLast 		= "";
	$sDspPhone 		= "";
	$sDspSubmitText = "Add Info";
}

$sErrorMsg 		= "";

/**
 * PROCESSING - Process the form
 */
if ( isset( $_POST ['submit'] )) {
	//do the processing
	if ( !empty( $_POST ['inputPhone'] )) {
		$sCleanPhone = preg_replace( REGEX_NOT_NUM, "", $_POST ['inputPhone'] );
		if ( 10 ==  strlen( $sCleanPhone )) {
			//good phone number
			$sDspPhone = formatPhone( $sCleanPhone );
		} else {
			//bad number
			$sDspPhone  = $_POST ['inputPhone'];
			$errors 	= true;
			$errorPhone = true;
		}
	} else {
		$errors 	= true;
		$errorPhone = true;
	}

	if ( !empty( $_POST ['inputFirst'] )) {
		$sUserFirst = trim( $_POST ['inputFirst'] );
		if ( !preg_match( REGEX_FIRST, $sUserFirst )) {
			$errors 	= true;
			$errorFirst = true;
		}

	} else {
		$errors 	= true;
		$errorFirst = true;
	}

	if ( !empty( $_POST ['inputLast'] )) {
		$sUserLast = trim( $_POST['inputLast']);
		if ( !preg_match( REGEX_FIRST, $sUserLast )){
			$errors 	= true;
			$errorLast 	= true;
		}
	} else {
		$errors 	= true;
		$errorLast 	= true;
	}

	if ( !$errors ){
		//run the database query
		/*
			The UPDATE vs INSERT question comes up over and over.
			One way to deal with this is to have one PAGE for
			creating (INSERT) data, and a separate PAGE for
			UPDATING data.

			Depending on the complexity of your data, this solution
			might make sense.

			Our pages put the INSERT and UPDATE functions on a single 
			PAGE.

			The PAGE is slightly more complex, but in the long run it
			is less code to write and maintain.

			When you are DESINGING your application, how to manage
			INSERT vs UPDATE should be one of the primary considerations
			since it effects the design of the CODE, the PAGES and the
			overall USER EXPERIENCE.

			NOTICE:
				We are using the variable $useUpate to determine what to do.
				$useUpdate was set above when we did a SELECT to see if
				the User had an existing user_description.

			Once we have the correct query, the processing for the UPDATE 
			and the INSERT are identical.
		*/
		try {	
			
			if ( $useUpdate ){
				$sql = "UPDATE user_personal_info 
						SET
							user_phone 	= :user_phone,
							user_first 	= :user_first,
							user_last  	= :user_last
						WHERE user_id	= {$_SESSION['user_id']}";
			} else {
				$sql = "INSERT INTO user_personal_info 
						SET
							user_phone 	= :user_phone,
							user_first 	= :user_first,
							user_last  	= :user_last,
							user_id		= {$_SESSION['user_id']}";	
			}
			
			$stmt = $dbh->prepare( $sql );
			$aParameters = [
								":user_phone" => $sCleanPhone,
								":user_first" => $sUserFirst,
								":user_last"  => $sUserLast	
							];
			$stmt->execute( $aParameters );

			$showMessage 	= true;
			$message = ( $useUpdate ) ? 
					  "Your changes have been saved." 
					: "Your personal information has been saved to the database.";
			// Now that a user_description exists, future processing will
			// be EDIT / UPDATE.
			$useUpdate	= true;


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
$jumboContent = wrapJumbotron( "<h2>Add/Edit Personal Info</h2>" );


//phone html
if ( $errorPhone ) {
	$sErrorMsg = wrapAlert( "danger", 
							"ERROR!", 
							"You must enter a 10 digit phone number with only numbers." );
}
$inputPhone = formInput(	"text", 
							"inputPhone", 
							$sDspPhone, 
							"Your Phone Number", 
							$sErrorMsg );
$sErrorMsg = "";


//first name html
if ( $errorFirst ) {
	$sErrorMsg = wrapAlert( "danger", 
							"Error!", 
							"You must enter a First name of only Letters" );
}
$inputFirst = formInput(	"text", 
							"inputFirst", 
							$sUserFirst, 
							"Your First Name", 
							$sErrorMsg );
$sErrorMsg = "";

//last name html
if ( $errorLast ) {
	$sErrorMsg = wrapAlert( "danger", 
							"ERROR!", 
							"You must enter a Last name of only Letters." );
}
$inputLast = formInput(	"text", 
						"inputLast", 
						$sUserLast, 
						"Your Last Name", 
						$sErrorMsg );
$sErrorMsg = "";

$inputSubmit = formSubmit( "submit", $sDspSubmitText );

$moreContent = wrapFormTags (	$inputPhone . $inputFirst . $inputLast . $inputSubmit, 
								"post", 
								$_SERVER ['PHP_SELF'], 
								$encType = false );

if ( $showMessage ) {
	$allRowsMessage = wrapAlert( "success", "CONGRATULATIONS", $message );
	$allRowsMessage = wrapContainer( $allRowsMessage );
}

$leftColContent = wrapColumn( $jumboContent . $moreContent, 9 );
$rowContent = wrapRow( $rightColContent . $leftColContent );


$sTitle         = "Add / Edit Personal Info";
$sTopContent    = outputTop( $sTitle, "My Profile" );
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