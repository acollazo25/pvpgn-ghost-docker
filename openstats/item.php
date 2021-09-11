<?php
  /*********************************************
   <!--
   *     DOTA OPENSTATS
   *
   *  Developers: Ivan.
   *  Contact: ivan.anta@gmail.com - Ivan
   *
   *
   *  Please see http://openstats.iz.rs
   *  and post your webpage there, so I know who's using it.
   *
   *  Files downloaded from http://openstats.iz.rs
   *
   *  Copyright (C) 2010  Ivan
   *
   *
   *  This file is part of DOTA OPENSTATS.
   *
   *
   *   DOTA OPENSTATS is free software: you can redistribute it and/or modify
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

  $itemid = safeEscape($_GET["item"]);
  
  $sql = "SELECT * FROM items WHERE LOWER(itemid) = LOWER('$itemid') LIMIT 1";
  
  $result = $db->query($sql);
  if ($db->num_rows($result) >= 1) {
      $list = $db->fetch_array($result, 'assoc');
      $itemid = $list["itemid"];

      $itemName = $list["name"];
	  $itemName2 = $list["shortname"];
      $itemInfo = $list["item_info"];
      $itemInfo = str_replace("\n\n", "<br>", $itemInfo);
      $itemInfo = str_replace("\n", "<br>", $itemInfo);
      $itemInfo = str_replace("Cost:", "<img alt='' title='Cost' style='vertical-align:middle;' border='0' src='./img/coin.gif'>", $itemInfo);
      $itemIcon = $list["icon"];
      
      echo "<div align='center'><table class='tableHeroPageTop'><tr>
     <th><div align='center'>$itemName2 information</div></th></tr>
     <tr><td>
           <div align='center'><table class='tableItem'>
       <tr>
       <td align='left' class='ItemInfo'>
           <img border='0' style='vertical-align:middle;' alt='$itemName2' title='' src='./img/items/$itemIcon'> <b>$itemName2</b><br>
           $itemInfo</td></tr></table></div>
     </td>
     </tr></table></div>
     ";

      $pageTitle = "$lang[site_name] | $itemName2";
      if ($ShowItemsMostUsedByHero == 1) {
          $sql = getMostUsedHeroByItem("", $itemid, 8,$itemName );
          
          $result = $db->query($sql);
          if ($db->num_rows($result) >= 1) {
              echo "<div align='center'><table class='tableHeroPageTop'><tr>
       <th><div align='center'>$_lang[most_used] </div></th>
       </tr><td align='center' width='64px' class='padLeft'>";
              while ($row = $db->fetch_array($result, 'assoc')) {
                  $hero = strtoupper($row["hero"]);
                  $heroName = convEnt2($row["heroname"]);
                  $itemName = convEnt2($itemName);
				  $itemName2 = convEnt2($itemName2);
                  $totals = $row["total"];
          //if (!file_exists("./img/heroes/$hero.gif")) {$hero = ""; $heroName.=convEnt2(" ($row[heroid].gif)");}
				  
                  echo "<a onMouseout='hidetooltip()' onMouseover='tooltip(\"<b>$heroName</b> used <br>$itemName2<br><b>$totals x</b>\",130)' href='hero.php?hero=$hero'>
        <img width='48px' height='48px' border='0' 
      src='./img/heroes/$hero.gif'></a>";
              }
              echo "</td></tr></table></div><br>";
          }
      }
  } 

  include('footer.php');
      $pageContents = ob_get_contents(); 
	  ob_end_clean();
      echo str_replace('<!--TITLE-->', $pageTitle, $pageContents);
	  //Cache this page
  if ($cachePages == '1')
  file_put_contents($CacheTopPage, str_replace("<!--TITLE-->",$pageTitle,$pageContents));
?>