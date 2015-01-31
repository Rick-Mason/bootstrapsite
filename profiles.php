<?php
session_start();
include_once( "lib/error_functions.php" );
include_once( "lib/config.php" );
include_once( "lib/wrapper_functions.php" );
include_once( "lib/db_connect.php" );
include_once( "lib/scripts.php");




$sql = "SELECT 
          user.user_id, 
          user.user_name,
          user_image.image_name 
        FROM 
          user 
        LEFT JOIN 
          user_image 
        ON 
          user.user_id = user_image.user_id
        ORDER BY
          user.user_name";

try {
  $sTableRows = "";
  $sTableCellOpen = "<td class=\"vert-align\">";
  $stmt = $dbh->query( $sql );
  while ( $row = $stmt->fetch( PDO::FETCH_ASSOC )) {
    $userId = $row[ 'user_id' ];
    $sTableRows .= "<tr>" . $sTableCellOpen;
    $sGetStringUserName = "&user_name=" . urlencode( $row[ 'user_name' ] );
    if( $row[ 'image_name' ] ) {
      $imagePath  = IMAGE_BASE_PATH . $row[ 'image_name' ];
      list( $iWidth, $iHeight ) = getimagesize( $imagePath );
      if( $iWidth > 150 ) {
        $fScale     = $iWidth / $iHeight;
        $iNewWidth  = 150;
        $iNewHeigth = intval( $iNewWidth / $fScale );
      } else {
        $iNewWidth  = $iWidth;
        $iNewHeigth = $iHeight;
      }
      $sSizeConstaints = " width=\"$iNewWidth\" height=\"$iNewHeigth\" ";
      $sTheImage   = wrapImageTag( $imagePath, $sSizeConstaints );
      $sTableRows .= $sTableCellOpen;
      $sTableRows .= "<a href=\"imageview.php?user_id=$userId$sGetStringUserName\" 
                        data-toggle=\"modal\" data-target=\"#basicModal3\">";
      $sTableRows .= $sTheImage;
      $sTableRows .= "</a>";
      

    } else {
      $sTableRows .= $sTableCellOpen . "&nbsp;";
    }
    
    $sTableRows .= "</td>";
    $sTableRows .= $sTableCellOpen;
    $sTableRows .= "<h3>" . $row[ 'user_name' ] . "</h3>";
    $sTableRows .= "</td>";
    $sTableRows .= $sTableCellOpen;
    $sTableRows .= wrapLinkButton( 
                    "btn btn-md btn-success" , 
                    "viewprofile.php?user_id=$userId", 
                    ">> VIEW PROFILE" );
    $sTableRows .= "</td>";
    $sTableRows .= "</tr>";
  }
} catch ( PDOException $e ) {
  echo $e;
}



/**
 * HTML - Contstruct the page
 */

$sTitle         = "View Bootstrap Profiles";
$sTopContent    = outputTop( $sTitle, "Profiles" );
$sBottomContent = outputBottom();
$sBodyContent   = outputNavBarFix();
$sTable         = "<table class=\"table table-striped\">";
$sTable        .= $sTableRows;
$sTable        .= "</table>";
$sBodyContent  .= wrapContainer( $sTable );



/**
 * OUTPUT - Make 1 large string and echo the string
 */
$sFinalOutput  = $sTopContent;
$sFinalOutput .= $sBodyContent;
$sFinalOutput .= $sBottomContent;

echo $sFinalOutput;