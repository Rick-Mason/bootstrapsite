<?php
define ("DB_HOST", "localhost");
define ("DB_NAME", "bootstrap");
define ("DB_USER", "root");
define ("DB_PASS", "");


$dbh = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, 
				DB_USER, 
				DB_PASS);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);


$stmt = $dbh->query( "SELECT * FROM user");
while( $row = $stmt->fetch( PDO::FETCH_ASSOC )){
	echo $row['user_id'] . " ";
	echo $row['user_name'] . " ";
	echo $row['user_email'] . " <br />";
}

echo "<br /><br />";

$stmt = $dbh->query( "SELECT user_id FROM user WHERE user_name='codedancer'");
$row = $stmt->fetch( PDO::FETCH_ASSOC );
var_dump($row);

echo "<br /><br />";
$stmt = $dbh->query( "SELECT user_id FROM user WHERE user_name='kingkong'");
$row = $stmt->fetch( PDO::FETCH_ASSOC );
var_dump($row);






















?>