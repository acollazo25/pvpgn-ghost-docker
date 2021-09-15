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
*    along with DOTA OPEN STATS.  If not, see <http://www.gnu.org/licenses/>
*
-->
**********************************************/


  include('header.php');
  
  $pageTitle = "$lang[site_name] | $lang[admins]";

  $sql = "
  SELECT Distinct(name), server FROM admins 
  ORDER BY 
  LOWER(name)";
  
  $result = $db->query($sql)  or die(mysql_error());
  $total_admins = $db->num_rows($result);
  
  echo "<div align='center'><table class='tableC'><tr>
  <td style='height:24px;' width='30%'>$lang[head_admin]  <span style='color:red'>$head_admin</span></td>
  <td width='70%'> $lang[tot_admin] $total_admins</td></tr></table></div>";
  echo "
  <div align='center'>
  <table class='tableA'> 
  <th class='padLeft'>$lang[admin_name]</th>
  <th>$lang[admin_server]</th>
  </tr>";
  
  while ($list = $db->fetch_array($result,'assoc')) {
  $name = strtolower($list['name']);
  $link = strtolower($list['name']);
  
  if (strtolower($name)== strtolower($head_admin) )
  {$name = "<span style='color:#00C412'>$name</span>";}
  
  echo "<tr class='row'>
  <td class='tableD' width='30%'><div class='padLeft' align='left'><a href='user.php?u=$link'>$name</a></td>
  <td width='70%'><div align='left'>$list[server]</td>
  </tr>
  ";
  
  
  }
  echo "</table></div>";
  
  echo "<div align='center'><table style='width:95%;margin:8px;'>
  <th></th><th>Game Commands:</th></tr>
  <td class='padLeft' width='30%'><div align='left'>!stats [name]</td>
  <td width='70%'><div align='left'>Display basic player statistics, optionally add [name] to display statistics for another player</td></tr>
  
  <tr>
  <td class='padLeft' width='30%'><div align='left'>!statsdota [name]</td>
  <td width='70%'><div align='left'>Display DotA player statistics, optionally add [name] to display statistics for another player</td></tr>
  
  <th></th><th>Admin Commands:</th></tr>
  
  <tr>
  <td class='padLeft' width='30%'><div align='left'>!priv [name]</td>
  <td width='70%'><div align='left'>Host a private game</td></tr>
  
  <tr>
  <td class='padLeft' width='30%'><div align='left'>!pub [name]</td>
  <td width='70%'><div align='left'>Host a public game</td></tr>
  
  <tr>
  <td class='padLeft' width='30%'><div align='left'>!unhost</td>
  <td width='70%'><div align='left'>Unhost the current game</td></tr>
  
  <tr>
  <td class='padLeft' width='30%'><div align='left'>!swap [s1] [s2]</td>
  <td width='70%'><div align='left'>Swap slots</td></tr>
  
  <tr>
  <td class='padLeft' width='30%'><div align='left'>!start [force]</td>
  <td width='70%'><div align='left'>Start game, optionally add [force] to skip checks</td></tr>
  
  <tr>
  <td class='padLeft' width='30%'><div align='left'>!ping [number]</td>
  <td width='70%'><div align='left'>Ping players, optionally add [number] to kick players with ping above [number]</td></tr>
  
  <tr>
  <td class='padLeft' width='30%'><div align='left'>!close [number]</td>
  <td width='70%'><div align='left'>Close slot</td></tr>
  
  <tr>
  <td class='padLeft' width='30%'><div align='left'>!Open [number]</td>
  <td width='70%'><div align='left'>Open slot</td></tr>
  
  <tr>
  <td class='padLeft' width='30%'><div align='left'>!ban [name] [reason]</td>
  <td width='70%'><div align='left'>Permabans [name] for [reason].</td></tr>
  
  <tr>
  <td class='padLeft' width='30%'><div align='left'>!kick [partial name]</td>
  <td width='70%'><div align='left'>Kick [partial name] from game.</td></tr>
  
  
  </table></div>
  ";

  include('footer.php');
  $pageContents = ob_get_contents();
  ob_end_clean();
  echo str_replace('<!--TITLE-->', $pageTitle, $pageContents);
  //Cache this page
  if ($cachePages == '1')
  file_put_contents($CacheTopPage, str_replace("<!--TITLE-->",$pageTitle,$pageContents));
  ?>