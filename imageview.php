<?php
include_once( "lib/config.php" );
include_once( "lib/db_connect.php" );

if ( isset( $_GET ['user_id']) 
	 && 
	 (0 != (int)$_GET['user_id'])
	) {
	//get the image from the database...
	$user_id = (int)$_GET['user_id'];
	$sql = "SELECT 
				image_name
			FROM 
				user_image
			WHERE 
				user_id = $user_id";
	$stmt = $dbh->query( $sql );
	$row  = $stmt->fetch( PDO::FETCH_ASSOC );
	$imagePath = IMAGE_BASE_PATH . $row['image_name'];
	$userName = urldecode( $_GET['user_name']);

}
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

