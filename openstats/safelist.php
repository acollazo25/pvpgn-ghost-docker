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
 //Safelist based on XanGregor Code: http://www.codelain.com/forum/index.php?topic=14297.msg105485#msg105485
 
 include('header.php');
 
 if ($enableSafeListPage == 1)
 {
 $pageTitle = "$lang[site_name] | $lang[safelist]";
 $sql = "SELECT COUNT(*) FROM safelist LIMIT 1";
 $result = $db->query($sql);
 $r = $db->fetch_row($result);
 $numrows = $r[0];

   $result_per_page = $bans_per_page;
   include('pagination.php');
   
    $order = 'id';
 
 if (isset($_GET['order']))
  {
  if ($_GET['order'] == 'name') {$order = ' LOWER(name) ';}
  if ($_GET['order'] == 'server') {$order = ' server ';}
  if ($_GET['order'] == 'voucher') {$order = ' LOWER(voucher) ';}
  if ($_GET['order'] == 'id') {$order = ' id ';}
  }
  
  $sort = 'DESC';
  if (isset($_GET['sort']) AND $_GET['sort'] == 'desc')
  {$sort = 'asc'; $sortdb = 'DESC';} else {$sort = 'desc'; $sortdb = 'ASC';}
   
  $sql = "
  SELECT * FROM safelist 
  ORDER BY 
  $order $sortdb LIMIT $offset, $rowsperpage";
   
   $result = $db->query($sql);
   echo "<br><div align='center'>
   <table><tr><td align='center'><h1>$lang[safelist]</h1></td></tr></table>
   <table class='tableItem'> 
  <tr>
   <th><div align='center'><a href='{$_SERVER['PHP_SELF']}?order=id&sort=$sort'>$lang[id]</a></div></th>
  <th><div align='left'><a href='{$_SERVER['PHP_SELF']}?order=name&sort=$sort'>$lang[name]</a></div></th>
  <th><div align='left'><a href='{$_SERVER['PHP_SELF']}?order=voucher&sort=$sort'>$lang[voucher]</a></div></th>
  <th><div align='left'><a href='{$_SERVER['PHP_SELF']}?order=server&sort=$sort'>$lang[admin_server]</a></div></th>
  </tr>";
  
  while ($list = $db->fetch_array($result,'assoc')) {
  $name = trim($list["name"]);
  $LinkName = strtolower(trim($list["name"]));
  $voucher = trim($list["voucher"]);
  $server = trim($list["server"]);
  $ID = $list["id"];
  ?>
  <tr>
  <td align="center"><?=$ID?></td>
  <td width="200px"><a href="user.php?u=<?=$LinkName?>"><?=$name?></a></td>
  <td><?=$voucher?></td>
  <td><?=$server?></td>
  </tr>

  <?
  }
  echo "</table></div>";
  
  include('pagination.php');
  
  echo "<br>";
  include('footer.php');
  $pageContents = ob_get_contents();
  ob_end_clean();
  echo str_replace('<!--TITLE-->', $pageTitle, $pageContents);
  //Cache this page
  if ($cachePages == '1')
  file_put_contents($CacheTopPage, str_replace("<!--TITLE-->",$pageTitle,$pageContents));
  }
 ?>