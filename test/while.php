<?php

$i = 1;
while( $i <= 10 ){
	echo "the number is: " . $i;
	echo "<br />";
	$i++;
}
echo "Im out of the loop.";


$array = ['foo', 'bar', 'tig', 'tag'];

$i = 0;

while($array){
	echo $array[$i];
	echo "<br />";
	$i++;
}