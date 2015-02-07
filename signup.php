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

/*
	PHP does not REQUIRE to declare variables in advance of using them.
	HOWEVER, it is very good coding practice to DECLARE your VARIABLES
	and initialize them to rational values.
	If you get into the habit of doing this, it will help you in many ways.
		Make you think through your code in advance, improving your design 
		Make the code easier to understand
		Make the code easier to maintain

	There are lots of other reasons too.
	A BIG one is that many other languages REQUIRE that you declare your
	variables before you use them, so being in the habit makes it
	easier to transition to a language like C++ or SWIFT.
*/

/*
	Let's look at the VARIABLES for this module. 
*/
$errors             = false;	// FLAG to say there is at least ONE error
$errorInputUsername = false;	// FLAG for errors in USER NAME 
$errorInputEmail    = false;	// FLAG for errors in EMAIL
$errorInputPassword = false;	// FLAG for errors in PASSWORD / CONFIRM PASSWORD
$bDatabaseSuccess   = false;	// FLAG for Database problems
$sErrorMsg          = "";		// STRING for error messages


$userName          = "";		// STRING for the USER NAME
$userEmail         = "";		// STRING for the USER EMAIL
$userPassword      = "";		// STRING for the USER PASSWORD
$userConfirm       = "";		// STRING for the CONFIRM PASSWORD
$sForm             = "";		// STRING to store the generated FORM
/*
	$sDspUsernameError could be an embedded string, or a defined CONSTANT
	By putting it here is it is easier to FIND and CHANGE as you develop
	YOUR version of the USERNAME.
*/
$sDspUsernameError = "You must enter a Username with letters, numbers, 
                      '_' or '-'. Your Username must be between 
                      3 and 16 characters.";

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

	CODING TIP:
		Try to be CONSISTENT in the names of commonly used items like
		the NAME of the basic SUBMIT button on your forms.
		It makes it easier to reuse code and keeps the basics clear.
*/
if ( isset( $_POST ['submit'] )) {
	/*
		The USER NAME is a REQUIRED value.
		The user MUST enter a value that matches the criteria 
		we have established.
		
		This includes min and max length, and the characters
		which can be used in the USER NAME.

		We check FIRST that there is a value:
			!empty() ==== NOT EMPTY
		Then we remove extra white space (spaces and tabs)
			trim()
		Then we compare to the PATTERN we created
			!preg_match()

		If any of those conditions are NOT MET
		we set the GENERAL ERROR FLAG: errors = TRUE
		AND we set the specific error flag for USER NAME to true.

		The ERROR MESSAGE for the USER NAME was given above,
		so we don't need to set it.

		QUIZ:
			How would you make SPECIFIC error messages for each type
			of problem that could arise with the USER NAME?
	*/
	if ( !empty( $_POST ['inputUsername'] )) {
		$userName = trim( $_POST ['inputUsername'] );
		if ( !preg_match( REGEX_USERNAME, $userName )) {
			//throw user error
			$errorInputUsername = true;
			$errors = true;
		}
	} else {
		//process error
		$errorInputUsername = true;
		$errors             = true;
	}

	/*
		We perform similar checks for the user's EMAIL ADDRESS
			NOT EMPTY
			TRIMMED
			COMPARED to PATTERN

		This is a sequence you will use OVER AND OVER for
		input processing.
	*/

	if ( !empty( $_POST ['inputEmail'] )) {
		$userEmail = trim( $_POST ['inputEmail'] );
		if ( !preg_match( REGEX_EMAIL, $userEmail )) {
			$errorInputEmail = true;
			$errors          = true;
		}
	} else {
		//process error
		$errorInputEmail  = true;
		$errors           = true;
	}

	/*
		PASSWORDs are SPECIAL
		SECURITY MATTERS! Forms treat passwords as special by NOT
		displaying the actual characters as they are typed.

		We also treat passwords as special in the way we handle them.
		We perform the THREE BASIC CHECKS:
			NOT EMPTY
			TRIMMED
			COMPARED to PATTERN

		THEN we also compare to a second input of the password to
		confirm MATCH.
		If they do not match, we make the User enter them again.
		If they DO match, we immediately encrypt them and from that point
		forward only use the ENCRYPTED version of the password.

		NEVER STORE PASSWORDS "in the clear", IN THE DATABASE
		It is a HUGE security risk to store unencrypted passwords.
		NEVER DO THIS!

		ERROR handling of passwords is also often different.
		For some inputs, we can offer a great deal of information
		to the User about what is "wrong" with the input by offering
		SPECIFIC error messages for each type of thing we check.

		For PASSWORDS, less is more. We don't want to offer too much
		information. We make sure that what they choose is "strong" 
		enough (special characters, numbers, capitol letters, etc.)
		AND, that they can type it twice in a row the exact same way.

		BUT, we don't want to give too many hints about what is wrong.
		So, we usually only give a SINGLE ERROR message that covers
		all cases, rather than specific messages for not matching
		length, not matching diversity of characters, not matching.

		ONE error message for all cases gives less information to
		unsavory operators on the NET who might be probing your system
		for vulnerabilities.

	*/
	if ( !empty( $_POST ['inputPassword'] )) {
		$userPassword = trim( $_POST ['inputPassword'] );
		if ( preg_match( REGEX_PASSWORD, $userPassword )) {
			if ( !empty( $_POST ['inputConfirm'] )) {
				$userConfirm = trim( $_POST ['inputConfirm'] );
				if ( $userPassword == $userConfirm ) {
					//now we can encrypt
					$encryptedPassword = encryptPassword( $userPassword );
				} else {
					$errors = true;
					$errorInputPassword = true;
				}

			} else {
				$errors = true;
				$errorInputPassword = true;
			}


		} else {
			$errorInputPassword = true;
			$errors = true;
		}
	} else {
		//process error
		$errorInputPassword = true;
		$errors             = true;
	}

	/*
		NOTICE:
			We check the general error flag.
			That flag ($errors) was set to TRUE if ANY input error
			occurred.

			This is reduntant in one way, but easier.

		COMPARE:
			IF ( !$errors )
			VS
			IF ( !$errorInputUsername AND
				 !$errorInputEmail    AND
				 !$errorInputPassword AND
			    )
		Support your FORM had 10 input items... or TWENTY...
		It is much easier to use a general FLAG.
	*/

	if ( !$errors ) {
		//check on the username...must be unique
		/*
			In general, USERNAMES, and generally EMAIL ADDRESSES
			must be UNIQUE, that is, if a person has already
			registered using a USERNAME or a specific EMAIL ADDRESS
			we don't want to allow another user using that information
			(or that same person to register again)

			Our first CHECK is for duplicate USERNAME

			QUIZ:
				How would you extend this check to also look for 
				DUPLICATE EMAIL ADDRESS
		*/
		$sql = "SELECT user_id 
				FROM user 
				WHERE user_name = :userName ";
		$stmt = $dbh->prepare( $sql );
		$stmt->execute( [":userName" => $userName] );
		$row = $stmt->fetch( PDO::FETCH_ASSOC );
		if ( $row ) {
			$errors = true;
			$errorInputUsername = true;
			$sDspUsernameError  = "That Username is already in use.";
		}
	}

	if ( !$errors ) {
		//do the insert into the database
		/*
			We are finally READY to INSERT the NEW USER's information
			into the database.

			Once that is done, we also add values to the SESSION
			to show that the user is LOGGED IN

			We can use those values to do several things, but
			the BIG ONE is:
				Change the look of the pages by adding menu items
					that only USERS can see

		*/
		try {
			$sql = "INSERT INTO 
						user 
					SET 
						user_email    = :userEmail,
						user_name     = :userName,
						user_password = :userPassword";
			$stmt = $dbh->prepare( $sql );
			$aParamenters = [ ":userEmail"    => $userEmail,
							  ":userName"     => $userName,
							  ":userPassword" => $encryptedPassword
							];
			$stmt->execute( $aParamenters );
			$user_id = $dbh->lastInsertId();
			$_SESSION ['user_logged']  = true;
			$_SESSION ['user_id']      = $user_id;
			header( "Location: myprofile.php" );


		} catch ( PDOException $e ) {
			echo $e;
		}
	}
}


