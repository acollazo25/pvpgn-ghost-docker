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
   
   if (isset($_GET["gameid"]) AND is_numeric($_GET["gameid"]))
   {
   $gid=safeEscape($_GET["gameid"]);

  
  $sql = "
  SELECT winner, creatorname, duration, datetime, gamename 
  FROM dotagames AS dg 
  LEFT JOIN games AS d ON d.id = dg.gameid 
  WHERE dg.gameid='$gid'";
  $result = $db->query($sql);
  $replayDate = "";
  
   $list = $db->fetch_array($result,'assoc');
   		$creatorname=$list["creatorname"];
		$duration=secondsToTime($list["duration"]);
		$gametime=date($date_format,strtotime($list["datetime"]));
		$gmtime = $list["datetime"];
		$replayDate = $list["datetime"];
		$gamename=$list["gamename"];
		$win=$list["winner"];
		
   $replayDate =  strtotime($replayDate)+($replayTimeOffset*3600)+(0*60);  //3*3600 = +3 HOURS,   +0 minutes.
   $replayDate = date("Y-m-d H:i",$replayDate);
   //echo date("Y-m-d H:i",strtotime($replayDate));
   
   $gametimenew = substr(str_ireplace(":","-",date("Y-m-d H:i",strtotime($replayDate))),0,16);
   require_once('./includes/get_replay.php');

  $pageTitle = "$lang[site_name] | $gamename";
  //echo $replayloc;
  
	if(file_exists($replayloc)){$DownloadReplay = "| <a href='".$replayurl."'>$lang[download_rep]</a> <!--<input type='button' class='inputButton' value='$lang[download_rep]' onclick='location.href=\"$replayurl\"'--> | <a href='#info'>Game Log</a>";}
	else {$DownloadReplay = "";}

  echo "<table><tr>
        <th><div class='padLeft' align='left'>$gamename $DownloadReplay</div></th><th></th><th></th><th></th>  </tr>
        <tr>
        <td>$lang[game]: <b>$gamename</b></td>
		<td>$lang[date]: <b>$gametime</b></td>
		<td>$lang[creator]: <b>$creatorname</b></td>
		<td>$lang[duration]: <b>$duration</b></td>
        </tr>
        </table>";
		
		echo "<table><tr>
		            <th class='padLeft' width='150px'><div align='center'>$lang[player]</div></th>
					<th  width='40px'><div align='center'>$lang[hero]</div></th>
					<th  width='50px'><div align='center'>$lang[kills]</div></th>
					<th  width='50px'><div align='center'>$lang[deaths]</div></td>
					<th  width='60px'><div align='center'>$lang[assists]</div></th>
					<th  width='60px'><div align='center'>$lang[creeps]</div></th>
					<th  width='60px'><div align='center'>$lang[denies]</div></th>
					<th  width='60px'><div align='center'>$lang[neutrals]</div></th>
					<th  width='60px'><div align='center'>$lang[towers]</div></th> 
					<th  width='60px'><div align='center'>$lang[gold]</div></th>
					<th  width='220x'><div align='center'>$lang[items]</div></th>
					<th  width='60px'><div align='left'>$lang[left_at]</div></th>
					<th  width='100px'>$lang[reason]</th>	
					                            </tr>";
	   
       $sql = getGameInfo($gid);
	   $result = $db->query($sql);
	   
	   $scourge = 1;
	   $sentinel = 1;
	   $sent_kills = 0;
	   $scour_kills = 0;
	   $my_score = 0;
	   $best_score = 0;
	   $best_player = "";	
	   while ($list = $db->fetch_array($result,'assoc')) {
	    $kills=$list["kills"];
		$deaths=$list["deaths"];
		$assists=$list["assists"];
		$creepkills=$list["creepkills"];
		$creepdenies=$list["creepdenies"];
		$neutralkills=$list["neutralkills"];
		$towerkills=$list["towerkills"];
		$raxkills=$list["raxkills"];
		$courierkills=$list["courierkills"];

		$gold=$list["gold"];
		$item1=$list["item1"];
		$item2=$list["item2"];
		$item3=$list["item3"];
		$item4=$list["item4"];
		$item5=$list["item5"];
		$item6=$list["item6"];
		
		$itemicon1=$list["itemicon1"];
		$itemicon2=$list["itemicon2"];
		$itemicon3=$list["itemicon3"];
		$itemicon4=$list["itemicon4"];
		$itemicon5=$list["itemicon5"];
		$itemicon6=$list["itemicon6"];
		
		$itemID1=$list["item1"];
		$itemID2=$list["item2"];
		$itemID3=$list["item3"];
		$itemID4=$list["item4"];
		$itemID5=$list["item5"];
		$itemID6=$list["item6"];
		
		if ($itemicon1=="") {$itemicon1 = "empty.gif";}
		if ($itemicon2=="") {$itemicon2 = "empty.gif";}
		if ($itemicon3=="") {$itemicon3 = "empty.gif";}
		if ($itemicon4=="") {$itemicon4 = "empty.gif";}
		if ($itemicon5=="") {$itemicon5 = "empty.gif";}
		if ($itemicon6=="") {$itemicon6 = "empty.gif";}
		
		if ($itemicon1!="" AND !file_exists("./img/items/$itemicon1")) 
		{$itemicon1 = "missing.gif"; $list["itemname1"].= ": $list[itemicon1]";}
		if ($itemicon2!="" AND !file_exists("./img/items/$itemicon2")) 
		{$itemicon2 = "missing.gif"; $list["itemname2"].= " :$list[itemicon2]";}
		if ($itemicon3!="" AND !file_exists("./img/items/$itemicon3")) 
		{$itemicon3 = "missing.gif"; $list["itemname3"].= " :$list[itemicon3]";}
		if ($itemicon4!="" AND !file_exists("./img/items/$itemicon4")) 
		{$itemicon4 = "missing.gif"; $list["itemname4"].= " :$list[itemicon4]";}
		if ($itemicon5!="" AND !file_exists("./img/items/$itemicon5")) 
		{$itemicon5 = "missing.gif"; $list["itemname5"].= " :$list[itemicon5]";}
		if ($itemicon6!="" AND !file_exists("./img/items/$itemicon6")) 
		{$itemicon6 = "missing.gif"; $list["itemname6"].= " :$list[itemicon6]";}
		
	    $left=secondsToTime($list["left"]);
		$leftreason=$list["leftreason"];
		$hero=strtoupper($list["hero"]);
		$heroname=$list["description"];
		
		if ($hero!="") {
        $img = convEnt2($heroname)."<br><img src=img/heroes/$hero.gif>";
		$hero = "<a onMouseout='hidetooltip()' onMouseover='tooltip(\"".$img."\",130)' href='hero.php?hero=$hero'><img alt='' width='28px' src='./img/heroes/$hero.gif' border=0></a>";}
		else {$hero = "<img title='No hero' alt='' width='28px' src='./img/heroes/blank.gif'>";}

		$name=trim($list["name"]);
		$name2=strtolower(trim($list["name"]));
		$name3=trim($list["name"]);
		$newcolour=$list["newcolour"];
		$gameid=$list["gameid"]; 
		$banname=$list["banname"];
		$myFlag = "";
		$IPaddress = $list["ip"];
		//COUNTRY FLAGS
		if ($CountryFlags == 1 AND file_exists("./includes/ip_files/countries.php")  AND $IPaddress!="")
		{
		$two_letter_country_code=iptocountry($IPaddress);
		include("./includes/ip_files/countries.php");
		$three_letter_country_code=$countries[$two_letter_country_code][0];
        $country_name=convEnt2($countries[$two_letter_country_code][1]);
		$file_to_check="./includes/flags/$two_letter_country_code.gif";
		if (file_exists($file_to_check)){
		        $flagIMG = "<img src=$file_to_check>";
                $flag = "<span style='float:left;padding-left:4px;'><img onMouseout='hidetooltip()' onMouseover='tooltip(\"".$flagIMG." $country_name\",100); return false' src='$file_to_check' width='20' height='13'></span>";
                }else{
                $flag =  "<img title='$country_name' src='./includes/flags/noflag.gif' width='20' height='13'>";
                }	
		$myFlag = $flag;
		}

		$my_score = ($kills - $deaths + $assists*0.5) + ($towerkills*0.3 + $raxkills*0.3);
		if ($my_score > $best_score AND $kills>0) 
		{$best_player = $name; 
		$best_score = ($kills - $deaths + $assists*0.5) + ($towerkills*0.3 + $raxkills*0.3);
		}
		
		if (trim(strtolower($banname)) == strtolower($name)) 
		{$name = "<span style='color:#BD0000'>$list[name]</span>";}
		
		//Trim down the leftreason
		$leftreason = str_ireplace("has", "", $leftreason);
		$leftreason = str_ireplace("was", "", $leftreason);
		$leftreason = ucfirst(trim($leftreason));
		$substring = strchr($leftreason, "(");
		$leftreason = str_replace($substring, "", $leftreason);
		
		if ($win==0) {$_sentinel = $lang["looser"]; $_scourge = $lang["looser"];}
		if ($win==1) {$_sentinel = "$lang[winner]"; $_scourge = "$lang[looser]";}
		if ($win==2) {$_sentinel = "$lang[looser]"; $_scourge = "$lang[winner]";}
		
		//User points mod	
		$Points = "";
		
		if ($UserPointsOnGamePage == 1)
		{
		    if ($AccuratePointsCalculation == 1 AND $ScoreMethod == 1) //Calculate points from database
		    {
		    $getSql = GetScoreBefore($scoreFormula,$minPlayedRatio,$gid,$name3,$minGamesPlayed,$gmtime);
	 
		    $result2 = $db->query($getSql);
		    $scoreBefore = $db->fetch_array($result2,'assoc');
	 
	 
		    $getSql = GetScoreAfter($scoreFormula,$minPlayedRatio,$name3,$minGamesPlayed,$gmtime);
		    $result2 = $db->query($getSql);
		    $scoreAfter = $db->fetch_array($result2,'assoc');
		    $CalPoints = $scoreAfter["totalscore"] - $scoreBefore["totalscore"];
	        }
		    //Or use "query-less" method
		   else {
		   $CalPoints = (($kills-$deaths+$assists*0.5+$towerkills*0.5+$raxkills*0.2+($courierkills+$creepdenies)*0.1+$neutralkills*0.03+$creepkills*0.03) * .2)*3; 
		
		   if ($win==2 AND $newcolour<=5 AND $CalPoints>=0) 
		   {$CalPoints = $CalPoints - (($deaths*.7) + ($kills*.5))+($assists*.2)+($courierkills+$creepdenies)*0.1+$towerkills*0.3+$raxkills*0.1;}	
		
		   if ($win==1 AND $newcolour>5 AND $CalPoints>=0) 
		   {$CalPoints = $CalPoints - (($deaths*.7) + ($kills*.5))+($assists*.2)+($courierkills+$creepdenies)*0.1+$towerkills*0.3+$raxkills*0.1;}	
		   }
		   $class = "DrawGame";
		   if (trim(strtolower($banname)) == strtolower($name3)) 
				   {$CalPoints = $ScoreDisc; $class = 'DisconnectPoints';} 
				   
		   		   if ($ScoreMethod == 2 AND $DBScore == 0) 
				   {
				   if ($win==1 AND $newcolour>5) {$CalPoints = $ScoreLosses; $class = 'NegativePoints';}
				   if ($win==1 AND $newcolour<=5) {$CalPoints = $ScoreWins; $class = 'PositivePoints';}
		
				   if ($win==2 AND $newcolour<=5) {$CalPoints = $ScoreLosses; $class = 'NegativePoints';} 
				   if ($win==2 AND $newcolour>5) {$CalPoints = $ScoreWins; $class = 'PositivePoints';} 
				   if ($win==0) {$CalPoints = 0;}
		
				   if (strstr($leftreason,"has lost the connection") 
				   OR strstr($leftreason,"was dropped")
				   OR strstr($leftreason,"Lagged out")
				   OR strstr($leftreason,"Dropped due to") 
				   OR strstr($leftreason,"Lost the connection") )
				   {$CalPoints = $ScoreDisc; $class = 'DisconnectPoints';} 
		
				   if (trim(strtolower($banname)) == strtolower($name3)) 
				   {$CalPoints = $ScoreDisc; $class = 'DisconnectPoints';} 
				   }

		$CalPoints = ROUND($CalPoints,1);
		
		      if ($CalPoints<0) 
			  {
		      if ($ScoreMethod == 1) {$class = 'DisconnectPoints';}
			  $Points = "<p class='alignright'><a title='$name3 has lost $CalPoints points for this game'><span class='$class'>$CalPoints</span></a></p>";
			  } 
		
		else 
		    {
			  if ($ScoreMethod == 1) {$class = 'PositivePoints';}
			 
		$Points = "<p class='alignright'><a title='$name3 gain $CalPoints points for this game'><span class='$class'>+$CalPoints</span></a></p>";}
		if ($CalPoints==0) 
		{$Points = "<p class='alignright'><a title='$name3 gain $CalPoints points for this game'><span class='$class'>$CalPoints</span></a></p>";}
		}
		//User points mod	
		if ($newcolour<=5) {$sent_kills += $kills;}
		if($sentinel == 1 AND $newcolour<=5){
			$sentinel=0;
			echo "<tr class='sentinelRow'>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td><span class='sentinelCol' >$lang[Sentinel]</span></td>
			<td><div align='left'><span class='sentinelCol'>$_sentinel</span><div></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			</tr>";
			}
		
		if ($newcolour>5) {$scour_kills += $kills;}
		
		if($scourge == 1 AND $newcolour>5){
			$scourge=0;
			echo "<tr class='scourgeRow'>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td><span class='scourgeCol'>$lang[Scourge]</span></td>
			<td><div align='left'><span  class='scourgeCol'>$_scourge</span></div></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			</tr>";
			}
			
	$a1 = convEnt2($list["itemname1"])."<br><img src=img/items/$itemicon1></a>";
	$a2 = convEnt2($list["itemname2"])."<br><img src=img/items/$itemicon2>";
	$a3 = convEnt2($list["itemname3"])."<br><img src=img/items/$itemicon3>";
	$a4 = convEnt2($list["itemname4"])."<br><img src=img/items/$itemicon4>";
	$a5 = convEnt2($list["itemname5"])."<br><img src=img/items/$itemicon5>";
	$a6 = convEnt2($list["itemname6"])."<br><img src=img/items/$itemicon6>";
			
		if ($list["itemname1"]!="") 
		{$ic1 = "onMouseout='hidetooltip()' onMouseover='tooltip(\"".$a1."\",130)'";} else {$ic1 = "";}
		
		if ($list["itemname2"]!="") 
		{$ic2 = "onMouseout='hidetooltip()' onMouseover='tooltip(\"".$a2."\",130)'";}  else {$ic2 = "";}
		
		if ($list["itemname3"]!="") 
		{$ic3 = "onMouseout='hidetooltip()' onMouseover='tooltip(\"".$a3."\",130)'";} else {$ic3 = "";}
		
		if ($list["itemname4"]!="") 
		{$ic4 = "onMouseout='hidetooltip()' onMouseover='tooltip(\"".$a4."\",130)'";} else {$ic4 = "";}
		if ($list["itemname5"]!="") 
		{$ic5 = "onMouseout='hidetooltip()' onMouseover='tooltip(\"".$a5."\",130)'";} else {$ic5 = "";}
		
		if ($list["itemname6"]!="") 
		{$ic6 = "onMouseout='hidetooltip()' onMouseover='tooltip(\"".$a6."\",130)'";} else {$ic6 = "";}
		
		
        if ($itemicon1 == "empty.gif") 
		{$ITEM1 = "<img border=0 title=\"\" alt='' width='28px' src='./img/items/$itemicon1'>";}
		else 
		{$ITEM1 = "<a href='item.php?item=$itemID1'><img $ic1 border=0 title=\"\" alt='' width='28px' src='./img/items/$itemicon1'></a>";}
		
		if ($itemicon2 == "empty.gif") 
		{$ITEM2 = "<img border=0 title=\"\" alt='' width='28px' src='./img/items/$itemicon2'>";}
		else 
		{$ITEM2 = "<a href='item.php?item=$itemID2'><img $ic2 border=0 title=\"\" alt='' width='28px' src='./img/items/$itemicon2'></a>";}
		
		if ($itemicon3 == "empty.gif") 
		{$ITEM3 = "<img border=0 title=\"\" alt='' width='28px' src='./img/items/$itemicon3'>";}
		else 
		{$ITEM3 = "<a href='item.php?item=$itemID3'><img $ic3 border=0 title=\"\" alt='' width='28px' src='./img/items/$itemicon3'></a>";}
		
		if ($itemicon4 == "empty.gif") 
		{$ITEM4 = "<img border=0 title=\"\" alt='' width='28px' src='./img/items/$itemicon4'>";}
		else 
		{$ITEM4 = "<a href='item.php?item=$itemID4'><img $ic4 border=0 title=\"\" alt='' width='28px' src='./img/items/$itemicon4'></a>";}
		
		if ($itemicon5 == "empty.gif") 
		{$ITEM5 = "<img border=0 title=\"\" alt='' width='28px' src='./img/items/$itemicon5'>";}
		else 
		{$ITEM5 = "<a href='item.php?item=$itemID5'><img $ic5 border=0 title=\"\" alt='' width='28px' src='./img/items/$itemicon5'></a>";}
		
		if ($itemicon6 == "empty.gif") 
		{$ITEM6 = "<img border=0 title=\"\" alt='' width='28px' src='./img/items/$itemicon6'>";}
		else 
		{$ITEM6 = "<a href='item.php?item=$itemID6'><img $ic6 border=0 title=\"\" alt='' width='28px' src='./img/items/$itemicon6'></a>";}
		
		
		if (empty($name2)) 
		{$gold = ""; $Points = ""; $hero =""; $kills = ""; $deaths =""; $assists="";$creepkills="";
		$creepdenies="";$neutralkills="";$towerkills="";
		$ITEM1="";$ITEM2="";$ITEM3="";$ITEM4="";$ITEM5="";$ITEM6="";$left="";$leftreason="";}
		
		if (!empty($name2) OR $ShowAllSlotsInGame == 1) {
		echo "<tr class='row'>
		      <td>$myFlag <a href='user.php?u=$name2'>$name</a> $Points</td>
			  <td>$hero</td>
			  <td><div align='center'>$kills</div></td>
			  <td><div align='center'>$deaths</div></td>
			  <td><div align='center'>$assists</div></td>
			  <td><div align='center'>$creepkills</div></td>
			  <td><div align='center'>$creepdenies</div></td>
			  <td><div align='center'>$neutralkills</div></td>
			  <td><div align='center'>$towerkills</div></td>
			  <td><div align='center'>$gold</div></td>
			  
			  <td><div align='left'>
			  $ITEM1 $ITEM2 $ITEM3 $ITEM4 $ITEM5 $ITEM6
			  </div>
			  </td>
			  
			  <td><div align='left'>$left</div></td>
			  <td><div align='left'><span class='leftReason'>$leftreason</span></div></td>
			  
			  </tr>";}
	   
	   }
  echo "</table>";
  echo "<table><tr>
  <td width='320px;' align='left' class='padLeft'>
  <b>Best player:</b> <a href='user.php?u=$best_player'>$best_player</a></td>
  
  <td class='padRight' align='left'>
  <h1><b>$lang[Sentinel]</b> $sent_kills:$scour_kills <b>$lang[Scourge]</b></h1></td></tr></table><br>";
  
     if(file_exists($replayloc)) {
     //include('./includes/AJAX2.php');
	  echo "<div class='padAll'>
	  <input type='button' class='inputButton' value='$lang[gamelog]' onclick='javascript:toggle();' />
	  <a class='inputButton' href='javascript:toggle();' id='displayText' name='info'>show</a>
	  <div id='toggleText' style='display: none'>";
	  
	  if (!file_exists($txtReplay) OR $SmartParsing == "0" OR !is_writable($txtReplay))
	  {require('./includes/get_chat.php');}
	    else 
	  {
	     if (file_exists($txtReplay) AND is_writable($txtReplay))
	     {echo file_get_contents($txtReplay);} else {require('./includes/get_chat.php');}
	  }
	  
	  echo "</div></div>";

	 /*$replayloc = str_replace("+","%2B",$replayloc);
	 $replayloc = str_replace(" ","%20",$replayloc);
     echo "<input type='button' class='inputButton' value='Game Log' onclick='requestActivities2(\"./includes/get_chat.php?replayLoc=$replayloc\");' />
    <div id='divActivities2'></div>";*/
     }
	 
  } else  {  
          $pageTitle = "$lang[site_name]";
          $pageContents = ob_get_contents();
          ob_end_clean();
		  echo str_replace('<!--TITLE-->', $pageTitle, $pageContents);
		  }
  
  include('footer.php');
  
  $pageContents = ob_get_contents();
  ob_end_clean();
  echo str_replace('<!--TITLE-->', $pageTitle, $pageContents);
  
  //Cache this page
  if ($cachePages == '1')
  file_put_contents($CacheTopPage, str_replace("<!--TITLE-->",$pageTitle,$pageContents));
  ?>