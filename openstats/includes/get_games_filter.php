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


  $year = date("Y");
  $month = date("n"); 
  $day_ = date("j"); 
  //$month  = "6"; $year = "2009"; $day_ = "26";
  
  //$day_stats = " AND $sqlDay= '$day_' ";
  $day_stats = "";
   
  $sqlYear="YEAR(datetime)";
  $sqlMonth="MONTH(datetime)";
  $sqlMonthName="MONTHNAME(datetime)";
  $sqlDay="DAYOFMONTH(datetime)";
  
    if (isset($_POST["days"]) AND $_POST["days"]!=0)
  {$day_ = safeEscape($_POST["days"]);
  $day_stats = " AND $sqlDay = '$day_' ";
  } else {$day_ = "";}
 
  
  $sql = "SELECT $sqlYear as y, $sqlMonth as m, $sqlMonthName as mn 
  FROM games 
  GROUP BY $sqlYear 
  ORDER BY $sqlYear DESC";
  
  $result = $db->query($sql);
  echo "<form name='myform' method='post' action='games.php'>";
  
  
  ///////// YEARS
  $buildYears = "<select id='yearsid' name='years'>";
  
   while ($list = $db->fetch_array($result,'assoc')) 
   {
   $select = "";
   if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   $year=safeEscape($_POST["years"]); 
   $month=safeEscape($_POST["months"]); 
    if ($_POST["years"] == $list["y"]) {$select = "selected";} else {$select = "";}   
	  }
      $buildYears .= "<option $select value='$list[y]'>$list[y]</option>";
   }
      $buildYears .= "</select>";

  
  ///////// MONTHS
  $buildMonths = "<select id='monthid' name='months'>";
  for ( $counter = 1; $counter <= 12; $counter += 1) {
  $m = date("n");
  if ($m == $counter) {$select = "selected";} else $select = "";
  
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      if ($_POST["months"] == $counter) {$select = "selected";} else {$select = "";}
  }
  
  $MonthName = getMonthName($counter,$lang["jan"],$lang["feb"],$lang["mar"],$lang["apr"],$lang["may"],$lang["jun"],$lang["jul"],$lang["aug"],$lang["sep"],$lang["oct"],$lang["nov"],$lang["dec"]);
  
  $buildMonths .= "<option $select value='$counter'>$MonthName</option>";
  }
  $buildMonths .= "</select>";

  
  //DAYS 
  if ($DaysOnMonthlyStats == 1)
  {
  $getDays = getDays($month);
  $buildDay = "<select id='dayid' name='days'><option value='0'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>";
  for ( $counter = 1; $counter <= $getDays; $counter += 1) {
  if (isset($_POST["days"]) AND $_POST["days"]!=0 and ($_POST["days"] == $counter)) 
  {$selectd = "selected";} else {$selectd = "";}
  $buildDay .= "<option $selectd value='$counter'>&nbsp;$counter</option>";
  }
  $buildDay .= "</select>";
  } else {$buildDay = "";}

  if (isset($_POST["days"]) AND $_POST["days"]!=0) {$DisplayDay = $day_;} else {$DisplayDay = ""; echo $day_;}
   
   $DisplayMonthName = getMonthName($month,$lang["jan"],$lang["feb"],$lang["mar"],$lang["apr"],$lang["may"],$lang["jun"],$lang["jul"],$lang["aug"],$lang["sep"],$lang["oct"],$lang["nov"],$lang["dec"]);
   
  echo "<table><tr><th>
  <div align='center'>$buildDay $buildMonths $buildYears 
  <input type='submit' class='inputButton' value='$lang[submit]' /> </div>
  </th></tr>
  <tr>
  
  </tr>
  </table>";
  
  echo "</form>";
  
   $post_y = ""; $post_m = ""; $post_d = "";
  
  if (isset($_POST["years"])) {$post_y = $_POST["years"];}
  if (isset($_POST["months"])) {$post_m = $_POST["months"];}
  if (isset($_POST["days"])) {$post_d = $_POST["days"];}
  ?>