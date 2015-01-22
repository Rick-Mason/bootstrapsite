<?php
include("errors_functions.php");


//initialize array with multiple values
$aMyArray = ["foo", "bar", "tig", "tag"];
preTags($aMyArray);


//add to an existing array
$aMyArray[] = "another value";
preTags($aMyArray);

//initialize array with one value
$aMySecondArray[] = "this is a value";
preTags($aMySecondArray);

//initialize multi associative array
$aMultiAssoc = ["foo" => "bar" ];
preTags($aMultiAssoc);

//initialize multi assoc array with our own keys
$aMultiAssoc2 = ["1" => "foo"];
preTags($aMultiAssoc2);


//initialize multi assoc array with our own keys
$aMultiAssoc3 = ["one" => "foo"];
preTags($aMultiAssoc3);

//initialize multi assoc array with our own keys show PHP overwriting
//and casting
$aMultiAssoc4 = [ 1 => "foo", "1" => "bar", 1.5 => "tig"];
preTags($aMultiAssoc4);

//create array with mixed associative keys
$aAnotherArray = [ 1 => "foo", "bar", "6" => "tig", "tag"];
preTags($aAnotherArray);

//add to an array
$aAnotherArray[8] = "ber";
preTags($aAnotherArray);

//add to an array no key specified
$aAnotherArray[] = "tos";
preTags($aAnotherArray);


















