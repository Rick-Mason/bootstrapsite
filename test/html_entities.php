<?php
 $sStringHtml = "<p>This is a paragraph</p>";

 $sEntities = htmlentities($sStringHtml, ENT_QUOTES);
 echo $sEntities;
 echo "<br />";

 echo html_entity_decode($sEntities);

?>