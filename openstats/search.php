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

  include ('header.php');
  //require_once('./includes/AJAX.php');
  $pageTitle = "$lang[site_name] | $lang[search_player]";
  $pageContents = ob_get_contents();
  ob_end_clean();
  echo str_replace('<!--TITLE-->', $pageTitle, $pageContents);
  
	 echo '
	 <table><tr><td class="Search">'.$lang["search_players"].'</td></tr>
	 </table>
	 <div align="center">
	 <form name="myForm" method="post" action="">
	 <table class="tableSearchH" border="1">
	  <tr><th class="tableSearchR" width="200px"><b>'.$lang["search_player"].'</b></th></tr>
	  <tr>
	  <td align="left" >
	  <input class="inputSearch" size="30" maxlength="30" type="text" id="text1" size="42"/>
	  <input maxlength="42" type="hidden" value="includes/ajax_search.php?searchterm=" id="buildUrl2" size="42"/>
	  <input maxlength="42" type="hidden" id="text2"/>
	  <input maxlength="42" type="hidden" id="text3"/>
	  <input maxlength="42" type="hidden" id="text4"/></td>
	  </tr>
	  <tr>
	  <td class="inputSearchButtons">
	  <input type="button" onclick="requestActivities()" class="inputButton"  value="'.$lang["search_player"].'" />
	  <input type="reset" class="inputButton"  size="42"/></td></tr></table></form></div>
	  ';
	 
	 
	 echo "<div id='divActivities2'></div><br/><br/>";
	 include('footer.php');
	 ?>
	 
	 