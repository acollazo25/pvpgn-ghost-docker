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

  include('header.php');
  require_once('includes/get_user_stats.php');

  $pageTitle = "DotA OpenStats | $realname";

	//User row
	   $tags = array(
   '{ALL_TIME_STATS}', 
   '{HIGH_HERO_STATS}',
   '{L_KILLS}',
   '{L_ASSISTS}',
   '{L_DEATHS}',
   '{L_KD}',
   '{L_GAMES}',
   '{L_WL}', 
   '{L_SCORE}',
   '{L_WIN_PERC}',
   '{L_CREEP_K}',
   '{L_TOWERS}',
   '{L_CREEP_D}',
   '{L_RAX}',
   '{L_COURIERS}',
   '{L_DISC}',
   
   '{L_WINS}',
   '{L_LOSSES}',
   '{L_PLAYED}',
   
   '{KILLS}', 
   '{ASSISTS}',
   '{DEATHS}',
   '{KDRATIO}',
   '{TOTGAMES}',
   '{WINS}','{LOSSES}',
   '{TOTSCORE}',
   '{WL}',
   '{CREEPKILLS}',
   '{TOWERKILLS}',
   '{CREEPDENIES}',
   '{RAX}',
   '{COURIER}',
   '{DISC}',
   
   '{MKHERO}',
   '{MDHERO}',
   '{MAHERO}',
   '{MWHERO}',
   '{MLHERO}',
   '{MPHERO}',
   '{MKCOUNT}',
   '{MDCOUNT}',
   '{MACOUNT}',
   '{MWCOUNT}',  
   '{MLCOUNT}',   
   '{MPCOUNT}',
   '{L_CPM}','{CREEPS_PER_MIN}','{L_KPG}','{KILLS_PER_GAME}','{TITLE_CPM}','{TITLE_KPG}','{TITLE_KD}','{TITLE_WL}','{TITLE_WP}','{TITLE_DISC}','{TITLE_DPG}','{DPG}');
   
   if ($displayUsersDisconnects == 1)	{$l_disc = $lang["disc"];} else {$l_disc = ""; $disc = "";}
	
   $data = array(
   $lang["all_time_stats"], 
   $lang["high_hero_stats"], 
   $lang["kills"], 
   $lang["assists"],
   $lang["deaths"], 
   $lang["kd"],
   $lang["games"],
   $lang["w_l"],
   $lang["score"],
   $lang["win_perc"],
   $lang["creep_kills"],
   $lang["towers"],
   $lang["creep_denies"],
   $lang["rax"], 
   $lang["couriers"],
   $l_disc,
   
   $lang["wins"],
   $lang["losses"],
   $lang["favorite"],
   
   $kills,
   $assists,
   $death,
   $kdratio,
   $totgames,
   $wins, $losses,
   $totscore,
   $winloose,
   $creepkills,
   $towerkills,
   $creepdenies,
   $raxkills,
   $courierkills,
   $disc." ($DiscPercent%)",
   $mkhimg, $mdhimg, $mahimg, $mwhimg, $mlhimg, $mphimg,
   $mostkillscount,
   $mostdeathscount,
   $mostassistscount, 
   $mostwinscount, 
   $mostlossescount, 
   $mostplayedcount, 
   $lang["CPM"] ,$creepsPerMin , $lang["KPG"], $killsPerGame,$lang["creeps_per_min"],$lang["kills_per_game"],$lang["kd_ratio"],$lang["wins_losses"],$lang["win_percent"],$lang["disc_title"],'Deaths Per Game', $deathsPerGame
   );
   echo str_replace($tags, $data, file_get_contents("./style/$default_style/user_row.html"));
	
		//ACHIEVEMENTS MOD
		if ($UserAchievements == 1)
		{echo '<div align="center"><table class="tableA"><tr><th class="padLeft">
		<img style="vertical-align: middle;" alt="" width="16px" height="16px" src="./img/achievements/play.gif">
		<a href="#info" name="info" class="poplink" onclick="showhide(\'div1\');return false;" id="link">Show '.$realname .' Achievements</a>
		</th></tr></table></div>
		<div id="div1" style="display: none;">';

		require_once('./includes/medals.php');
		echo '</div>';
		}

		//ACHIEVEMENTS MOD
		
		//DURATIONS
		echo "<div align='center'><table class='tableA'>
		<th class='padLeft'>$lang[max_duration]</th> 
		<th>$lang[min_duration]</th>
		<th>$lang[avg_duration]</th> 
		<th>$lang[total_duration]</th> 
		</tr>
		<tr>
		<td width='25%' class='padLeft'><div align='left'>$maxDuration</div></td>
		<td width='25%'><div align='left'>$minDuration</div></td>
		<td width='25%'><div align='left'>$avgDuration</div></td>
		<td width='25%'><div align='left'>$totalDuration</div></td>
		</tr></table></div>";
	    //DURATIONS
		
		if ($FastGameWon == 1)
        {		
		//FASTEST AND LONGEST GAME WON
		echo "<div align='center'><table class='tableA'>
		<tr class='row'>
		<th class='padLeft'><b>$lang[fastest_game]</b></td> 
		<th><b>$lang[duration]:</b></th>
		<th><div align='center'><b>$lang[kills]</b></div></th>
		<th><div align='center'><b>$lang[deaths]</b></div></th>
		<th><div align='center'><b>$lang[assists]</b></div></th>
		<th><div align='center'><b>$lang[creeps]</b></div></th>
		<th><div align='center'><b>$lang[denies]</b></div></th>
		<th><div align='center'><b>$lang[neutrals]</b></div></th>
		<th></th>

		</tr>
		<tr class='row'>
		<td  width='180px'  class='padLeft'><div align='left'>
		<a href='game.php?gameid=$fastGameWonID'>$fastGameWonName</a></div></td>
		<td width='120px'><div align='left'>$fastGameWonTime</div></td>
		<td width='56px'><div align='center'>$fastGameWonKills</div></td>
		<td width='56px'><div align='center'>$fastGameWonDeaths</div></td>
		<td width='56px'><div align='center'>$fastGameWonAssists</div></td>
		<td width='56px'><div align='center'>$fastGameWonCreeps</div></td>
		<td width='56px'><div align='center'>$fastGameWonDenies</div></td>
		<td width='56px'><div align='center'>$fastGameWonNeutrals</div></td>
		<td></td>
		</tr>
		
		<tr>
		<th class='padLeft' width='180px'><b>$lang[longest_game]</b></th>
		<th><div align='left'><b>$lang[duration]:</b></th>
		<th><div align='center'><b>$lang[kills]</b></div></th>
		<th><div align='center'><b>$lang[deaths]</b></div></th>
		<th><div align='center'><b>$lang[assists]</b></div></th>
		<th><div align='center'><b>$lang[creeps]</b></div></th>
		<th><div align='center'><b>$lang[denies]</b></div></th>
		<th><div align='center'><b>$lang[neutrals]</b></div></th>
		<th></th>
		
		</tr>
		<tr class='row'>
		<td width='180px' class='padLeft' width='180px'><div align='left'>
		<a href='game.php?gameid=$longestGameWonID'>$longestGameWonName</a></div></td>
		<td width='120px'><div align='left'>$longestGameWonTime</div></td>
		<td width='56px'><div align='center'>$longestGameWonKills</div></td>
		<td width='56px'><div align='center'>$longestGameWonDeaths</div></td>
		<td width='56px'><div align='center'>$longestGameWonAssists</div></td>
		<td width='56px'><div align='center'>$longestGameWonCreeps</div></td>
		<td width='56px'><div align='center'>$longestGameWonDenies</div></td>
		<td width='56px'><div align='center'>$longestGameWonNeutrals</div></td>
		<td></td>
		
		</tr></table></div>";
		//FASTEST AND LONGEST GAME WON
		}
		
	    $sql = "SELECT COUNT(*) as count 
		FROM
		   (SELECT a.hero
            FROM dotaplayers AS a 
			LEFT JOIN gameplayers AS b ON b.gameid = a.gameid 
			AND a.colour = b.colour 
			LEFT JOIN dotagames AS c ON c.gameid = a.gameid 
            LEFT JOIN games AS d ON d.id = a.gameid 
			LEFT JOIN heroes as e ON a.hero = heroid 
			WHERE name= '$username' 
			AND description <> 'NULL') as t LIMIT 1";
 
    $result = $db->query($sql);
	//$db->close($result);
    $r = $db->fetch_row($result);
    $numrows = $r[0];
	$result_per_page = $games_per_page;
	$order = 'datetime';
	
	 if (isset($_GET['order']))
  {
  if ($_GET['order'] == 'game') {$order = ' LOWER(gamename) ';}
  if ($_GET['order'] == 'hero') {$order = ' description ';}
  if ($_GET['order'] == 'type') {$order = ' type ';}
  if ($_GET['order'] == 'date') {$order = ' datetime ';}
  if ($_GET['order'] == 'kills') {$order = ' kills ';}
  if ($_GET['order'] == 'deaths') {$order = ' deaths ';}
  if ($_GET['order'] == 'assists') {$order = ' assists ';}
  if ($_GET['order'] == 'ratio') {$order = ' kdratio ';}
  if ($_GET['order'] == 'creeps') {$order = ' creepkills ';}
  if ($_GET['order'] == 'denies') {$order = ' creepdenies ';}
  if ($_GET['order'] == 'neutral') {$order = ' neutralkills ';}
  if ($_GET['order'] == 'result') {$order = ' outcome ';}
  }
  
  $sort = 'desc';
  $sortdb = 'DESC';
       if (isset($_GET['sort']) AND $_GET['sort'] == 'asc')
       {$sort = 'desc'; $sortdb = 'ASC';} else {$sort = 'asc'; $sortdb = 'DESC';}
       echo "<table><tr><td><b>$lang[game_history]</b></td></tr></table>";
		
	include('pagination.php');
	
	$sql = getUserGameHistory($LEAVER,$username,$order,$sortdb,$offset, $rowsperpage,$minPlayedRatio);
 
    $result = $db->query($sql);
	
     echo "<div align='center'><table class='tableA'><tr>
	 <th style='padding-left:12px;width:200px;'>
