<?PHP

   if ($_GET)
            {
     require_once ('../config.php');
	 require_once('../includes/class.database.php');
	 require_once('../includes/common.php');
	 require_once('../includes/db_connect.php');
	 require_once("../lang/$default_language.php");
	 
	 	 
	 $sqlYear = safeEscape($_GET["sqlyear"]);
	 $sqlMonth = safeEscape($_GET["sqlmonth"]);
	 //$day_stats = EscapeStr($_GET["daystats"]); 
	 $day_stats = "";
     $year = safeEscape($_GET["year"]);
	 $month = safeEscape($_GET["month"]);
	 $day = safeEscape($_GET["day"]);
	 
	 if (isset($_GET['games'])) {$games = safeEscape($_GET['games']);} else {$games = 2;}
	 
	  if ($DBScore == 0)
      {
	     if ($ScoreMethod == 2) 
		    {$scoreFormula = "$ScoreStart + (wins*$ScoreWins) + (losses*$ScoreLosses) + (disc*$ScoreDisc)";}

	 if ($HideBannedUsersOnTop == 1) {$_sql = "AND bans.name is null";} else {$_sql = "";}

$sql = "
	SELECT *, 
	case when (kills = 0) then 0 
	when (deaths = 0) then 1000 
	else ((kills*1.0)/(deaths*1.0)) end as killdeathratio, 
	($scoreFormula) as totalscore 
	 FROM ( 
	 SELECT 
	 gp.name as name, 
	 gp.ip as ip, 
	 bans.name as banname, 
	 avg(dp.courierkills) as courierkills, 
	 avg(dp.raxkills) as raxkills,
	 avg(dp.towerkills) as towerkills, 
	 avg(dp.assists) as assists, 
	 SUM(dp.assists) as SumAssists,
	 avg(dp.creepdenies) as creepdenies, 
	 avg(dp.creepkills) as creepkills,
	 avg(dp.neutralkills) as neutralkills, 
	 avg(dp.deaths) as deaths, 
	 avg(dp.kills) as kills, 
	 SUM(dp.kills) as totkills,
	 SUM(dp.deaths) as totdeaths,
	 SUM(dp.creepkills) as SumCreepkills,
	 SUM(dp.neutralkills) as SumNeutralkills,
	 SUM(dp.creepdenies) as SumCreepdenies, 
	 SUM(dp.deaths) as SumDeaths, 
	 SUM(dp.kills) as SumKills,
	 COUNT(*) as totgames, 
	 case when (kills = 0) then 0 
	 when (deaths = 0) then 1000 
	 else ((kills*1.0)/(deaths*1.0)) 
	 end as killdeathratio,
	 SUM(case when(((dg.winner = 1 and dp.newcolour < 6) 
	 or (dg.winner = 2 and dp.newcolour > 6)) 
     AND gp.`left`/ga.duration >= $minPlayedRatio) then 1 
	 else 0 end) as wins, 
     SUM(case when(((dg.winner = 2 and dp.newcolour < 6) 
	 or (dg.winner = 1 and dp.newcolour > 6)) 
     AND gp.`left`/ga.duration >= $minPlayedRatio) 
	 then 1 else 0 end) as losses
	 
	 , SUM(
	 (gp.`leftreason` LIKE ('%has lost the connection%'))  
	 OR (gp.`leftreason` LIKE ('%was dropped%')) 
	 OR (gp.`leftreason` LIKE ('%Lagged out%')) 
	 OR (gp.`leftreason` LIKE ('%Dropped due to%'))
	 ) as disc 
	 
	 
     FROM gameplayers as gp 
     LEFT JOIN dotagames as dg ON gp.gameid = dg.gameid 
     LEFT JOIN dotaplayers as dp ON dg.gameid = dp.gameid 
	 AND gp.colour = dp.colour 
	 AND dp.newcolour <> 12 
	 AND dp.newcolour <> 6
	 LEFT JOIN games as ga ON dp.gameid = ga.id 
	 LEFT JOIN bans on bans.name = gp.name 
	 WHERE dg.winner <>0 AND $sqlYear = '$year' AND $sqlMonth = '$month' $day_stats 
	 $_sql
	 GROUP BY gp.name having totgames >= $games) as i 
	 ORDER BY totalscore DESC, name DESC LIMIT $monthly_stats";}
	  else
	 {
	 if ($HideBannedUsersOnTop == 1) {$_sql = "AND bans.name is null";} else {$_sql = "";}
	 $sql = "
	 SELECT *, 
	 case when (kills = 0) then 0 
	 when (deaths = 0) then 1000 
	 else ((kills*1.0)/(deaths*1.0)) 
	 end as killdeathratio 
	 FROM (
          SELECT gp.name as name, 
		  gp.ip as ip, 
		  bans.name as banname, 
		  avg(dp.courierkills) as courierkills, 
		  avg(dp.raxkills) as raxkills,
		  avg(dp.towerkills) as towerkills, 
		  avg(dp.assists) as assists, 
		  avg(dp.creepdenies) as creepdenies, 
		  avg(dp.creepkills) as creepkills,
		  avg(dp.neutralkills) as neutralkills, 
		  avg(dp.deaths) as deaths, 
		  avg(dp.kills) as kills, 
		  SUM(dp.kills) as totkills,
		  SUM(dp.assists) as SumAssists,
		  SUM(dp.creepkills) as SumCreepkills,
	      SUM(dp.neutralkills) as SumNeutralkills,
	      SUM(dp.creepdenies) as SumCreepdenies, 
		  SUM(dp.deaths) as SumDeaths, 
	      SUM(dp.kills) as SumKills,
		  sc.score as totalscore, 
		  COUNT(*) as totgames, 
		  SUM(case when(((dg.winner = 1 and dp.newcolour < 6) 
		  or (dg.winner = 2 and dp.newcolour > 6)) 
		  AND gp.`left`/ga.duration >= $minPlayedRatio) then 1 else 0 end) as wins, 
		  SUM(case when(((dg.winner = 2 and dp.newcolour < 6) 
		  or (dg.winner = 1 and dp.newcolour > 6)) 
		  AND gp.`left`/ga.duration >= $minPlayedRatio) then 1 else 0 end) as losses
		  
		  , SUM(
	 (gp.`leftreason` LIKE ('%has lost the connection%'))  
	 OR (gp.`leftreason` LIKE ('%was dropped%')) 
	 OR (gp.`leftreason` LIKE ('%Lagged out%')) 
	 OR (gp.`leftreason` LIKE ('%Dropped due to%'))
	 ) as disc 
		  
		  FROM gameplayers as gp 
		  LEFT JOIN dotagames as dg ON gp.gameid = dg.gameid 
		  LEFT JOIN dotaplayers as dp ON dg.gameid = dp.gameid 
		  AND gp.colour = dp.colour 
		  AND dp.newcolour <> 12 
		  AND dp.newcolour <> 6
		  LEFT JOIN games as ga ON dp.gameid = ga.id 
		  LEFT JOIN scores as sc ON sc.name = gp.name 
		  LEFT JOIN bans on bans.name = gp.name 
		  WHERE dg.winner <> 0 $_sql
		  GROUP BY gp.name having totgames >= $games) as i 
	 ORDER BY totalscore DESC, name DESC LIMIT $monthly_stats";
	 }
  
	   
	 $result = $db->query($sql);
	 
	 $monthName = getMonthName($month,$lang["jan"],$lang["feb"],$lang["mar"],$lang["apr"],$lang["may"],$lang["jun"],$lang["jul"],$lang["aug"],$lang["sep"],$lang["oct"],$lang["nov"],$lang["dec"]);
	 
	 $monthName = substr($monthName,0,3);
	 
	 echo "<div align='center'><table class='tableA'><tr>
  <th class='padLeft'><div align='left'>$lang[toprank] $monthName $year</div></th>
  <th><div align='center'>$lang[score]</div></th>
  <th><div align='center'>$lang[games]</div></th>
  <th><div align='center'>$lang[wins]</div></th>
  <th><div align='center'>$lang[win_perc]</div></th>
  <th><div align='center'>$lang[losses]</div></th>
  <th><div align='center'>$lang[kills]</div></th>
  <th><div align='center'>$lang[deaths]</div></th>
  <th><div align='center'>$lang[assists]</div></th>
  <th><div align='center'>$lang[kd]</th>
  <th><div align='center'>$lang[creeps]</div></th>
  <th><div align='center'>$lang[denies]</div></th>
  <th><div align='center'>$lang[neutrals]</div></th>
	 </tr>";
	 
	 if ($db->num_rows($result) <=0) {echo "<tr><td class='padLeft'>No ranks for $monthName $year</td></tr>";}
	 else
	 {	    $noRanks = "";
	 while ($list = $db->fetch_array($result,'assoc')) {

	    $totgames=$list["totgames"];
		$kills=ROUND($list["kills"],1);
		$death=ROUND($list["deaths"],1);
		$assists=ROUND($list["assists"],1);
		$creepkills=ROUND($list["creepkills"],1);
		$creepdenies=ROUND($list["creepdenies"],1);
		$neutralkills=ROUND($list["neutralkills"],1);
		$courierkills=ROUND($list["courierkills"],1);
		$wins=$list["wins"];
		$losses=$list["losses"];
		
		if ($wins <=0)
		{$winlosses = 0;}
		else
		if($wins == 0 and $wins+$losses == 0){ $winlosses = 0;}
		else
		if($wins+$losses == 0){$winlosses = 1000;}
		else
		if ($wins >0)
		{$winlosses = ROUND($wins/($wins+$losses), 3)*100;} 
		
		$totalscore=ROUND($list["totalscore"],2);
		$killdeathratio=ROUND($list["killdeathratio"],1); 
	 
	 $list["totalscore"] = ROUND($list["totalscore"],2);
	 $plName = strtolower($list["name"])."'a";
	 $tooltipName = convEnt2($list["name"]);
	 
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
	 
	 echo "<tr class='row'>
		<td width='180px' style='padding-left:4px;'>$myFlag <a href='user.php?u=$plName'>$list[name]</a></td>
		<td width='110px'><div align='center'>$list[totalscore]</div></td>
		<td width='64px'><div align='center'>$totgames</div></td>
		<td width='64px'><div align='center'>$wins</div></td>
		<td width='64px'><div align='center'>$winlosses%</div></td>
		<td width='70px'><div align='center'>$losses</div></td>
		
		<td width='70px'><div align='center'>
		<a onMouseout='hidetooltip()' onMouseover='tooltip(\"<div align=left><i>$tooltipName</i><br><b>$lang[kills]:</b><br>$lang[average] $kills<br>$lang[total] $list[SumKills]</div>\",130); return false'>$kills</a>
		</div></td>
		
		<td width='70px'><div align='center'>
		<a onMouseout='hidetooltip()' onMouseover='tooltip(\"<div align=left><i>$tooltipName</i><br><b>$lang[deaths]:</b><br>$lang[average] $death<br>$lang[total] $list[SumDeaths]</div>\",130); return false'>$death</a></div></td>
		
		<td width='70px'><div align='center'>
		<a onMouseout='hidetooltip()' onMouseover='tooltip(\"<div align=left><i>$tooltipName</i><br><b>$lang[assists]:</b><br>$lang[average] $assists<br>$lang[total] $list[SumAssists]</div>\",130); return false'>
		$assists</a></div></td>
		
		<td width='90px'><div align='center'>$killdeathratio:1</div></td>
		
		<td width='70px'><div align='center'>
		<a onMouseout='hidetooltip()' onMouseover='tooltip(\"<div align=left><i>$tooltipName</i><br><b>$lang[creeps]:</b><br>$lang[average] $creepkills<br>$lang[total] $list[SumCreepkills]</div>\",130); return false'>
		$creepkills</a></div></td>
		
		<td width='70px'><div align='center'>
		<a onMouseout='hidetooltip()' onMouseover='tooltip(\"<div align=left><i>$tooltipName</i><br><b>$lang[denies]:</b><br>$lang[average] $creepdenies<br>$lang[total] $list[SumCreepdenies]</div>\",130); return false'>
		$creepdenies</a></div></td>
		
		<td width='70px'><div align='center'>
		<a onMouseout='hidetooltip()' onMouseover='tooltip(\"<div align=left><i>$tooltipName</i><br><b>$lang[neutrals]:</b><br>$lang[average] $neutralkills<br>$lang[total] $list[SumNeutralkills]</div>\",130); return false'>
		$neutralkills</a></div></td>
		
		</tr>";
	 }
	 
  }
 
		echo "</table></div>";
		
		} else {echo "Direct access not allowed!";}
		
		
		?>