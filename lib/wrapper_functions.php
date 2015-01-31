<?php
/*
	A variety of functions that are used to generate parts of the screen.
	By using functions we guarentee:
		1) consistence for various parts that can appear more than once.
		   We are taking advantage of the set of CSS CLASSES found in css/styles.css
		   These classes are named according to the BOOTSTRAP conventions

		2) hide the mess of mixing HTML and PHP away from the mainline code, 
			making it easier to read and follow
*/

/*
	The SIDEBAR contains links which the user might want to access.
	Add links to the list below and they will always appear whenever the sidebar appears. 
*/
function sidebar () {
	$current_page = str_replace( "/bootstrapsite/", "", $_SERVER ['PHP_SELF'] );
	$aLinks = [
				"myprofile.php" => "View My Profile",
				"myprofile_image.php" => "Add/Edit Image",
				"myprofile_description.php" => "Add/Edit Description",
				"myprofile_user_info.php" => "Add/Edit Personal Info"
			  ];
	/*
		NOTICE: 
			The special characters \n and \t
			These are used to format the generated HTML making it easier to read and debug.
			\n forces a NEWLINE character, making whatever follows appear on the next line
			\t forces a TAB character, indenting the text that follows.

		The LINKS provided in the ARRAY above are generated as an UNORDERED LIST in the HTML
		If you wanted a different kind of HTML, you would change this part of the code.
	*/
	$output = "\n\t\t<ul class=\"nav nav-pills nav-stacked\">\n";
	foreach ( $aLinks as $path => $dspLabel ) {
		// Output each LIST ITEM with appropirate tags
		$output .= "<li role=\"presentation\"";
		if ( $path == $current_page ) {
			$output .= " class=\"active\"";
		}
		// The actual link is given taken from the array KEY and VALUE
		// The entire LI element is generated on a single line in the HTML
		$output .= "><a href=\"$path\">$dspLabel</a></li>";
	}
	$output .= "\t\t</ul>\n";
	return $output;
}

/*
	A COLUMN on the display is a DIV in the HTML, and display is controlled
	by the CSS, so correctly generated class names are needed.
*/
function wrapColumn ( $html, $iNumCols ) {
	$output = "\n\t\t<div class=\"col-md-$iNumCols\">\n";
	$output .= $html;
	$output .= "\t</div>\n";
	return $output;
}

/*
	A ROW on the display is also a DIV with a different set of classes
*/
function wrapRow ( $html ) {
	$output = "\n\t<div class=\"row\">\n";
	$output .= $html;
	$output .= "\t</div>\n";
	return $output;
}

/*
	wrapImageTag takes TWO paramters.
	The second paramter is defaulted to NULL

	CODING TIP:
		You might be tempted to check for a value and only output if one is provided:
		if ( !empty( $sSizeConstraints ) ) {
			$output .= $sSizeConstraints;
		}

		This is NOT necessary since outputting a NULL does not effect the display

	NOTICE: 
		We "hard code" the alternate image name.
		If you wanted to have this change with each image, you could add a paramter
		with a default value and output that variable

		function wrapImageTag ( $sPath, $sSizeConstraints = "", $sAlt = "Responsive Image")

		Then use code like this:
		$output .= "alt='". $sAlt . "'>";
		If not value was passed, the default value is used.
*/
function wrapImageTag ( $sPath, $sSizeConstraints = "" ) {
	$output = "<img src=\"$sPath\" ";
	$output .= "class=\"img-responsive\" ";
//	if( !empty( $sSizeConstraints ) ) {
		$output .= $sSizeConstraints;
//	}
	$output .= "alt=\"Responsive image\">";
	return $output;
}

/*
	We often want LINKS to appear as BUTTONS.
	One easy way to do that is use the ROLE element and CSS to re-style an A tag
*/
function wrapLinkButton ( $sClasses, $sPath, $sLabel ) {
	return "<a class=\"$sClasses\" href=\"$sPath\" role=\"button\">$sLabel</a>";
}


