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
     if (!$_GET) {echo 'This file cannot be called directly.'; die;}
	  
     require_once ('../config.php');
	 require_once("../lang/$default_language.php");
	 require_once('../includes/class.database.php');
	 require_once('../includes/common.php');
	 require_once('../includes/db_connect.php');
	 
	 
	 $sqlYear = $_GET["sqlyear"];
	 $sqlMonth = $_GET["sqlmonth"];
	 $day_stats = $_GET["daystats"];
     $year = $_GET["year"];
	 $month = $_GET["month"];
	 $day = $_GET["day"];
	 
	 if ($day!=0) {$DisplayDay = $day;} else {$DisplayDay = "";}
   
   $DisplayMonthName = getMonthName($month,$lang["jan"],$lang["feb"],$lang["mar"],$lang["apr"],$lang["may"],$lang["jun"],$lang["jul"],$lang["aug"],$lang["sep"],$lang["oct"],$lang["nov"],$lang["dec"]);
	 
	 echo "<table><tr><th><div align='center'>$DisplayDay <b>$DisplayMonthName</b> $year</div></th></tr></table>";
	
   if ($monthRow1 == 1)
   {
     echo "<div align='center'>
	 <table class='tableA'> <tr>
     <th>$lang[top_kills]</th>
	 <th>$lang[top_assists]</th>
	 <th>$lang[top_deaths]</th>
	 <th>$lang[top_creeps]</th>
	 <th>$lang[top_denies]</th>
	 </tr>
	 <td valign='top'>
	        <table>";

   $phraseKills = "SELECT original as topHero, description as topHeroName, kills as topValue, b.name as topUser, a.gameid as topGame
		FROM dotaplayers AS a 
		LEFT JOIN gameplayers AS b ON b.gameid = a.gameid and a.colour = b.colour 
		LEFT JOIN games as c on a.gameid = c.id 
		LEFT JOIN bans on b.name = bans.name 
		JOIN heroes as d on hero = heroid 
		WHERE $sqlYear = '$year' AND $sqlMonth = '$month' $day_stats
		AND bans.name is null  ORDER BY topValue DESC, a.id ASC LIMIT $monthly_stats";
		
		$result = $db->query($phraseKills);
		while ($list = $db->fetch_array($result,'assoc')) {
		if ($list["topValue"]>0)
		{echo "<tr class='row'><td width='180px'><a href='hero.php?hero=$list[topHero]' title='$list[topHeroName]'>
		 <img style='vertical-align: middle;' width='32px' height='32px' src='img/heroes/$list[topHero].gif' border=0/></a>
		 (<a href='game.php?gameid=$list[topGame]'>$list[topValue]</a>) 
		 
		 <a href='user.php?u=$list[topUser]' title='$list[topUser]'>$list[topUser]</a>
		 </td></tr>
		 ";} else {echo "<tr class='row'><td width='180px'></td></tr>";}
		 }

		echo "</table>";
		
		echo "</td>
		      <td valign='top'><table>";
			  
			  
		$phraseAssists = "SELECT original as topHero, description as topHeroName, assists as topValue, b.name as topUser, a.gameid as topGame
		FROM dotaplayers AS a 
		LEFT JOIN gameplayers AS b ON b.gameid = a.gameid and a.colour = b.colour 
		LEFT JOIN games as c on a.gameid = c.id 
        LEFT JOIN bans on b.name = bans.name 		
		JOIN heroes as d on hero = heroid WHERE $sqlYear = '$year' AND $sqlMonth = '$month' $day_stats
		AND bans.name is null  ORDER BY topValue DESC, a.id ASC LIMIT $monthly_stats";
		
		$result = $db->query($phraseAssists);
		while ($list = $db->fetch_array($result,'assoc')) {
		if ($list["topValue"]>0)
		{echo "<tr class='row'><td width='180px'><a href='hero.php?hero=$list[topHero]' title='$list[topHeroName]'>
		 <img style='vertical-align: middle;' width='32px' height='32px' src='img/heroes/$list[topHero].gif' border=0/></a>
		 (<a href='game.php?gameid=$list[topGame]'>$list[topValue]</a>) 
		 
		 <a href='user.php?u=$list[topUser]' title='$list[topUser]'>$list[topUser]</a>
		 </td></tr>
		 ";} else {echo "<tr class='row'><td width='180px'></td></tr>";}
		 }

		echo "</table>";
		
		
		echo "</td>
		      <td valign='top'><table>";
			  
			  
		$phraseDeaths = "SELECT original as topHero, description as topHeroName, deaths as topValue, b.name as topUser, a.gameid as topGame
		FROM dotaplayers AS a 
		LEFT JOIN gameplayers AS b ON b.gameid = a.gameid and a.colour = b.colour 
		LEFT JOIN games as c on a.gameid = c.id 
        LEFT JOIN bans on b.name = bans.name 		
		JOIN heroes as d on hero = heroid WHERE $sqlYear = '$year' AND $sqlMonth = '$month' $day_stats
		AND bans.name is null  ORDER BY topValue DESC, a.id ASC LIMIT $monthly_stats";
		
		$result = $db->query($phraseDeaths);
		while ($list = $db->fetch_array($result,'assoc')) {
		if ($list["topValue"]>0)
		{echo "<tr class='row'><td width='180px'><a href='hero.php?hero=$list[topHero]' title='$list[topHeroName]'>
		 <img style='vertical-align: middle;' width='32px' height='32px' src='img/heroes/$list[topHero].gif' border=0/></a>
		 (<a href='game.php?gameid=$list[topGame]'>$list[topValue]</a>) 
		 
		 <a href='user.php?u=$list[topUser]' title='$list[topUser]'>$list[topUser]</a>
		 </td></tr>
		 ";} else {echo "<tr class='row'><td width='180px'></td></tr>";}
		 } 

		echo "</table>";
	 
		echo "</td>
		      <td valign='top'><table>";
			  
			  
		$phraseCK = "SELECT original as topHero, description as topHeroName, creepkills as topValue, b.name as topUser, a.gameid as topGame
		FROM dotaplayers AS a 
		LEFT JOIN gameplayers AS b ON b.gameid = a.gameid and a.colour = b.colour 
		LEFT JOIN games as c on a.gameid = c.id 
        LEFT JOIN bans on b.name = bans.name 		
		JOIN heroes as d on hero = heroid WHERE $sqlYear = '$year' AND $sqlMonth = '$month' $day_stats
		AND bans.name is null  ORDER BY topValue DESC, a.id ASC LIMIT $monthly_stats";
		
		$result = $db->query($phraseCK);
		while ($list = $db->fetch_array($result,'assoc')) {
		if ($list["topValue"]>0)
		{echo "<tr class='row'><td width='180px'><a href='hero.php?hero=$list[topHero]' title='$list[topHeroName]'>
		 <img style='vertical-align: middle;' width='32px' height='32px' src='img/heroes/$list[topHero].gif' border=0/></a>
		 (<a href='game.php?gameid=$list[topGame]'>$list[topValue]</a>) 
		 
		 <a href='user.php?u=$list[topUser]' title='$list[topUser]'>$list[topUser]</a>
		 </td></tr>
		 ";} else {echo "<tr class='row'><td width='180px'></td></tr>";}
		 }

		echo "</table>";
		
		echo "</td>
		      <td valign='top'><table>";
			  
		$phraseCD = "SELECT original as topHero, description as topHeroName, creepdenies as topValue, b.name as topUser, a.gameid as topGame
		FROM dotaplayers AS a 
		LEFT JOIN gameplayers AS b ON b.gameid = a.gameid and a.colour = b.colour 
		LEFT JOIN games as c on a.gameid = c.id 
        LEFT JOIN bans on b.name = bans.name 		
		JOIN heroes as d on hero = heroid WHERE $sqlYear = '$year' AND $sqlMonth = '$month' $day_stats
		AND bans.name is null  ORDER BY topValue DESC, a.id ASC LIMIT $monthly_stats";
		
		$result = $db->query($phraseCD);
		while ($list = $db->fetch_array($result,'assoc')) {
		if ($list["topValue"]>0)
		{echo "<tr class='row'><td width='180px'><a href='hero.php?hero=$list[topHero]' title='$list[topHeroName]'>
		 <img style='vertical-align: middle;' width='32px' height='32px' src='img/heroes/$list[topHero].gif' border=0/></a>
		 (<a href='game.php?gameid=$list[topGame]'>$list[topValue]</a>) 
		 
		 <a href='user.php?u=$list[topUser]' title='$list[topUser]'>$list[topUser]</a>
		 </td></tr>
		 ";} else {echo "<tr class='row'><td width='180px'></td></tr>";}
		 }

		echo "</table> </td></tr></table>";
		        }
		///    END ROW 1   /////////////////////////////////////////////////////////////////////////////////
		////////////////////////////////////////////////////////////////////////////////////////////////////
		////////////////////////////////////////////////////////////////////////////////////////////////////
		if ($monthRow2 == 1)
   {
		echo "<table class='tableA'> <tr>
     <th>$lang[top_gold]</th>
	 <th>$lang[top_neutrals]</th>
	 <th>$lang[top_towers]</th>
	 <th>$lang[top_rax]</th>
	 <th>$lang[top_couriers]</th>
	 </tr>
	 <td valign='top'>
	        <table>";
		
		$phraseGold = "SELECT original as topHero, description as topHeroName, gold as topValue, b.name as topUser, a.gameid as topGame
		FROM dotaplayers AS a 
		LEFT JOIN gameplayers AS b ON b.gameid = a.gameid and a.colour = b.colour 
		LEFT JOIN games as c on a.gameid = c.id 
		LEFT JOIN bans on b.name = bans.name 
		JOIN heroes as d on hero = heroid 
		WHERE $sqlYear = '$year' AND $sqlMonth = '$month' $day_stats
		AND bans.name is null  ORDER BY topValue DESC, a.id ASC LIMIT $monthly_stats";
		
		$result = $db->query($phraseGold);
		while ($list = $db->fetch_array($result,'assoc')) {
		if ($list["topValue"]>0)
		{echo "<tr class='row'><td width='180px'><a href='hero.php?hero=$list[topHero]' title='$list[topHeroName]'>
		 <img style='vertical-align: middle;' width='32px' height='32px' src='img/heroes/$list[topHero].gif' border=0/></a>
		 (<a href='game.php?gameid=$list[topGame]'>$list[topValue]</a>) 
		 
		 <a href='user.php?u=$list[topUser]' title='$list[topUser]'>$list[topUser]</a>
		 </td></tr>
		 ";} else {echo "<tr class='row'><td width='180px'></td></tr>";}
		 }

		echo "</table>";
		
		echo "</td>
		      <td valign='top'><table>";  
			  
		$phraseCK = "SELECT original as topHero, description as topHeroName, neutralkills as topValue, b.name as topUser, a.gameid as topGame
		FROM dotaplayers AS a 
		LEFT JOIN gameplayers AS b ON b.gameid = a.gameid and a.colour = b.colour 
		LEFT JOIN games as c on a.gameid = c.id 
        LEFT JOIN bans on b.name = bans.name 		
		JOIN heroes as d on hero = heroid WHERE $sqlYear = '$year' AND $sqlMonth = '$month' $day_stats
		AND bans.name is null  ORDER BY topValue DESC, a.id ASC LIMIT $monthly_stats";
		
		$result = $db->query($phraseCK);
		while ($list = $db->fetch_array($result,'assoc')) {
		if ($list["topValue"]>0)
		{echo "<tr class='row'><td width='180px'><a href='hero.php?hero=$list[topHero]' title='$list[topHeroName]'>
		 <img style='vertical-align: middle;' width='32px' height='32px' src='img/heroes/$list[topHero].gif' border=0/></a>
		 (<a href='game.php?gameid=$list[topGame]'>$list[topValue]</a>) 
		 
		 <a href='user.php?u=$list[topUser]' title='$list[topUser]'>$list[topUser]</a>
		 </td></tr>
		 ";} else {echo "<tr class='row'><td width='180px'></td></tr>";}
		 }

		echo "</table>";
		
		echo "</td>
		      <td valign='top'><table>";  
			  
		$phraseCK = "SELECT original as topHero, description as topHeroName, towerkills as topValue, b.name as topUser, a.gameid as topGame
		FROM dotaplayers AS a 
		LEFT JOIN gameplayers AS b ON b.gameid = a.gameid and a.colour = b.colour 
		LEFT JOIN games as c on a.gameid = c.id 
        LEFT JOIN bans on b.name = bans.name 		
		JOIN heroes as d on hero = heroid WHERE $sqlYear = '$year' AND $sqlMonth = '$month' $day_stats
		AND bans.name is null  ORDER BY topValue DESC, a.id ASC LIMIT $monthly_stats";
		
		$result = $db->query($phraseCK);
		while ($list = $db->fetch_array($result,'assoc')) {
		if ($list["topValue"]>0)
		{echo "<tr class='row'><td width='180px'><a href='hero.php?hero=$list[topHero]' title='$list[topHeroName]'>
		 <img style='vertical-align: middle;' width='32px' height='32px' src='img/heroes/$list[topHero].gif' border=0/></a>
		 (<a href='game.php?gameid=$list[topGame]'>$list[topValue]</a>) 
		 
		 <a href='user.php?u=$list[topUser]' title='$list[topUser]'>$list[topUser]</a>
		 </td></tr>
		 ";} else {echo "<tr class='row'><td width='180px'></td></tr>";}
		 }

		echo "</table>";
		
		echo "</td>
		      <td valign='top'><table>";  
			  
		$phraseCK = "SELECT original as topHero, description as topHeroName, raxkills as topValue, b.name as topUser, a.gameid as topGame
		FROM dotaplayers AS a 
		LEFT JOIN gameplayers AS b ON b.gameid = a.gameid and a.colour = b.colour 
		LEFT JOIN games as c on a.gameid = c.id 
        LEFT JOIN bans on b.name = bans.name 		
		JOIN heroes as d on hero = heroid WHERE $sqlYear = '$year' AND $sqlMonth = '$month' $day_stats
		AND bans.name is null  ORDER BY topValue DESC, a.id ASC LIMIT $monthly_stats";
		
		$result = $db->query($phraseCK);
		while ($list = $db->fetch_array($result,'assoc')) {
		if ($list["topValue"]>0)
		{echo "<tr class='row'><td width='180px'><a href='hero.php?hero=$list[topHero]' title='$list[topHeroName]'>
		 <img style='vertical-align: middle;' width='32px' height='32px' src='img/heroes/$list[topHero].gif' border=0/></a>
		 (<a href='game.php?gameid=$list[topGame]'>$list[topValue]</a>) 
		 
		 <a href='user.php?u=$list[topUser]' title='$list[topUser]'>$list[topUser]</a>
		 </td></tr>
		 ";} else {echo "<tr class='row'><td width='180px'></td></tr>";}
		 }

		echo "</table>";
		
		echo "</td>
		      <td valign='top'><table>";  
			  
		$phraseCK = "SELECT original as topHero, description as topHeroName, courierkills as topValue, b.name as topUser, a.gameid as topGame
		FROM dotaplayers AS a 
		LEFT JOIN gameplayers AS b ON b.gameid = a.gameid and a.colour = b.colour 
		LEFT JOIN games as c on a.gameid = c.id 
        LEFT JOIN bans on b.name = bans.name 		
		JOIN heroes as d on hero = heroid 
		WHERE $sqlYear = '$year' AND $sqlMonth = '$month' $day_stats
		AND bans.name is null  ORDER BY topValue DESC, a.id ASC LIMIT $monthly_stats";
		
		$result = $db->query($phraseCK);
		while ($list = $db->fetch_array($result,'assoc')) {
		if ($list["topValue"]>0)
		{echo "<tr class='row'><td width='180px'><a href='hero.php?hero=$list[topHero]' title='$list[topHeroName]'>
		 <img style='vertical-align: middle;' width='32px' height='32px' src='img/heroes/$list[topHero].gif' border=0/></a>
		 (<a href='game.php?gameid=$list[topGame]'>$list[topValue]</a>) 
		 
		 <a href='user.php?u=$list[topUser]' title='$list[topUser]'>$list[topUser]</a>
		 </td></tr>
		 ";} else {echo "<tr class='row'><td width='180px'></td></tr>";}
		 }

		echo "</table></td></tr></table>";
		
		}
		////////////////////////////////////////////////////////////////////////////////////////////////////
		////////////////////////////////////////////////////////////////////////////////////////////////////
		////////////////////////////////////////////////////////////////////////////////////////////////////
	if ($monthRow3 == 1)
   {	
		echo "<table class='tableA'> <tr>
     <th>$lang[top_kd]</th>
	 <th>$lang[top_ad]</th>
	 <th>$lang[top_games]</th>
	 <th>$lang[top_wins]</th>
	 <th>$lang[top_stay]</th>
	 </tr>
	 <td valign='top'>
	        <table>";
			
		$phraseKDR = "SELECT name as topUser, case when (totKills = 0) then 0 when (totDeaths = 0) then 1000 else ((totKills*1.0)/(totDeaths*1.0)) end as topValue from (Select b.name as name, MAX(a.id) as id,
		SUM(kills) as totKills,
		SUM(deaths) as totDeaths 
		FROM dotaplayers AS a 
		LEFT JOIN gameplayers AS b ON a.gameid = b.gameid and a.colour = b.colour 
		LEFT JOIN games as c on a.gameid = c.id 
		LEFT JOIN dotagames as d on d.gameid = c.id 
		LEFT JOIN bans on b.name = bans.name 
		WHERE winner <> 0 
		AND $sqlYear = '$year' 
		AND $sqlMonth = '$month' $day_stats 
		AND bans.name is null 
		GROUP BY b.name having count(*) >= $minGamesPlayed) as subsel 
		ORDER BY topValue DESC, id ASC 
		LIMIT $monthly_stats";
		
		$result = $db->query($phraseKDR);
		while ($list = $db->fetch_array($result,'assoc')) {
		$list["topValue"] = ROUND($list["topValue"],2);
		if ($list["topValue"]>0)
		{echo "<tr class='row'><td width='180px'>($list[topValue]) 
		 <a href='user.php?u=$list[topUser]' title='$list[topUser]'>$list[topUser]</a>
		 </td></tr>";} else {echo "<tr class='row'><td width='180px'></td></tr>";}
		 }

		echo "</table>";
		
		echo "</td>
		      <td valign='top'><table>";  
			  
		$phraseADR = "SELECT name as topUser, case when (totAssists = 0) then 0 when (totDeaths = 0) then 1000 else ((totAssists*1.0)/(totDeaths*1.0)) end as topValue from (Select b.name as name, MAX(a.id) as id,
		SUM(assists) as totAssists,
		SUM(deaths) as totDeaths 
		FROM dotaplayers AS a 
		LEFT JOIN gameplayers AS b ON a.gameid = b.gameid and a.colour = b.colour 
		LEFT JOIN games as c on a.gameid = c.id 
		LEFT JOIN dotagames as d on d.gameid = c.id 
		LEFT JOIN bans on b.name = bans.name 
		where winner <> 0 
		AND $sqlYear = '$year' 
		AND $sqlMonth = '$month' $day_stats 
		AND bans.name is null 
		GROUP BY b.name having count(*) >= $minGamesPlayed) as subsel 
		ORDER BY topValue DESC, id ASC 
		LIMIT $monthly_stats";
		
		$result = $db->query($phraseADR);
		while ($list = $db->fetch_array($result,'assoc')) {
		$list["topValue"] = ROUND($list["topValue"],2);
		if ($list["topValue"]>0)
		{echo "<tr class='row'><td width='180px'>($list[topValue]) 
		 <a href='user.php?u=$list[topUser]' title='$list[topUser]'>$list[topUser]</a>
		 </td></tr>";} else {echo "<tr class='row'><td width='180px'></td></tr>";}
		 }

		echo "</table>";
		
		echo "</td>
		      <td valign='top'><table>";  
			  
		$phraseMG = "SELECT name as topUser, totGames as topValue from (Select b.name as name, MAX(a.id) as id,
		COUNT(*) as totGames,
		SUM(deaths) as totDeaths 
		FROM dotaplayers AS a 
		LEFT JOIN gameplayers AS b ON a.gameid = b.gameid and a.colour = b.colour 
		LEFT JOIN games as c on a.gameid = c.id 
		LEFT JOIN dotagames as d on d.gameid = c.id 
		LEFT JOIN bans on b.name = bans.name 
		WHERE bans.name is null  
		AND b.name is not null  
		AND $sqlYear = '$year' 
		AND $sqlMonth = '$month' $day_stats 
		GROUP BY b.name having count(*) >= $minGamesPlayed) as subsel 
		ORDER BY topValue DESC, id ASC 
		LIMIT $monthly_stats";
		
		$result = $db->query($phraseMG);
		while ($list = $db->fetch_array($result,'assoc')) {
		$list["topValue"] = ROUND($list["topValue"],2);
		if ($list["topValue"]>0)
		{echo "<tr class='row'><td width='180px'>($list[topValue]) 
		 <a href='user.php?u=$list[topUser]' title='$list[topUser]'>$list[topUser]</a>
		 </td></tr>";} else {echo "<tr class='row'><td width='180px'></td></tr>";}
		 }

		echo "</table>";
		
		echo "</td>
		      <td valign='top'><table>";  
			  
		$phraseBW = "SELECT name as topUser, 100*wins*1.0/(totgames*1.0) as topValue, ' %' as topValueUnit from (Select b.name as name, MAX(a.id) as id,
		count(*) as totgames,
		SUM(case when((d.winner = 1 and a.newcolour < 6) or (d.winner = 2 and a.newcolour > 6)) then 1 else 0 end) as wins, 
		SUM(case when((d.winner = 2 and a.newcolour < 6) or (d.winner = 1 and a.newcolour > 6)) then 1 else 0 end) as losses
		FROM dotaplayers AS a 
		LEFT JOIN gameplayers AS b ON a.gameid = b.gameid and a.colour = b.colour 
		LEFT JOIN games as c on a.gameid = c.id 
		LEFT JOIN dotagames as d on d.gameid = c.id 
		LEFT JOIN bans on b.name = bans.name  
		where winner <> 0 AND bans.name is null  
		AND b.name is not null  
		AND $sqlYear = '$year' 
		AND $sqlMonth = '$month' $day_stats 
		AND b.`left`*1.0/c.duration*1.0 >= $minPlayedRatio
		GROUP BY b.name having count(*) >= $minGamesPlayed) as subsel 
		ORDER BY topValue DESC, id ASC 
		LIMIT $monthly_stats";
		
		$result = $db->query($phraseBW);
		while ($list = $db->fetch_array($result,'assoc')) {
		$list["topValue"] = ROUND($list["topValue"],1);
		if ($list["topValue"]>0)
		{echo "<tr class='row'><td width='180px'>($list[topValue] %) 
		 <a href='user.php?u=$list[topUser]' title='$list[topUser]'>$list[topUser]</a>
		 </td></tr>";} else {echo "<tr class='row'><td width='180px'></td></tr>";}
		 }

		echo "</table>";
		
		echo "</td>
		      <td valign='top'><table>";  
			  
		$phraseTS = "SELECT name as topUser, 100*playedTime*1.0/gameDuration*1.0 as topValue, ' %' as topValueUnit from (Select b.name as name, MAX(a.id) as id,
		SUM(`left`) as playedTime,
		SUM(duration) as gameDuration 
		FROM dotaplayers AS a 
		LEFT JOIN gameplayers AS b ON a.gameid = b.gameid and a.colour = b.colour 
		LEFT JOIN games as c on a.gameid = c.id 
		LEFT JOIN dotagames as d on d.gameid = c.id 
		LEFT JOIN bans on b.name = bans.name  
		where winner <> 1000 AND bans.name is null  
		AND b.name is not null  
		AND $sqlYear = '$year' 
		AND $sqlMonth = '$month' $day_stats 
		GROUP BY b.name having count(*) >= $minGamesPlayed) as subsel 
		ORDER BY topValue DESC, id ASC 
		LIMIT $monthly_stats";
		
		$result = $db->query($phraseTS);
		while ($list = $db->fetch_array($result,'assoc')) {
		$list["topValue"] = ROUND($list["topValue"],1);
		if ($list["topValue"]>0)
		{echo "<tr class='row'><td width='180px'>($list[topValue] %) 
		 <a href='user.php?u=$list[topUser]' title='$list[topUser]'>$list[topUser]</a>
		 </td></tr>";} else {echo "<tr class='row'><td width='180px'></td></tr>";}
		 }
      
		echo "</table></td></tr></table>";
        }
		////////////////////////////////////////////////////////////////////////////////////////////////////
		////////////////////////////////////////////////////////////////////////////////////////////////////
		////////////////////////////////////////////////////////////////////////////////////////////////////	
		
	 if ($monthRow4 == 1)
   {	
	 echo "<table class='tableA'> <tr>
     <th>$lang[most_kills]</th>
	 <th>$lang[most_assists]</th>
	 <th>$lang[most_deaths]</th>
	 <th>$lang[most_creeps]</th>
	 <th>$lang[most_denies]</th>
	 </tr>
	 <td valign='top'>
	        <table>";
			
		$phraseMK = "SELECT name as topUser, sumKills as topValue from (Select b.name as name, MAX(a.id) as id,
		SUM(kills) as sumKills 
		FROM dotaplayers AS a 
		LEFT JOIN gameplayers AS b ON a.gameid = b.gameid and a.colour = b.colour 
		LEFT JOIN games as c on a.gameid = c.id 
		LEFT JOIN dotagames as d on d.gameid = c.id 
		LEFT JOIN bans on b.name = bans.name  
		WHERE $sqlYear = '$year' 
		AND $sqlMonth = '$month' $day_stats 
		AND bans.name is null 
		GROUP BY b.name having count(*) >= $minGamesPlayed) as subsel 
		ORDER BY topValue DESC, id ASC 
		LIMIT $monthly_stats";
		
		$result = $db->query($phraseMK);
		while ($list = $db->fetch_array($result,'assoc')) {
		$list["topValue"] = ROUND($list["topValue"],2);
		if ($list["topValue"]>0)
		{echo "<tr class='row'><td width='180px'>($list[topValue]) 
		 <a href='user.php?u=$list[topUser]' title='$list[topUser]'>$list[topUser]</a>
		 </td></tr>";} else {echo "<tr class='row'><td width='180px'></td></tr>";}
		 }

		echo "</table>";
		
		echo "</td>
		      <td valign='top'><table>";  
			  
		$phraseMA = "SELECT name as topUser, sumAssists as topValue from (Select b.name as name, MAX(a.id) as id,
		SUM(assists) as sumAssists 
		FROM dotaplayers AS a 
		LEFT JOIN gameplayers AS b ON a.gameid = b.gameid and a.colour = b.colour 
		LEFT JOIN games as c on a.gameid = c.id 
		LEFT JOIN dotagames as d on d.gameid = c.id 
		LEFT JOIN bans on b.name = bans.name  
		WHERE $sqlYear = '$year' 
		AND $sqlMonth = '$month' $day_stats 
		AND bans.name is null 
		GROUP BY b.name having count(*) >= $minGamesPlayed) as subsel 
		ORDER BY topValue DESC, id ASC 
		LIMIT $monthly_stats";
		
		$result = $db->query($phraseMA);
		while ($list = $db->fetch_array($result,'assoc')) {
		$list["topValue"] = ROUND($list["topValue"]);
		   if ($list["topValue"]>0)
		   {echo "<tr class='row'><td width='180px'>($list[topValue]) 
		   <a href='user.php?u=$list[topUser]' title='$list[topUser]'>$list[topUser]</a>
		   </td></tr>";} else {echo "<tr class='row'><td width='180px'></td></tr>";}
		   }

		echo "</table>";
		
		echo "</td>
		      <td valign='top'><table>";  
			  
		$phraseMD = "SELECT name as topUser, sumDeaths as topValue from (Select b.name as name, MAX(a.id) as id,
		SUM(deaths) as sumDeaths 
		FROM dotaplayers AS a 
		LEFT JOIN gameplayers AS b ON a.gameid = b.gameid and a.colour = b.colour 
		LEFT JOIN games as c on a.gameid = c.id 
		LEFT JOIN dotagames as d on d.gameid = c.id  
		LEFT JOIN bans on b.name = bans.name  
		WHERE $sqlYear = '$year' 
		AND $sqlMonth = '$month' $day_stats 
		AND bans.name is null 
		GROUP BY b.name having count(*) >= $minGamesPlayed) as subsel 
		ORDER BY topValue DESC, id ASC 
		LIMIT $monthly_stats";
		
		$result = $db->query($phraseMD);
		while ($list = $db->fetch_array($result,'assoc')) {

		   if ($list["topValue"]>0)
		   {echo "<tr class='row'><td width='180px'>($list[topValue]) 
		   <a href='user.php?u=$list[topUser]' title='$list[topUser]'>$list[topUser]</a>
		   </td></tr>";} else {echo "<tr class='row'><td width='180px'></td></tr>";}
		   }

		echo "</table>";
		
		echo "</td>
		      <td valign='top'><table>";  
			  
		$phraseCK = "SELECT name as topUser, sumCreepKills as topValue from (Select b.name as name, MAX(a.id) as id,
		SUM(creepkills) as sumCreepKills 
		FROM dotaplayers AS a 
		LEFT JOIN gameplayers AS b ON a.gameid = b.gameid and a.colour = b.colour 
		LEFT JOIN games as c on a.gameid = c.id 
		LEFT JOIN dotagames as d on d.gameid = c.id 
		LEFT JOIN bans on b.name = bans.name  
		WHERE $sqlYear = '$year' 
		AND $sqlMonth = '$month' $day_stats 
		AND bans.name is null 
		GROUP BY b.name having count(*) >= $minGamesPlayed) as subsel 
		ORDER BY topValue DESC, id ASC 
		LIMIT $monthly_stats";
		
		$result = $db->query($phraseCK);
		while ($list = $db->fetch_array($result,'assoc')) {

		   if ($list["topValue"]>0)
		   {echo "<tr class='row'><td width='180px'>($list[topValue]) 
		   <a href='user.php?u=$list[topUser]' title='$list[topUser]'>$list[topUser]</a>
		   </td></tr>";} else {echo "<tr class='row'><td width='180px'></td></tr>";}
		   }

		echo "</table>";
		
		
		echo "</td>
		      <td valign='top'><table>";  
			  
		$phraseCK = "SELECT name as topUser, sumCreepDenies as topValue from (Select b.name as name, MAX(a.id) as id,
		SUM(creepdenies) as sumCreepDenies 
		FROM dotaplayers AS a 
		LEFT JOIN gameplayers AS b ON a.gameid = b.gameid and a.colour = b.colour 
		LEFT JOIN games as c on a.gameid = c.id 
		LEFT JOIN dotagames as d on d.gameid = c.id  
		LEFT JOIN bans on b.name = bans.name  
		WHERE $sqlYear = '$year' 
		AND $sqlMonth = '$month' $day_stats 
		AND bans.name is null 
		GROUP BY b.name having count(*) >= $minGamesPlayed) as subsel 
		ORDER BY topValue DESC, id ASC 
		LIMIT $monthly_stats";
		
		$result = $db->query($phraseCK);
		while ($list = $db->fetch_array($result,'assoc')) {

		   if ($list["topValue"]>0)
		   {echo "<tr class='row'><td width='180px'>($list[topValue]) 
		   <a href='user.php?u=$list[topUser]' title='$list[topUser]'>$list[topUser]</a>
		   </td></tr>";} else {echo "<tr class='row'><td width='180px'></td></tr>";}
		   }

		echo "</table> </td></tr></table>";
		}
		
		if ($monthRow5 == 1)
   {
		echo "<table class='tableA'> <tr>
     <th>$lang[avg_kills]</th>
	 <th>$lang[avg_assists]</th>
	 <th>$lang[avg_deaths]</th>
	 <th>$lang[avg_creeps]</th>
	 <th>$lang[avg_denies]</th>
	 </tr>
	 <td valign='top'>
	        <table>";
			
		$phraseAVGK = "SELECT name as topUser, sumKills*1.0/totGames*1.0 as topValue from (Select b.name as name, MAX(a.id) as id,
		COUNT(*) as totGames,
		SUM(kills) as sumKills 
		FROM dotaplayers AS a 
		LEFT JOIN gameplayers AS b ON a.gameid = b.gameid and a.colour = b.colour 
		LEFT JOIN games as c on a.gameid = c.id 
		LEFT JOIN dotagames as d on d.gameid = c.id 
		LEFT JOIN bans on b.name = bans.name  
		where winner <> 0 
		AND $sqlYear = '$year' 
		AND $sqlMonth = '$month' $day_stats 
		AND bans.name is null 
		GROUP BY b.name having count(*) >= $minGamesPlayed) as subsel 
		ORDER BY topValue DESC, id ASC 
		LIMIT $monthly_stats";
		
		$result = $db->query($phraseAVGK);
		while ($list = $db->fetch_array($result,'assoc')) {
		$list["topValue"] = ROUND($list["topValue"],2);
		if ($list["topValue"]>0)
		{echo "<tr class='row'><td width='180px'>($list[topValue]) 
		 <a href='user.php?u=$list[topUser]' title='$list[topUser]'>$list[topUser]</a>
		 </td></tr>";} else {echo "<tr class='row'><td width='180px'></td></tr>";}
		 }

		echo "</table>";
		
		echo "</td>
		      <td valign='top'><table>";  
			  
		$phraseAVGA = "SELECT name as topUser, sumAssists*1.0/totGames*1.0 as topValue from (Select b.name as name, MAX(a.id) as id,
		COUNT(*) as totGames,
		SUM(assists) as sumAssists 
		FROM dotaplayers AS a 
		LEFT JOIN gameplayers AS b ON a.gameid = b.gameid and a.colour = b.colour 
		LEFT JOIN games as c on a.gameid = c.id 
		LEFT JOIN dotagames as d on d.gameid = c.id 
		LEFT JOIN bans on b.name = bans.name  
		where winner <> 0 
		AND $sqlYear = '$year' 
		AND $sqlMonth = '$month' $day_stats 
		AND bans.name is null 
		GROUP BY b.name having count(*) >= $minGamesPlayed) as subsel 
		ORDER BY topValue DESC, id ASC 
		LIMIT $monthly_stats";
		
		$result = $db->query($phraseAVGA);
		while ($list = $db->fetch_array($result,'assoc')) {
        $list["topValue"] = ROUND($list["topValue"],2);
		   if ($list["topValue"]>0)
		   {echo "<tr class='row'><td width='180px'>($list[topValue]) 
		   <a href='user.php?u=$list[topUser]' title='$list[topUser]'>$list[topUser]</a>
		   </td></tr>";} else {echo "<tr class='row'><td width='180px'></td></tr>";}
		   }

		echo "</table>";
		
		echo "</td>
		      <td valign='top'><table>";  
			  
		$phraseAVGD = "SELECT name as topUser, sumDeaths*1.0/totGames*1.0 as topValue from (Select b.name as name, MAX(a.id) as id,
		COUNT(*) as totGames,
		SUM(deaths) as sumDeaths 
		FROM dotaplayers AS a 
		LEFT JOIN gameplayers AS b ON a.gameid = b.gameid and a.colour = b.colour 
		LEFT JOIN games as c on a.gameid = c.id 
		LEFT JOIN dotagames as d on d.gameid = c.id  
		LEFT JOIN bans on b.name = bans.name  
		where winner <> 0 
		AND $sqlYear = '$year' 
		AND $sqlMonth = '$month' $day_stats 
		AND bans.name is null 
		GROUP BY b.name having count(*) >= $minGamesPlayed) as subsel 
		ORDER BY topValue DESC, id ASC 
		LIMIT $monthly_stats";
		
		$result = $db->query($phraseAVGD);
		while ($list = $db->fetch_array($result,'assoc')) {
        $list["topValue"] = ROUND($list["topValue"],2);
		   if ($list["topValue"]>0)
		   {echo "<tr class='row'><td width='180px'>($list[topValue]) 
		   <a href='user.php?u=$list[topUser]' title='$list[topUser]'>$list[topUser]</a>
		   </td></tr>";} else {echo "<tr class='row'><td width='180px'></td></tr>";}
		   }

		echo "</table>";
		
		
		echo "</td>
		      <td valign='top'><table>";  
			  
		$phraseAVGCK = "SELECT name as topUser, sumCreepKills*1.0/totGames*1.0 as topValue from (Select b.name as name, MAX(a.id) as id,
		COUNT(*) as totGames,
		SUM(creepkills) as sumCreepKills 
		FROM dotaplayers AS a 
		LEFT JOIN gameplayers AS b ON a.gameid = b.gameid and a.colour = b.colour 
		LEFT JOIN games as c on a.gameid = c.id 
		LEFT JOIN dotagames as d on d.gameid = c.id 
		LEFT JOIN bans on b.name = bans.name  
		where winner <> 0 
		AND $sqlYear = '$year' 
		AND $sqlMonth = '$month' $day_stats 
		AND bans.name is null 
		GROUP BY b.name having count(*) >= $minGamesPlayed) as subsel 
		ORDER BY topValue DESC, id ASC 
		LIMIT $monthly_stats";
		
		$result = $db->query($phraseAVGCK);
		while ($list = $db->fetch_array($result,'assoc')) {
        $list["topValue"] = ROUND($list["topValue"],1);
		   if ($list["topValue"]>0)
		   {echo "<tr class='row'><td width='180px'>($list[topValue]) 
		   <a href='user.php?u=$list[topUser]' title='$list[topUser]'>$list[topUser]</a>
		   </td></tr>";} else {echo "<tr class='row'><td width='180px'></td></tr>";}
		   }

		echo "</table>";
		
		
		echo "</td>
		      <td valign='top'><table>";  
			  
		$phraseAVGCD = "SELECT name as topUser, sumCreepDenies*1.0/totGames*1.0 as topValue from (Select b.name as name, MAX(a.id) as id,
		COUNT(*) as totGames,
		SUM(creepdenies) as sumCreepDenies 
		FROM dotaplayers AS a 
		LEFT JOIN gameplayers AS b ON a.gameid = b.gameid and a.colour = b.colour 
		LEFT JOIN games as c on a.gameid = c.id 
		LEFT JOIN dotagames as d on d.gameid = c.id  
		LEFT JOIN bans on b.name = bans.name  
		where winner <> 0 
		AND $sqlYear = '$year' 
		AND $sqlMonth = '$month' $day_stats 
		AND bans.name is null 
		GROUP BY b.name having count(*) >= $minGamesPlayed) as subsel 
		ORDER BY topValue DESC, id ASC 
		LIMIT $monthly_stats";
		
		$result = $db->query($phraseAVGCD);
		while ($list = $db->fetch_array($result,'assoc')) {
        $list["topValue"] = ROUND($list["topValue"],2);
		   if ($list["topValue"]>0)
		   {echo "<tr class='row'><td width='180px'>($list[topValue]) 
		   <a href='user.php?u=$list[topUser]' title='$list[topUser]'>$list[topUser]</a>
		   </td></tr>";} else {echo "<tr class='row'><td width='180px'></td></tr>";}
		   }

		echo "</table>";
		

		
		
		
		/////////////////////////
   echo "</td></tr></table></div>";
			}
  ?>
