<?php
session_start();
if ( !$_SESSION ['user_logged'] ) {
	header("Location: login.php");
}

include_once( "lib/error_functions.php" );
include_once( "lib/config.php" );
include_once( "lib/wrapper_functions.php" );
include_once( "lib/db_connect.php" );
include_once( "lib/scripts.php" );



/**
 * Pull all relavent data from the database in one query
 */

 
 /*
	In our example application, we added each piece of data one at a time,
	using separate files for the processing:
		myprofile_description.php
		myprofile_image.php
		myprofile_user_info.php

	Each one of these files took the EXISTING USER and added additional
	information about THAT USER, storing that information in the Database.

	Each piece of data stored in a separate table.

	It is good DATABASE DESIGN to use the same name for a key value in each 
	table where it is used to 'relate' data from one table to data in another
	table.

	We have tried to be consistent. Our primary KEY is "user_id".
	Any table that contains additional USER INFORMATION uses "user_id" to relate
	that data to the main (Master) table.

	It is possible to construct a SINGLE QUERY to SELECT all the data from
	all the tables at once, and return it in a single row of data. That query
	is in comments below.

	Depending on the Database Design and table structures you have created, this 
	might not be possible, OR, might make for such a complicated query that
	it would be hard to understand and maintain.

	We demonstrate how to make a query with a single LEFT JOIN, then make
	additional queries to get the remaining data.

	If you want to know more about DATABASE DESIGN and how to use SQL, 
	we offer a course that dives deeper into those areas.
 */

/*$sql = "SELECT 	
			user.user_name,
			user.user_email,
			user_description.user_description,
			user_personal_info.user_phone,
			user_personal_info.user_first,
			user_personal_info.user_last,
			user_image.image_name
		FROM 
			user, 
			user_description, 
			user_personal_info,
			user_image
		WHERE 
			user.user_id = {$_SESSION ['user_id']}
		AND
			user.user_id = user_description.user_id
		AND 
			user.user_id = user_personal_info.user_id
		AND 
			user.user_id = user_image.user_id";
*/

/*
	NOTICE:
		The query we use below in the LIVE contains a LEFT JOIN.

		The purpose of a LEFT JOIN is to return the requested
		column (or columns) from another table where it is 
		possible that NO data exists. In other words, a NULL
		might be returned for those columns.

		If you don't do the LEFT JOIN, the query might result
		in NO rows returned, even though the primay table has
		data associated with. 

		The BASIC query, without the LEFT JOIN looks like:
		SELECT
			user.user_id,
			user.user_name,
			user.user_email,
			user_image.image_name
		FROM
			user,
			user_image
		WHERE
			user.user_id = {$_SESSION ['user_id']}
		AND
			user_image.user_id = user.user_id

	There is not 'wrong' with this query, but it will return
	NO rows if user_image.user_id does not exist.
	This is not what we actually WANT.

	What we want is the user_id, user_name, user_email for the USER,
	AND the image_name. IF the image_name does NOT exist, we STILL
	want the rest of the information, and a NULL for the image_name.

	That's exactly what a LEFT JOIN does for us.

*/
// data from user and user_image in one joined query
$sql = "SELECT 
			user.user_id, 
  			user.user_name,
  			user.user_email, 
   			user_image.image_name 
		FROM 
			user 
		LEFT JOIN 
			user_image 
		ON 
			user.user_id = user_image.user_id
		WHERE 
			user.user_id = {$_SESSION ['user_id']}";

$stmt = $dbh->query( $sql );
$row = $stmt->fetch( PDO::FETCH_ASSOC );
$sUserEmail = $row ['user_email'];
$sUserName = $row ['user_name'];

/*
	NOTICE:
		The COLUMN associated with image_name exists and is returned as:
			$row ['image_name']
		but it might have a NULL value for image_name.
		We check to see if the value is NULL before we try to use.
*/
if ( $row ['image_name'] ) {
	$sImagePath = IMAGE_BASE_PATH . $row ['image_name'];
	$sImageString = wrapImageTag( $sImagePath );
} else {
	$sImageString = "<h3>No Image on File</h3>";
}


