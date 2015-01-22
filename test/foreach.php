<?php
include("errors_functions.php");

//foreach is a construct that allows you to loop over keys and/or values of an array


//looping over values only
$aArray1 = ["foo", "bar", "tig", "tag"];
foreach( $aArray1 as $value ){
	//do action
	echo $value;
	echo "<br />";
}


//loooping over keys and values
$aArray2 = [ "foo" => "cat", "bar" => "dog", "tig" => "fish", "tag" => "bird" ];
foreach( $aArray2 as $key => $value ){
	//doaction on each itiration 
	echo "<strong>$key</strong> is $value <br />";
}
unset( $key );
unset( $value );

