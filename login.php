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
$sErrorMsg = "";

$errors         = false;
$errorEmail     = false;
$errorPassword  = false;
$noMatch        = false;


/**
 * PROCESSING - Process the form
 */

if( isset( $_POST['submit'] )) {
  //check email
  if( !empty( $_POST['inputEmail'])) {
    $dbEmail = trim( $_POST['inputEmail'] );
  } else {
    $errorEmail = true;
    $errors = true;
  }

  //check password
  if( !empty( $_POST['inputPassword'])){
    $dbPassword = encryptPassword( trim( $_POST['inputPassword']));
  } else {
    $errorPassword = true;
    $errors = true;
  }

  if( !$errors){
    //check database
    try{
      $sql = "SELECT user_id
              FROM user
              WHERE user_email = :user_email
              AND user_password = :user_password";
      $stmt = $dbh->prepare( $sql );
      $aParameters = [
                    ":user_email"     => $dbEmail,
                    ":user_password"  => $dbPassword
                    ];
      $stmt->execute( $aParameters );
      $row = $stmt->fetch( PDO::FETCH_ASSOC );
      if( $row ){
        $_SESSION['user_logged']  = true;
        $_SESSION['user_id']      = $row['user_id'];
        header("Location: myprofile.php");
      } else {
        $noMatch = true;
      }
    } catch (PDOException $e){
      echo $e;
    }
    
  }

}


/**
 * HTML - Contstruct the page
 */

$sTitle         = "BootStrap Page";
$sTopContent    = outputTop( $sTitle, "Login" );
$sBottomContent = outputBottom();
$sBodyContent   = outputNavBarFix();



//email
if( $errorEmail ){
  $sErrorMsg = wrapAlert( "danger", "Error!", "You must enter a valid email." );
}
$sForm = formInput( "text", "inputEmail", $sUserEmail, "Email", $sErrorMsg);
$sErrorMsg = "";



//password
if( $errorPassword ){
  $sErrorMsg = wrapAlert( "danger", "Error!", "You must enter a password.");
}

$sForm .= formInput( "password", "inputPassword", "", "Password", $sErrorMsg);
$sErrorMsg = "";


$sForm .= formSubmit( "submit", "Login");

if( $noMatch){
  $sForm .= wrapAlert( "danger", "Error!", "No match found for that Email and Password.");
}


$sCompleted = wrapFormTags($sForm, "post", $_SERVER['PHP_SELF']);
$sBodyContent .= wrapContainer($sCompleted);


/**
 * OUTPUT - Make 1 large string and echo the string
 */


$sFinalOutput  = $sTopContent;
$sFinalOutput .= $sBodyContent;
$sFinalOutput .= $sBottomContent;

echo $sFinalOutput;




?>