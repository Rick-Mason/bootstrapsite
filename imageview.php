<?php
include_once( "lib/config.php" );
include_once( "lib/db_connect.php" );

/*
	Look at the IF statement.
	NOTICE: 
		It has TWO parts
		The first part checks to see if the variable we want to look at is SET
		If it is NOT SET, the second part won't even run because we 
		specified && (AND)

	In the second part, we extract the value, which we know is SET, 
	and CAST it to an integer.

	ALL GET and POST variable are passed as STRINGS.
	But we store the USER_ID as an integer in the Database

	The special syntax
		(int)
	tells the PHP processor to convert the STRING to the NUMBER represented 
	by the string.

*/
if ( isset( $_GET ['user_id'] )  && 
   ( 0 != (int) $_GET ['user_id'] )) {
	//get the image from the database...
	$user_id = (int) $_GET ['user_id'];
	$sql = "SELECT 
				image_name
			FROM 
				user_image
			WHERE 
				user_id = $user_id";
	$stmt = $dbh->query ( $sql );
	$row  = $stmt->fetch ( PDO::FETCH_ASSOC );
	$imagePath = IMAGE_BASE_PATH . $row ['image_name'];
	$userName = urldecode( $_GET ['user_name'] );

}

/*
	NOTICE:
		We have completed the PROCESSING. What's left is the DISPLAY.

		We can CLOSE the PHP part of the file, using the special
		closing tag: ?>
		Anything after the closing tag will be interpreted as HTML

		We can still use PHP, because this is a .php file
		To insert PHP code, use the special PHP open tag:<?php

		Then write the code you need (all the variables you could 
		access before are still available)

		Once you complete the needed PHP coding, close the PHP tag
		to return to HTML.

		In this example all we need to do is insert the $userName
		and $imagePath that we obtained in the code above.

	CODING TIP:
		It is good coding practice to avoid mixing HTML and PHP.

		In this file all of the PROCESSING happens first.
		Then we switch to HTML and only use PHP to ECHO values.

		If you find yourself doing a lot of additional LOGIC or 
		PROCESSING that you have to EMBED in the HTML, rethink
		your layout or processing needs to see if you can reduce
		the complexity, or separate out the LOGIC / PROCESSING
		to keep your code CLEAN, CLEAR and READABLE.
*/
?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <title>Remote file for Bootstrap Modal</title>  
</head>
<body>
<div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                 <h2 class="modal-title"><?php echo $userName ?></h2>
            </div>			<!-- /modal-header -->
            <div class="modal-body">


<img src = "<?php echo $imagePath ?>" alt="User Profile Image" >
</div>			<!-- /modal-body -->
            
</body>
</html>		

