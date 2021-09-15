<?PHP
    $sql = getSentScourWon();
    $result = $db->query($sql);
    $row = $db->fetch_array($result,'assoc');

    $_totals = $row["sentinelWon"]+$row["scourgeWon"]+$row["draw"];
    $_sentWon = number_format($row["sentinelWon"],"0",".",",");
	$_sentWon2 = $row["sentinelWon"];
    $_scourWon = number_format($row["scourgeWon"],"0",".",",");
	$_scourWon2 = $row["scourgeWon"];
    $_draw = number_format($row["draw"],"0",".",",");
	
	
    if ($_totals>=1)
    {$_sentPerc = ROUND(($_sentWon2/$_totals)*100,1);
    $_scourPerc = ROUND(($_scourWon2/$_totals)*100,1);
    $_drawPerc = ROUND(($_draw/$_totals)*100,1);}
	else 
	{
	$_sentPerc  = 0;
	$_scourPerc = 0;
	$_drawPerc  = 0;
	}
	
	//Get games summary Sentinels
	$sql = getGamesSummary(1);
	$result = $db->query($sql);
	$row = $db->fetch_array($result,'assoc');
	$TotSentinelKills = number_format($row["Kills"],"0",".",",");
	$TotSentinelDeaths = number_format($row["Deaths"],"0",".",",");
	$TotSentinelAssists = number_format($row["Assists"],"0",".",",");
	$TotSentinelCreepKills = number_format($row["CreepKills"],"0",".",",");
	$TotSentinelCreepDenies = number_format($row["CreepDenies"],"0",".",",");
	$TotSentinelTowers = number_format($row["towerkills"],"0",".",",");
	$TotSentinelRax = number_format($row["raxkills"],"0",".",",");
	$TotSentinelCourier = number_format($row["courierkills"],"0",".",",");
	
	//Get games summary Scourge
	$sql = getGamesSummary(2);
	$result = $db->query($sql);
	$row = $db->fetch_array($result,'assoc');
	$TotScourgeKills = number_format($row["Kills"],"0",".",",");
	$TotScourgeDeaths = number_format($row["Deaths"],"0",".",",");
	$TotScourgeAssists = number_format($row["Assists"],"0",".",",");
	$TotScourgeCreepKills = number_format($row["CreepKills"],"0",".",",");
	$TotScourgeCreepDenies = number_format($row["CreepDenies"],"0",".",",");
	$TotScourgeTowers = number_format($row["towerkills"],"0",".",",");
	$TotScourgeRax = number_format($row["raxkills"],"0",".",",");
	$TotScourgeCourier = number_format($row["courierkills"],"0",".",",");
	
	 
	 //DURATION AND TOTAL GAMES
	  $sql = "SELECT MAX(duration), MIN(duration), AVG(duration), SUM(duration) 
	  FROM games 
	  WHERE LOWER(map) LIKE LOWER('%dota%') LIMIT 1";
	  
	  $result = $db->query($sql);
	  $row = $db->fetch_array($result,'assoc');

      $maxDuration=secondsToTime($row["MAX(duration)"]);
      $minDuration=secondsToTime($row["MIN(duration)"]);
      $avgDuration=secondsToTime($row["AVG(duration)"]);
 	  $totalDuration=secondsToTime($row["SUM(duration)"]);
	  $totalGames = number_format($numrows,"0",".",",");

  echo "<div align='center'><table class='tableA'><tr>
     <th class='padLeft'></th>
     <th><div align='left'>$lang[total] $lang[wins]</div></th>
	 <th><div align='center'>$lang[kills]</div></th>
	 <th><div align='center'>$lang[deaths]</div></th>
	 <th><div align='center'>$lang[assists]</div></th>
	 <th><div align='center'>$lang[creep_kills]</div></th>
	 <th><div align='center'>$lang[denies]</div></th>
	 <th><div align='center'>$lang[towers]</div></th>
	 <th><div align='center'>$lang[rax]</div></th>
	 <th><div align='center'>$lang[couriers]</div></th>
	 <th></th>
	 </tr>
	 <tr class='row'>
	 <td class='padLeft' width='90px'><span class='sentinel'><b>$lang[Sentinel]</b></span></td>
	 <td width='90px'>$_sentWon ($_sentPerc%)</td>
	 <td align='center' width='90px'>$TotSentinelKills</td>
	 <td align='center' width='90px'>$TotSentinelDeaths</td>
	 <td align='center' width='90px'>$TotSentinelAssists</td>
	 <td align='center' width='90px'>$TotSentinelCreepKills</td>
	 <td align='center' width='90px'>$TotSentinelCreepDenies</td>
	 <td align='center' width='90px'>$TotSentinelTowers</td>
	 <td align='center' width='90px'>$TotSentinelRax</td>
	 <td align='center' width='90px'>$TotSentinelCourier</td>
	 <td></td>
	 
	 <tr class='row'>
	 <td class='padLeft' width='90px'><span class='scourge'><b>$lang[Scourge]</b></span></td>
	 <td width='90px'>$_scourWon ($_scourPerc%)</td>
	 <td align='center' width='90px'>$TotScourgeKills</td>
	 <td align='center' width='90px'>$TotScourgeDeaths</td>
	 <td align='center' width='90px'>$TotScourgeAssists</td>
	 <td align='center' width='90px'>$TotScourgeCreepKills</td>
	 <td align='center' width='90px'>$TotScourgeCreepDenies</td>
	 <td align='center' width='90px'>$TotScourgeTowers</td>
	 <td align='center' width='90px'>$TotScourgeRax</td>
	 <td align='center' width='90px'>$TotScourgeCourier</td>
	 <td></td>
	 
	 <tr class='row'>
	 <td class='padLeft' width='90px'><span class='GamesDraw'>$_lang[draw_games]</span></td>
	 <td width='120px'>$_draw ($_drawPerc%)</th>
	 <td></td>
	 <td></td>
	 <td></td>
	 <td></td>
	 <td></td>
	 <td></td>
	 <td></td>
	 <td></td>
	 <td></td>
	 </tr>
	 </table>";
	 

     echo "
	 <table class='tableA'><tr>
     <th width='33%' class='padLeft'>$lang[total_games] $numrows</th>
	 <th width='33%'>$lang[avg_duration] $avgDuration</th>
	 <th width='33%'>$lang[total_duration] $totalDuration</th>
	 </tr>
	 </table></div>";
	 
	 ?>