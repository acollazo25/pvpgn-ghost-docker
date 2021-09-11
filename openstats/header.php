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
  $time = microtime();
  $time = explode(' ', $time);
  $time = $time[1] + $time[0];
  $start = $time;

   require_once('config.php');
   require_once('./lang/'.$default_language.'.php');

   //Caching START
   if ($cachePages == '1')
   {
  $break = explode('/',$_SERVER["SCRIPT_NAME"]);
  $cPage = $break[count($break) - 1];
  $cPage = str_replace(".php","",$cPage);

  $CacheTopPage = "./$cacheDir/$cPage"."_".$_SERVER['QUERY_STRING'].".html";
  $CacheTopPage = str_replace("=","_",$CacheTopPage);
  $CacheTopPage = str_replace("&","_",$CacheTopPage);

  //cache TOP
  if (file_exists($CacheTopPage) AND $_SERVER['REQUEST_METHOD'] == 'POST')
  			{
			   if (is_numeric($_POST['gp'])){
			   $gplay = trim(strip_tags($_POST['gp']));
			   $games = $gplay;
			   $CacheTopPage = str_replace("gp_$minGamesPlayed","gp_$gplay",$CacheTopPage);
			   }
			}
   //cache MONTHLY
    if ($_SERVER['REQUEST_METHOD'] == 'POST' AND isset($_POST["months"]) AND isset($_POST["years"])) {
	$dd = strip_tags($_POST["days"]);
	$mm = strip_tags(mysql_real_escape_string($_POST["months"]));
	$yyyy = strip_tags(mysql_real_escape_string($_POST["years"]));
    $CacheTopPage = "./$cacheDir/$cPage-$dd-$mm-$yyyy.html";
    }

  if (file_exists($CacheTopPage) AND time() - $cachetime < filemtime($CacheTopPage))
  {
   //echo $CacheTopPage;
  $created = filemtime($CacheTopPage);
  $nextUpdate = $cachetime + $created;
  $cached = file_get_contents($CacheTopPage);
  echo $cached;
       if ($showUpdate == '1')
           {
	      echo "<table><tr>
          <td align='center'>".$lang["last_update"]." ".date($date_format, filemtime($CacheTopPage))."</td>
          <td align='center'>".$lang["next_update"]." ".date($date_format, $nextUpdate)."</td></tr></table><br>";
		  exit;
		  }
	   }
	}
   //Caching END
   require_once('./includes/class.database.php');
   require_once('./includes/db_connect.php');
   require_once('./includes/common.php');
   require_once('./includes/get_style.php');

   if ($enableSafeListPage == 1)
   {$safeListLink = "<a class='menuButtons' href='safelist.php'>$lang[safelist]</a>";} else $safeListLink = "";

   if ($enableItemsPage == 1) {$itemButton = "<a class='menuButtons' href='items.php'>$lang[items]</a>";} else {$itemButton = "";}
   //HEADER
   $data = array($default_style,"<img alt='' style='vertical-align: middle;' src='style/$default_style/img/logo.png'/>",
   $lang["site_name"],
   $lang["bans"],
   $lang["top_players"],
   $lang["monthly_top"],
   $lang["home"],
   $lang["search"],
   "dota,stats,open stats,statistics,hero,users",
   "Dota Open Stats",
   $lang["dota_games"],
   $lang["admins"],
   $minGamesPlayed,
   $lang["heroes"],
   $itemButton,
   $safeListLink);

   $tags = array('{STYLE}',
   '{LOGO}',
   '{NAME}',
   '{BANS}',
   '{TOP}',
   '{MONTHLY}',
   '{HOME}',
   '{SEARCH}',
   '{METAKEY}',
   '{METADESC}',
   '{GAMES}',
   '{ADMINS}',
   '{MINGAMES}',
   '{HEROES}',
   '{ITEMS}',
   '(SAFELIST)');

   echo str_replace($tags, $data, file_get_contents("./style/$default_style/header.html"));

?>