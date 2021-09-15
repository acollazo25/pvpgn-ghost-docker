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
*    along with DOTA OPEN STATS.  If not, see <http://www.gnu.org/licenses/>
*
-->
**********************************************/


     if (!$_GET) {
      //someone is calling the file directly, which we don't want
      echo 'This file cannot be called directly.'; die;
	  }
	  
	 require_once ('../config.php');
	 require_once("../lang/$default_language.php");
	 require_once('../includes/class.database.php');
	 require_once('../includes/common.php');
	 require_once('../includes/db_connect.php');
	 
	 
	 if (strlen(trim(strip_tags($_GET['searchterm']))) <= 2)
	 
	  {echo "<div align='center'<span style='color:red'>$lang[err_search] </span></div><br/>";die;}

	  $searchTerm = trim($_GET['searchterm']);
	  $searchTerm = strip_tags($searchTerm); // remove any html/javascript.
	  $searchTerm = EscapeStr($searchTerm); 
	  
	  $bans_only = "	  AND b.name = e.name"; //Maybe for later usage
	  $sql_reson = " , e.reason";
	  
	  $sql = "
	  SELECT 
	  COUNT(a.id) as totgames
	  , AVG(kills) as kills
	  , AVG(deaths) as deaths
	  , AVG(assists) as assists
	  , AVG(creepkills) as creepkills
	  , AVG(creepdenies) as creepdenies
	  , AVG(neutralkills) as neutralkills
	  , AVG(towerkills) as towerkills
	  , MAX(datetime) as lastplayed
	  , MIN(datetime) as firstplayed
	  , b.name as name
	  , b.ip as ip
	  , e.name as banname 
	  , e.reason 
      FROM dotaplayers AS a 
	  LEFT JOIN gameplayers AS b ON b.gameid = a.gameid and a.colour = b.colour 
      LEFT JOIN dotagames AS c ON c.gameid = a.gameid 
	  LEFT JOIN games as d ON d.id = c.gameid 
      LEFT JOIN bans AS e on b.name = e.name
      WHERE b.name <> '' and winner <> 0 
	  AND LOWER(b.name) LIKE LOWER('%{$searchTerm}%') 
	  GROUP BY b.name
	  ORDER BY COUNT(a.id) DESC, b.name ASC 
	  LIMIT $search_limit";
	  
	  $qry_result = $db->query($sql) or die(mysql_error());

	  //Build Result String
	echo "<table><tr><td style='padding-left:8px;' height='20px'>$lang[search_term_used] <b>$searchTerm</b></td></tr>";
    $total_matches = $db->num_rows($qry_result);
	
	 if ($total_matches < 1) 
	 {echo "<tr><td><div align='center'<span style='color:red'>$lang[no_maches] <b>{$searchTerm}</b></span><br/></div></td></tr>";}
	
	 echo "<tr><td style='padding-left:8px;' height='20px'>$lang[search_found] ".$total_matches." $lang[search_users]</td></tr></table>";
	 
	 echo "<div align='center'><table style='width:95%;margin:8px;'> 
        <tr>
        <th style='padding-left:4px;'><div align='left'>$lang[name]</div></th>
		<th><div align='center'>$lang[games]</div></th>
		<th><div align='center'>$lang[average]</div></th>
		<th><div align='center'>$lang[kills]</div></th>
		<th><div align='center'>$lang[deaths]</div></th>
		<th><div align='center'>$lang[assists]</div></th>
		<th><div align='center'>$lang[creeps]</div></th>
		<th><div align='center'>$lang[denies]</div></th>
		<th><div align='center'>$lang[first_game]</div></th>
		<th><div align='center'>$lang[last_game]</div></th>
		<th></th>
		</tr>";
	 
	 
	 while ($list = $db->fetch_array($qry_result,'assoc')) {
	 	$totgames=$list["totgames"];
		$kills=ROUND($list["kills"],2);
		$death=ROUND($list["deaths"],2);
		$assists=ROUND($list["assists"],2);
		$creepkills=ROUND($list["creepkills"],2);
		$creepdenies=ROUND($list["creepdenies"],2);
		$neutralkills=ROUND($list["neutralkills"],2);
		$towerkills=ROUND($list["towerkills"],2);
		$firstplayed=date($date_format,strtotime($list["firstplayed"]));
		$lastplayed=date($date_format,strtotime($list["lastplayed"]));
		$name=$list["name"];
		$name2=trim(strtolower($list["name"]));
        $banname=$list["banname"];
		$ntitle = "title='$name'";
		
		$myFlag = "";
		$IPaddress = $list["ip"];
		//COUNTRY FLAGS
		if ($CountryFlags == 1 AND file_exists("../includes/ip_files/countries.php") AND $IPaddress!="")
		{
		$two_letter_country_code=iptocountry($IPaddress);
		include("../includes/ip_files/countries.php");
		$three_letter_country_code=$countries[$two_letter_country_code][0];
        $country_name=convEnt2($countries[$two_letter_country_code][1]);
		$file_to_check="./flags/$two_letter_country_code.gif";
		if (file_exists($file_to_check)){
		        $flagIMG = "<img src=includes/$file_to_check>";
                $flag = "<img onMouseout='hidetooltip()' onMouseover='tooltip(\"".$flagIMG." $country_name\",100); return false' src='includes/$file_to_check' width='20' height='13'>";
                }else{
                $flag =  "<img title='$country_name' src='includes/flags/noflag.gif' width='20' height='13'>";
                }	
		$myFlag = $flag;
		}
		
		
		if (trim(strtolower($banname)) == $name2) 
		{$reason = " <br>$lang[reason]: ".trim($list["reason"]);
		$name = "<span style='color:#BD0000'>$list[name]</span>";
		$ntitle = "title='Banned'"; } else $reason = "";
		
	echo "<tr class='row'>
	<td style='padding-left:4px; width='150px'>
	<div align='left'>$myFlag <a $ntitle href='user.php?u=$name2'>$name</a> $reason</div></td>
	
	<td width='64px'><div align='center'>$totgames</div></td>
	<td width='32px'><div align='center'></div></td>
	<td width='64px'><div align='center'>$kills</div></td>
	<td width='64px'><div align='center'>$death</div></td>
	<td width='64px'><div align='center'>$assists</div></td>
	<td width='64px'><div align='center'>$creepkills</div></td>
	<td width='64px'><div align='center'>$creepdenies</div></td>
	<td width='160px'><div align='center'>$firstplayed</div></td>
	<td width='160px'><div align='center'>$lastplayed</div></td>
	<td width='16px'></td>
	</tr>";
	 }
	 echo "</table></div>";
	  
	  ?>