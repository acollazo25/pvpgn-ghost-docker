<?php
/*********************************************
<!-- 
*   	DOTA OPENSTATS
*   
*	Developers: Ivan.
*	Contact: ivan.anta@gmail.com - Ivan
*
*	
*	Please see http://openstats.iz.rs
*	and post your webpage there, so I know who's using it.
*
*	Files downloaded from http://openstats.iz.rs
*
*	Copyright (C) 2010  Ivan
*
*
*	This file is part of DOTA OPENSTATS.
*
* 
*	 DOTA OPENSTATS is free software: you can redistribute it and/or modify
*    it under the terms of the GNU General Public License as published by
*    the Free Software Foundation, either version 3 of the License, or
*    (at your option) any later version.
*
*    DOTA OPEN STATS is distributed in the hope that it will be useful,
*    but WITHOUT ANY WARRANTY; without even the implied warranty of
*    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*    GNU General Public License for more details.
*
*    You should have received a copy of the GNU General Public License
*    along with DOTA OPENSTATS.  If not, see <http://www.gnu.org/licenses/>
*
-->
**********************************************/
  include('header.php');
  //include('./includes/AJAX.php');
    
    $pageTitle = "$lang[site_name] | $lang[heroes]";
   
   if (!isset($_GET["u"])) {$showStatsFor = $lang["hero_stats_all"];} 
   else {
   
   $showStatsFor = "$lang[hero_stats] <a href='user.php?u=".safeEscape($_GET["u"])."'><b>$_GET[u]</b></a>";}
  
    echo "<TABLE><TR><TD class='tableB'> $showStatsFor</TD></TR></TABLE><br/>";
	
	$sql = "SELECT * FROM heroes WHERE summary!= '-' ORDER BY LOWER(description) ASC";
	
	$result = $db->query($sql);
	
	echo '<form name="myForm" method="post" action="">
	<select id="buildUrl2" name = "searchterm">';
	
	$c = -1;
	
	 while ($list = $db->fetch_array($result,'assoc')) {
	 $hero = $list['description'];
	 $c++;
	 echo "<option  value = 'includes/ajax_get_hero.php?searchterm=$list[original]'>$list[description]</option>";
	 }
	echo '</select>
	 <input maxlength="42" type="hidden" id="text1"/>
	 <input maxlength="42" type="hidden" id="text2"/>
	 <input maxlength="42" type="hidden" id="text3"/>
	 <input maxlength="42" type="hidden" id="text4"/>
	 <input type="button" onclick="requestActivities()" class="inputButton"  value="'.$lang["hero_sel"].'" />';
	
  
  echo "<div id='divActivities2'></div></form><br/>";
  $result_per_page = $games_per_page;
  $numrows = $c;
  
    include('pagination.php');
	/*include('./includes/ajax_heroes.php');

   echo " <body onload='requestActivities2(\"includes/ajax_get_heroes.php?sort=asc&order=description&offset=0&rowsperpage=$rowsperpage&page=1\");'> ";
   
    echo "<div id='divActivities2'></div>";*/
	
	include('includes/get_heroes.php');
  
    include('pagination.php');
	echo "<br>";
  
    include('footer.php');

    $pageContents = ob_get_contents();
    ob_end_clean();
    echo str_replace('<!--TITLE-->', $pageTitle, $pageContents);
  
   //Cache this page
  if ($cachePages == '1')
  file_put_contents($CacheTopPage, str_replace("<!--TITLE-->",$pageTitle,$pageContents));
  ?>