<?php
session_start();
include("errors_functions.php");



if( isset( $_GET[ 'myName' ]) && isset( $_GET['myHeight' ])){
	echo "<br /><h1>MY NAME IS: </h1>";
	echo $_GET['myName'];
	echo "<h1>My Height is</h1>";
	echo $_GET['myHeight'];	
} else {
	echo 'nothing in $_GET';
}
echo "<br /><hr /><br />";

if( isset( $_COOKIE['TestCookie'])){
	echo '<h3>The Value of $_COOKIE["TestCookie"] is:</h3>';
	echo $_COOKIE['TestCookie'];
} else {
	echo 'nothing in $_COOKIE';
}
echo "<br /><hr /><br />";

if( isset( $_SESSION)){
	echo '<h3>The Value of $_SESSION["my_yard"] is:</h3>';
	echo $_SESSION['my_yard'];
} else {
	echo 'nothing in $_SESSION';
}
echo "<br /><hr /><br />";

if( isset( $_POST)){
	echo '<h3>The Value of $_POST["first_name"] is:</h3>';
	echo $_POST['first_name'];
} else {
	echo 'nothing in $_POST';
}
echo "<br /><hr /><br />";










