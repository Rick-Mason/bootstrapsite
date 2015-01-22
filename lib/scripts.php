<?php

function encryptPassword ( $sPassword ){
	$output = crypt( $sPassword, 
                '$6$rounds=5000$' . PASSWORD_SALT . '$');
    $output = substr( $output, 15 );
    return $output;	
}

function formatPhone( $sPhoneNumber ){
	$areacode 	= substr($sPhoneNumber, 0, 3);
	$prefix		= substr($sPhoneNumber, 3, 3);
	$last4 		= substr($sPhoneNumber, -4);
	$output 	= "($areacode) $prefix - $last4";
	return $output;
}

