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

$sUserEmail = "";
$sErrorMsg  = "";

/*
	NOTICE:
		We have made the effort to ALIGN the "=" signs for the variables
		It's not necessary, but it does make them stand out and easier to read

		Having code that's easier to read is very important for maintenance.
*/
$errors         = false;
$errorEmail     = false;
$errorPassword  = false;
$noMatch        = false;


/**
* PROCESSING - Process the form
*/

if ( isset( $_POST ['submit'] )) {
	//check email
	if ( !empty( $_POST ['inputEmail'] )) {
		$dbEmail = trim( $_POST ['inputEmail'] );
	} else {
		$errorEmail = true;
		$errors = true;
	}

	//check password
	if ( !empty( $_POST ['inputPassword'] )) {
		$dbPassword = encryptPassword( trim( $_POST ['inputPassword'] ));
	} else {
		$errorPassword = true;
		$errors = true;
	}

	if ( !$errors ) {
		//check database
		/*
			NOTICE:
				We have formatted the SQL query in a special way.
				The MySQL engine does not care. As long as there is one space between each 'word'
				in the query, it will understand it.

				BUT we have to be able to understand the code too.
				Making the QUERY easy to read and understand helps in maintenance.
				We make the SQL KEY WORDS stand out by putting them in ALL CAPS
				We make the colums and paramters stand out by putting them on separate lines.
		*/
		try {
			$sql = "SELECT 
						user_id
					FROM 
						user
					WHERE 
						user_email = :user_email
					AND 
						user_password = :user_password";

			/*
				Because we are using PARAMETERS in our SQL statement,
				we must PREPARE the statement before we can EXECUTE the statement.

				The PREPARE method looks for the special markers :xxx
				It then knows that when the statement is executed, we have to pass in values to
				SUBSTITUTE in for those markers.

				A PREPARED STATEMENT can be used over and over by executing it with different values.
				The values are passed in using an ARRAY where the KEY matches the parameter.
			*/
			$stmt = $dbh->prepare( $sql );
			$aParameters = [":user_email"     => $dbEmail,
							":user_password"  => $dbPassword
							];

			$stmt->execute( $aParameters );
			$row = $stmt->fetch( PDO::FETCH_ASSOC );

			/*
				We passed in the TWO values needed to check login:
				The user's EMAIL and their ENCRYPTED PASSWORD

				IF both values MATCH, the Database will return the SINGLE ROW that matches.
				If either value does NOT MATCH, we will get ZERO ROWS back.
			*/
			if ( $row ) {
				$_SESSION ['user_logged']  = true;
				$_SESSION ['user_id']      = $row ['user_id'];
				header( "Location: myprofile.php" );
			} else {
				$noMatch = true;
			}
		} catch ( PDOException $e ) {
			echo $e;
		}
	}
}


/**
* HTML - Contstruct the page
*/

/*
	NOTICE:
		We can construct the PAGE using the wrapper functions we created.
*/
$sTitle         = "BootStrap Page";
$sTopContent    = outputTop( $sTitle, "Login" );
$sBottomContent = outputBottom();
$sBodyContent   = outputNavBarFix();



/*
	Here's how each FIELD on the form is process:
	CHECK FOR ANY ERRORS
	IF FOUND, generate an ALERT and save that as an error message
	Then, call formInput with the paramters for the EMAIL field
	Then, NULL the error message string so we can use it again.

	We will do this for each field on the form, adding the fields in the order
	we want them to appear.
*/

//email
if ( $errorEmail ) {
	$sErrorMsg = wrapAlert( "danger", "Error!", "You must enter a valid email." );
}
$sForm = formInput( "text", "inputEmail", $sUserEmail, "Email", $sErrorMsg );
$sErrorMsg = "";



//password
if ( $errorPassword ) {
	$sErrorMsg = wrapAlert( "danger", "Error!", "You must enter a password." );
}

$sForm .= formInput( "password", "inputPassword", "", "Password", $sErrorMsg );
$sErrorMsg = "";


/*
	This form is simple, just TWO fields (email and password)
	Once we have those, we can add the SUBMIT button
*/
$sForm .= formSubmit( "submit", "Login" );

/*
	Then we can check to see if we have an error message for NO MATCH on the values
	This is a different kind of error.
	The error messages for EMAIL and PASSWORD would exist if the user didn't input a value
	for that field.

	The NO MATCH error is because the values they entered do not match any values in our database.
*/
if ( $noMatch ) {
	$sForm .= wrapAlert( "danger", "Error!", "No match found for that Email and Password." );
}

/*
	Once we have all the fields, buttons and messages, we can GENERATE the form itself.
	We pass in the various parts of the form, along with the information on HOW and WHERE to process the form.
	This form will POST (rather than GET) and is process by THIS FILE, rather than some other file.
*/
$sCompleted = wrapFormTags( $sForm, "post", $_SERVER ['PHP_SELF'] );

/*
	Now that we have the completed form, we can WRAP IT in a CONTAINER on the screen
*/
$sBodyContent .= wrapContainer( $sCompleted );


/*
	For the final OUTPUT, we have
	TOP
	CONTENT IN A CONTAINER
	BOTTOM

	We are collecting all those parts into a SINGLE VARIABLE to echo,
	but you could just ECHO each part:
		echo $sTopContent . $sBodyContent . $sBottomContent;

	Once reason to collect it into a single variable is, if at some point in the future
	we change the LOGIC, we might RETURN the screen information rather than ECHO it.
	This is easier to do from a single variable.
*/


$sFinalOutput  = $sTopContent;
$sFinalOutput .= $sBodyContent;
$sFinalOutput .= $sBottomContent;

echo $sFinalOutput;


?>