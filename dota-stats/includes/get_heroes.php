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
	 
	 if (isset($_GET['rowsperpage']))
	 {$rowsperpage = safeEscape($_GET['rowsperpage']);
	 if (!is_numeric($_GET['rowsperpage']))     {die;}
	 }
	 
	 if (isset($_GET['page']))
	 {$page = safeEscape($_GET['page']);
	 if (!is_numeric($_GET['page']))     {die;}
	 }
	 else $page = 1;
	 
	 
	  //echo "$_GET[page] - page<br/>";
	  //echo "$_GET[rowsperpage] - rowperpage<br/>";
	  //echo "$_GET[offset] - offset<br/>";
	  //echo "$_GET[gp] - gp<br/>";
	  //echo "$_GET[u] - u<br/>";
	  //echo "$_GET[sort] - sort<br/>";
	  //echo "$_GET[order] - order<br/>";
	 
	  
	  
	    $order = ' LOWER(description) ';
        if (isset($_GET['order']))
        {
        if ($_GET['order'] == 'games') {$order = ' totgames ';}
        if ($_GET['order'] == 'wins') {$order = ' wins ';}
        if ($_GET['order'] == 'losses') {$order = ' losses ';}
        if ($_GET['order'] == 'winratio') {$order = ' winratio ';}
        if ($_GET['order'] == 'kills') {$order = ' kills ';}
		if ($_GET['order'] == 'deaths')  {$order = ' deaths ';}
		if ($_GET['order'] == 'assists') {$order = ' assists ';}
		if ($_GET['order'] == 'kd') {$order = ' kdratio ';}
		if ($_GET['order'] == 'creeps') {$order = ' creepkills ';}
		if ($_GET['order'] == 'denies') {$order = ' creepdenies ';}
		if ($_GET['order'] == 'neutrals') {$order = ' neutralkills ';}
		if ($_GET['order'] == 'hero') {$order = ' description ';}
        }
	    
		$url = "http://$_SERVER[SERVER_NAME]$_SERVER[REQUEST_URI]";
		//echo $url;
		
      $sort = 'ASC';
      if (isset($_GET['sort']) AND $_GET['sort'] == 'desc')
      {$sort = 'asc'; $sortdb = 'DESC';} else {$sort = 'desc'; $sortdb = 'ASC';}
	  
	  if (isset($_GET['u'])) 
	  {$player = "AND LOWER(d.name) = '".safeEscape(strtolower($_GET['u']))."'";
	  $pref = "u=".safeEscape($_GET['u'])."&";
	  }
	  else {$player = ""; $pref = "";}
	  
	 //echo "Gsort=".$_GET['sort']." order=".$_GET['order']."  page = ".$_GET['page']. " offset = ".$_GET['offset'];
	 //echo "<br/> sort=".$sort." order=".$order."  page = ".$_GET['page']. " offset = ".$offset;
	 
  $sql = "SELECT *, 
    case when (wins = 0) then 0 when (losses = 0) then 1000 else ((wins*1.0)/(losses*1.0)) end as winratio,
	case when (kills = 0) then 0 when (deaths = 0) then 1000 else ((kills*1.0)/(deaths*1.0)) end as kdratio 
	FROM 
	   (
	   SELECT original, description, count(*) as totgames, 
	   SUM(case when(((c.winner = 1 AND a.newcolour < 6) 
	   OR (c.winner = 2 and a.newcolour > 6)) 
	   AND d.`left`/e.duration >= $minPlayedRatio) then 1 else 0 end) as wins, 
	   SUM(case when(((c.winner = 2 and a.newcolour < 6) 
	   OR (c.winner = 1 and a.newcolour > 6)) 
	   AND d.`left`/e.duration >= $minPlayedRatio) then 1 else 0 end) as losses, 
	   AVG(kills) as kills, AVG(deaths) as deaths, AVG(assists) as assists, 
	   AVG(creepkills) as creepkills, AVG(creepdenies) as creepdenies, AVG(neutralkills) as neutralkills
	   FROM dotaplayers AS a 
	   LEFT JOIN heroes as b ON hero = heroid 
	   LEFT JOIN dotagames as c ON c.gameid = a.gameid 
	   LEFT JOIN gameplayers as d ON d.gameid = a.gameid 
	   AND a.colour = d.colour 
	   LEFT JOIN games as e ON d.gameid = e.id
	   WHERE original <> 'NULL' AND c.winner <> 0  AND summary!= '-'  
	   $player
	   GROUP BY original
	   ) as y 
	   WHERE y.totgames > 0 
	   ORDER BY $order $sortdb LIMIT $offset, $rowsperpage";
	
	$result = $db->query($sql);
	
	echo "
	<div align='center'>
	<TABLE style='width:95%;margin:8px;'><TR>
	<TH style='padding-left:4px;'><a href='{$_SERVER['PHP_SELF']}?".$pref."order=hero&sort=$sort'>$lang[hero]</a></TH>
	<TH></TH>
	<TH><div align='center'><a href='{$_SERVER['PHP_SELF']}?".$pref."order=games&sort=$sort'>$lang[played]</a></div></TH>
	
	<TH><div align='center'><a href='{$_SERVER['PHP_SELF']}?".$pref."order=wins&sort=$sort'>$lang[wins]</a></div></TH>
	
	<TH><div align='center'><a href='{$_SERVER['PHP_SELF']}?".$pref."order=losses&sort=$sort'>$lang[losses]</a></div></TH>
	<TH><div align='center'><a href='{$_SERVER['PHP_SELF']}?".$pref."order=winratio&sort=$sort'>$lang[w_l]</a></div></TH>
	<TH><div align='center'><a href='{$_SERVER['PHP_SELF']}?".$pref."order=kills&sort=$sort'>$lang[kills]</a></div></TH>
	<TH><div align='center'><a href='{$_SERVER['PHP_SELF']}?".$pref."order=deaths&sort=$sort'>$lang[deaths]</a></div></TH>
	<TH><div align='center'><a href='{$_SERVER['PHP_SELF']}?".$pref."order=assists&sort=$sort'>$lang[assists]</a></div></TH>
	<TH><div align='center'><a href='{$_SERVER['PHP_SELF']}?".$pref."order=kd&sort=$sort'>$lang[kd]</a></div></TH>
	<TH><div align='center'><a href='{$_SERVER['PHP_SELF']}?".$pref."order=creeps&sort=$sort'>$lang[creeps]</a></div></TH>
	<TH><div align='center'><a href='{$_SERVER['PHP_SELF']}?".$pref."order=denies&sort=$sort'>$lang[denies]</a></div></TH>
	<TH><div align='center'><a href='{$_SERVER['PHP_SELF']}?".$pref."order=neutrals&sort=$sort'>$lang[neutrals]</a></div></TH>
	</TR>";
	
	while ($list = $db->fetch_array($result,'assoc')) {
	    $hero=$list["description"];
		$totgames=$list["totgames"];
		$wins=$list["wins"];
		$losses=$list["losses"];
		$winratio=ROUND($list["winratio"],2);

		$kills=ROUND($list["kills"],2);
		$deaths=ROUND($list["deaths"],2);
		$assists=ROUND($list["assists"],2);
		$kdratio=ROUND($list["kdratio"],2);
		$creepkills=ROUND($list["creepkills"],2);
		$creepdenies=ROUND($list["creepdenies"],2);
		$neutralkills=ROUND($list["neutralkills"],2);
		$hid=strtoupper($list["original"]);
		
		echo "<tr class='row'>
		<td width='40px'><a href='hero.php?hero=$hid'><img width='32px' height='32px' alt='' src='./img/heroes/$hid.gif' border=0 /></a></div></td>
		<td width='150px'><a href='hero.php?hero=$hid'>$hero</a></td>
		<td width='50px'><div align='center'>$totgames</div></td>
		<td><div align='center'>$wins</div></td>
		<td><div align='center'>$losses</div></td>
		<td><div align='center'>$winratio</div></td>
		<td><div align='center'>$kills</div></td>
		<td><div align='center'>$deaths</div></td>
		<td><div align='center'>$assists</div></td>
		<td><div align='center'>$kdratio</div></td>
		<td><div align='center'>$creepkills</div></td>
		<td><div align='center'>$creepdenies</div></td>
		<td><div align='center'>$neutralkills</div></td>
		</tr>";
		

		
	}
	echo "</TABLE></div><br/>";
	
	?>