/**
* HTML - Contstruct the page
*/

$sTitle         = "BootStrap SignUp Page";
$sTopContent    = outputTop( $sTitle, "Sign-Up" );
$sBottomContent = outputBottom();
$sBodyContent   = outputNavBarFix();




/*
	Above, we were looking at the GENERAL ERROR FLAG: $errors

	NOW, we are interested in the SPECIFIC ERRORS associated
	with each INPUT VALUE on the FORM

	As we CONSTRUCT the FORM, we will check to see if individual
	errors associated with that field need to be displayed.
*/

//username input
if ( $errorInputUsername ) {
	$sErrorMsg = wrapAlert( "danger", "Error", $sDspUsernameError );
}
$sForm    .= formInput("text", "inputUsername", $userName, "Username", $sErrorMsg  );
$sErrorMsg = "";

//email input
if ( $errorInputEmail ) {
	$sErrorMsg = wrapAlert( "danger", "Error", "You must enter a valid Email." );
}
$sForm    .= formInput( "text", "inputEmail", $userEmail, "Email", $sErrorMsg );
$sErrorMsg = "";
//password
if ( $errorInputPassword ) {
	$sErrorMsg = wrapAlert( "danger", "Error", "You must enter a Password, a Confirm Password and they must match." );
}
$sForm    .= formInput( "password", "inputPassword", "", "Password", $sErrorMsg );
$sErrorMsg = "";

//confirm Password
$sForm .= formInput( "password", "inputConfirm", "", "Confirm Password", "" );


//submit button
$sForm .= formSubmit( "submit", "Sign Up!" );



/*
	We had a SPECIAL DATABASE FLAG that got set above when the INSERT was successful.
	This is used to display a message to the USER that SIGN UP was successful.

	FEEDBACK to the USER is very important in WEB BASED APPLICATIONS.
	It's good to provide both ERROR MESSAGES and POSSITIVE / SUCCESS MESSAGES
	so that the User always knows what's going on.
*/
if ( $bDatabaseSuccess ) {
	$sForm .= wrapAlert( "success", "Congrats!", "You are now signed up" );
}


/*
	Once the various PARTS (input fields, buttons, messages, etc) of
	the FORM have been constructed, we WRAP those elements in the
	actual FORM tags.

	The WHOLE FORM, contained in the STRING variable $sCompletedForm
	is then output with the rest of the page.
*/

/*
	NOTE TO RICK:
		The following VARIABLES were NOT declared:
		$sTitle
		$sTopContent
		$sBottomContent
		$sBodyContent
		$sCompletedForm
		$sFinalOutput

	DO WE WANT TO DISCUSS WHY NOT, or ADD THEM ABOVE on ALL PAGES....
*/
$sCompletedForm = wrapFormTags( $sForm, "post", $_SERVER[ 'PHP_SELF' ] );

/**
* OUTPUT - Make 1 large string and echo the string
*/

$sBodyContent .= wrapContainer( $sCompletedForm );
$sFinalOutput  = $sTopContent;
$sFinalOutput .= $sBodyContent;
$sFinalOutput .= $sBottomContent;

echo $sFinalOutput;




?>