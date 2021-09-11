<?PHP
if (!isset($_SESSION)) {session_start();}
    ob_start();
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

$server = 'ghostpp_db';
$database = 'ghost';
$username = 'ghost';
$password = 'secret';

$default_style = 'dota';
$default_language = 'english';

$bans_per_page = '20';
$games_per_page = '20';
$heroes_per_page = '30';
$top_players_per_page = '30';
$news_per_page = '5';
$search_limit = '50';

//Display fastest and longest game time win on User page
$FastGameWon = '1';
//Show frequently used items for every hero (2 for each slot)
$ShowHeroMostUsedItems = '1';
//Show most used heroes for every item. (Note: this can take up much more resources. Care with huge database)
$ShowItemsMostUsedByHero = '1';
//Show Items button on Menu buttons

$enableItemsPage = '1';
$enableSafeListPage = '0'; //Only for GhostOne

//All slots are shown on Game page (Although they play 2v2, 3v3...)
//If this option is disabled then you can not see the exact slot of player. It will hide empty slots.
$ShowAllSlotsInGame = '0';

//Show total summary of all games (sentinel and scourge: wins, kills, creeps...)
$ShowSentinelScourgeWon = '1';

//Show Country Flags
$CountryFlags = '1';

//Show All Time Stats on Top page
$AllTimeStats = '1';
//All time stats. How many results to show for All time stats on Top page.
$top_stats = '5';

//Hide banned users on Top and Monthly page.
$HideBannedUsersOnTop = '1';

$displayUsersDisconnects = '1';

//Filter the results on the game page by years, months and days
$FiltersOnGamePage = '1';

//Replay Location:
$replayLocation = 'replays';
//You can use timeoffset to match replay date (from -12 to 13 hours)
//If replays working fine dont change this value (default 0).
$replayTimeOffset = '0';

//If Smart Parsing enabled replay will be saved into HTML output after first parsing.
//There is no need to parse same replay anymore.
//If you disable this option replays will parse always
$SmartParsing = '1';

//Max. page links before and after current page
$max_pagination_link = '5';

//Score Method 1. This method use score formula (see $scoreFormula below) to calucate users score.
//Score Method 2. This method use league system to calculate user score. Eg. wins*5 - losses*3 - disconnects*10
//On game page winner will receive 5 points, loosers will loose 3 points by default.
$ScoreMethod = '2';
//Here you can setup how many points user receive when he win, lose or disconnect.
$ScoreStart = '1000';
$ScoreWins = '5';
$ScoreLosses = '-3';
$ScoreDisc = '-10';


//Score Method 1
//(Only used if $DBScore = '0')
$scoreFormula = '((((kills-deaths+assists*0.5+towerkills*0.5+raxkills*0.2+(courierkills+creepdenies)*0.1+neutralkills*0.03+creepkills*0.03) * .2)+(wins-losses)))*10';

//Pre-Calculate score
//If enabled:  Player scores will be taken from the score table in your MySQL database. You must populate this table through your own methods.
$DBScore = '0';

//Minimal ratio (lefttime/duration) that a player/hero has to complete a game to be counted as win/loss. otherwise game is ignored.
$minPlayedRatio = '0.8';
$minGamesPlayed = '2';

$date_format = 'd.m.Y H:i';

//How many records to show for each month row
$monthly_stats = '5';

//Enable month rows. 1 - ENABLED, 0 - DISABLED

//Top Kills,Top Assists,Top Deaths,Top Creep Kills,Top Creep Denies
$monthRow1 = '1';
//Top Gold,Top Neutrals,Top Towers,Top Rax,Top Couriers Kills
$monthRow2 = '1';
//Best K/D,Best A/D Ratio,Most games,Best Win %,Top Stay %
$monthRow3 = '1';
//Most Kills,Assists,Deaths,Creeps,Denies
$monthRow4 = '1';
//AVG Kills,Assists,Deaths,Creeps,Denies
$monthRow5 = '1';

$DaysOnMonthlyStats = '1';
$TopRanksOnMonthly = '1';

//Show Points gained for each game for all users on game page
$UserPointsOnGamePage = '1';

//If this option above is enabled, points  will be calculated accurately (from database)
//It will calculate total score before and after selected game. (SCORE BEFORE)-(SCORE AFTER) = POINTS per game
//This will also take up much more resources (queries)
$AccuratePointsCalculation = '0';

$head_admin = 'TheBot';
$bot_name = 'Ghost bot';

$LEAVER = 'LEAVER';

// Achievements plugin
 //Enable/disable User Achievements
$UserAchievements = '1';
// Configuration

$KillsMedal = '500'; //Kill 500 enemy heroes!
$AssistMedal = '200'; //Assist in 200 kills. How many kills to achieve this medal (default 200).
$WinPercentMedal = '85'; //Achieve 85 % victory
$KillsPercentMedal = '60'; //Achieve 60 % of kills.
$GamesMedal = '50'; //Play 50 games!
$WinsMedal = '50'; //Win 50 games!
$CreepsMedal = '5000'; //Kill 5000 creeps!
$DeniesMedal = '500'; //Deny 500 creeps!
$TowersMedal = '50'; //Destroy 50 towers!
$CouriersMedal = '30'; //Kill 30 enemy couriers!
$NeutralsMedal = '500'; //Kill 500 neutrals!
$PlayDurationMedal = '30'; //Play at least 30 hours!

//CACHING//
//Enable page caching
$cachePages = '0';
//Caching directory (you MUST create this directory)
$cacheDir = 'cache';
//Cache time in minutes (default 30 min)
$cachetime = 60 * 30;
//Show Last cached time and Next update time in footer
$showUpdate = '1';

$pageGen = '1'; //Enable/disable info about page generation and total queries on every page
$_debug = '0'; //Enable error reportings

?>