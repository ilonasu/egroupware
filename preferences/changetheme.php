<?php
  /**************************************************************************\
  * phpGroupWare - preferences                                               *
  * http://www.phpgroupware.org                                              *
  * Written by Joseph Engo <jengo@phpgroupware.org>                          *
  * --------------------------------------------                             *
  *  This program is free software; you can redistribute it and/or modify it *
  *  under the terms of the GNU General Public License as published by the   *
  *  Free Software Foundation; either version 2 of the License, or (at your  *
  *  option) any later version.                                              *
  \**************************************************************************/

  /* $Id$ */

  $phpgw_info["flags"] = array("noheader" => True, "nonavbar" => True, "currentapp" => "preferences");

  include("../header.inc.php");
  
  if ($ntheme) {
     $theme = $ntheme;
     $phpgw->common->preferences_update($phpgw_info["user"]["account_id"],"theme","common");
     Header("location: " . $phpgw->link("changetheme.php"));
     exit;
  }

  $dh = opendir($phpgw_info["server"]["server_root"] . "/themes");
  while ($file = readdir($dh)) {
#    if ($file != "." && $file != ".." && $file != "CVS") {
    if ( eregi( "\.theme$", $file ) ) {
       $installed_themes[] = substr($file,0,strpos($file,"."));
    }
  }

  $phpgw->common->phpgw_header();
  $phpgw->common->navbar();

  echo "<br>" . lang("your current theme is: x",$phpgw_info["user"]["preferences"]["theme"]);
  echo "<br>" . lang("please, select a new theme") . ":";
  echo "<br>";

  for ($i=0; $i<count($installed_themes); $i++) {
     echo "<br><a href=\"" . $phpgw->link("changetheme.php","ntheme="
	. $installed_themes[$i]) . "\">" . $installed_themes[$i] . "</a>\n";
  }

  $phpgw->common->phpgw_footer();
?>