<div align='left'><a href='{$_SERVER['PHP_SELF']}?u=$username&order=game&sort=$sort'>$lang[game_name]</a></div></th>
	 
    <th><div align='center'><a href='{$_SERVER['PHP_SELF']}?u=$username&order=type&sort=$sort'>$lang[type]</a></div></th>
	 <th><div align='center'>
	 <a href='{$_SERVER['PHP_SELF']}?u=$username&order=date&sort=$sort'>$lang[date]</a></div></div></th>

	 <th><div align='left'>
	 <a href='{$_SERVER['PHP_SELF']}?u=$username&order=hero&sort=$sort'>$lang[hero_played]</a></div></th>";
	 
	 echo "<th><div align='center'>
	 <a href='{$_SERVER['PHP_SELF']}?u=$username&order=kills&sort=$sort'>$lang[kills]</a></div></div></th>
	 
	 <th><div align='center'>
	 <a href='{$_SERVER['PHP_SELF']}?u=$username&order=deaths&sort=$sort'>$lang[deaths]</a></div></th>
	 
	 <th><div align='center'>
	 <a href='{$_SERVER['PHP_SELF']}?u=$username&order=assists&sort=$sort'>$lang[assists]</a></div></th>
	 
	 <th><div align='center'>
	 <a href='{$_SERVER['PHP_SELF']}?u=$username&order=ratio&sort=$sort'>$lang[kd]</a></div></th>
	 
	 <th><a href='{$_SERVER['PHP_SELF']}?u=$username&order=creeps&sort=$sort'>$lang[creeps]</a></th>
	 <th><a href='{$_SERVER['PHP_SELF']}?u=$username&order=denies&sort=$sort'>$lang[denies]</a></th>
	 <th><a href='{$_SERVER['PHP_SELF']}?u=$username&order=neutral&sort=$sort'>$lang[neutrals]</a></th>
	 <th><a href='{$_SERVER['PHP_SELF']}?u=$username&order=result&sort=$sort'>$lang[result]</a></th>
	 </tr><tr>";


     while ($list = $db->fetch_array($result,'assoc')) {
	$gametime=date($date_format,strtotime($list["datetime"]));
	$kills=$list["kills"];
	$death=$list["deaths"];
    $assists=$list["assists"];
	$gamename=trim($list["gamename"]);
	$hid=strtoupper($list["original"]);
	$hero=$list["description"];
	$name=trim($list["name"]);
	$colour=$list["newcolour"];
	$winner=$list["winner"];
	$gameid=$list["id"]; 
	$type=$list["type"];
	$outcome=$list["outcome"];
	
	$kdratio = round($list["kdratio"],1);
	if ($kdratio == "1000") $kdratio = "10";
	
	$creepkills=$list["creepkills"];
	$creepdenies=$list["creepdenies"];
	$neutralkills=$list["neutralkills"];
	
	if ($outcome == "WON") 
	{$outcome="<span style='color:#B30505'>$list[outcome]</span>";
	$gamename="<span style='color:#B30505'>$list[gamename]</span>";
	}
	
	if ($outcome == "LOST") 
	{$outcome="<span style='color:#0E9A00'>$list[outcome]</span>";
	$gamename="<span style='color:#0E9A00'>$list[gamename]</span>";}
	
	if ($outcome == "DRAW") 
	{$outcome="<span style='color:#4368BB'>$list[outcome]</span>";
	$gamename="<span style='color:#4368BB'>$list[gamename]</span>";}
	
	if ($outcome == "$LEAVER") 
	{$outcome="<span style='color:#7E7E7E'>$list[outcome]</span>";
	$gamename="<span style='color:#7E7E7E'>$list[gamename]</span>";}
	
	$hero_img = "<a href='hero.php?hero=$hid'><img border=0 alt='' title='$hero' width='28px' style='vertical-align: middle;' src='./img/heroes/$hid.gif'/></a>"; 
	  
	  echo "<tr class='row'>
	  <td style='padding-left:12px;width:200px;'>
	  <div align='left'><a href='game.php?gameid=$gameid'>$gamename</a></div></td>
	  
	  <td style='width:30px;'>$type</td>
	  <td style='width:150px;'><div align='center'>$gametime</div></td>
	  
	  <td style='width:180px;height:32px;'>
	  
	  <div align='left'>$hero_img $hero</div>
	  </td>
	  
	  <td align='center' style='width:40px;'>$kills</td>
	  <td align='center' style='width:40px;'>$death</td>
	  <td align='center' style='width:60px;'>$assists</td>
	  <td align='center' style='width:60px;'>$kdratio:1</td>
	  <td align='center' style='width:60px;'>$creepkills</td>
	  <td align='center'style='width:60px;'>$creepdenies</td>
	  <td align='center' style='width:60px;'>$neutralkills</td>
	  <td align='center' style='width:60px;'><div align='left'>$outcome</div></td>
	  </tr>";}
	  
    echo "</table></div><br/>";
	
	include('pagination.php');
	echo "<br/>";
	
	include('footer.php');
  $pageContents = ob_get_contents();
  ob_end_clean();
  echo str_replace('<!--TITLE-->', $pageTitle, $pageContents);
  //Cache this page
  if ($cachePages == '1')
  file_put_contents($CacheTopPage, str_replace("<!--TITLE-->",$pageTitle,$pageContents));
  ?>