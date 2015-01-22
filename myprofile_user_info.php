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
$errors 	= false;
$errorPhone = false;
$errorFirst = false;
$errorLast 	= false;
$useUpdate	= false;

$sql = "SELECT 	
			user_phone,
			user_first,
			user_last
		FROM user_personal_info
		WHERE user_id = {$_SESSION['user_id']}";
$stmt = $dbh->query( $sql );
$row = $stmt->fetch( PDO::FETCH_ASSOC );
if( $row ){
	$sDspPhone 		= formatPhone( $row['user_phone'] );
	$sUserFirst 	= $row['user_first'];
	$sUserLast 		= $row['user_last'];
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
if ( isset( $_POST['submit'])){
	//do the processing
	if( !empty( $_POST['inputPhone'])){
		$sCleanPhone = preg_replace( REGEX_NOT_NUM, "", $_POST['inputPhone'] );
		if( 10 ==  strlen( $sCleanPhone )){
			//good phone number
			$sDspPhone = formatPhone( $sCleanPhone );
		} else {
			//bad number
			$sDspPhone  = $_POST['inputPhone'];
			$errors 	= true;
			$errorPhone = true;
		}
	} else {
		$errors 	= true;
		$errorPhone = true;
	}

	if( !empty( $_POST['inputFirst'])){
		$sUserFirst = trim( $_POST['inputFirst']);
		if( !preg_match( REGEX_FIRST, $sUserFirst )){
			$errors 	= true;
			$errorFirst = true;
		}

	} else {
		$errors 	= true;
		$errorFirst = true;
	}

	if( !empty( $_POST['inputLast'])){
		$sUserLast = trim( $_POST['inputLast']);
		if( !preg_match( REGEX_FIRST, $sUserLast )){
			$errors 	= true;
			$errorLast 	= true;
		}
	} else {
		$errors 	= true;
		$errorLast 	= true;
	}

	if( !$errors ){
		//run the database query
		try{	
			
			if( $useUpdate ){
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
$jumboContent = wrapJumbotron( "<h2>Add/Edit Personal Info</h2>");



//phone html
if( $errorPhone ){
	$sErrorMsg = wrapAlert( "danger", 
							"ERROR!", 
							"You must enter a 10 digit phone number with only numbers.");
}
$inputPhone = formInput(	"text", 
							"inputPhone", 
							$sDspPhone, 
							"Your Phone Number", 
							$sErrorMsg);
$sErrorMsg = "";



//first name html
if( $errorFirst ){
	$sErrorMsg = wrapAlert( "danger", 
							"Error!", 
							"You must enter a First name of only Letters");
}
$inputFirst = formInput(	"text", 
							"inputFirst", 
							$sUserFirst, 
							"Your First Name", 
							$sErrorMsg);
$sErrorMsg = "";


//last name html
if( $errorLast ){
	$sErrorMsg = wrapAlert( "danger", 
							"ERROR!", 
							"You must enter a Last name of only Letters.");
}
$inputLast = formInput(	"text", 
						"inputLast", 
						$sUserLast, 
						"Your Last Name", 
						$sErrorMsg);
$sErrorMsg = "";

$inputSubmit = formSubmit( "submit", $sDspSubmitText );



$moreContent = wrapFormTags (	$inputPhone . $inputFirst . $inputLast . $inputSubmit, 
								"post", 
								$_SERVER['PHP_SELF'], 
								$encType = false);

$leftColContent = wrapColumn( $jumboContent . $moreContent, 9 );
$rowContent = wrapRow( $rightColContent . $leftColContent );




$sTitle         = "Add Edit Personal Info";
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