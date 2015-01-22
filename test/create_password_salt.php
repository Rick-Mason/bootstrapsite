<?php
$salt = mcrypt_create_iv( 22, MCRYPT_DEV_URANDOM );
$salt = base64_encode( $salt );
$salt = str_replace('+', ".", $salt);


$password = "mypassword";

$encrypted_password = crypt( $password, 
	'$6$rounds=5000$P14VtlebkRFSsNY78cU5kMgm6n4EsA==$');

echo $encrypted_password;



?>