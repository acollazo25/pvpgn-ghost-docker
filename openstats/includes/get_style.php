<?php

	if (isset($_GET['style']))
	{
	    if (file_exists("./style/$_GET[style]"))
		{
		$_SESSION['style'] = safeEscape($_GET['style']);
		$default_style = $_SESSION['style'];
		}
	}

   	if (isset($_SESSION['style']))
	{
	   if (file_exists("./style/$_SESSION[style]"))
		{
	   $default_style = $_SESSION['style'];
	    }
	}
	
	if (!file_exists($cacheDir) AND $cachePages==1) 
	{echo "<table><tr><td align='center'>Warning: missing folder '<b>$cacheDir</b>'</td></tr></table>";}

   ?>