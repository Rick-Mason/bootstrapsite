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
 * PROCESSING - Logout and dump the session
 */
$_SESSION = [];
if ( ini_get( "session.use_cookies" )){
	$params = session_get_cookie_params();
	setcookie( 
		session_name(), 
		'', 
		time() - 42000,
		$params["path"],
		$params['domain'],
		$params['secure'],
		$params['httponly']
		);	
}





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