/*
	NOTICE:
		We use a simple, single table select to look
		for the  user_description.

		BUT, there is not guarentee that ANY data exists
		in the Database for this user in the user_description table.
*/

//data from user_description
$sql = "SELECT 
			user_description
		FROM 
			user_description
		WHERE
			user_id = {$_SESSION ['user_id']}";
$stmt = $dbh->query( $sql );
$row  = $stmt->fetch( PDO::FETCH_ASSOC );

/*
	Before, we could check for VALUE to be NULL:
		if ( $row ['image_name'] ) {

	For USER_DESCRIPTION, we need to first check to see if
	the QUERY returned ANY data, that is, is $ROW itself NULL.

	THEN, once we are sure the ROW exists, we can check to see if
	there is a non-null value for us to use.

	This apporach is very FORMAL. 
	You would do this level of checking if you needed to be very sure
	that data you were going to access is pristine.

	Below, we will provide examples of less formal, less 'paranoid'
	ways of doing the checks.
*/
if ( $row && $row ['user_description'] ) {
	$sUserDescription = html_entity_decode( $row ['user_description'] );
} else {
	$sUserDescription = "<h3>No paragraph on file</h3>";
}


//data from user_personal_info
$sql = "SELECT 
			user_first,
			user_last,
			user_phone
		FROM	
			user_personal_info
		WHERE
			user_id = {$_SESSION ['user_id']}";
$stmt = $dbh->query( $sql );
$row  = $stmt->fetch( PDO::FETCH_ASSOC );

/*
	Here, we are only checking to see if a ROW was returned.
	We are assumming that if the ROW exists, the values a NOT NULL.

	We can safely make this assumption because in our application
	we wrote the code that put the data into the database.
	When we did that, we checked for NULL values and empty strings
	and didn't do the INSERT unless the values existed and were NOT NULL.

	If you are working on a large project, with many other programmers,
	you might NOT know how that data was created. In that case, you 
	might want to write the code like we did above for USER_DESCRIPTION.
*/
if ( $row ) {
	$sUserFirstAndLast = ucfirst( $row ['user_first'] ) 
						 . " "
						 . ucfirst ( $row ['user_last'] );
} else {
	$sUserFirstAndLast = "No First and Last name on file.";
}

/*
	This is the least formal, and technically incorrect example.
	We don't check to see if the ROW is null at all.
	We just check to see if the value exists or not.

	We really shouldn't try to access a KEY in an array that
	MIGHT actually be NULL itself. It's.... bad form.
	BUT, the intention of the code is clear, and it won't BREAK.
	It might throw a warning or notice depending on your server setting,
	but you can ignore them if you want.

	If you know the data is SAFE, then writing the code for clarity
	rather than complete correctness is sometimes a better choice.
*/
if ( $row ['user_phone'] ) {
	$sUserPhone = formatPhone( $row ['user_phone'] );
} else {
	$sUserPhone = "No Phone number on file.";
}





/**
 * HTML - Contstruct the page
 */


//right column
$sidebar = sidebar();
$rightColContent = wrapColumn( $sidebar, 3 );

//left column



$jumboContent = wrapJumbotron( "<h1>$sUserName</h1>" );
$morecontent =  $sImageString;
$morecontent .= "<h2>$sUserFirstAndLast</h2>";
$morecontent .= "<h4>$sUserPhone</h4>";
$morecontent .= "<p>$sUserEmail</p>";
$morecontent .= $sUserDescription;

$leftColContent = wrapColumn( $jumboContent . $morecontent, 9 );

$rowsContent = wrapRow( $rightColContent . $leftColContent );







$sTitle         = "BootStrap Page";
$sTopContent    = outputTop( $sTitle, "My Profile" );
$sBottomContent = outputBottom();
$sBodyContent   = outputNavBarFix();
$sBodyContent  .= wrapContainer( $rowsContent );




/**
 * OUTPUT - Make 1 large string and echo the string
 */
$sFinalOutput  = $sTopContent;
$sFinalOutput .= $sBodyContent;
$sFinalOutput .= $sBottomContent;

echo $sFinalOutput;




?>