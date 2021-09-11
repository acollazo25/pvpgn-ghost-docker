<?PHP
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
     require_once ('../config.php');
	 require_once("../lang/$default_language.php");
	 require_once('../includes/class.database.php');
	 require_once('../includes/common.php');
	 require_once('../includes/db_connect.php');
	 
	 if($_GET)
	 {
	 
   $heroid = safeEscape($_GET['searchterm']);

   $sql = getHero($heroid);
	
	$sql2 = getHeroInfo($heroid, $minPlayedRatio, $minPlayedRatio);
	
	$result = $db->query($sql);
	if ($db->num_rows($result)<=0) {echo "";die;}
	
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
		
		$summ = str_replace("’","&rsquo;",$summ );
		$summ = str_replace("…","&hellip;",$summ );
		$skills = str_replace("’","&rsquo;",$skills );
		$skills = str_replace("ç","&ccedil;",$skills );
		
		$stats = str_replace("Strength","<img style='vertical-align: middle;' alt='' src='./img/strength.gif' border=0 />Strength",$stats );
		
		$stats = str_replace("Agility","<img style='vertical-align: middle;' alt='' src='./img/agility.gif' border=0 />Agility",$stats );
		
		$stats = str_replace("Intelligence","<img style='vertical-align: middle;' alt='' src='./img/intelligence.gif' border=0 />Intelligence",$stats );
		
		$summ = str_replace("’","&rsquo;",$summ );
		$skills = str_replace("’","&rsquo;",$skills );
		
		//echo "<TABLE><TR><TD style='height:24px;'>  <B>$hero</B> $lang[info]</TD></TR></TABLE><br/>";
		
		
		$data = array($hid,$hero,$lang["info"],$summ,$stats,$skills,$lang["wins"],$wins,$lang["games"],$totgames,$lang["losses"],$losses,$lang["w_l"],$winratio,$lang["kills"],$kills,$lang["assists"],$assists,$lang["deaths"],$deaths,$lang["kd"],$kdratio,$lang["creeps"],$creepkills,$lang["neutrals"],$neutralkills,$lang["denies"],$creepdenies,$lang["towers"],$towerkills,$lang["rax"],$raxkills,$lang["couriers"],$courierkills);
		
		$tags = array('{HEROID}','{HERO}', '{L_INFO}','{SUMMARY}','{STATS}','{SKILLS}','{L_WINS}','{WINS}','{L_GAMES}','{TOTGAMES}','{L_LOSSES}','{LOSSES}','{L_WLRATIO}','{WLRATIO}','{L_KILLS}','{KILLS}','{L_ASSISTS}','{ASSISTS}','{L_DEATHS}','{DEATHS}','{L_KD}','{KD}','{L_CREEPS}','{CREEPS}','{L_NEUTRALS}','{NEUTRALS}','{L_DENIES}','{DENIES}','{L_TOWERS}','{TOWERS}','{L_RAX}','{RAX}','{L_COURIERS}','{COURIERS}');
		
	 echo str_replace($tags, $data, file_get_contents("../style/$default_style/hero.html"));
    }
	if ($ShowHeroMostUsedItems==1) {
	$size = "48px";
	//echo "<div align='center'><table class='tableA'><tr><td align='top'>";
	require_once("get_hero_items.php");
	//echo "</tr></table></div>";
	}
	
	
	
	
	else {echo ""; die;}
	
	?>
	