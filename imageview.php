<?php
include_once( "lib/config.php" );
include_once( "lib/db_connect.php" );

/*
	Look at the IF statement.
	NOTICE: It has TWO parts
	The first part checks to see if the variable we want to look at is SET
	If it is NOT SET, the second part won't even run because we specified && (AND)

	In the second part, we extract the value, which we know is SET, and CAST it to an integer.
	ALL GET and POST variable are passed as STRINGS.
	But we store the USER_ID as an integer in the Database

	The special syntax
		(int)
	Tell the PHP processor to convert the STRING to the NUMBER represented by the string
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
		We are closing the PHP part of the file.
		Anything after the closing tag will be interpreted as HTML

		We can still use PHP, because this is a .php file
		Just put in the special PHP tag <?php
		Write the code you need (all the variables you could access before are still available)
		Then CLOSE the PHP with the closing tag ?> to get back to HTML

		We insert both the $userName and the $imagePath using this method in the HTML below.
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

