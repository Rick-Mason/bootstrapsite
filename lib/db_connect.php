<?php
/*
	NOTICE: 
		All the Database paramters are DEFINED CONSTANTS from the config.php file

	The PDO::xxx values are defined constants that are part of the PDO class and 
	are referenced using NAMESPACE syntax
*/

$dbh = new PDO ( 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', 
				  DB_USER, 
				  DB_PASS );

$dbh->setAttribute ( PDO::ATTR_ERRMODE, 
					 PDO::ERRMODE_EXCEPTION );

$dbh->setAttribute ( PDO::ATTR_EMULATE_PREPARES, 
					 false );