/*
	This function provides a FORM SUBMIT button using the name/value provided.
	The formatting will always be consistent.
	This is very useful for simple forms, but you might need to hand-craft the submit buttons
	on a more complex form with a variety of submit button options (SAVE, CANCEL, REVERT, etc.)
*/
function formSubmit ( $sName, $sValue ) {
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



/*
	formTextarea takes a large number of parameters.
	NOTICE: 
		the last parameter: $sErrorMsg
		If NOT EMPTY, it would display an error message underneath the text area
		GENERATE AN ERROR and look at the HTML in your browser's inspector.
*/
function formTextarea ( $iRows, $sName, $sValue, $sDspLabelName, $sErrorMsg ) {
	$output  = "\t<div class=\"form-group\">\n";
	$output .= "\t\t<label for=\"$sName\" 
	            class=\"col-sm-3 control-label\">$sDspLabelName</label>\n";
	$output .= "\t\t<div class=\"col-sm-9\">\n";
	$output .= "\t\t\t<textarea 
	                  name=\"$sName\"
	                  class=\"form-control\" 
	                  id=\"$sName\" 
	                  rows=\"$iRows\"
	                  placeholder=\"$sDspLabelName\">$sValue</textarea>\n";
	$output .= $sErrorMsg;
	$output .= "\t\t</div>\n";
	$output .= "\t</div>\n";
	return $output;
}

/*
	the INPUT form field is a workhorse. Our wrapper will handle the basic types.
	For some of the special INPUT types, such as FILE, we will make special wrapper functions.
	SEE: 
		formFILES below for an example
*/
function formInput ( $sType, $sName, $sValue, $sDspLabelName, $sErrorMsg ) {
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

/* 
	NOTICE: 
		We have both a LABEL and an INPUT 
		BOOTSTRAP provides CSS to make our labels and input fields look the way we want.
	
		Of course, we could extend the wrapper to pass in CLASSES which would override the look
		that BOOTSTRAP provides.
	
	CODING EXERCISE:
		HOW WOULD YOU DO THAT?
*/
function formFiles ( $sName, $sDspLabelName ) {
	$output = "\n\t<div class=\"form-group\">\n";
	$output .= "\t\t<label for=\"$sName\">$sDspLabelName</label>\n";
	$output .= "\t\t<input type=\"file\" 
	              id=\"$sName\"
	              name=\"$sName\" />\n";
	$output .= "\t</div>\n";
	return $output;
}

/*
	CODING TIP:
		In wrapImageTag we had a parameter ($sSizeConstraints = "") that was set to an empty string.
		We could safely output that parameter in the correct place without checking it.

		In wrapFormTags we have a parameter, $encType = false.
		This paramter is used to determine if we need to output a special, hardcoded, value as
		part of the FORM itself.

		So, we must check the value, and only output the special value if the form designer wants it.

		A simple form does not need MULTIPART FORM DATA
		But a form that allows us to UPLOAD FILES does need that specified.
*/
function wrapFormTags ( $sForm, $sMethod, $sAction, $encType = false ) {
	$output = "\n<form class=\"form-horizontal\"
	                    method=\"$sMethod\" 
	                    action=\"$sAction\" ";
	if( $encType ) {
		$output .= " enctype=\"multipart/form-data\" "; 
	}
	$output .= ">\n";
	$output .= $sForm;
	$output .= "</form>\n";
	return $output;
}

/*
	JUMBOTROM is one of the special BOOTSTRAP classes that makes the screen look the way we want.

	CODING EXERCISE:
		Find where this is called in the main code and COMMENT IT OUT to see what happens.
*/
function wrapJumbotron ( $sStringData ) {
	$output = "\n<div class=\"jumbotron\">\n";
	$output .= $sStringData;
	$output .= "</div>\n";
	return $output;
}


/*
	An ALERT is a MESSAGE that tells the user something.
	We have FOUR types. 

	CODING TIP:
		You could write FOUR FUNCTIONS, one for each type
		We have chosen to write ONE FUNCTION and use the ALERT TYPE to change a SINGLE VALUE,
		which is what BOOTSTRAP CLASS is used to format the message.

		Both ways work equally well. It's a choice you make as a SOFTWARE DESIGNER.
	
		One way to choose is if you find yourself writing the same code, over and over,
		with only small differences, consider writing a generalized function to handle that.
*/
function wrapAlert ( $sAlertType, $sStrongText, $sNormalText ) {
	$aPossibleAlerts = [ 'success', 'info', 'warning', 'danger' ];
	$output = "";
	if ( !in_array( $sAlertType, $aPossibleAlerts )) {
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

/*
	CONTAINERS are a common DIV element.
	Most screen layers will define a container of some sort.
*/
function wrapContainer ( $sData ) {
	$output = "\n<div class='container'>";
	$output .= $sData;
	$output .= "</div>\n";
	return $output;
}

/*
	As nice as HTML and CSS are, not all browsers work equally well.
	Some very clever people have come up with "fixes" for some of most common
	problems. 
	We are taking advantage of that here for our navigation links
*/
function outputNavBarFix () {
	return '<div class="nav-bar-fix"></div>';
}


/*
	Every PAGE requires certain common elements.
	There are a lot of HTML elements (tags) that we specify so that each page
	will look consistent across the whole application.

	In addition, we have a LOGIN SYSTEM.
	Users who are LOGGED IN see one set of LINKS
	Users who are NOT LOGGED IN see a different set of links.

	So, our outputTOP wrapper checks the SESSION to determine if the USER is logged in
	and outputs the correct links for that user, in addition to all the basic stuff needed to generate
	the page using the BOOTSTRAP template. 
*/
function outputTop ( $title = "My Website", $pageName = "" ) {
	if( isset( $_SESSION [ 'user_logged' ] ) && true == $_SESSION [ 'user_logged' ] ) {
		$aLinks =  [ "Home"       => "index.php",
					 "Profiles"   => "profiles.php",
					 "My Profile" => "myprofile.php",
					 "Logout"     => "logout.php"
	        	   ];
	} else {
		$aLinks = [ "Home"      => "index.php",
	            	"Profiles"  => "profiles.php",
	            	"Sign-Up"   => "signup.php",
	            	"Login"     => "login.php"
	          	  ];
  }
  

/*
	CODING TIP:
		We are taking advantage of a very nice feature of PHP called a HEREDOC
		It is a way to put a LOT of formatted text into a variable without worrying
		about nested quotes and lots of special things.
		It uses the special syntax <<< with the START MARKER
		You CAN NOT have a space between the <<< and the START MARKER or it won't work.
		When the code processor encounters the START MARKER again, as the only thing on a
		line (so no spaces in front of it) the HEREDOC ends and normal processing starts again

		You can still reference PHP variable in your HEREDOC
		Do you see $title below?
*/

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

/*
	NOTICE: 
		We're back to normal PHP coding
*/
	$sLinks = "";
	foreach ( $aLinks as $linkTitle => $path ) {
		$sLinks .= "<li";
		if ( $linkTitle == $pageName ) {
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
/*
	Another HEREDOC
	NOTICE: 
		We have added some HTML COMMENTS after some of the closing tags
		This is helpful when you are looking at the generated HTML for matching up
		the start of big DIVs with either end, since generated HTML doesn't always look
		as nice as we might like and it can be tricky to find where everything matches up.
		We'll take all the help we can get.
*/      
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


/*
	Just like at the TOP of a page there are many common elements,
	we have common elements at the bottom of a page.

	We have included a FOOTER
	You could added nagivation links at the bottom in your footer.

	CODING EXERCISE:
		HOW WOULD YOU DO THAT?

	We have also added the HTML tags that will load the BOOTSTRAP javascript
	
	We've attempted to indent and format, but just in case, many of the
	DIV tags HTML COMMENTS saying what they are for.
*/
function outputBottom () {
	$sBottomContent = <<<EOD
     <!-- FOOTER -->
    <div class="container">
      <footer>
        <p class="pull-right"><a href="#">Back to top</a></p>
        <p>&copy; 2014 Company, Inc. &middot; 
        <a href="privacy.html" data-toggle="modal" data-target="#basicModal1">Privacy</a>
         &middot; 
         <a href="tos.html" data-toggle="modal" data-target="#basicModal2">Terms</a></p>
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
    <div class="modal fade" id="basicModal1" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
        </div><!-- /end .modal-content-->
      </div><!-- /end .modal-dialog-->
    </div><!-- /end .modal-->
    <div class="modal fade" id="basicModal2" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
        </div><!-- /end .modal-content-->
      </div><!-- /end .modal-dialog-->
    </div><!-- /end .modal-->
    <div class="modal fade" id="basicModal3" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
        </div><!-- /end .modal-content-->
      </div><!-- /end .modal-dialog-->
    </div><!-- /end .modal-->
  </body>
</html>

EOD;

	return $sBottomContent;
}