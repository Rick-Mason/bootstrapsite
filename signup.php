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
$errors             = false;
$errorInputUsername = false;
$errorInputEmail    = false;
$errorInputPassword = false;
$bDatabaseSuccess   = false;
$sErrorMsg          = "";


$userName         = "";
$userEmail        = "";
$userPassword     = "";
$userConfirm      = "";
$sForm            = "";
$sDspUsernameError = "You must enter a Username with letters, numbers, 
                      '_' or '-'. Your Username must be between 
                      3 and 16 characters.";

/**
 * PROCESSING - Process the form
 */
if( isset( $_POST['submit'] )){
  if( !empty( $_POST['inputUsername'])){
    $userName = trim($_POST['inputUsername']);
    if( !preg_match( REGEX_USERNAME, $userName)){
      //throw user error
      $errorInputUsername = true;
      $errors = true;
    }
  } else {
    //process error
    $errorInputUsername = true;
    $errors             = true;
  }

  if( !empty( $_POST['inputEmail'])){
    $userEmail = trim( $_POST['inputEmail']);
    if( !preg_match( REGEX_EMAIL, $userEmail )){
      $errorInputEmail = true;
      $errors           = true;
    }
  } else {
    //process error
    $errorInputEmail  = true;
    $errors           = true;
  }

  if( !empty( $_POST['inputPassword'])){
    $userPassword = trim( $_POST['inputPassword'] );
    if( preg_match( REGEX_PASSWORD, $userPassword )){
      if( !empty( $_POST['inputConfirm'])){
        $userConfirm = trim( $_POST['inputConfirm']);
        if( $userPassword == $userConfirm){
          //now we can encrypt
          $encryptedPassword = encryptPassword($userPassword);
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

  if( !$errors ){
    //check on the username...must be unique
    $sql = "SELECT user_id FROM user WHERE user_name = :userName ";
    $stmt = $dbh->prepare( $sql );
    $stmt->execute( [":userName" => $userName] );
    $row = $stmt->fetch( PDO::FETCH_ASSOC );
    if( $row ){
      $errors = true;
      $errorInputUsername = true;
      $sDspUsernameError  = "That Username is already in use.";
    }
  }

  if( !$errors ){
    //do the insert into the database
    try {
      $sql = "INSERT INTO user 
              SET 
                user_email    = :userEmail,
                user_name     = :userName,
                user_password = :userPassword";
      $stmt = $dbh->prepare( $sql );
      $aParamenters = [ 
                        ":userEmail"    => $userEmail,
                        ":userName"     => $userName,
                        ":userPassword" => $encryptedPassword
                      ];
      $stmt->execute($aParamenters);
      $user_id = $dbh->lastInsertId();
      $_SESSION['user_logged']  = true;
      $_SESSION['user_id']      = $user_id;
      header( "Location: myprofile.php" );
        

    } catch (PDOException $e){
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





//username input
if( $errorInputUsername ){
  $sErrorMsg = wrapAlert( "danger", "Error", $sDspUsernameError);
}
$sForm    .= formInput("text", "inputUsername", $userName, "Username", $sErrorMsg);
$sErrorMsg = "";

//email input
if( $errorInputEmail ){
  $sErrorMsg = wrapAlert( "danger", "Error", "You must enter a valid Email.");
}
$sForm    .= formInput("text", "inputEmail", $userEmail, "Email", $sErrorMsg);
$sErrorMsg = "";
//password
if( $errorInputPassword ){
  $sErrorMsg = wrapAlert( "danger", "Error", "You must enter a Password, a Confirm Password and they must match.");
}
$sForm    .= formInput("password", "inputPassword", "", "Password", $sErrorMsg);
$sErrorMsg = "";

//confirm Password
$sForm .= formInput("password", "inputConfirm", "", "Confirm Password", "");


//submit button
$sForm .= formSubmit("submit", "Sign Up!");





if( $bDatabaseSuccess ){
  $sForm .= wrapAlert( "success", "Congrats!", "You are now signed up");
}


$sCompletedForm = wrapFormTags($sForm, "post", $_SERVER['PHP_SELF']);

/**
 * OUTPUT - Make 1 large string and echo the string
 */

$sBodyContent .= wrapContainer($sCompletedForm);
$sFinalOutput  = $sTopContent;
$sFinalOutput .= $sBodyContent;
$sFinalOutput .= $sBottomContent;

echo $sFinalOutput;




?>