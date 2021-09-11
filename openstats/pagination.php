<?PHP
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


   // number of rows to show per page
   $ord= ""; $sorted = ""; $un = ""; $news = "";
   if (isset($_GET['order']) )
   {$ord = 'order='.safeEscape($_GET['order']).'&';}
   
   if (isset($_GET['sort']) )
   {$sorted = 'sort='.safeEscape($_GET['sort']).'&';}
   
   if (isset($_GET['u']) )
   {$un = 'u='.safeEscape($_GET['u']).'&';}
   
   if (isset($_GET['hero']) )
   {$herois = 'hero='.safeEscape($_GET['hero']).'&';}  else {$herois = "";}
   
   if (!isset($games)) {$games = $minGamesPlayed;}
   
   if (isset($_GET['gp']) )
   {$gplay = 'gp='.$games.'&';} else {$gplay = 'gp='.$minGamesPlayed.'&';}
   
   if (isset($_GET['news']) )
   {$news = 'news'.safeEscape($_GET['news']).'&';}
   
   if (isset($_GET['l']) )
   {$lett = 'l='.safeEscape($_GET['l']).'&';} else {$lett = "";}
   
      
   if (isset($_GET["y"])) {$pref_ydm = "y=".safeEscape($_GET['y'])."&";}
   else {$pref_ydm = "";}
   
   if (isset($_GET["m"])) {$pref_ydm .= "m=".safeEscape($_GET['m'])."&";}
   if (isset($_GET["d"])) {$pref_ydm .= "d=".safeEscape($_GET['d'])."&";}
   
   if (isset($_POST["years"])) {$pref_ydm = "y=".safeEscape($_POST['years'])."&";}
   
   if (isset($_POST["months"])) {$pref_ydm .= "m=".safeEscape($_POST['months'])."&";}
   if (isset($_POST["days"])) {$pref_ydm .= "d=".safeEscape($_POST['days'])."&";}
   
   
              $rowsperpage = $result_per_page;
              $totalpages = ceil($numrows / $rowsperpage);
              if (isset($_GET['page']) && is_numeric($_GET['page'])) {
                  $currentpage = (int)$_GET['page'];
              } else {
                  $currentpage = 1;
              }
              if ($currentpage > $totalpages) {
                  $currentpage = $totalpages;
              }
              if ($currentpage < 1) {
                  $currentpage = 1;
              }
              if ($totalpages <= 1) {
                  $totalpages = 1;
              }

              $offset = ($currentpage - 1) * $rowsperpage;
              if (isset($_GET['page']) AND is_numeric($_GET['page'])){
                          $current_page = safeEscape($_GET['page']);
                          }

                          if (!isset($current_page)) {
                              $current_page = 1;
                          }
              $range = $max_pagination_link;
              if ($range >= $totalpages) {
                  $range = $totalpages;
              }
			  
			  if ($current_page > $totalpages) {$current_page = $totalpages;}
			  
			  
              echo '<table><tr><td style="padding-right:24px;" align="right" class="pagination"> <b>'.$lang["page"].' ' . $current_page . ' '.$lang["of"].' ' . $totalpages . '</b> [' . $numrows . ' '.$lang["maches"].']  &nbsp; &nbsp;';
              if ($currentpage > 1) {
                  echo " <a title= '$lang[firstpage]' href='{$_SERVER['PHP_SELF']}?".$news.$herois.$gplay.$un.$ord.$sorted.$lett.$pref_ydm."page=1'><<</a> ";
                  $prevpage = $currentpage - 1;
                  echo " <a title='$lang[prevpage]' href='{$_SERVER['PHP_SELF']}?".$news.$herois.$gplay.$un.$ord.$sorted.$lett.$pref_ydm."page=$prevpage'><</a> ";
              }
              for ($x = ($currentpage - $range); $x < (($currentpage + $range) + 1); $x++) {
                  if (($x > 0) && ($x <= $totalpages)) {
                      if ($x == $currentpage) {
                          echo " [<b>$x</b>] ";
                      } else {
                          echo " <a title='$lang[gotopage] " . $x . "' href='{$_SERVER['PHP_SELF']}?".$news.$herois.$gplay.$un.$ord.$sorted.$lett.$pref_ydm."page=$x'>$x</a> ";
                      }
                  }
              }
              if ($currentpage != $totalpages) {
                  $nextpage = $currentpage + 1;
                  echo " <a title='$lang[nextpage] ' href='{$_SERVER['PHP_SELF']}?".$news.$herois.$gplay.$un.$ord.$sorted.$lett.$pref_ydm."page=$nextpage'>></a> ";
                  echo " <a title='$lang[gotolastpage]' href='{$_SERVER['PHP_SELF']}?".$news.$herois.$gplay.$un.$ord.$sorted.$lett.$pref_ydm."page=$totalpages'>>></a> ";
              }
              echo '</td></tr></table>';
			  
			  
			  ?>