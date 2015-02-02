<?php

/*
	a variety of functions that might be used in any part of the application. 
*/

/*
	SECURITY TIP:
		We do NOT store user's passwords "in the clear", meaning that they 
		can be read by a human.
		We store an ENCRYPTED STRING that is generated from the password.
		
	NOTICE: 
		We only store 15 characters of the encrypted string.

	When the user provides the password, we run the encryption on the string 
	entered by the user, and compare the encypted string we just generated
	with the one we have stored.
	
	They should match since they were generated in exactly the same way.
*/
function encryptPassword ( $sPassword ) {
	$output = crypt( $sPassword, 
                	'$6$rounds=5000$' . PASSWORD_SALT . '$' );
    $output = substr( $output, 15 );
    return $output;	
}

/*
	Take the 10 digits of a US phone number and reformat it to a 
	standard display format:
		(xxx) xxx-xxxx
	NOTICE: 
		This can also be done in JAVASCRIPT using masks
*/
function formatPhone ( $sPhoneNumber ) {
	$areacode 	= substr( $sPhoneNumber, 0, 3 );
	$prefix		= substr( $sPhoneNumber, 3, 3 );
	$last4 		= substr( $sPhoneNumber, -4 );
	$output 	= "($areacode) $prefix - $last4";
	return $output;
}

