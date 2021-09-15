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
	
	$heroid=safeEscape($_GET["hero"]);
	
	$sql = getHero($heroid);
	$sql2 = getHeroInfo($heroid, $minPlayedRatio, $minPlayedRatio);
	
	$result = $db->query($sql);
	$result2 = $db->query($sql2);
	
	$list = $db->fetch_array($result,'assoc');
	$row = $db->fetch_array($result2,'assoc');
		
		$totgames=number_format($row["totgames"],"0",".",",");
		$wins=number_format($row["wins"],"0",".",",");
		$losses=number_format($row["losses"],"0",".",",");
		$winratio=ROUND($row["winratio"],2);
		$kills=number_format(ROUND($row["kills"],2),"0",".",",");
		$deaths=ROUND($row["deaths"],2);
		$assists=number_format(ROUND($row["assists"],2),"0",".",",");
		$kdratio=ROUND($row["kdratio"],2);
		$creepkills=number_format(ROUND($row["creepkills"],2),"0",".",",");
		$creepdenies=number_format(ROUND($row["creepdenies"],2),"0",".",",");
		$neutralkills=number_format(ROUND($row["neutralkills"],2),"0",".",",");
		$towerkills=number_format(ROUND($row["towerkills"],2),"0",".",",");
		$raxkills=number_format(ROUND($row["raxkills"],2),"0",".",",");
		$courierkills=number_format(ROUND($row["courierkills"],2),"0",".",",");
		$summary=$row["summary"];
		$stats=$row["stats"];
		$skills=$row["skills"];
	
	    $hero=convEnt($list["description"]);
		$hid=convEnt(strtoupper($list["original"]));
		$summ=convEnt($list["summary"]);
		$stats=convEnt($list["stats"]);
		$skills=convEnt($list["skills"]);
		
			
	$pageTitle = "$lang[site_name] | $hero";

		$summ = str_replace("’","&rsquo;",$summ );
		$summ = str_replace("…","&hellip;",$summ );
		$skills = str_replace("’","&rsquo;",$skills );
		$skills = str_replace("ç","&ccedil;",$skills );
		
		$stats = str_replace("Strength","<img style='vertical-align: middle;' alt='' src='./img/strength.gif' border=0 />Strength",$stats );
		
		$stats = str_replace("Agility","<img style='vertical-align: middle;' alt='' src='./img/agility.gif' border=0 />Agility",$stats );
		
		$stats = str_replace("Intelligence","<img style='vertical-align: middle;' alt='' src='./img/intelligence.gif' border=0 />Intelligence",$stats );
		
		$summ = str_replace("’","&rsquo;",$summ );
		$skills = str_replace("’","&rsquo;",$skills );
		
		$data = array($hid,$hero,$lang["info"],$summ,$stats,$skills,$lang["wins"],$wins,$lang["games"],$totgames,$lang["losses"],$losses,$lang["w_l"],$winratio,$lang["kills"],$kills,$lang["assists"],$assists,$lang["deaths"],$deaths,$lang["kd"],$kdratio,$lang["creeps"],$creepkills,$lang["neutrals"],$neutralkills,$lang["denies"],$creepdenies,$lang["towers"],$towerkills,$lang["rax"],$raxkills,$lang["couriers"],$courierkills);
		
		$tags = array('{HEROID}','{HERO}', '{L_INFO}','{SUMMARY}','{STATS}','{SKILLS}','{L_WINS}','{WINS}','{L_GAMES}','{TOTGAMES}','{L_LOSSES}','{LOSSES}','{L_WLRATIO}','{WLRATIO}','{L_KILLS}','{KILLS}','{L_ASSISTS}','{ASSISTS}','{L_DEATHS}','{DEATHS}','{L_KD}','{KD}','{L_CREEPS}','{CREEPS}','{L_NEUTRALS}','{NEUTRALS}','{L_DENIES}','{DENIES}','{L_TOWERS}','{TOWERS}','{L_RAX}','{RAX}','{L_COURIERS}','{COURIERS}');
		
	 echo str_replace($tags, $data, file_get_contents("./style/$default_style/hero.html"));
	  
     // HERO GAME HISTORY
	 $sql = getHeroHistoryCount($heroid);
	 
	 $result = $db->query($sql);
	 
	 $r = $db->fetch_row($result);
	 $numrows = $r[0];

	 $result_per_page = $games_per_page;
	 $order = 'kdratio';
  if (isset($_GET['order']))
  {
  if ($_GET['order'] == 'player') {$order = ' name ';}
  if ($_GET['order'] == 'game') {$order = ' gamename ';}
  if ($_GET['order'] == 'type') {$order = ' type ';}
  if ($_GET['order'] == 'result') {$order = ' result ';}
  if ($_GET['order'] == 'kills') {$order = ' kills ';}
  if ($_GET['order'] == 'deaths') {$order = ' deaths ';}
  if ($_GET['order'] == 'assists') {$order = ' assists ';}
  if ($_GET['order'] == 'kd') {$order = ' kdratio ';}
  if ($_GET['order'] == 'creeps') {$order = ' creepkills ';}
  if ($_GET['order'] == 'denies') {$order = ' creepdenies ';}
  if ($_GET['order'] == 'neutrals') {$order = ' neutralkills ';}
  }
  
  $sort = 'DESC';
  if (isset($_GET['sort']) AND $_GET['sort'] == 'desc')
  {$sort = 'asc'; $sortdb = 'DESC';} else {$sort = 'desc'; $sortdb = 'ASC';}
	 
    echo "<br>";
	
	if ($ShowHeroMostUsedItems==1) {
	$size = "48px";
	require_once("./includes/get_hero_items.php");
	}
	 
	 echo "<div align='center'><table class='tableA'><tr><th>
	 <div align='center'>$lang[hero_player_history] $hero</div>
	 </th></tr></table></div>";
	  include('pagination.php');
	 $sql = getHeroHistory($minPlayedRatio,$heroid,$order,$sortdb,$offset, $rowsperpage,$LEAVER);
 
    $result = $db->query($sql);

 echo "<div align='center'><table class='tableA'> 
  <tr>
  <th style='padding-left:4px;'><div align='left'><a name='history' href='{$_SERVER['PHP_SELF']}?hero=$heroid&order=player&sort=$sort#history'>$lang[player]</a></div></th>
  <th><div align='left'><a name='history' href='{$_SERVER['PHP_SELF']}?hero=$heroid&order=game&sort=$sort#history'>$lang[game]</a></div></th>
  <th><div align='left'><a name='history' href='{$_SERVER['PHP_SELF']}?hero=$heroid&order=type&sort=$sort#history'>$lang[type]</a></div></th>
  <th><div align='left'><a name='history' href='{$_SERVER['PHP_SELF']}?hero=$heroid&order=result&sort=$sort#history'>$lang[result]</a></div></th>
  <th><div align='center'><a name='history' href='{$_SERVER['PHP_SELF']}?hero=$heroid&order=kills&sort=$sort#history'>$lang[kills]</a></div></th>
  <th><div align='center'><a name='history' href='{$_SERVER['PHP_SELF']}?hero=$heroid&order=deaths&sort=$sort#history'>$lang[deaths]</a></div></th>
  <th><div align='center'><a name='history' href='{$_SERVER['PHP_SELF']}?hero=$heroid&order=assists&sort=$sort#history'>$lang[assists]</a></div></th>
  <th><div align='center'><a name='history' href='{$_SERVER['PHP_SELF']}?hero=$heroid&order=kd&sort=$sort#history'>$lang[kd]</a></div></th>
  <th><div align='center'><a name='history' href='{$_SERVER['PHP_SELF']}?hero=$heroid&order=creeps&sort=$sort#history'>$lang[creeps]</a></div></th>
  <th><div align='center'><a name='history' href='{$_SERVER['PHP_SELF']}?hero=$heroid&order=denies&sort=$sort#history'>$lang[denies]</a></div></th>
  <th><div align='center'><a name='history' href='{$_SERVER['PHP_SELF']}?hero=$heroid&order=neutrals&sort=$sort#history'>$lang[neutrals]</a></div></th>
  </tr>";
 
  while ($list = $db->fetch_array($result,'assoc')) {
    $gameid = $list["gameid"];
	$kills=$list["kills"];
	$death=$list["deaths"];
    $assists=$list["assists"];
	$kdratio=ROUND($list["kdratio"],2);
	$gamename=trim($list["gamename"]);
    $banname=$list["banname"];
	
	$name=$list["name"];
	$name2=trim(strtolower($list["name"]));
	
	$IPaddress = $list["ip"];
	if (trim(strtolower($banname)) == strtolower($name)) 
	{$name = "<span style='color:#BD0000'>$list[name]</span>";}
	$myFlag = "";
	if (strlen($gamename)>=30) {$gamename = "".substr($gamename,0,30)."...";}
	
	//COUNTRY FLAGS
		if ($CountryFlags == 1 AND file_exists("./includes/ip_files/countries.php") AND $IPaddress!="")
		{
		$two_letter_country_code=iptocountry($IPaddress);
		include("./includes/ip_files/countries.php");
		$three_letter_country_code=$countries[$two_letter_country_code][0];
        $country_name=convEnt2($countries[$two_letter_country_code][1]);
		$file_to_check="./includes/flags/$two_letter_country_code.gif";
		if (file_exists($file_to_check)){
		        $flagIMG = "<img src=$file_to_check>";
                $flag = "<img onMouseout='hidetooltip()' onMouseover='tooltip(\"".$flagIMG." $country_name\",100); return false' src='$file_to_check' width='20' height='13'>";
                }else{
                $flag =  "<img title='$country_name' src='./includes/flags/noflag.gif' width='20' height='13'>";
                }	
		$myFlag = $flag;
		}
	
	$win = $list["winner"];
	$winner=$list["result"];
	$type=$list["type"];
	$creepkills=$list["creepkills"];
	$creepdenies=$list["creepdenies"];
	$neutralkills=$list["neutralkills"];
	$towerkills=$list["towerkills"];
	$raxkills=$list["raxkills"];
	
	//if ($win == 1) {$gamename = "<span class='GamesSentinel'>$gamename</span>";}
	//if ($win == 2) {$gamename = "<span class='GamesScourge'>$gamename</span>";}
	
	echo "<tr class='row'>
	<td style='padding-left:4px;width:160px;' >$myFlag <a href='user.php?u=$name2'>$name</a></td>
	<td width='220px'><a title='$list[gamename]' href='game.php?gameid=$gameid'>$gamename</a></td>
	<td width='56px'>$type</td>
	<td width='64px'>$winner</td>
	<td width='56px'><div align='center'>$kills</div></td>
	<td width='56px'><div align='center'>$death</div></td>
	<td width='56px'><div align='center'>$assists</div></td>
	<td width='56px'><div align='center'>$kdratio</div></td>
	<td width='56px'><div align='center'>$creepkills</div></td>
	<td width='56px'><div align='center'>$creepdenies</div></td>
	<td width='56px'><div align='center'>$neutralkills</div></td>
	
	</tr>";
  }
		
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