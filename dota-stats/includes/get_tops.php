<?PHP

    if (isset($_GET["alltimestats"]))
	{
     require_once ('../config.php');
	 require_once('../includes/class.database.php');
	 require_once('../includes/common.php');
	 require_once('../includes/db_connect.php');
	 require_once("../lang/$default_language.php");
     		}

$sqlKill = "SELECT 
        original as topHero, 
		description as topHeroName, 
		kills as topValue, 
		b.name as topUser, 
		a.gameid as topGame
		FROM dotaplayers AS a 
		LEFT JOIN gameplayers AS b ON b.gameid = a.gameid 
		AND a.colour = b.colour 
		LEFT JOIN games as c on a.gameid = c.id
		LEFT JOIN heroes as d on hero = heroid 
		LEFT JOIN bans on b.name = bans.name 
		WHERE bans.name is null 
		ORDER BY topValue DESC, a.id ASC 
		LIMIT $top_stats";
		
		$sqlAssists = "SELECT 
		original as topHero, 
		description as topHeroName, 
		assists as topValue, 
		b.name as topUser, 
		a.gameid as topGame
		FROM dotaplayers AS a 
		LEFT JOIN gameplayers AS b ON b.gameid = a.gameid and a.colour = b.colour 
		LEFT JOIN games as c on a.gameid = c.id
		LEFT JOIN heroes as d on hero = heroid
		LEFT JOIN bans on b.name = bans.name 
		WHERE bans.name is null 
		ORDER BY topValue DESC, a.id ASC 
		LIMIT $top_stats";
		
		$sqlDeaths = "SELECT 
		original as topHero, 
		description as topHeroName, 
		deaths as topValue, 
		b.name as topUser, 
		a.gameid as topGame
		FROM dotaplayers AS a 
		LEFT JOIN gameplayers AS b ON b.gameid = a.gameid and a.colour = b.colour 
		LEFT JOIN games as c on a.gameid = c.id
		LEFT JOIN heroes as d on hero = heroid 
		LEFT JOIN bans on b.name = bans.name 
		WHERE bans.name is null 
		ORDER BY topValue DESC, a.id ASC LIMIT $top_stats";
		
		$sqlCreeps = "SELECT 
		original as topHero, 
		description as topHeroName, 
		creepkills as topValue, 
		b.name as topUser, 
		a.gameid as topGame
		FROM dotaplayers AS a 
		LEFT JOIN gameplayers AS b ON b.gameid = a.gameid and a.colour = b.colour 
		LEFT JOIN games as c on a.gameid = c.id
		LEFT JOIN heroes as d on hero = heroid 
		LEFT JOIN bans on b.name = bans.name 
		WHERE bans.name is null 
		ORDER BY topValue DESC, a.id ASC 
		LIMIT $top_stats";
		
		$sqlDenies = "SELECT 
		original as topHero, 
		description as topHeroName, 
		creepdenies as topValue, 
		b.name as topUser, 
		a.gameid as topGame
		FROM dotaplayers AS a 
		LEFT JOIN gameplayers AS b ON b.gameid = a.gameid and a.colour = b.colour 
		LEFT JOIN games as c on a.gameid = c.id
		LEFT JOIN heroes as d on hero = heroid 
		LEFT JOIN bans on b.name = bans.name 
		WHERE bans.name is null 
		ORDER BY topValue DESC, a.id ASC 
		LIMIT $top_stats";
		
		echo "<br/><table class='tableA'><tr><td><div align='center'>$lang[all_time_stats]</div></td></tr></table>";	
		
		echo "<table class='tableA'><tr>
		<th style='padding-left:8px;'>$lang[top_kills]</th>
		<th>$lang[top_assists]</th>
		<th>$lang[top_deaths]</th>
		<th>$lang[top_creeps]</th>
		<th>$lang[top_denies]</th>
		</tr><tr><td width='20%' valign='top'>
		         <table>";
		
		$result = $db->query($sqlKill);
		 while ($list = $db->fetch_array($result,'assoc')) {
		 $himg = strtoupper($list["topHero"]);
		 if ($list['topHero']=="") {$himg = "blank";}
		 
		 echo "
		 <tr class='row'><td width='35px'><a href='hero.php?hero=$list[topHero]' title='$list[topHeroName]'>
		 <img style='vertical-align: middle;' width='32px' height='32px' src='img/heroes/$himg.gif' border=0/></a></td>
		 <td width='32px' align='left'>(<a href='game.php?gameid=$list[topGame]'>$list[topValue]</a>) </td>
		 
		 <td align='left'><a href='user.php?u=$list[topUser]' title='$list[topUser]'>$list[topUser]</a></td>
		 </tr>
		 ";
		 }
		 
		 echo "</table></td><td width='20%' valign='top'>
		         <table>";
		 
		 
		 $result = $db->query($sqlAssists);
		 while ($list = $db->fetch_array($result,'assoc')) {
		 $himg = strtoupper($list["topHero"]);
		 if ($list['topHero']=="") {$himg = "blank";}
		 echo "
		 <tr class='row'><td width='35px'><a href='hero.php?hero=$list[topHero]' title='$list[topHeroName]'>
		 <img style='vertical-align: middle;' width='32px' height='32px' src='img/heroes/$himg.gif' border=0/></a></td>
		 <td width='32px' align='left'>(<a href='game.php?gameid=$list[topGame]'>$list[topValue]</a>) </td>
		 
		 <td align='left'><a href='user.php?u=$list[topUser]' title='$list[topUser]'>$list[topUser]</a></td>
		 </tr>
		 ";
		 }
		 
		  echo "</table></td><td width='20%' valign='top'>
		         <table>";
		 
		 $result = $db->query($sqlDeaths);
		 while ($list = $db->fetch_array($result,'assoc')) {
		 $himg = strtoupper($list["topHero"]);
		 if ($list['topHero']=="") {$himg = "blank";}
		 echo "
		 <tr class='row'><td width='35px'><a href='hero.php?hero=$list[topHero]' title='$list[topHeroName]'>
		 <img style='vertical-align: middle;' width='32px' height='32px' src='img/heroes/$himg.gif' border=0/></a></td>
		 <td width='32px' align='left'>(<a href='game.php?gameid=$list[topGame]'>$list[topValue]</a>) </td>
		 
		 <td align='left'><a href='user.php?u=$list[topUser]' title='$list[topUser]'>$list[topUser]</a></td>
		 </tr>
		 ";
		 }
		 
		 echo "</table></td><td width='20%' valign='top'>
		         <table>";
		 
		 $result = $db->query($sqlCreeps);
		 while ($list = $db->fetch_array($result,'assoc')) {
		 $himg = strtoupper($list["topHero"]);
		 if ($list['topHero']=="") {$himg = "blank";}
		 
		 echo "
		 <tr class='row'><td width='35px'><a href='hero.php?hero=$list[topHero]' title='$list[topHeroName]'>
		 <img style='vertical-align: middle;' width='32px' height='32px' src='img/heroes/$himg.gif' border=0/></a></td>
		 <td width='32px' align='left'>(<a href='game.php?gameid=$list[topGame]'>$list[topValue]</a>) </td>
		 
		 <td align='left'><a href='user.php?u=$list[topUser]' title='$list[topUser]'>$list[topUser]</a></td>
		 </tr>
		 ";
		 }
		 
		  echo "</table></td><td width='20%' valign='top'>
		         <table>";
		 
		 $result = $db->query($sqlDenies);
		 while ($list = $db->fetch_array($result,'assoc')) {
		 $himg = strtoupper($list["topHero"]);
		 if ($list['topHero']=="") {$himg = "blank";}
		 
		 echo "
		 <tr class='row'><td width='35px'><a href='hero.php?hero=$list[topHero]' title='$list[topHeroName]'>
		 <img style='vertical-align: middle;' width='32px' height='32px' src='img/heroes/$himg.gif' border=0/></a></td>
		 <td width='32px' align='left'>(<a href='game.php?gameid=$list[topGame]'>$list[topValue]</a>) </td>
		 
		 <td align='left'><a href='user.php?u=$list[topUser]' title='$list[topUser]'>$list[topUser]</a></td>
		 </tr>
		 ";
		 }
		 
		 
		echo "</table></td></tr></table>";
		
		?>