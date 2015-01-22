<?php
$string = "abcdefghijk";

// start at character 4, print the remainder.
$example1 = substr($string, 3);
echo $example1;
echo "<br /><br />";

//start at character 2, print a number of characters
$example2 = substr( $string, 1, 4);
echo $example2;
echo "<br /><br />";

//start from the end at character 2, print number of characters
$example3 = substr( $string, -5, 4);
echo $example3;
echo "<br /><br />";

//start from the end of string, print the remainder but subtract a total
$example4 = substr( $string, -7, -2);
echo $example4;