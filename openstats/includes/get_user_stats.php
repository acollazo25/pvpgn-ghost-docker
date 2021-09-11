<?PHP

  $username=strtolower(safeEscape($_GET["u"]));
  
  $BANNED =  "";
  $COLOR = "";
  
  $sql = "
  SELECT 
  gp.name AS name, bans.name AS banname, count(1) AS counttimes, gp.ip AS ip
  FROM 
  gameplayers gp 
  JOIN dotaplayers dp ON dp.colour = gp.colour 
  AND 
  dp.gameid = gp.gameid 
  LEFT JOIN bans ON bans.name = gp.name
  WHERE 
  LOWER(gp.name) = LOWER('$username') GROUP BY gp.name 
  ORDER BY 
  counttimes DESC, gp.name ASC";
  
  $result = $db->query($sql);
  
  if ($db->num_rows($result) <=0) {echo $lang["err_user"] ; die;}

  $list = $db->fetch_array($result,'assoc'); 
  
  if (strtolower("$list[name]") == strtolower($list['banname'])) {$BANNED =  "(Banned)"; $COLOR = "style='color:#DC0000;'";}

  $realname = $list['name'];
  $myFlag = "";
  $IPaddress = $list["ip"];
  
  if ($CountryFlags == 1 AND file_exists("./includes/ip_files/countries.php") AND $IPaddress!="" )
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
  
  /////////////////////////////////// HERO STATS ///////////////////////////////////
  
  //////////////////////////////
  //Find hero with most kills
	$sql = "
	SELECT 
	original, description, max(kills) 
	FROM dotaplayers AS dp 
	LEFT JOIN gameplayers AS gp ON gp.gameid = dp.gameid AND dp.colour = gp.colour 
	LEFT JOIN heroes on hero = heroid 
	WHERE LOWER(name)= LOWER('$username') 
	GROUP BY original 
	ORDER BY max(kills) DESC LIMIT 1 ";
	
	$result = $db->query($sql);
	$list = $db->fetch_array($result,'assoc'); 
	$mostkillshero=strtoupper($list["original"]);
	$mostkillsheroname=$list["description"];
	$mostkillscount=$list["max(kills)"];
	
	
	//////////////////////////////
	//Find hero with most deaths
	$sql = "SELECT original, description, max(deaths) 
	FROM dotaplayers AS a 
	LEFT JOIN gameplayers AS b ON b.gameid = a.gameid and a.colour = b.colour 
	LEFT JOIN heroes on hero = heroid 
	WHERE LOWER(name) = LOWER('$username') 
	GROUP BY original 
	ORDER BY max(deaths) DESC 
	LIMIT 1 ";
	
	$result = $db->query($sql);
	$list = $db->fetch_array($result,'assoc'); 
	$mostdeathshero=strtoupper($list["original"]);
	$mostdeathsheroname=$list["description"];
	$mostdeathscount=$list["max(deaths)"];

	
	//////////////////////////////
	//Find hero with most assists
	$sql = "SELECT original, description, max(assists) 
	FROM dotaplayers AS a 
	LEFT JOIN gameplayers AS b ON b.gameid = a.gameid and a.colour = b.colour 
	LEFT JOIN heroes on hero = heroid 
	WHERE LOWER(name) = LOWER('$username') 
	GROUP BY original 
	ORDER BY max(assists) DESC 
	LIMIT 1 ";
	
	$result = $db->query($sql);
	$list = $db->fetch_array($result,'assoc'); 
	$mostassistshero=strtoupper($list["original"]);
	$mostassistsheroname=$list["description"];
	$mostassistscountlist=$list["max(assists)"];
	$mostassistscount=$list["max(assists)"];
	
	
	//////////////////////////////
	//Get hero with most wins
	$sql = "
	SELECT original, description, COUNT(*) as wins 
	FROM gameplayers 
	LEFT JOIN games ON games.id=gameplayers.gameid 
	LEFT JOIN dotaplayers ON dotaplayers.gameid=games.id 
	AND dotaplayers.colour=gameplayers.colour 
	LEFT JOIN dotagames ON games.id=dotagames.gameid 
	LEFT JOIN heroes on hero = heroid 
	WHERE LOWER(name) = LOWER('$username') 
	AND((winner=1 
	AND dotaplayers.newcolour>=1 
	AND dotaplayers.newcolour<=5) 
	OR (winner=2 
	AND dotaplayers.newcolour>=7 
	AND dotaplayers.newcolour<=11)) 
	GROUP BY original order by wins desc limit 1";
	
	$result = $db->query($sql);
	$list = $db->fetch_array($result,'assoc'); 
	$mostwinshero=strtoupper($list["original"]);
	$mostwinsheroname=$list["description"];
	$mostwinscount=$list["wins"];
	
	
	//////////////////////////////
	//Get hero with most losses
	$sql = "
	SELECT original, description, COUNT(*) as losses 
	FROM gameplayers 
	LEFT JOIN games ON games.id=gameplayers.gameid 
	LEFT JOIN dotaplayers ON dotaplayers.gameid=games.id 
	AND dotaplayers.colour=gameplayers.colour 
	LEFT JOIN dotagames ON games.id=dotagames.gameid 
	LEFT JOIN heroes on hero = heroid 
	WHERE LOWER(name) = LOWER('$username') 
	AND((winner=2 AND dotaplayers.newcolour>=1 AND dotaplayers.newcolour<=5) OR (winner=1 AND dotaplayers.newcolour>=7 AND dotaplayers.newcolour<=11)) 
	GROUP BY original order by losses desc limit 1";
	
	$result = $db->query($sql);
	$list = $db->fetch_array($result,'assoc'); 
	$mostlosseshero=strtoupper($list["original"]);
	$mostlossesheroname=$list["description"];
	$mostlossescount=$list["losses"];
	
	
	//////////////////////////////
	//Get hero you have played most with
	$sql = "SELECT SUM(`left`) AS timeplayed, original, description, 
	COUNT(*) AS played 
	FROM gameplayers 
	LEFT JOIN games ON games.id=gameplayers.gameid 
	LEFT JOIN dotaplayers ON dotaplayers.gameid=games.id 
	AND dotaplayers.colour=gameplayers.colour  
	LEFT JOIN dotagames ON games.id=dotagames.gameid 
    JOIN heroes on hero = heroid 
	WHERE LOWER(name)=LOWER('$username')
	GROUP BY original 
	ORDER BY played DESC LIMIT 1";
	
	    $result = $db->query($sql);
	    $list = $db->fetch_array($result,'assoc'); 
		$mostplayedhero=strtoupper($list["original"]);
		$mostplayedheroname=$list["description"];
		$mostplayedcount=$list["played"];
		$mostplayedtime=secondsToTime($list["timeplayed"]);

	//////////////////////////////
	//Using score table
	if ($DBScore != 1) {
	
	$sql = "SELECT 
	($scoreFormula) as score 
	FROM(SELECT *, (kills/deaths) as killdeathratio 
	   FROM (
		SELECT avg(dp.courierkills) as courierkills, 
	    avg(dp.raxkills) as raxkills,
		avg(dp.towerkills) as towerkills, 
		avg(dp.assists) as assists, 
		avg(dp.creepdenies) as creepdenies, 
		avg(dp.creepkills) as creepkills,
		avg(dp.neutralkills) as neutralkills, 
		avg(dp.deaths) as deaths, 
		avg(dp.kills) as kills,
		COUNT(*) as totgames, 
		SUM(case when(((dg.winner = 1 and dp.newcolour < 6) 
		or (dg.winner = 2 and dp.newcolour > 6)) 
		AND gp.`left`/ga.duration >= 0.8) then 1 else 0 end) as wins, 
		SUM(case when(((dg.winner = 2 and dp.newcolour < 6) 
		or (dg.winner = 1 and dp.newcolour > 6)) 
		AND gp.`left`/ga.duration >= 0.8) then 1 else 0 end) as losses
		FROM gameplayers as gp 
		LEFT JOIN dotagames as dg ON gp.gameid = dg.gameid 
		LEFT JOIN games as ga ON ga.id = dg.gameid 
		LEFT JOIN dotaplayers as dp on dp.gameid = dg.gameid 
		and gp.colour = dp.colour where dg.winner <> 0 
		and gp.name = '$username') as h) as i LIMIT 1";} else
		{$sql = "SELECT scores.score from scores WHERE LOWER(name) = LOWER('$username')";}
		
	$result = $db->query($sql);
	$list = $db->fetch_array($result,'assoc');
	$score=$list["score"];

	//FINAL STEP
	$result = $db->query("SELECT 
	COUNT(dp.id), 
	SUM(kills), 
	SUM(deaths), 
	SUM(creepkills), 
	SUM(creepdenies), 
	SUM(assists), 
	SUM(neutralkills), 
	SUM(towerkills), 
	SUM(raxkills), 
	SUM(courierkills), name 
	FROM dotaplayers AS dp 
	LEFT JOIN gameplayers AS b ON b.gameid = dp.gameid 
	and dp.colour = b.colour 
	WHERE name= '$username' 
	GROUP BY name 
	ORDER BY sum(kills) desc 
	LIMIT 1");
	
	$row = $db->fetch_array($result,'assoc');

	$kills=number_format($row["SUM(kills)"],"0",".",",");
	$kills2=$row["SUM(kills)"];
	$death=number_format($row["SUM(deaths)"],"0",".",",");
	$death2=$row["SUM(deaths)"];
    $assists=number_format($row["SUM(assists)"],"0",".",",");
	$assists2=$row["SUM(assists)"];
	$creepkills=number_format($row["SUM(creepkills)"],"0",".",",");
	$creepkills2=$row["SUM(creepkills)"];
	$creepdenies=number_format($row["SUM(creepdenies)"],"0",".",",");
	$creepdenies2=$row["SUM(creepdenies)"];
	$neutralkills=number_format($row["SUM(neutralkills)"],"0",".",",");
	$neutralkills2=$row["SUM(neutralkills)"];
	$towerkills=number_format($row["SUM(towerkills)"],"0",".",",");
	$towerkills2=$row["SUM(towerkills)"];
	$raxkills=number_format($row["SUM(raxkills)"],"0",".",",");
	$courierkills=number_format($row["SUM(courierkills)"],"0",".",",");
	$courierkills2=$row["SUM(courierkills)"];
	$name=$row["name"];
	//$totgames=number_format($row["COUNT(dp.id)"],"0",".",",");
    //$totgames2=$row["COUNT(dp.id)"];
	
	if ($displayUsersDisconnects == 1 OR $ScoreMethod == 2)	
	{
	$sql = "SELECT 
   SUM(
	 (gp.`leftreason` LIKE ('%has lost the connection%'))  
	 OR (gp.`leftreason` LIKE ('%was dropped%')) 
	 OR (gp.`leftreason` LIKE ('%Lagged out%')) 
	 OR (gp.`leftreason` LIKE ('%Dropped due to%'))
	 OR (gp.`leftreason` LIKE ('%Lost the connection%'))
	 ) as disc 
   FROM gameplayers as gp 
   LEFT JOIN games ON games.id=gp.gameid 
   LEFT JOIN dotaplayers ON dotaplayers.gameid=games.id 
   AND dotaplayers.colour=gp.colour 
   LEFT JOIN dotagames ON games.id=dotagames.gameid 
   WHERE LOWER(gp.name)=LOWER('$username') 
   AND dotagames.winner <>0 
   LIMIT 1";
	
	$sql2222 = " SELECT COUNT(*) 
    FROM `gameplayers`
    WHERE 
	(
	   `leftreason` LIKE('%has lost the connection%') 
	OR `leftreason` LIKE('%was dropped%') 
	OR `leftreason` LIKE('%Lagged out%') 
	OR `leftreason` LIKE('%Dropped due to%')
	OR (gp.`leftreason` LIKE ('%Lost the connection%'))
	) 
	AND name= '$username' LIMIT 1";
	
	$result = $db->query($sql);
	$row = $db->fetch_array($result,'assoc');
    $disc = $row["disc"]; }
	
	echo "<table>
	<tr>
	<td style='width:36%;padding-left:8px; height:24px;'>
	<div align='left'><a href='heroes.php?u=$realname'>$lang[show_hero_stats] 
	<span $COLOR><b>$realname</b></span></a></div>
	</td>
	<td><div align='left'>$lang[show_stats_user] <b>$realname $myFlag <span $COLOR>$BANNED</span></b></div></td>
	</tr>
	</table>";
	//calculate wins
    $wins=$db->getUserWins($username);
    //calculate losses
    $losses=$db->getUserLosses($username);

	if ($death2 >=1)
	{$kdratio = ROUND($kills2/$death2,1);} else {$kdratio =0;}
	
	$totgames = number_format($wins+$losses,"0",".",",");
	$totgames2 = $wins+$losses;
	$totscore = number_format(ROUND($score,2),"0",".",",");
	
	if($wins == 0 and $wins+$losses == 0)
	{$winloose = 0;}
	else
	{$winloose = round($wins/($wins+$losses), 4)*100;}
	
     		
	if ($mostkillshero == "") {$mostkillshero = "blank";}
	if ($mostdeathshero == "") {$mostdeathshero = "blank";}
	if ($mostassistshero == "") {$mostassistshero = "blank";}
	if ($mostwinshero == "") {$mostwinshero = "blank";}
	if ($mostlosseshero == "") {$mostlosseshero = "blank";}
	if ($mostplayedhero == "") {$mostplayedhero = "blank";}
    
	$mkhimg = "<a href='hero.php?hero=$mostkillshero'><img width='64px' title='$mostkillsheroname' alt='' src='img/heroes/$mostkillshero.gif'/ border=0></a>";
	$mdhimg = "<a href='hero.php?hero=$mostdeathshero'><img width='64px' title='$mostdeathsheroname' alt='' src='img/heroes/$mostdeathshero.gif' border=0 /></a>";
	$mahimg = "<a href='hero.php?hero=$mostassistshero'><img width='64px' title='$mostassistsheroname' alt='' src='img/heroes/$mostassistshero.gif' border=0/></a>";
	$mwhimg = "<a href='hero.php?hero=$mostwinshero'><img width='64px' title='$mostwinsheroname' alt='' src='img/heroes/$mostwinshero.gif' border=0/></a>";
	$mlhimg = "<a href='hero.php?hero=$mostlosseshero'><img width='64px' title='$mostlossesheroname' alt='' src='img/heroes/$mostlosseshero.gif' border=0/></a>";
	$mphimg = "<a href='hero.php?hero=$mostplayedhero'><img width='64px' title='$mostplayedheroname' alt='' src='img/heroes/$mostplayedhero.gif' border=0/></a>";
	
	   
   $sql = "SELECT 
   MIN(datetime), 
   MIN(loadingtime), 
   MAX(loadingtime), 
   AVG(loadingtime), 
   MIN(`left`), 
   MAX(`left`), 
   AVG(`left`), 
   SUM(`left`) 
   FROM gameplayers 
   LEFT JOIN games ON games.id=gameplayers.gameid 
   LEFT JOIN dotaplayers ON dotaplayers.gameid=games.id 
   AND dotaplayers.colour=gameplayers.colour 
   LEFT JOIN dotagames ON games.id=dotagames.gameid 
   WHERE LOWER(name)=LOWER('$username') LIMIT 1";
	
	$result = $db->query($sql);
	
	$row = $db->fetch_array($result,'assoc');
	//$db->close($result);
	$firstgame=$row["MIN(datetime)"];
		$minLoading=millisecondsToTime($row["MIN(loadingtime)"]);
		$maxLoading=millisecondsToTime($row["MAX(loadingtime)"]);
		$avgLoading=millisecondsToTime($row["AVG(loadingtime)"]);
		$minDuration=secondsToTime($row["MIN(`left`)"]);
		$maxDuration=secondsToTime($row["MAX(`left`)"]);
		$avgDuration=secondsToTime($row["AVG(`left`)"]);
		$totalDuration=secondsToTime($row["SUM(`left`)"]);
		
		$totalHours=ROUND($row["SUM(`left`)"]/ 3600,1);
		$totalMinutes=ROUND($row["SUM(`left`)"]/ 3600*60,1);

        $firstgame=$row["MIN(datetime)"];
  
		if ($totalMinutes>0)
		{$killsPerMin = ROUND($kills2/$totalMinutes,2);
		$killsPerHour = ROUND($kills2/$totalHours,2);
		$deathsPerMin = ROUND($death2/$totalMinutes,2);
		$creepsPerMin = ROUND($creepkills2/$totalMinutes,2);
		}  
		    else 
		    {
		    $killsPerMin = 0; 
		    $deathsPerMin = 0; 
		    $killsPerHour = 0; 
		    $creepsPerMin =0;
			}
		
		if ($totgames2>0)
		{
		$killsPerGame = ROUND($kills2/$totgames2,2);	
		$deathsPerGame = ROUND($death2/$totgames2,2);
		$DiscPercent = ROUND($disc/($disc+$totgames2), 4)*100;
		} 
		else {$killsPerGame = 0; $DiscPercent = 0; $deathsPerGame =0;}
		
		if ($kills2 >0)
	    {$KillsPercent = ROUND($kills2/($kills2+$death2), 4)*100; } else {$KillsPercent = 0;}
		
		if ($totgames2 >0)
		{$AssistsPerGame = ROUND($assists2/$totgames2,2);} else {$AssistsPerGame = 0;}
		
		if ($ScoreMethod == 2 AND $DBScore == 0)
		{$totscore = number_format($ScoreStart + ($wins * $ScoreWins) + ($losses * $ScoreLosses) + ($disc*$ScoreDisc),"0",".",",") ; 
		//$totscore = ROUND( $ScoreStart+(($wins * 5) - ($losses * 3)) , 2) ; 
		if ($BANNED !="") {$totscore  = $ScoreStart + ($ScoreDisc*10);}
		}
		
		///////////////////////
		//FASTEST GAME WON ///
		if ($FastGameWon == 1)
		{
		$sql = fastGameWon($username);
		$result = $db->query($sql);
		$row = $db->fetch_array($result,'assoc');
		$fastGameWonID = $row["gameid"];
		$fastGameWonTime = secondsToTime($row["duration"]);
		$fastGameWonName = $row["gamename"];
		$fastGameWonKills = $row["kills"];
		$fastGameWonDeaths = $row["deaths"];
		$fastGameWonAssists = $row["assists"];
		$fastGameWonCreeps = $row["creepkills"];
		$fastGameWonDenies = $row["creepdenies"];
		$fastGameWonNeutrals = $row["neutralkills"];
		
		///////////////////////
		//LONGEST GAME WON ///
		$sql = longGameWon($username);
		$result = $db->query($sql);
		$row = $db->fetch_array($result,'assoc');
		$longestGameWonID = $row["gameid"];
		$longestGameWonTime = secondsToTime($row["duration"]);
		$longestGameWonName = $row["gamename"];
		$longestGameWonKills = $row["kills"];
		$longestGameWonDeaths = $row["deaths"];
		$longestGameWonAssists = $row["assists"];
		$longestGameWonCreeps = $row["creepkills"];
		$longestGameWonDenies = $row["creepdenies"];
		$longestGameWonNeutrals = $row["neutralkills"];

		}
   ?>
	
	
	