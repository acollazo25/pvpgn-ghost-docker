<?PHP

   if (isset($_GET["replayLoc"]) ) 
   {$replayloc = "../".EscapeStr($_GET["replayLoc"]); }

   if(file_exists("$replayloc"))
   {
   echo "<div class='hid' align='center'>
   <table class='tableA'><tr><td align='center'><b>Game Log:</b></td></tr>
   <tr>
   <td colspan='13'>
   <table width='80%'><tr>
   <th><div align='right'>Time</div></th>
   <th style='width:100px;padding-right:4px;'><div align='right'>Player &nbsp;</div></th>
   <th></th></tr>";

	require('chat.php');
	$replay = new replay($replayloc);
	if (!isset($error)) {
	
	///////////////////     COLORS            ////////////
	
			$firstBlood = true;
		$i = 1;
		foreach ($replay->teams as $team=>$players) {
			if ($team != 12) {	
				foreach ($players as $player) {          
					// remember there's no color in tournament replays from battle.net website
					if ($player['color']) {
						//echo('<span class="'.$player['color'].'">'.$player['color'].'</span>');
						// since version 2.0 of the parser there's no players array so
						// we have to gather colors and names earlier as it will be harder later ;)
						$colors[$player['player_id']] = $player['color'];
						$names[$player['player_id']] = $player['name'];
					}
				}
				$i++;
			}
		}
		for($i = 0; $i <= 14; $i++)
		{
			switch($i) {
			
			case 0:
				$slotname[$i] = 'The Sentinel';
				$slotcolor[$i] = 'sentinel';
				break;
			case 1:
				for($n = 0; $n < 12; $n++)
				{
					if(isset($colors[$n]))
					{
						if($colors[$n] == 'blue')
						{
							$playerID = $n;
						}
					}
				}				
				$slotname[$i] = $names[$playerID];
				$slotcolor[$i] = $colors[$playerID];
				break;
			case 2:
				for($n = 0; $n < 12; $n++)
				{
					if(isset($colors[$n]))
					{
						if($colors[$n] == 'teal')
						{
							$playerID = $n;
						}
					}
				}				
				$slotname[$i] = $names[$playerID];
				$slotcolor[$i] = $colors[$playerID];
				break;
			case 3:
				for($n = 0; $n < 12; $n++)
				{
					if(isset($colors[$n]))
					{
						if($colors[$n] == 'purple')
						{
							$playerID = $n;
						}
					}
				}				
				$slotname[$i] = $names[$playerID];
				$slotcolor[$i] = $colors[$playerID];
				break;
			case 4:
				for($n = 0; $n < 12; $n++)
				{
					if(isset($colors[$n]))
					{
						if($colors[$n] == 'yellow')
						{
							$playerID = $n;
						}
					}
				}				
				$slotname[$i] = $names[$playerID];
				$slotcolor[$i] = $colors[$playerID];
				break;
			case 5:
				for($n = 0; $n < 12; $n++)
				{
					if(isset($colors[$n]))
					{
						if($colors[$n] == 'orange')
						{
							$playerID = $n;
						}
					}
				}				
				$slotname[$i] = $names[$playerID];
				$slotcolor[$i] = $colors[$playerID];
				break;
			case 6:
				$slotname[$i] = 'The Scourge';
				$slotcolor[$i] = 'scourge';
				break;
			case 7:
				for($n = 0; $n < 12; $n++)
				{
					if(isset($colors[$n]))
					{
						if($colors[$n] == 'pink')
						{
							$playerID = $n;
						}
					}
				}				
				$slotname[$i] = $names[$playerID];
				$slotcolor[$i] = $colors[$playerID];
				break;
			case 8:
				for($n = 0; $n < 12; $n++)
				{
					if(isset($colors[$n]))
					{
						if($colors[$n] == 'gray')
						{
							$playerID = $n;
						}
					}
				}				
				$slotname[$i] = $names[$playerID];
				$slotcolor[$i] = $colors[$playerID];
				break;
			case 9:
				for($n = 0; $n < 12; $n++)
				{
					if(isset($colors[$n]))
					{
						if($colors[$n] == 'light-blue')
						{
							$playerID = $n;
						}
					}
				}				
				$slotname[$i] = $names[$playerID];
				$slotcolor[$i] = $colors[$playerID];
				break;
			case 10:
				for($n = 0; $n < 12; $n++)
				{
					if(isset($colors[$n]))
					{
						if($colors[$n] == 'dark-green')
						{
							$playerID = $n;
						}
					}
				}				
				$slotname[$i] = $names[$playerID];
				$slotcolor[$i] = $colors[$playerID];
				break;
			case 11:
				for($n = 0; $n < 12; $n++)
				{
					if(isset($colors[$n]))
					{
						if($colors[$n] == 'brown')
						{
							$playerID = $n;
						}
					}
				}				
				$slotname[$i] = $names[$playerID];
				$slotcolor[$i] = $colors[$playerID];
				break;
			case 12:		
				$slotname[$i] = 'Neutral Creeps';
				$slotcolor[$i] = 'system';
				break;	
			case 13:		
				$slotname[$i] = 'The Sentinel';
				$slotcolor[$i] = 'sentinel';
				break;
			case 14:
				$slotname[$i] = 'The Scourge';
				$slotcolor[$i] = 'scourge';
				break;
			}
		}
		$colors[''] = 'system';
		$names[''] = 'System';
		
         //////////////       COLORS              /////////////
		 	$str = "<div class='hid' align='center'>
   <table class='tableA'><tr><td align='center'><b>Game Log:</b></td></tr>
   <tr>
   <td colspan='13'>
   <table width='80%'><tr>
   <th><div align='right'>Time</div></th>
   <th style='width:100px;padding-right:4px;'><div align='right'>Player &nbsp;</div></th>
   <th></th></tr>";
			
		if ($replay->chat) {
			foreach ($replay->chat as $content) {
				$time = $content['time'];
				$mode = $content['mode'];
				$playerID = $content['player_id'];
				$playerName = $names[$playerID];
				$playerColor = $colors[$playerID];
				$text = convEnt2($content['text']);
				$ply = "<span class='GameSystem'><i>(System)</i></span>";
			
				
	if($mode == 'All' || getTeam($playerColor) == 1) 
	{$ply = "<a href='user.php?u=$playerName'><span class='$playerColor'>$playerName</span></a>";} 
	
	if($mode == 'All' || getTeam($playerColor) == 2) 
	{$ply = "<a href='user.php?u=$playerName'><span class='$playerColor'>$playerName</span></a>";}

	$timeSec = secondsToTime($time/1000);
				
				echo "<tr class='row'><td align='right' width='82px'>$timeSec</td>
				<td style='width:100px;padding-right:4px;' align='right'>$ply: </td>";
				$str .= "<tr class='row'><td align='right' width='82px'>$timeSec</td>
				<td style='width:100px;padding-right:4px;' align='right'>$ply: </td>";
				
				if($mode == 'All') 
				{echo "<td class='all'>[All] $text</td>"; $str .= "<td class='all'>[All] $text</td>";}
				
				else if($mode == 'System') {
					 if($content['type'] == 'Start') 
					 {echo "<td class='GameSystem'>$text</td>"; $str.= "<td class='GameSystem'>$text</td>";}
					 
					        else if($content['type'] == 'Hero')
							{
								$victim = trim($content['victim']);
								$killer = $content['killer'];
	if($firstBlood)
	{
		if($content['killer'] < 12)
		{
		echo "<td class='GameSystem'>
		<div style='background-color:#580202;'><span class='$slotcolor[$killer]'>$slotname[$killer]</span>
		$text<span class='$slotcolor[$victim]'>$slotname[$victim]</span> <b>for first blood</b></div></td>";
		$firstBlood = false;
		$str .= "<td class='GameSystem'>
		<div style='background-color:#580202;'><span class='$slotcolor[$killer]'>$slotname[$killer]</span>
		$text<span class='$slotcolor[$victim]'>$slotname[$victim]</span> <b>for first blood</b></div></td>";
		}
		else
		{
		echo "<td class='GameSystem'>
		<span class='$slotcolor[$killer]'>$slotname[$killer]</span> 
		$text<span class='$slotcolor[$victim]'> $slotname[$victim]</span></td>";
		$str .= "<td class='GameSystem'>
		<span class='$slotcolor[$killer]'>$slotname[$killer]</span> 
		$text<span class='$slotcolor[$victim]'> $slotname[$victim]</span></td>";
		}
	 }
	  else
	  {
	  if($victim == $killer)
	  {
	  echo "<td class='GameSystem'><span class='$slotcolor[$killer]'>$slotname[$killer]</span> has killed himself!</td>";
	  $str .= "<td class='GameSystem'><span class='$slotcolor[$killer]'>$slotname[$killer]</span> has killed himself!</td>";
	  }
	  else if(($victim < 6 && $killer < 6) || ($victim > 6 && $killer > 6) && $killer <= 11)
	  {
	  echo "<td class='GameSystem'>
	  <span class='$slotcolor[$killer]'>$slotname[$killer]</span> denied his teammate <span class='$slotcolor[$victim]'>$slotname[$victim]</span></td>";
	  $str .= "<td class='GameSystem'>
	  <span class='$slotcolor[$killer]'>$slotname[$killer]</span> denied his teammate <span class='$slotcolor[$victim]'>$slotname[$victim]</span></td>";
	  }
	  else
	  {
	  echo "<td class='GameSystem'><span class='$slotcolor[$killer]'>$slotname[$killer]</span> $text<span class='$slotcolor[$victim]'> $slotname[$victim]</span></td>";
	  $str .= "<td class='GameSystem'><span class='$slotcolor[$killer]'>$slotname[$killer]</span> $text<span class='$slotcolor[$victim]'> $slotname[$victim]</span></td>";
	  }
	}
  }
	  else if($content['type'] == 'Courier')
	  {
	  $victim = trim($content['victim']);
	  $killer = $content['killer'];
	  echo "<td class='GameSystem'><span class='$slotcolor[$victim]'>$slotname[$victim] </span>$text<span class='$slotcolor[$killer]'> $slotname[$killer]</span></td>";
	  $str .= "<td class='GameSystem'><span class='$slotcolor[$victim]'>$slotname[$victim] </span>$text<span class='$slotcolor[$killer]'> $slotname[$killer]</span></td>";
	  }
	  else if($content['type'] == 'Tower')
	  {
	  $killer = $content['killer'];
								
	  echo "<td class='GameSystem'><span class='$slotcolor[$killer]'>$slotname[$killer]</span>$text $content[side]  level  $content[level]<span class=".strtolower($content['team'])."> $content[team] </span> tower</td>";
	  $str .= "<td class='GameSystem'><span class='$slotcolor[$killer]'>$slotname[$killer]</span>$text $content[side]  level  $content[level]<span class=".strtolower($content['team'])."> $content[team] </span> tower</td>";
	  }
	  else if($content['type'] == 'Rax')
	  {
	  $killer = $content['killer'];
	  echo "<td class='GameSystem'><span class='$slotcolor[$killer]'>$slotname[$killer]</span>$text $content[side] $content[raxtype]<span class='".strtolower($content['team'])."'> $content[team]</span> barracks</td>";
	  $str .= "<td class='GameSystem'><span class='$slotcolor[$killer]'>$slotname[$killer]</span>$text $content[side] $content[raxtype]<span class='".strtolower($content['team'])."'> $content[team]</span> barracks</td>";
	  }
	  else if($content['type'] == 'Throne') {echo "<td class='GameSystem'>$text</td>"; $str .= "<td class='GameSystem'>$text</td>";}
	  else if($content['type'] == 'Tree')   {echo "<td class='GameSystem'>$text</td>"; $str .= "<td class='GameSystem'>$text</td>";}
	  }
	 else
	{
	if ($mode == 9)
	  {echo "<td align='left' class='scourge'><span style='color:#00A404;'>[Priv] $text</span></td>";
	  $str .= "<td align='left' class='scourge'><span style='color:#00A404;'>[Priv] $text</span></td>";}
	  
       else
       {
	   if(getTeam($playerColor) == 1) {echo "<td align='left' class='sentinel'><span style='color:#B32704;'>[Allies] $text</span></td>"; 
	   $str .= "<td align='left' class='sentinel'><span style='color:#B32704;'>[Allies] $text</span></td>";}
	   else
	   { echo "<td align='left' class='scourge'><span style='color:#00A404;'>[Allies] $text</span></td>"; 
	   $str .= "<td align='left' class='scourge'><span style='color:#00A404;'>[Allies] $text</span></td>";}}
       }
	}

			}
		}	
	}
	echo "</table>
	</td>
	</tr>
	     </table></div>";
		 
		$str .=  "</table></td></tr></table></div>";
		 //STORE PARSED DATA INTO ARRAY. WITH textarea YOU CAN OUTPUT HTML CODE FOR GAME LOG.
		
		 $htmlOutput = $str;
		 
		 $permLink = "http://".$_SERVER["SERVER_NAME"].dirname($_SERVER["PHP_SELF"]);
		 /*
		 $parts=explode(",", $str);
		 
		 for($i=0; $i <= count($parts)-1; ++$i) {
		 echo "<div align='left'>$parts[$i]</div>";
		 $fix = str_replace("<a href='user.php?u=","<a href='$permLink/user.php?u=",$parts[$i]);
		 $fix = str_replace("<span class='","<span style='color:",$fix);
		 $htmlOutput .= "<div style=\"text-align: left;\">$fix</div>\n";
		 }
		 /*
		  echo "<div align='left'><textarea style='width:400px;height:140px;'>".$htmlOutput."</textarea></div>";
		 */
		 	
	//Save parsed data into .txt
	if ($SmartParsing == 1 AND !file_exists($txtReplay) AND is_writable($replayLocation))
	{
	$htmlOutput = str_replace("<a href='user.php?u=","<a href='$permLink/user.php?u=",$htmlOutput);
	$fh = fopen($txtReplay, 'w');
	fwrite($fh, $htmlOutput);
    fclose($fh);

	}


?>		