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
    //FOOTER
	   
   if (isset($_SESSION['style'])){
   $mystyle = $_SESSION['style'];
   }
   else {$mystyle = "$default_style";}
   
   if ($handle = opendir("./style")) {
       echo "<form name='myForm' method='post' action=''>";
       $chooses = '<select ONCHANGE="location = this.options[this.selectedIndex].value;" name = "style">';
	   
     while (false !== ($file = readdir($handle))) 
	{
	       $selected = "";
		   
	       if ($file !="." AND  $file !="index.html" AND $file !=".." AND strstr($file,".png")==false AND strstr($file,".css")==false AND strstr($file,".js")==false AND strstr($file,".")==false)
        {       
		$lang = $lang.$file;
        $style = str_replace(".php","",$file);		
        if (trim($style) == trim($mystyle)) {$selected="selected";}
        $chooses.='<option  '.$selected.' value="index.php?style=' . $style . '">' . $style . '</option>';
		}
    }
	
	
  $time = microtime();
  $time = explode(' ', $time);
  $time = $time[1] + $time[0];
  $finish = $time;
  $total_time = round(($finish - $start), 4);
		   
   $data = array('Select style:',date('Y'),$chooses);
   $tags = array('{L_SEL_STYLE}','{Y}','{SELECT_STYLE}');
   
   echo str_replace($tags, $data, file_get_contents("./style/$default_style/footer.html"));
			
            while ($file = readdir($handle)) {echo "$file";}
			
            closedir($handle); 
         }
  echo "</select></form>";
  	
	if ($pageGen == 1)
	{
   echo "<table><tr>
   <td align='center'>Page generated in: $total_time sec with ".$db->get_query_cout()." queries.</td>
   </tr></table><br>";   
   } else echo "<br>";
  
  ?> 