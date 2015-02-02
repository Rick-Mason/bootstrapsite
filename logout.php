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


/**
 * PROCESSING - Logout and destroy the session
 */

/*
	NOTICE:
		$_SESSION is a SUPER GLOBAL that is also an ARRAY.
		The special syntax
			$_SESSION = [];
		Is used to force all the values in the SESSION ARRAY
		to be logically written over with NULL values.
		This makes it impossible to access that data.

	Once the application specific SESSION DATA has been eliminated,
	we want to destroy the SESSION itself.

	We need to 'time out' the COOKIE.
	We do this by setting the various cookies values with a TIME 
	that is in the past.
		time() - 42000
	You may see this code in a lot of places.
	The PHP manual uses this particular value in its example.
	So, most programmers use the same value in their code.

	The final step is to actually DESTROY the SESSION.
	There is a PHP system function that does this:
		destroy_session ();

	This block of code is essentially universal.
	It's SO common, that you might want to just extract it, as is,
	and make a function that you take with you for every project
	that you work on, so you don't have to rewrite, or even 
	cut and paste the code. Just call your function and you're done.

*/
$_SESSION = [];
if ( ini_get( "session.use_cookies" )) {
	$params = session_get_cookie_params();
	setcookie( 
		session_name(), 
		'', 
		time() - 42000,
		$params ['path'],
		$params ['domain'],
		$params ['secure'],
		$params ['httponly']
		);	
}

session_destroy();

/**
 * HTML - Contstruct the page
 */

$sTitle         = "BootStrap Page";
$sTopContent    = outputTop( $sTitle, "" );
$sBottomContent = outputBottom();
$sBodyContent   = outputNavBarFix();

$sMoreContent = '<h1>Logged Out Successfully!</h1>
  					<p>Please Visit Again</p>
  					<p><a class="btn btn-success btn-lg" href="login.php" role="button">Go to Login Page</a></p>';
$sMoreContent = wrapJumbotron( $sMoreContent );

$sBodyContent .= wrapContainer( $sMoreContent );



/**
 * OUTPUT - Make 1 large string and echo the string
 */
$sFinalOutput  = $sTopContent;
$sFinalOutput .= $sBodyContent;
$sFinalOutput .= $sBottomContent;

echo $sFinalOutput;




?>