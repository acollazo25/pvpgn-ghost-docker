<?php

//
// // ----------------------------------------------------------------------------------------------------
// // - Display Errors
// // ----------------------------------------------------------------------------------------------------
// ini_set('display_errors', 'On');
// ini_set('html_errors', 0);
//
// // ----------------------------------------------------------------------------------------------------
// // - Error Reporting
// // ----------------------------------------------------------------------------------------------------
// error_reporting(-1);
//
// // ----------------------------------------------------------------------------------------------------
// // - Shutdown Handler
// // ----------------------------------------------------------------------------------------------------
// function ShutdownHandler()
// {
//     if(@is_array($error = @error_get_last()))
//     {
//         return(@call_user_func_array('ErrorHandler', $error));
//     };
//
//     return(TRUE);
// };
//
// register_shutdown_function('ShutdownHandler');
//
// // ----------------------------------------------------------------------------------------------------
// // - Error Handler
// // ----------------------------------------------------------------------------------------------------
// function ErrorHandler($type, $message, $file, $line)
// {
//     $_ERRORS = Array(
//         0x0001 => 'E_ERROR',
//         0x0002 => 'E_WARNING',
//         0x0004 => 'E_PARSE',
//         0x0008 => 'E_NOTICE',
//         0x0010 => 'E_CORE_ERROR',
//         0x0020 => 'E_CORE_WARNING',
//         0x0040 => 'E_COMPILE_ERROR',
//         0x0080 => 'E_COMPILE_WARNING',
//         0x0100 => 'E_USER_ERROR',
//         0x0200 => 'E_USER_WARNING',
//         0x0400 => 'E_USER_NOTICE',
//         0x0800 => 'E_STRICT',
//         0x1000 => 'E_RECOVERABLE_ERROR',
//         0x2000 => 'E_DEPRECATED',
//         0x4000 => 'E_USER_DEPRECATED'
//     );
//
//     if(!@is_string($name = @array_search($type, @array_flip($_ERRORS))))
//     {
//         $name = 'E_UNKNOWN';
//     };
//
//     return(print(@sprintf("%s Error in file \xBB%s\xAB at line %d: %s\n", $name, @basename($file), $line, $message)));
// };
//
// $old_error_handler = set_error_handler("ErrorHandler");

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
*    along with DOTA OPENSTATS.  If not, see <http://www.gnu.org/licenses/>
*
-->
**********************************************/
  include('header.php');
  $pageTitle = $lang[site_name] . " | " . $lang[title];

  echo "<TABLE><TR><TD style='height:24px;'> $lang[welcome_title]</TD>
  <TR><TD>DotA OpenStats is Php/MySql based web statistic and CMS site for DotA Games</TD></TR>
  <TR><TD><a href='https://sourceforge.net/projects/dotaopenstats/'>Download Dota OpenStats</a></TD>
  </TR></TABLE><br/>";

    if (isset($_GET['id']))
	{
	$newsID = safeEscape($_GET['id']);
	$sql = "SELECT * FROM news WHERE news_id = $newsID LIMIT 1";
	$result = $db->query($sql);
	$row = $db->fetch_array($result,'assoc');
	$title = $row["news_title"];
	$text = $row["news_content"];
	$date = date($date_format,strtotime($row["news_date"]));
	echo "<div align='center'><table class='tableNews'>
	 <tr>
	 <th class='padLeft'><p class='alignleft'>$title</p>
	 <p class='alignright'>$lang[posted]  $date
	 <a name='$row[news_id]' href='index.php?id=$row[news_id]#$row[news_id]'>link</a></p></th>
	 </tr>
	 <td class='NewsText'>$text</td>
	 </tr>
	 </table>
	 </div><br>";
	}

  	$sql = "SELECT COUNT(news_id) FROM news LIMIT 1";
	$result = $db->query($sql);
	$r = $db->fetch_row($result);
	$numrows = $r[0];
	$result_per_page = $news_per_page;

	include('pagination.php');
	echo "<br>";
	$sql = "SELECT * FROM news ORDER BY news_date DESC LIMIT $offset, $rowsperpage";
	$result = $db->query($sql);

	if (isset($_GET['page'])) {$mypage = '?page=';}

	while ($row = $db->fetch_array($result,'assoc')) {
	 $title = "$row[news_title]";
	 $text = "$row[news_content]";
	 //$text = str_replace("<br>","<br>",$row["news_content"]);
	 $date = date($date_format,strtotime($row["news_date"]));
	 //$text = str_replace("&lt;br&gt;","",$row["news_content"]);
	 echo "<div align='center'><table class='tableNews'>
	 <tr>
	 <th class='padLeft'><p class='alignleft'>$title</p><p class='alignright'>$lang[posted]  $date <a name='$row[news_id]' href='index.php?page=$currentpage&id=$row[news_id]#$row[news_id]'>link</a></p></th>
	 </tr><td class='NewsText'>$text</td>
	 </tr>
	 </table>
	 </div><br>";

	 }

  include('footer.php');

  $pageContents = ob_get_contents();
  ob_end_clean();
  echo str_replace('<!--TITLE-->', $pageTitle, $pageContents);

  //Cache this page
  if ($cachePages == '1')
    file_put_contents($CacheTopPage, str_replace("<!--TITLE-->",$pageTitle,$pageContents));
  ?>