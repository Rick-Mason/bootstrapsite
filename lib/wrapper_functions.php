<?php
//echo 1;

function sidebar(){
  $current_page = str_replace("/bootstrapsite/", "", $_SERVER['PHP_SELF']);
  $aLinks = [
              "myprofile.php" => "View My Profile",
              "myprofile_image.php" => "Add/Edit Image",
              "myprofile_description.php" => "Add/Edit Description",
              "myprofile_user_info.php" => "Add/Edit Personal Info"
            ];
  $output = "\n\t\t<ul class=\"nav nav-pills nav-stacked\">\n";
  foreach( $aLinks as $path => $dspLabel ){
    $output .= "<li role=\"presentation\"";
    if( $path == $current_page){
      $output .= " class=\"active\"";
    }

    $output .= "><a href=\"$path\">$dspLabel</a></li>";
  }
  $output .= "\t\t</ul>\n";
  return $output;
}

function wrapColumn($html, $iNumCols){
  $output = "\n\t\t<div class=\"col-md-$iNumCols\">\n";
  $output .= $html;
  $output .= "\t</div>\n";
  return $output;
}

function wrapRow($html){
  $output = "\n\t<div class=\"row\">\n";
  $output .= $html;
  $output .= "\t</div>\n";
  return $output;
}


function formSubmit( $sName, $sValue ){
  $output = "\t<div class=\"form-group\">\n";
  $output .= "\t\t<div class=\"col-sm-offset-3 col-sm-9\">\n";
  $output .= "\t\t\t<input type=\"submit\" 
                    class=\"btn btn-success btn-lg\" 
                    name=\"$sName\" 
                    value=\"$sValue\" />\n";
  $output .= "\t\t</div>\n";
  $output .= "\t</div>\n";
  return $output;
}

function formInput($sType, $sName, $sValue, $sDspLabelName, $sErrorMsg){
  $output  = "\t<div class=\"form-group\">\n";
  $output .= "\t\t<label for=\"$sName\" 
                class=\"col-sm-3 control-label\">$sDspLabelName</label>\n";
  $output .= "\t\t<div class=\"col-sm-9\">\n";
  $output .= "\t\t\t<input 
                      type=\"$sType\" 
                      name=\"$sName\"
                      class=\"form-control\" 
                      id=\"$sName\" 
                      placeholder=\"$sDspLabelName\" 
                      value=\"$sValue\">\n";
  $output .= $sErrorMsg;
  $output .= "\t\t</div>\n";
  $output .= "\t</div>\n";
  return $output;
}

function wrapFormTags ($sForm, $sMethod, $sAction, $encType = false){
  $output = "\n<form class=\"form-horizontal\" 
                        method=\"$sMethod\" 
                        action=\"$sAction\">\n";
  $output .= $sForm;
  $output .= "</form>\n";
  return $output;
}

function wrapJumbotron($sStringData){
  $output = "\n<div class=\"jumbotron\">\n";
  $output .= $sStringData;
  $output .= "</div>\n";
  return $output;
}


function wrapAlert($sAlertType, $sStrongText, $sNormalText){
  $aPossibleAlerts = ['success', 'info', 'warning', 'danger'];
  $output = "";
  if( !in_array( $sAlertType, $aPossibleAlerts )){
    return;
  } else { 
    $output .= '<div class="alert alert-' . $sAlertType;
  }
  

  $output .= ' alert-dismissible" role="alert">';
  $output .= '<button type="button" class="close" 
              data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>';
  $output .= '<strong>';
  $output .= $sStrongText;
  $output .= '</strong> ';
  $output .= $sNormalText;
  $output .= '</div>';
  return $output;
}





function wrapContainer($sData){
  $output = "\n<div class='container'>";
  $output .= $sData;
  $output .= "</div>\n";
  return $output;
}

function outputNavBarFix(){
  return '<div class="nav-bar-fix"></div>';
}

function outputTop ( $title = "My Website", $pageName = "" ){
	if( isset($_SESSION['user_logged']) && true == $_SESSION['user_logged'] ){
    $aLinks = [ 
              "Home"       => "index.php",
              "Profiles"   => "profiles.php",
              "My Profile" => "myprofile.php",
              "Logout"     => "logout.php"
            ];
  } else {
    $aLinks = [ 
                "Home"      => "index.php",
                "Profiles"  => "profiles.php",
                "Sign-Up"   => "signup.php",
                "Login"     => "login.php"
              ];
  }
  


  $metadata = <<<EOT
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>$title</title>

    <!-- Bootstrap core CSS -->
    <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">

  

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Custom styles for this template -->
    <link href="css/styles.css" rel="stylesheet">
  </head>
<!-- NAVBAR
================================================== -->
  <body>
    <div class="navbar-wrapper">
      <div class="container">

        <nav class="navbar navbar-inverse navbar-static-top">
          <div class="container">
            <div class="navbar-header">
              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" href="index.php">My Bootstrap Site</a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
              <ul class="nav navbar-nav">
EOT;
  $sLinks = "";
  foreach( $aLinks as $linkTitle => $path ){
    $sLinks .= "<li";
    if( $linkTitle == $pageName ){
      $sLinks .= " class='active'";
    }
    $sLinks .= ">";
    $sLinks .= "<a href='$path'>";
    $sLinks .= $linkTitle;
    $sLinks .= "</a>";
    $sLinks .= "</li>";
  }
  unset( $linkTitle );
  unset( $path );

    $metadata .= $sLinks;       
$metadata     .= <<<EOT
              </ul>
            </div>
          </div>
        </nav>

      </div><!-- /END .container-->
    </div><!-- /END .navbar-wrapper-->
EOT;

	return $metadata;
}



function outputBottom(){
	$sBottomContent = <<<EOD
     <!-- FOOTER -->
    <div class="container">
      <footer>
        <p class="pull-right"><a href="#">Back to top</a></p>
        <p>&copy; 2014 Company, Inc. &middot; <a href="#">Privacy</a> &middot; <a href="#">Terms</a></p>
      </footer>
    </div><!-- /.container  -->

    


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="js/workaround.js"></script>
  </body>
</html>

EOD;

 return $sBottomContent;
}