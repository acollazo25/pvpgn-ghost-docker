<?PHP
	    
		$TotalMedals = 0;

		
		echo "<div align='center'>
		<table class='tableA'><!-- <tr>
		<th></th>
		<th>$realname Achievements</th>
		</tr>-->";
		
		$opacity = ""; 
		$ColClass = "NotAchieved";
		$buildAchievements = ""; 
		$countAchievements = 0;
		
		//KILLS		
		if ($kills2 < $KillsMedal ) {$opacity = " class='alpha' "; $ColClass = 'NotAchieved';} 
		else {$ColClass = 'Achieved'; $TotalMedals++; }
		$buildAchievements .= "<tr class='row'>
		<td width='72px' align='left'><img $opacity alt='' width='48px' border=0 src='./img/achievements/kills.png'></td>
		<td align='left'><span class='$ColClass'>Kill $KillsMedal enemy heroes. ($kills2 kills)</span></td>
		</tr>"; $opacity = ""; $countAchievements++;
		
		//ASSISTS		
		if ($assists2 < $AssistMedal ) {$opacity = " class='alpha' "; $ColClass = 'NotAchieved';} 
		else {$ColClass = 'Achieved'; $TotalMedals++; }
		$buildAchievements .= "<tr class='row'>
		<td width='72px' align='left'><img $opacity alt='' width='48px' border=0 src='./img/achievements/assist.png'></td>
		<td align='left'><span class='$ColClass'>Assist in $AssistMedal kills. ($assists2 assists)</span></td>
		</tr>"; $opacity = ""; $countAchievements++;
		

		//WIN PERCENT		
		if ($winloose < $WinPercentMedal ) {$opacity = " class='alpha' "; $ColClass = 'NotAchieved';} 
		else {$ColClass = 'Achieved'; $TotalMedals++; }
		$buildAchievements .= "<tr class='row'>
		<td width='72px' align='left'><img $opacity alt='' width='48px' border=0 src='./img/achievements/winperc.gif'></td>
		<td align='left'><span class='$ColClass'>Achieve $WinPercentMedal % wins. ($winloose%)</span></td>
		</tr>"; $opacity = ""; $countAchievements++;
		
		//KILLS PERCENT		
		if ($KillsPercent < $KillsPercentMedal) {$opacity = " class='alpha' "; $ColClass = 'NotAchieved';} 
		else {$ColClass = 'Achieved'; $TotalMedals++; }
		$buildAchievements .= "<tr class='row'>
		<td width='72px' align='left'><img $opacity alt='' width='48px' border=0 src='./img/achievements/killperc.gif'></td>
		<td align='left'><span class='$ColClass'>Achieve $KillsPercentMedal % of kills. ($KillsPercent %)</span></td>
		</tr>"; $opacity = ""; $countAchievements++;
		
		//TOTAL GAMES	
		if ($totgames2 < $GamesMedal ) {$opacity = " class='alpha' "; $ColClass = 'NotAchieved';} 
		else {$ColClass = 'Achieved'; $TotalMedals++; }
		$buildAchievements .= "<tr class='row'>
		<td width='72px' align='left'><img $opacity alt='' width='48px' border=0 src='./img/achievements/games.png'></td>
		<td align='left'><span class='$ColClass'>Play $GamesMedal games. ($totgames2 games)</span></td>
		</tr>"; $opacity = ""; $countAchievements++;
		
		//WINS
		if ($wins < $WinsMedal ) {$opacity = " class='alpha' "; $ColClass = 'NotAchieved';} 
		else {$ColClass = 'Achieved'; $TotalMedals++; }
		$buildAchievements .= "<tr class='row'>
		<td width='72px' align='left'><img $opacity alt='' width='48px' border=0 src='./img/achievements/wins.png'></td>
		<td align='left'><span class='$ColClass'>Win $WinsMedal games. ($wins wins)</span></td>
		</tr>"; $opacity = ""; $countAchievements++;
		
		//CREEPS
		if ($creepkills2 < $CreepsMedal ) {$opacity = " class='alpha' "; $ColClass = 'NotAchieved';} 
		else {$ColClass = 'Achieved'; $TotalMedals++; }
		$buildAchievements .= "<tr class='row'>
		<td width='72px' align='left'><img $opacity alt='' width='48px' border=0 src='./img/achievements/creeps.gif'></td>
		<td align='left'><span class='$ColClass'>Kill $CreepsMedal creeps. ($creepkills2 creeps)</span></td>
		</tr>"; $opacity = ""; $countAchievements++;
		
		//DENIES
		if ($creepdenies2 < $DeniesMedal ) {$opacity = " class='alpha' "; $ColClass = 'NotAchieved';} 
		else {$ColClass = 'Achieved'; $TotalMedals++; }
		$buildAchievements .= "<tr class='row'>
		<td width='72px' align='left'><img $opacity alt='' width='48px' border=0 src='./img/achievements/denies.gif'></td>
		<td align='left'><span class='$ColClass'>Deny $DeniesMedal creeps. ($creepdenies2 denies)</span></td>
		</tr>"; $opacity = ""; $countAchievements++;
		
		//TOWERS
		if ($towerkills2 < $TowersMedal ) {$opacity = " class='alpha' "; $ColClass = 'NotAchieved';} 
		else {$ColClass = 'Achieved'; $TotalMedals++; }
		$buildAchievements .= "<tr class='row'>
		<td width='72px' align='left'><img $opacity alt='' width='48px' border=0 src='./img/achievements/towers.png'></td>
		<td align='left'><span class='$ColClass'>Destroy $TowersMedal towers. ($towerkills2 towers)</span></td>
		</tr>"; $opacity = ""; $countAchievements++;
		
		//COURIERS
		if ($courierkills2 < $CouriersMedal ) {$opacity = " class='alpha' "; $ColClass = 'NotAchieved';} 
		else {$ColClass = 'Achieved'; $TotalMedals++; }
		$buildAchievements .= "<tr class='row'>
		<td width='72px' align='left'><img $opacity alt='' width='48px' border=0 src='./img/achievements/couriers.gif'></td>
		<td align='left'><span class='$ColClass'>Kill $CouriersMedal enemy couriers. ($courierkills2 couriers)</span></td>
		</tr>"; $opacity = ""; $countAchievements++;
		
		//NEUTRALS
		if ($neutralkills2 < $NeutralsMedal ) {$opacity = " class='alpha' "; $ColClass = 'NotAchieved';} 
		else {$ColClass = 'Achieved'; $TotalMedals++; }
		$buildAchievements .= "<tr class='row'>
		<td width='72px' align='left'><img $opacity alt='' width='48px' border=0 src='./img/achievements/neutrals.png'></td>
		<td align='left'><span class='$ColClass'>Kill $NeutralsMedal neutrals. ($neutralkills2 neutrals)</span></td>
		</tr>"; $opacity = ""; $countAchievements++;
		
		//PLAY DURATION
		if ($totalHours < $PlayDurationMedal ) {$opacity = " class='alpha' "; $ColClass = 'NotAchieved';} 
		else {$ColClass = 'Achieved'; $TotalMedals++; }
		$buildAchievements .= "<tr class='row'>
		<td width='72px' align='left'><img $opacity alt='' width='48px' border=0 src='./img/achievements/play.gif'></td>
		<td align='left'><span class='$ColClass'>Play at least $PlayDurationMedal hours. ($totalHours h)</span></td>
		</tr>"; $opacity = ""; $countAchievements++;

		$buildAchievements .= "<tr>
		<td width='72px' align='left'></td>
		<td align='left'>Total achievements: $TotalMedals of $countAchievements</td>
		</tr>";

		
		$buildAchievements .= "</table></div><br>";

		echo $buildAchievements;

	
	
	?>