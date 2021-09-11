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
  
  $pageTitle = "$lang[site_name] | $lang[dota_games]";
  
  if ($FiltersOnGamePage == 1) {
  if (isset($_POST["years"])) {$sql_year = "AND YEAR(datetime) = '".safeEscape($_POST["years"])."'";} 
  else $sql_year = "";
  if (isset($_POST["months"])) {$sql_month = "AND MONTH(datetime) = '".safeEscape($_POST["months"])."'";}
  else   $sql_month = "";
  if (isset($_POST["days"]) AND $_POST["days"]>0) 
  {$sql_day = "AND DAYOFMONTH(datetime) = '".safeEscape($_POST["days"])."'";} 
  else $sql_day = "";
  
  if (isset($_GET["y"])) {$sql_year = "AND YEAR(datetime) = '".safeEscape($_GET["y"])."'";} 
  if (isset($_GET["m"])) {$sql_month = "AND MONTH(datetime) = '".safeEscape($_GET["m"])."'";}
  if (isset($_GET["d"]) AND $_GET["d"]>0) 
  {$sql_day = "AND DAYOFMONTH(datetime) = '".safeEscape($_GET["d"])."'";} 
  } else {$sql_year =""; $sql_month =""; $sql_day=""; }
  
  $sql = "SELECT COUNT(*) FROM games 
  WHERE map LIKE '%dota%' 
  $sql_year $sql_month $sql_day
  LIMIT 1";
  
  $result = $db->query($sql);
  $r = $db->fetch_row($result);
  $numrows = $r[0];
  $result_per_page = $games_per_page;
  
  $order = 'id';
  if (isset($_GET['order']))
  {
  if ($_GET['order'] == 'game') {$order = ' LOWER(gamename) ';}
  if ($_GET['order'] == 'duration') {$order = ' duration ';}
  if ($_GET['order'] == 'type') {$order = ' type ';}
  if ($_GET['order'] == 'date') {$order = ' datetime ';}
  if ($_GET['order'] == 'creator') {$order = ' LOWER(creatorname) ';}
  }
  
  $sort = 'DESC';
  if (isset($_GET['sort']) AND $_GET['sort'] == 'asc')
  {$sort = 'desc'; $sortdb = 'ASC';} else {$sort = 'asc'; $sortdb = 'DESC';}
  
  //Show sentinel and scourge won
  if ($ShowSentinelScourgeWon == 1)
    {require_once("./includes/get_games_summary.php");}
	
	if ($FiltersOnGamePage == 1) {
	require_once('./includes/get_games_filter.php');
	}
	
    include('pagination.php');
	
  $sql = "SELECT 
          g.id, map, datetime, gamename, ownername, duration, creatorname, dg.winner, 
		  CASE WHEN(gamestate = '17') THEN 'PRIV' ELSE 'PUB' end AS type 
		  FROM games as g 
		  LEFT JOIN dotagames as dg ON g.id = dg.gameid 
		  WHERE map LIKE '%dota%' $sql_year $sql_month $sql_day
		  ORDER BY $order $sortdb 
		  LIMIT $offset, $rowsperpage";
  
  $result = $db->query($sql);
  
  
  echo "<div align='center'><table class='tableA'> 
  <tr>
  <th class='tableD'><div align='left'><a href='{$_SERVER['PHP_SELF']}?order=game&sort=$sort'>$lang[game]</a></div></th>
  <th><div align='left'><a href='{$_SERVER['PHP_SELF']}?order=duration&sort=$sort'>$lang[duration]</a></div></th>
  <th><div align='left'><a href='{$_SERVER['PHP_SELF']}?order=type&sort=$sort'>$lang[type]</a></div></th>
  <th><div align='left'><a href='{$_SERVER['PHP_SELF']}?order=date&sort=$sort'>$lang[date]</a></div></th>
  <th><a href='{$_SERVER['PHP_SELF']}?order=creator&sort=$sort'>$lang[creator]</a></th>
  </tr>";
  
  while ($list = $db->fetch_array($result,'assoc')) {
        $gameid=$list["id"]; 
		$map=convEnt2(substr($list["map"], strripos($list["map"], '\\')+1));
		$type=$list["type"];
		$gametime=date($date_format,strtotime($list["datetime"]));
		$gamename=trim($list["gamename"]);
		$ownername=$list["ownername"];
		$duration=secondsToTime($list["duration"]);
		$creator=trim($list["creatorname"]);
		$creator2=trim(strtolower($list["creatorname"]));
		$winner=$list["winner"];
		$dispWinner = "";
		if ($winner == 1) {$dispWinner = "onMouseout='hidetooltip()' onMouseover='tooltip(\"<b>Map</b>: $map<br><b>$lang[winner]: </b>$lang[Sentinel]\", 150); return false'";
		$gamename = "<span class='GamesSentinel'>$gamename</span>";
		}
		
		if ($winner == 2) {$dispWinner = "onMouseout='hidetooltip()' onMouseover='tooltip(\"<b>Map</b>: $map<br><b>$lang[winner]: </b>$lang[Scourge]\", 150); return false'";
		$gamename = "<span class='GamesScourge'>$gamename</span>";}
		
		if ($winner == 0) {$dispWinner = "onMouseout='hidetooltip()' onMouseover='tooltip(\"<b>Map</b>: $map<br><b>Draw Game\", 150); return false'";
		$gamename = "<span class='GamesDraw'>$gamename</span>";}

	echo "<tr class='row'>
	<td title='' class='tableD' width='300px'><div align='left'><a $dispWinner  href='game.php?gameid=$gameid'>$gamename</a></div></td>
	<td width='160px'><div align='left'>$duration</div></td>
	<td width='100px'><div align='left'>$type</div></td>
	<td width='200px'><div align='left'>$gametime</div></td>
	<td width='200px'><div align='left'><a href='user.php?u=$creator2'>$creator</a></div></td>
	</tr>";
  }
      echo "</table></div>";
	  
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