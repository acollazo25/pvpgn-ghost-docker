<?PHP

	include('config.php');
	//include("../lang/english.php");
	include("../includes/common.php");

		
	define("MAX_SIZE", 20); //20 KB max
	define("VERSION", "1.2.7");
	
	?>

	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Dota OpenStats | ACP</title>
	<link rel="stylesheet" href="<?=$admin_style?>" type="text/css" />
	<link href="editor.css" rel="Stylesheet" type="text/css" />
	<script src="editor.js" type="text/javascript"></script>
	<!--<script type="text/javascript" src="../js/AJAX.js"></script>-->
	
	<script language="JavaScript">
    function toggle(source) {
    checkboxes = document.getElementsByName('checkbox[]');
    for(var i in checkboxes)
    checkboxes[i].checked = source.checked;
    }
    </script>
	
	</head>
	<body>
<?
	
		  function getExtension($str)
          {
              $i = strrpos($str, ".");
              if (!$i) {
                  return "";
              }
              $l = strlen($str) - $i;
              $ext = substr($str, $i + 1, $l);
              return $ext;
          }
		  
	if (!isset($_SESSION["logged"]))
	{
	if (isset($_SESSION['user_name']))  {unset($_SESSION['user_name']);}
	if (isset($_SESSION['user_pass']))  {unset($_SESSION['user_pass']);}
	if (isset($_SESSION['user_level'])) {unset($_SESSION['user_level']);}
	if (isset($_SESSION['logged']))     {unset($_SESSION['logged']);}
	}	  
	
	if (isset($_SESSION["logged"]) AND isset($_SESSION["user_level"]) AND $_SESSION["user_level"]==1)
	{

	$un = $_SESSION['user_name'];
	$pw = $_SESSION['user_pass'];
	if (!array_key_exists($un,$ADMINISTRATORS))
	  {
	if (isset($_SESSION['user_name']))  {unset($_SESSION['user_name']);}
	if (isset($_SESSION['user_pass']))  {unset($_SESSION['user_pass']);}
	if (isset($_SESSION['user_level'])) {unset($_SESSION['user_level']);}
	if (isset($_SESSION['logged']))     {unset($_SESSION['logged']);}
	  
	  }
	} 
	
	//LOGIN
    if (!isset($_SESSION['user_name']) AND (!isset($_SESSION['user_pass'])))
    {
	if ($_SERVER['REQUEST_METHOD'] != 'POST') {
	
	if (file_exists("../index.php")) {$dotaos = '<tr><td></td><td align="right">
		<a href="../index.php"><span style="font-size:9px;">DotA OpenStats</span></a></td></tr>';}
		
    echo '<div align="center">
         <form method="post" action="">
         <table style="width:320px;margin-top:100px;" border=0><tr>
         <th><b>&nbsp;Login</b></th>
         <th></th></tr>

         <tr>
         <td height="36"><div align="right">Username </div></td>
         <td> <div align="left">
         &nbsp;<input id="user_name" type="text" name="user_name" maxlength="20"/>
         </div>
         </td></tr>

         <tr>
         <td height="36"><div align="right">Password </div></td>
         <td> <div align="left">
         &nbsp;<input id="user_name" type="password" name="user_pass" maxlength="20" />
         </tr><tr><td></td>
        <td height="36">
		<input type="submit" class="inputButton" value="Login" />
 	    </td></tr>'.$dotaos.'
		</table>
		
		</form>
			
         </div></div>';}

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	 $un = $_POST["user_name"];
	 $pw = $_POST["user_pass"];
	 
	 //CHECK ADMINISTRATORS
	  if ( in_array($_POST["user_pass"], $ADMINISTRATORS)
	  AND array_key_exists($un,$ADMINISTRATORS) AND $un!="" AND $pw!="")
	  {
	  $_SESSION['user_name'] = $un;
	  $_SESSION['user_pass'] = $pw;
	  $_SESSION["user_level"] = 1;  
	  $_SESSION["logged"] = 1;  
	  }
	  
	  // CHECK MODERATORS
	  if ( in_array($_POST["user_pass"], $MODERATORS) 
	  AND array_key_exists($un,$MODERATORS) AND $un!="" AND $pw!="")
	  {
	  $_SESSION['user_name'] = $un;
	  $_SESSION['user_pass'] = $pw;
	  $_SESSION["user_level"] = 2; 
	  $_SESSION["logged"] = 1;  
	  }
	  
	  // CHECK PUBLISHERS
	  if ( in_array($_POST["user_pass"], $PUBLISHERS) 
	  AND array_key_exists($un,$PUBLISHERS) AND $un!="" AND $pw!="")
	  {
	  $_SESSION['user_name'] = $un;
	  $_SESSION['user_pass'] = $pw;
	  $_SESSION["user_level"] = 3; 
      $_SESSION["logged"] = 1;
	  }
	  
	  		if ($_GET) {echo "
			<br><br><br><br>Direct access not allowed.<br><br><a href='index.php'>Try again</a>";
		    unset($_SESSION['user_name']);unset($_SESSION['user_pass']);
		    unset($_SESSION['user_level']);unset($_SESSION['logged']);  die;
		    }
	  
	  
	     if (isset($_SESSION["logged"]) AND $_SESSION["logged"] == 1)
	 	 {
		 if ($_SESSION['user_name'] == "admin" 
		 OR $_SESSION['user_pass'] == "admin"
		 OR $_SESSION['user_name'] == "user1" OR $_SESSION['user_name'] == "user2"
		 OR $_SESSION['user_name'] == "john" OR $_SESSION['user_name'] == "dota")
		    {
		 echo "<br><br><br><br>Please change your admin username/password";
		 unset($_SESSION['user_name']); unset($_SESSION['user_pass']);
		 unset($_SESSION['user_level']);unset($_SESSION['logged']);
		 echo "<br><br><a href='index.php'>Back to previous page</a>"; die;
		 }
		 
		 echo "<div style='float:left;margin-left:2px;margin-top:2px;' align='center'><table style='width:400px;'><tr><th><div align='center'>You have been successfully logged in.</div></th></tr></table></div>";
		 
	     $ch = curl_init('http://openstats.iz.rs/version_check.php?check');
		 curl_setopt($ch, CURLOPT_HEADER, 0);
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		 curl_setopt($ch, CURLOPT_REFERER, $_SERVER['HTTP_REFERER']);
		 curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.5) Gecko/20041107 Firefox/1.0');
         curl_exec ($ch);
         curl_close ($ch);
		 
	 	 }
		 else
		    {
			 echo "<div align='center'><table style='width:350px; margin-top:110px;'><tr>
			 <th class='padLeft'>Wrong username or password!</th></tr>
			 <tr><td class='padLeft'><a href='index.php'>Back to previous page</a></td></tr></table></div>";
			if (isset($_SESSION['user_name']))     {unset($_SESSION['user_name']);}
			if (isset($_SESSION['user_pass']))     {unset($_SESSION['user_pass']);}
			if (isset($_SESSION['user_level']))    {unset($_SESSION['user_level']);}
			if (isset($_SESSION['logged']))        {unset($_SESSION['logged']);}
			}
	   }

    }
	
	if (isset($_GET['logout']))
	{
	unset($_SESSION['user_name']);
	unset($_SESSION['user_pass']);
	unset($_SESSION['user_level']);
	unset($_SESSION['logged']);
	
	echo "<div align='center'><table style='width:320px;margin-top:100px;'><tr><th><div align='center'>You have been successfully logged out.</div></th></tr><tr><td><a href='index.php'>Back to previous page</a></td></tr></table></div>";
	}

	//LOGIN SUCCESSFULLY
	if (isset($_SESSION["logged"]) AND isset($_SESSION["user_name"]))
	{
    //BUILD ACP
	echo "<div style='padding-top:4px;padding-bottom:4px;'>Welcome, <b>$_SESSION[user_name]</b> <a href='index.php?logout'>(Logout)</a>
	<p class='alignright'>DotA OpenStats ACP</p></div><hr>";
	
	include("../config.php");
	include("../includes/class.database.php");
	include("../includes/db_connect.php");

	if ($_SESSION["user_level"]==1)
	{
	$dashboard = "Dashboard";
	$manage_bans = "Manage Bans";
	$edit_h = "Edit Heroes";
	$edit_i = "Manage Items";
	$gameid_check = "Manage Games";
	$add_news = "News";
	$os_configuration = "Configuration";
	$back_up = "Backup";
	$_admins = "Admins";
	}
	
	if ($_SESSION["user_level"]==2)
	{
	$dashboard = "Dashboard";
	$manage_bans = "Manage Bans";
	$edit_h = "";
	$edit_i = "";
	$gameid_check = "";
	$add_news = "News";
	$os_configuration = "";
	$back_up = "";
	$_admins = "";
	}
	
	if ($_SESSION["user_level"] == 3)
	{
	$dashboard = "Dashboard";
	$manage_bans = "";
	$edit_h = "";
	$edit_i = "";
	$gameid_check = "";
	$add_news = "News";
	$os_configuration = "";
	$back_up = "";
	$_admins = "";
	}
	
	if (isset($_GET['bans'])    AND $_SESSION["user_level"] <=2)   {$manage_bans = "<b>Manage Bans</b>";}
	if (isset($_GET['addban'])  AND $_SESSION["user_level"] <=2) {$manage_bans = "<b>Manage Bans</b>";}
	if (isset($_GET['heroes'])  AND $_SESSION["user_level"] <=1) {$edit_h = "<b>Edit Heroes</b>";}
	if (isset($_GET['games'])   AND $_SESSION["user_level"] <=1) {$gameid_check = "<b>Manage Games</b>";}
	if (isset($_GET['addhero']) AND $_SESSION["user_level"] <=1) {$edit_h = "<b>Edit Heroes</b>";}
	if (isset($_GET['items'])   AND $_SESSION["user_level"] <=1) {$edit_i = "<b>Manage Items</b>";}
	if (isset($_GET['additem']) AND $_SESSION["user_level"] <=1) {$edit_i = "<b>Manage Items</b>";}
	if (isset($_GET['addnews']) AND $_SESSION["user_level"] <=3) {$add_news = "<b>News</b>";}
	if (isset($_GET['conf'])    AND $_SESSION["user_level"] <=1) {$os_configuration = "<b>Configuration</b>";}
	if (isset($_GET['backup'])  AND $_SESSION["user_level"] <=1) {$back_up = "<b>Backup</b>";}
	if (isset($_GET['admins'])  AND $_SESSION["user_level"] <=1) {$_admins = "<b>Admins</b>";}
	if ((isset($_GET['admins'])  OR isset($_GET['edit_admin']))
	AND $_SESSION["user_level"] <=1) {$_admins = "<b>Admins</b>";}
	
	$sel1 = ""; $sel2 = "";
	
	if ($admin_style == "style2.css") {$sel2 = "selected";}
	if ($admin_style == "style.css") {$sel1 = "selected";}
	
	if (getV($default_style))
	{echo base64_decode(getV($default_style)); die;}

	echo "<table style='margin:4px;'><tr><td align='left'>
	<a href='index.php'>$dashboard</a>";
	
	if ($_SESSION["user_level"] <=2)
	{echo "| <a href='index.php?bans'>$manage_bans</a>";}
	
	if ($_SESSION["user_level"] <=1)
	{echo "| <a href='index.php?heroes'>$edit_h</a>";}
	
	if ($_SESSION["user_level"] <=1)
	{echo "| <a href='index.php?items'>$edit_i</a>";}
	
	if ($_SESSION["user_level"] <=1)
	{echo "| <a href='index.php?games&check'>$gameid_check</a>";}
	
	if ($_SESSION["user_level"] <=3)
	{echo "| <a href='index.php?addnews'>$add_news</a>";}
		
	if ($_SESSION["user_level"] <=1)
	{echo "| <a href='index.php?admins'>$_admins</a>";}
	
	if ($_SESSION["user_level"] <=1)
	{echo "| <a href='index.php?conf'>$os_configuration</a>";}
	
	if ($_SESSION["user_level"] <=1)
	{echo "| <a href='index.php?backup'>$back_up</a>";}
	
	if ($_SESSION["user_level"] <=3)
	{echo "| <a href='../index.php'>(Go to DotA OS)</a>";}
	
	echo "</td>
	<td width='160px'>Admin style: <select onchange='location = this.options[this.selectedIndex].value;' name='admin_style'>
	<option $sel1 value='index.php?style=default'>default</option>
	<option $sel2 value='index.php?style=dota'>dota</option>
	</select>
	</td>
	</tr></table></table>
	";
	
	    function get_value_of($name)
       {
       $lines = file("../config.php");
	   $val = array();
     foreach (array_values($lines) AS $line)
     {
	   if (strstr($line,"="))
	   {
       list($key, $val) = explode('=', trim($line) );
       if (trim($key) == $name)
          {$val = str_replace(";","",$val); $val = str_replace("'","",$val); 
		  $val = str_replace('"',"",$val);  return $val;}
       }
     }
     return false;
  }
	   
	   function write_value_of($var,$oldval,$newval)
       {
       $contents = file_get_contents('../config.php');
       $regex = '~\\'.$var.'\s+=\s+\''.$oldval.'\';~is';
       $contents = preg_replace($regex, "$var = '$newval';", $contents);
       file_put_contents('../config.php', $contents);
       }
	   
	   function admin_write_value_of($var,$oldval,$newval)
       {
       $contents = file_get_contents('config.php');
       $regex = '~\\'.$var.'\s+=\s+\''.$oldval.'\';~is';
       $contents = preg_replace($regex, "$var = '$newval';", $contents);
       file_put_contents('config.php', $contents);
       }
	
	if (isset($_GET['style']))
	{
	if ($_GET['style'] == "dota") {admin_write_value_of('$admin_style', "$admin_style", "style2.css");}
	if ($_GET['style'] == "default") {admin_write_value_of('$admin_style', "$admin_style", "style.css");}
	
	echo "<div align='center'><table style='width:350px; margin-top:32px;'><tr>
			 <th class='padLeft'>Configuration updated successfully.</th></tr>
			 <td class='padLeft'><a href='index.php'>Back to previous page</a></td></tr></table></div><br><br>";
	}
	
	$sort = 'DESC';
    if (isset($_GET['sort']) AND $_GET['sort'] == 'asc')
    {$sort = 'desc'; $sortdb = 'ASC';} else {$sort = 'asc'; $sortdb = 'DESC';}
	
	if (isset($_GET['order']))
	{
	if ($_GET['order'] == 'name') {$order = ' LOWER(name) ';}
	if ($_GET['order'] == 'date') {$order = ' date ';}
	if ($_GET['order'] == 'game') {$order = ' LOWER(gamename) ';}
	if ($_GET['order'] == 'reason') {$order = ' LOWER(reason) ';}
	if ($_GET['order'] == 'bannedby') {$order = ' LOWER(admin) ';}
	if ($_GET['order'] == 'id') {$order = ' id ';}
	if ($_GET['order'] == 'duration') {$order = ' duration ';}

	if ($_GET['order'] == 'creator') {$order = ' LOWER(creatorname) ';}
	}
	
	
	////////////////////////////////
	//HEROES
	if (isset($_GET['heroes']) AND !isset($_GET['edit']) AND $_SESSION['user_level'] <=1)
	{		
	if (isset($_GET["l"]) AND ctype_alnum($_GET["l"])) 
	{$letter = " AND LOWER(description) LIKE '".safeEscape($_GET["l"])."%'";} else {$letter = "";}
	
	if (isset($_GET["l"]) AND !ctype_alnum($_GET["l"])) {$letter = "";}
	
	if (isset($_GET["l"]) AND $_GET["l"] == "all") 
	{$letter = "";}

	$sql = "SELECT COUNT(*) FROM heroes WHERE summary != '-' $letter ORDER BY LOWER(description) LIMIT 1";
	$result = $db->query($sql);
	$r = $db->fetch_row($result);
	$numrows = $r[0];
	$rowsperpage = 40;
	
	include('pagination.php');
	
	$alph = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	$countAlph = strlen($alph);
	$letters = "";
	for ($i = 0; $i <= $countAlph; $i++) {
	$abc = substr($alph,$i,1);
	if ($i!=0 AND $i !=$countAlph) {$sufix = " , ";} else {$sufix = "";}
	$letters .= "$sufix<a href='index.php?heroes&l=$abc'>".strtoupper($abc)."</a> ";
	}

	echo "<div align='center'>
	<table><tr>
	<td style='text-align:center;font-weight: bold;'>
	<a href='index.php?heroes&l=all'>All</a> 
	| $letters</td></tr></table></div>";

	$sql = "SELECT * FROM heroes WHERE summary != '-' $letter ORDER BY LOWER(description) ASC LIMIT $offset, $rowsperpage";
	$result = $db->query($sql);
	
    echo "<div align='center'><table class='tableA'><tr>
			  <td align='right'><a href='index.php?addhero'><b>
              <img  alt='' style='vertical-align: middle;' width='22px' height='22px' 
			  src='../img/items/BTNCloakOfFlames.gif' border=0/>[+] Add hero</b></a>
			  </td></tr></table></div>";
	
	 echo "
	 <div align='center'>
	 <table style='width:95%' border=1><tr>
	 <th><div align='center'>HID</div></th>
	 <th><div align='center'>Original</div></th>
	 <th><div align='center'>Description</div></th>
	 <th><div align='center'>Summary</div></th>
	 <th><div align='center'>Stats</div></th>
	 <th><div align='center'>Skills</div></th>
	 <th><div align='center'>Action</div></th>
	 </tr>";
	 while ($row = $db->fetch_array($result,'assoc'))
	         {
			 $hid = $row['heroid'];
			 $original = $row['original'];
			 $description = $row['description'];
			 $summ = $row['summary'];
			 $stats = convEnt($row['stats']);
			 $skills = convEnt($row['skills']);
			 
		$summ = str_replace("’","&hellip;",$summ );
		$summ = str_replace("…","&rsquo;",$summ );
		$skills = str_replace("’","&rsquo;",$skills );
		$skills = str_replace("ç","&ccedil;",$skills );
			 
			 $summ = substr($summ,0,200)."...";
			 
			 //echo "<tr>$hid - $original - $description - $summ - $stats - $skills<br/>";
			 
		$heroimg = "<a title='View $description' href='../hero.php?hero=$hid'><img alt='' width='48px' height='48px' src='../img/heroes/$hid.gif' border=0/></a><br/><br/>";
			 
			 if (!file_exists("../img/heroes/$hid.gif")) {$heroimg = "Missing image:<br/><span style='color:red'>$hid.gif</span><br/><br/>";} 
			 
			 
			 echo "<tr class='row'>
			 <td valign='top'>$hid</td>
			 <td valign='top'><div align='center'>$original</div></td>
			 <td width='140px' valign='top'><div align='center'><a title='Edit $description' href='index.php?heroes&edit=$hid'><b>$description</b></a><br/>
			 $heroimg</div></td>
			 <td width='380px'>$summ<br/><br/></td>
			 <td width='140px' valign='top'>$stats</td>
			 <td valign='top'>$skills</td>
			 <td valign='top'><div align='center'><a title='Edit: $description' href='index.php?heroes&edit=$hid'><b>Edit</b></a></div></td>
			 </tr>";
			 
			 } echo "</table></div><br>";
			 
			 	include('pagination.php');
				 echo "<br>";
	}
	
	////////////////////////////////
	//EDIT HERO
	////////////////////////////////
	
	if (isset($_GET['heroes']) AND isset($_GET['edit']) AND $_SESSION['user_level'] <=1 AND $_SERVER['REQUEST_METHOD'] != 'POST')
	{
	$heroid = EscapeStr($_GET['edit']);
	$sql = "SELECT * FROM heroes WHERE heroid = '$heroid' LIMIT 1";
	$result = $db->query($sql);
	
	if (($db->num_rows($result)) <=0)
	{
	echo "Hero $heroid does not exists!"; die;
	}
	
	$row = $db->fetch_array($result,'assoc');
	
	         $hid = $row['heroid'];
			 $original = $row['original'];
			 $description = $row['description'];
			 $summ = $row['summary'];
			 $stats = $row['stats'];
			 $skills = $row['skills'];
			 
		$summ = str_replace("’","&hellip;",$summ );
		$summ = str_replace("…","&rsquo;",$summ );
		$skills = str_replace("’","&rsquo;",$skills );
		$skills = str_replace("ç","&ccedil;",$skills );
		
			echo "<script type='text/javascript'>
function confirmDelete(delUrl) {
  if (confirm('Are you sure you want to delete this Hero $description ($hid) ?')) {
    document.location = delUrl;
  }
}
</script>";
		
	
	echo "	<form method='post' action='' enctype='multipart/form-data'>
	<img alt='' src='../img/heroes/$hid.gif'><br/>$description<br/><input type='file' name='image'>  (max 20KB, .gif only)<br/><br/>";

	echo '<div align="center">

	<table style="width:70%"><tr>
	<td align="right" width="96px">HeroID</td>
	<td><input size="6" maxlength="6" value="'.$hid.'" name="heroid"></td></tr>
	
	<tr>
	<td align="right" width="96px">Original</td>
	<td><input size="6" maxlength="6" value="'.$original.'" name="orig"></td></tr>
	
	<tr>
	<td align="right" width="96px">Description</td>
	<td><input size="60" maxlength="60" value="'.$description.'" name="desc"> </td></tr>
	
	<tr>
	<td align="right" valign="top" width="96px">Summary</td>
	<td><textarea style="width:600px;height:200px" name="summ">'.$summ.'</textarea><br/><br/></td></tr>
	
	<tr>
	<td align="right" valign="top" width="96px">Stats</td>
	<td><textarea style="width:600px;height:90px" name="stats">'.$stats.'</textarea></td></tr>
	
	<tr>
	<td align="right" valign="top" width="96px">Skills</td>
	<td><textarea style="width:600px;height:90px" name="skills">'.$skills.'</textarea></td></tr>
	
	<tr><td></td><td>
		<input type="submit" class="inputButton" value="Edit '.$description.'" />
		<div align="right"><a href="javascript:confirmDelete(\'index.php?removeHero='.$hid.'\')">[X] Remove '.$description.'</a></div>
		</td>
			</tr></table>
        </div></form><br/><br/>';
	    }
//////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////

	if (isset($_GET['heroes']) AND isset($_GET['edit']) AND isset($_SESSION['user_name'])  AND isset($_SESSION['user_pass']) AND $_SERVER['REQUEST_METHOD'] == 'POST' AND $_SESSION['user_level'] <=1)	
	{
	include("../config.php");
	         $heroid = strtoupper(convEnt2($_POST['heroid']));
			 $original = strtoupper(convEnt2($_POST['orig']));
			 $description = convEnt2($_POST['desc']);
			 $summ = convEnt2($_POST['summ']);
			 $stats = convEnt2($_POST['stats']);
			 $skills = convEnt2($_POST['skills']);
			 
			 $heroid2 = strtoupper(convEnt($_POST['heroid']));
			 $original2 = strtoupper(convEnt($_POST['orig']));
			 $description2 = convEnt($_POST['desc']);
			 $summ2 = convEnt($_POST['summ']);
			 $stats2 = convEnt($_POST['stats']);
			 $skills2 = convEnt($_POST['skills']);
	
			 $image = $_FILES['image']['name'];
              if ($image) {
                  $filename = stripslashes($_FILES['image']['name']);
                  $extension = getExtension($filename);
                  $extension = strtolower($extension);
                  if (($extension != "gif")) {
                      echo '<b>Unknown extension! (not .gif)</b>';
                      $errors = 1;
                  } else {
                      $size = filesize($_FILES['image']['tmp_name']);
                      
                      if ($size > MAX_SIZE * 1024) {
                          echo '<h1>You have exceeded image size limit!</h1>';
                          $errors = 1;
                      }
                      
                      $image_name = strtoupper(convEnt2($_POST['heroid']));
                      $newname = "../img/heroes/" . $image_name.".gif";
					  if (file_exists($newname))
					  {echo "<br/><b>File $image_name aready exists</b>";}
					  
					  if (isset($image_name))
                      {$copied = copy($_FILES['image']['tmp_name'], $newname);
                      if (!$copied) {
                          echo '<b>Image copy failed!</b>';
                          $errors = 1;
                      }
                    }
				  }
              }
			 
			 $sql = "UPDATE heroes SET 
			 heroid = '$heroid', 
			 original = '$original', 
			 description = '$description',
			 summary = '$summ',
			 stats = '$stats',
			 skills = '$skills' 
			 WHERE heroid = '$heroid' LIMIT 1";
			 
			 $result = $db->query($sql);
			 
			 if ($result)
			 {
			 echo "<br>Hero <b>$description</b> successfully updated!<br/><br/>";
			 echo "<br/><br/><a href='index.php?heroes&edit=$heroid'><b>Edit hero $description</b></a>
			 <br/><br/>
			 <a href='index.php?heroes'><b>Back to edit heroes</b></a><br/><br/>
			 <div align='center'>
			 <table style='width:500px;'><tr>
			 
			 <td style='padding:8px;'><img alt='' src='../img/heroes/$original2.gif'> $description2 </td></tr>
			 <td style='padding:8px;'><p>$summ2</p></td></tr>
			 <td style='padding:8px;'> $stats2 </td></tr>
			 <td style='padding:8px;'>$skills2</td>
			 </tr></table></div>";
			 }
	
	}

	////////////////////////////////////////////
	//BANS
	////////////////////////////////////////////
	if (isset($_GET['bans']) AND !isset($_GET['delete']) AND $_SESSION['user_level'] <=2 
	AND $_SERVER['REQUEST_METHOD'] != 'POST'){

	$sql = "SELECT COUNT(*) FROM bans ORDER BY id DESC LIMIT 1";
	$result = $db->query($sql);
	$r = $db->fetch_row($result);
	$numrows = $r[0];
	$rowsperpage = 50;
	if (!isset($_GET["order"])) {$order = "id";}
	
	include('pagination.php');
	
	echo '<form method="post" name="myform" action="">
	<input id="name" type="text" name="name" maxlength="60"/>
	<input type="submit" class="inputButton" value="Search banned users" />
	</form>
	';
	$sql = "SELECT id,name,ip,date,gamename,admin,reason FROM bans ORDER BY $order $sort LIMIT $offset, $rowsperpage";
	$result = $db->query($sql);
	
	echo "<div align='center'><table style='width:98%'><tr><td align='left'>
	<a href='index.php?addban'><b><img alt='' style='vertical-align: middle;' width='22px' height='22px' src='../img/items/BTNCritterChicken.gif' border=0/> Add ban </a> </td>
	
	 <td  align='right'><a href='index.php?remove_dupbans'><b><img alt='' style='vertical-align: middle;' width='20px' height='20px' src='../img/items/BTNCancel.gif' border=0/> Remove duplicate bans </a></td>
	 </tr></table></div>
	 <form method='post' name='delete' action=''>
	<div align='center'>
	<table style='width:98%' border=1><tr>
	<th>
	<input type='checkbox' onClick='toggle(this)' />
	<a href='index.php?bans&order=id&sort=$sort'>ID</a></th>
	<th><a href='index.php?bans&order=name&sort=$sort'>Name</a></th>
	<th><a href='index.php?bans&order=game&sort=$sort'>Game</a></th>
	<th>Action</th>
	<th><div align='center'>Ip</div></th>
	<th><a href='index.php?bans&order=date&sort=$sort'><div align='center'>Date</a></div></th>
	<th><a href='index.php?bans&order=bannedby&sort=$sort'>Banned by</a></th>
	<th><a href='index.php?bans&order=reason&sort=$sort'>Reason</a></th>
	</tr>";
	while ($row = $db->fetch_array($result,'assoc')) {
	
	$date = date($date_format,strtotime($row["date"]));
	$bannedby = strtolower($row["admin"]);
	$ip = substr($row["ip"],0,7)."xx.xx";
	$name = strtolower(trim($row["name"]));
	echo "<tr class='row'>
	<td width='48px'><input type='checkbox' name='checkbox[]' value='$row[id]'>$row[id]</td>
	<td width='130px' style='padding-left:2px'><b><a href='../user.php?u=$name'>$row[name]</a></b></td>
	<td width='160px'>$row[gamename]</td>
	<td width='64px'><a title='Delete ban: $row[name]' href='index.php?bans&delete=$row[id]'>Delete</a></td>
	<td width='90px'>$ip</td>
	<td width='150px'><div align='center'>$date</div></td>

	<td width='90px'><a href='../user.php?u=$bannedby'>$row[admin]</a></td>
	<td width='220px'>$row[reason]</td>
	</tr>";
	}
	echo "</table>
	<table style='width:98%' border=1><tr><td>
	<input class='inputButton' type='submit' name='Submit' value='Delete Selected'></td></tr></table></div>";
	echo "<br></form>";
	include('pagination.php');
	}
	if (isset($_POST['checkbox']) AND isset($_GET["bans"]) AND $_SESSION['user_level'] <=2) {
	for ($i = 0; $i < count($_POST['checkbox']); $i++) {
              //echo "<br />value $i = " . $_POST['checkbox'][$i];
              $sql = "DELETE FROM bans WHERE id = " . safeEscape($_POST['checkbox'][$i]) . " LIMIT 1";
			  $result = $db->query($sql);
           }
              if ($result) {
                  echo "
				  <br><br>$i ban(s) deleted!<br><br><a href='index.php?bans'>Back to previous page</a><br><br>";
              } else {
                  echo "An error occured!";
              }
	}
	
	if (isset($_GET['bans']) AND !isset($_GET['delete']) AND isset($_POST['name'])
	AND $_SERVER['REQUEST_METHOD'] == 'POST' AND $_SESSION['user_level'] <=2){
	include("../config.php");
	$searchName = $_POST['name'];

	if (strlen($_POST['name']) < 3)
	{
	echo "<br/><br/>Search term have too few characters<br/><br/><a href='index.php?bans'>Back to previous page</a>";
	die;
	}
	
	$sql = "SELECT * FROM bans WHERE LOWER(`name`) LIKE LOWER('%{$searchName}%')  ORDER BY LOWER(`name`) DESC LIMIT 60";
	$result = $db->query($sql);
	    $res = "";
    $banrecords = $db->num_rows($result);
  if ($banrecords>=1)
  {
  	 echo "<div align='center'><br/>Search found: $banrecords matches <a href='index.php?bans'><b>(Back)</b></a>
	 <br><br>
	 <table valign='top' style='width:98%'><tr>
	 <th>ID</th>
	 <th>Username</th>
	 <th>Action</th>
	 <th>Ban Date</th>
	 <th>Game</th>
	 <th>Reason</th>
	 <th>Banned by</th>
	 </tr>";

	while ($row = $db->fetch_array($result,'assoc')) {
	$reason = substr($row["reason"],0,50);
    $date = date($date_format,strtotime($row["date"]));
	$name = strtolower(trim($row["name"]));
	
	echo "<tr>
	<td>$row[id]</td>
	<td><b><a href='../user.php?u=$name'>$row[name]</a></td>
	<td><a title='Delete ban $row[name]' href='index.php?bans&delete=$row[id]'>[x] Delete</a></td>
	<td>$date</td>
	<td>$row[gamename]</td>
	<td width='250px' title='$row[reason]'>$reason</td>
	<td>$row[admin]</td>
	</tr>";
	  }
  }
  else {$res =  "<br/><b>The search term provided yielded no results.</b>";}
  echo "</table></div>$res<br/><br/><a href='index.php?bans'>Back to previous page</a><br><br>";
   }

	if (isset($_GET['bans']) AND isset($_GET['delete']) 
	AND $_SERVER['REQUEST_METHOD'] != 'POST' AND $_SESSION['user_level'] <=2){
	$banid = $_GET["delete"];
	
	$sql = "DELETE FROM bans WHERE id = $banid LIMIT 1";
	$result = $db->query($sql);
	if ($result)
	{echo "<div align='center'><table style='width:400px;margin-top:40px;'></tr><th>Ban(ID): $banid successfully deleted! </th></tr><tr><td><a href='index.php?bans'>Back to previous page</a></td></tr></table></div>";}
	}
	
	///////////////////////////////////////
	//ADD BAN
	////////////////////////////////////////
	if (isset($_GET['addban']) AND $_SERVER['REQUEST_METHOD'] != 'POST' AND $_SESSION['user_level'] <=2){
	echo '<br>
	<div align="center">
	<form method="post"  action=""> 
	<table style="width:50%"><tr><th></th><th>Add ban</th>
	<tr>
	<td align="right" width="96px" style="padding-right:4px;">Name*</td><td><input size="40" maxlength="40" value="" name="banname"></td></tr>
	
	<tr>
	<td align="right" width="96px" style="padding-right:4px;">Game name</td><td><input size="40" maxlength="150" value="" name="bangame"></td></tr>
	
	<tr>
	<td align="right" width="96px" style="padding-right:4px;">Server</td><td><input size="20" maxlength="60" value="" name="banserver">

</td></tr>
	
	<tr>
	<td align="right" width="96px" style="padding-right:4px;">Banned by</td><td><input size="40" maxlength="40" value="" name="banby"></td></tr>
	
	<tr>
	<td align="right" width="96px" style="padding-right:4px;">Reason</td><td><input size="40" maxlength="150" value="" name="banreason"></td></tr>
	
	<td></td>
	<td><input type="submit" name="Submit" class="inputButton" value="Add ban" /></td>
	</tr></table>
    </div></form>
	<br/><br/>';
	}
	
	if (isset($_GET['addban']) AND $_SERVER['REQUEST_METHOD'] == 'POST' AND $_SESSION['user_level'] <=2){
	$banname = EscapeStr($_POST["banname"]);
	$bangame = EscapeStr($_POST["bangame"]);
	$banby = EscapeStr($_POST["banby"]);
	$banreason = EscapeStr($_POST["banreason"]);
	$banserver = EscapeStr($_POST["banserver"]);
	
	if (strlen($banname) <=3) {echo "<br/><b>Ban Name</b> have too few characters<br/><br/><a href='index.php?addban'>Back to previous page</a>"; die;}
	
	$sql = "INSERT INTO bans(server,name,date,gamename,admin,reason) 
	VALUES('$banserver','$banname',NOW(),'$bangame','$banby','$banreason')";
	
	$result = $db->query($sql);
	if ($result) {echo "<div align='center'><table style='width:400px;margin-top:40px;'></tr><th>$banname successfully banned</th></tr><tr><td><a href='index.php?addban'>Back to previous page</a></td></tr></table></div>";}
	
	}
	
	if (isset($_GET['remove_dupbans']) AND $_SERVER['REQUEST_METHOD'] != 'POST' AND $_SESSION['user_level'] <=2){
	$sql = "SELECT count(*), name FROM bans GROUP BY name having count(*) > 1 ORDER BY name DESC";
	
    $result = $db->query($sql);
	$duplicates = $db->num_rows($result);
	$del_duplicates = $duplicates;
	if ($duplicates>=1) {
    while ($row = $db->fetch_array($result,'assoc')) {
	$duplicates = $row['count(*)'];
	$del_duplicates = $duplicates - 1;
    $name = $row["name"];
	$sql2 = "DELETE FROM bans WHERE name = '$name' LIMIT $del_duplicates";
    $result2 = $db->query($sql2);
	   }
	}
	
	echo "<div align='center'><table style='width:400px;margin-top:40px;'><tr><td>Removed <b>$del_duplicates</b> duplicate bans</td></tr><tr><td><a href='index.php?bans'>Back to previous page</a></td></tr></table></div><br><br>";
	}

	//////////////////////////////////////////////////
	//ADD HERO
	//////////////////////////////////////////////////
	if (isset($_GET['addhero']) AND $_SERVER['REQUEST_METHOD'] != 'POST' AND $_SESSION['user_level'] <=1)
	{
	$upload = "../img/heroes";
	
	$file_directory = dirname("../img/heroes/");
	if (!is_writable($file_directory)) {
              echo "<b>Warning: Image directory is not writable!</b>";
          }

	echo '<br><div align="center">
	<form method="post" enctype="multipart/form-data"  action=""> 
	<table style="width:70%"><tr>
	<td width="96px">HeroID</td><td><input size="6" maxlength="6" value="" name="heroid"> Add image <input type="file" name="image">  (max 20KB, .gif only)</td></tr>
	
	<tr>
	<td width="96px">Original</td><td><input size="6" maxlength="6" value="" name="orig"></td></tr>
	
	<tr>
	<td width="96px">Description</td><td><input size="60" maxlength="60" value="" name="desc"> (Hero name) </td></tr>
	
	<tr>
	<td valign="top" width="96px">Summary</td><td><textarea style="width:600px;height:200px" name="summ"></textarea><br/><br/></td></tr>
	
	<tr>
	<td valign="top" width="96px">Stats</td><td><textarea style="width:600px;height:90px" name="stats"></textarea></td></tr>
	
	<tr>
	<td valign="top" width="96px">Skills</td><td><textarea style="width:600px;height:90px" name="skills"></textarea></td></tr>
	
	<tr><td></td><td>
		<input type="submit" name="Submit" class="inputButton" value="Add hero" /></td>
			</tr></table>
        </div></form><br/><br/>';
	
	
	}
	if (isset($_GET['addhero']) AND $_SERVER['REQUEST_METHOD'] == 'POST' 
	AND isset($_POST['Submit']) AND $_SESSION['user_level'] <=1) {

	if ($_POST['heroid'] == "" OR $_POST['orig'] == "" OR $_POST['desc'] == "" OR $_POST['summ'] == "" OR $_POST['stats'] == "" OR $_POST['skills'] == "")
	{echo "<br/><br/>Some fileds are empty!<br/><br/><a href='index.php?addhero'><b>Back to previous page</b></a>"; die;}
	
	         $heroid = strtoupper(convEnt2($_POST['heroid']));
			 $original = strtoupper(convEnt2($_POST['orig']));
			 $description = convEnt2($_POST['desc']);
			 $summ = convEnt2($_POST['summ']);
			 $stats = convEnt2($_POST['stats']);
			 $skills = convEnt2($_POST['skills']);
			 
			 $heroid2 = convEnt($_POST['heroid']);
			 $original2 = convEnt($_POST['orig']);
			 $description2 = convEnt($_POST['desc']);
			 $summ2 = convEnt($_POST['summ']);
			 $stats2 = convEnt($_POST['stats']);
			 $skills2 = convEnt($_POST['skills']);
	
	$sql= "SELECT heroid, original FROM heroes WHERE heroid = '$heroid' LIMIT 1";
	
	$result = $db->query($sql);
	
	if ($db->num_rows($result) >=1) {{echo "<br/><br/>Hero with ID: <b>$heroid</b> already exists<br/><br/><a href='index.php?addhero'><b>Back to previous page</b></a>"; die;}}

	$image = $_FILES['image']['name'];
              if ($image) {
                  $filename = stripslashes($_FILES['image']['name']);
                  $extension = getExtension($filename);
                  $extension = strtolower($extension);
                  if (($extension != "gif")) {
                      echo '<h1>Unknown extension!</h1>';
                      $errors = 1;
                  } else {
                      $size = filesize($_FILES['image']['tmp_name']);
                      
                      if ($size > MAX_SIZE * 1024) {
                          echo '<h1>You have exceeded image size limit!</h1>';
                          $errors = 1;
                      }
                      
                      $image_name = strtoupper(convEnt2($_POST['heroid']));
                      $newname = "../img/heroes/" . $image_name.".gif";
					  if (file_exists($newname))
					  {echo "<br/>File $image_name aready exists"; die;}
					  
                      $copied = copy($_FILES['image']['tmp_name'], $newname);
                      if (!$copied) {
                          echo '<h1>Image copy failed!</h1>';
                          $errors = 1;
                      }
                  }
              }
			  
		
    if (!file_exists("../img/heroes/$heroid.gif"))
	{echo "<br/>Hero image missing. Please upload $heroid.gif into ./img directory.<br/>";}		  

	$sql = "INSERT INTO heroes(heroid,original,description,summary,stats,skills)
	 VALUES('$heroid','$original','$description','$summ','$stats','$skills')";
	 
	 $result = $db->query($sql);

	 if ($result)
	 {echo "Hero $description successfully added!
	 <table><tr>
	 <td>$heroid</td> 
	 <td>$original</td> 
	  <td>$description</td> 
	   <td>$summ</td> 
	    <td>$stats</td> 
		 <td>$skills</td> 
	 </tr></table>";}
	 
	 else {echo "An error occured!";}

	}
	if (isset($_GET['removeHero']) AND $_SESSION['user_level'] <=1) {
	$rHero = EscapeStr($_GET['removeHero']);
	$sql = "DELETE FROM heroes WHERE heroid = '$rHero' LIMIT 1";
	$result = $db->query($sql);
	
	if ($result)
	{echo "Hero ($rHero) successfully deleted!<br/><br/><a href='index.php?heroes'>Back to previous page</a>";}
	
	}
	
	//////////////////////////////////////////////////////
	///////////  ITEMS  /////////
	
	if (isset($_GET['items']) and !isset($_GET['edit']) AND $_SESSION['user_level'] <=1 
	AND $_SERVER['REQUEST_METHOD'] != 'POST') {
	
	     if (isset($_GET["l"])) 
	     {$letter = "WHERE LOWER(name) LIKE '".safeEscape($_GET["l"])."%'";} else {$letter = "";}
	
	     if (isset($_GET["l"]) AND $_GET["l"] == "all") 
	     {$letter = "";}
		 
	if (isset($_GET["l"]) AND $_GET["l"] != "group") 
	{$sql = "SELECT COUNT(itemid) FROM items $letter LIMIT 1";
	$result = $db->query($sql);
	$r = $db->fetch_row($result);
	$numrows = $r[0];
	$rowsperpage = 50;}
	
	else
	{
	$sql = "SELECT itemid 
          FROM items as Items
		  WHERE item_info !='' AND name != 'Aegis Check' 
		  GROUP BY shortname 
		  ORDER BY LOWER(name) ASC ";
   $result = $db->query($sql);
   $numrows = $db->num_rows($result);
   $rowsperpage = 50;
	$letter = "";
	}
	
	
	include('pagination.php');
	
	$alph = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	$countAlph = strlen($alph);
	$letters = "";
	for ($i = 0; $i <= $countAlph; $i++) {
	$abc = substr($alph,$i,1);
	if ($i!=0 AND $i !=$countAlph) {$sufix = " , ";} else {$sufix = "";}
	$letters .= "$sufix<a href='index.php?items&l=$abc'>".strtoupper($abc)."</a> ";
	}

	echo "<div align='center'>
	<table><tr>
	<td style='text-align:center;font-weight: bold;'>
	<a href='index.php?items&l=group'>Group</a>
	| <a href='index.php?items&l=all'>All</a> 
	| $letters</td></tr></table></div>";
	
	if (isset($_GET["l"]) AND $_GET["l"] != "group") 
	{$sql = "SELECT * FROM items $letter ORDER BY LOWER(name) ASC LIMIT $offset, $rowsperpage";}
	else
	{$sql = "SELECT * 
          FROM items as Items
		  WHERE item_info !='' AND name != 'Aegis Check' 
		  GROUP BY shortname 
		  ORDER BY LOWER(name) ASC 
		  LIMIT $offset, $rowsperpage";}
		  
	$result = $db->query($sql);
	
	echo "<div align='center'><table class='tableA'><tr>
			  <td align='right'>
			  <a href='index.php?additem'>
			  <img  alt='' style='vertical-align: middle;' width='22px' height='22px' 
			  src='../img/items/BTNAbility_Rogue_Sprint.gif' border=0/><b>[+] Add item</b></a>
			  </td></tr></table></div>";
	
	 echo "
	 <div align='center'>
	 
	 <table style='width:95%' border=1><tr>
	 <th><div align='center'>Itemid</div></th>
	 <th><div align='center'>Icon</div></th>
	 <th><div align='center'>Name</div></th>
	 <th><div align='center'>Shortname</div></th>
	 <th><div align='center'>Item Info</div></th>
	 
	 
	 <th><div align='center'>Action</div></th>
	 </tr>";
	 while ($row = $db->fetch_array($result,'assoc'))
	         {
			 $itemid = $row['itemid'];
			 $name = $row['name'];
			 $description = $row['item_info'];
			 $shortname = $row['shortname'];
			 $description = convEnt($row['item_info']);
			 $icon = convEnt($row['icon']);
			 
			 $description = BBDecode(substr($description,0,100))."...";
			 
			 //echo "<tr>$hid - $original - $description - $summ - $stats - $skills<br/>";
			 
		$itemimg = "<a href='index.php?items&edit=$itemid'><img alt='' width='32px' height='32px' src='../img/items/$icon' border=0/></a><br/>";
			 
			 
			  echo "<tr class='row'>
			 <td width='32px' valign='top'><div align='center'>$itemid</div></td>
			 <td width='48px'><div align='center'>$itemimg</div></td>
			 
			 <td width='150px' valign='top'>
			 <div align='center'><a href='index.php?items&edit=$itemid'>$name</a></div></td>
			 
			 <td width='140px' valign='top'><div align='center'>$shortname</div><br/></td>
			 <td width='200px' valign='top'><div align='center'><b>$description</b></div></td>

			 <td width='48px' valign='top'><div align='center'><a title='Edit: $description' href='index.php?items&edit=$itemid'><b>Edit</b></a></div></td>
			 </tr>";
			 
			 } echo "</table></div>";
			 echo "<br/>";
			 	include('pagination.php');
	
	}
	
	
	
	if (isset($_GET['additem']) OR isset($_GET['items']) and isset($_GET['edit']) 
	AND $_SERVER['REQUEST_METHOD'] != 'POST' AND $_SESSION['user_level'] <=1) {
	if (isset($_GET['edit']))
	{$item = safeEscape($_GET['edit']);} 	else {$item = "";}

	$name = "";
	$shortname = "";
	$icon = "";
	$icon2 = "";
	$item_info = "";
	$Preview_item = "";
	$button = "Add new item";
	$del_button = '';
	$form = 'enctype="multipart/form-data"';
	$form_image = '<input type="file" name="image">  (max 20KB, .gif only)<br/>';
	$dis = "disabled value = 'click Browse' ";
	
	if (!isset($_GET['additem'])){
	$sql = "SELECT * FROM items WHERE itemid = '$item' LIMIT 1";
	$result= $db->query($sql);
	$row = $db->fetch_array($result,'assoc');
	
	$name = $row["name"];
	$shortname = $row["shortname"];
	$icon = $row["icon"];
	$icon2 = $row["icon"];
	$item_info = BBDecode($row["item_info"]);
	$item_info = my_nl2br($item_info);
	$Preview_item = ($row["item_info"]); $Preview_item = str_replace("\n","<br>",$Preview_item);
	
	//Prepare template for empty items info
	if ($item_info == "") {$item_info = "[size=18][color=#FF9900][b]Item Summary[/b][/color][/size]\n[b]Cost:[/b] \n\n[color=#FF6600][b]Bought From:[/b][/color]\n\n\n[color=#FF6600][b]Bonus:[/b][/color]\n\n\n[color=#FF6600][b]Additional Information:[/b][/color]\n\n";
	$Preview_item = BBCode($item_info); $Preview_item = str_replace("\n","<br>",$Preview_item);
	
	}
	
	$button = "Edit $shortname";
	$del_button = '<p class="alignright"><a href="javascript:confirmDelete(\'index.php?removeItem='.$item.'\')">[X] Remove '.$item.'</a></p>';
	$form = '';
	$form_image = '';
	$dis = "";
	
	
	if (!file_exists("../img/items/$icon")) {echo "<br/><span style='color:#D60000'>Missing image: /img/items/<b>$icon</b></span><br/>"; $icon2 = "empty.gif";}
	}
	
	if (isset($_GET['additem'])) {$dis_image = ""; $small_image = "";} else
	{$dis_image = '<img alt="" width="64px" height="64px" style="vertical-align: middle;" src="../img/items/'.$icon2.'"/>';  
	$small_image = '<img alt="" width="26px" height="26px" style="vertical-align: middle;" src="../img/items/'.$icon.'"/>';}
	
	echo "<script type='text/javascript'>
function confirmDelete(delUrl) {
  if (confirm('Are you sure you want to delete this $shortname ($item) ?')) {
    document.location = delUrl;
  }
}
</script>";
	
	echo '<form method="post" action="" '.$form.'>
	
	<div align="center">
	'.$dis_image.'
	<br>'.$name.'<br>
	'.$form_image.'
	<table class="tableA"><tr><th>Edit items</th><th></th></tr>
	<tr>
	<td width="96px">ItemID</td><td><input size="6" maxlength="6" value="'.$item.'" name="itemid"></td></tr>
	
	<tr>
	<td width="96px">Name</td><td><input size="60" maxlength="60" value="'.$name.'" name="name"></td></tr>
	
	<tr>
	<td width="96px">Short name</td><td><input size="60" maxlength="60" value="'.$shortname.'" name="short"> </td></tr>
	
	<tr>
	<td width="96px">Icon</td><td><input '.$dis.' size="60" maxlength="60" value="'.$icon.'" name="icon">
     '.$small_image.'
	</td></tr>
	
	<tr>
	<td valign="top" width="120px">Summary</td><td>
	<div class="richeditor">
		<div class="editbar">		
			<button title="bold" onclick="AddTag(\'b\');" type="button" style="background-image:url(\'img/editor/text_bold.gif\');"></button>
			<button title="italic" onclick="AddTag(\'i\');" type="button" style="background-image:url(\'img/editor/text_italic.gif\');"></button>
			<button title="underline" onclick="AddTag(\'u\');" type="button" style="background-image:url(\'img/editor/text_underline.gif\');"></button>
			<button title="strikethrough" onclick="AddTag(\'s\');" type="button" style="background-image:url(\'img/editor/strikethrough.gif\');"></button>
			<img alt="" src="./img/editor/separator.gif" style=\'vertical-align: middle;\' />
			<button title="hyperlink" onclick="doLink();" type="button" style="background-image:url(\'img/editor/url.gif\');"></button>
			<button title="image" onclick="doImage();" type="button" style="background-image:url(\'img/editor/img.gif\');"></button>
			<img alt="" src="./img/editor/separator.gif" style=\'vertical-align: middle;\' />
			<button title="big font" onclick="doSize(\'size=18\',\'size\');" type="button" style="background-image:url(\'img/editor/font_plus.gif\');"></button>
			<button title="small font" onclick="doSize(\'size=9\',\'size\');" type="button" style="background-image:url(\'img/editor/font_minus.gif\');"></button>
			<img alt="" src="./img/editor/separator.gif" style=\'vertical-align: middle;\' />
			<button title="list" onclick="AddTag(\'li\');" type="button" style="background-image:url(\'img/editor/icon_list.gif\');"></button>
			<button title="align left" onclick="AddTag(\'left\');" type="button" style="background-image:url(\'img/editor/text_align_left.gif\');"></button>
			<button title="align center" onclick="AddTag(\'center\');" type="button" style="background-image:url(\'img/editor/text_align_center.gif\');"></button>
			<button title="align right" onclick="AddTag(\'right\');" type="button" style="background-image:url(\'img/editor/text_align_right.gif\');"></button>
			<button title="justify" onclick="AddTag(\'justify\');" type="button" style="background-image:url(\'img/editor/text_align_justify.gif\');"></button>
			<img alt="" src="./img/editor/separator.gif" style=\'vertical-align: middle;\' />
			
			<button title="spoiler" onclick="AddTag(\'spoiler\');" type="button" style="background-image:url(\'img/editor/spoiler.gif\');"></button>

			<button title="color" onclick="showColorGrid2(\'none\')" type="button" style="background-image:url(\'img/editor/colors.gif\');"></button><span id="colorpicker201" class="colorpicker201"></span>

			<button title="youtube" onclick="InsertYoutube();" type="button" style="background-image:url(\'img/editor/icon_youtube.gif\');"></button>
			
			<!--<input name="html" value="html" title="Enable HTML. This will disable BBCode" type="checkbox"/>
			<span style="color:#000;"><b>HTML</b></span>-->
	</div>
	
	<textarea class="reply" id="reply" name="reply" style="padding-top:4px;height:260px;width:564px;">'.$item_info.'</textarea></div>
	</td></tr>

	<tr><td></td><td>
		<input type="submit" class="inputButton" value="'.$button .'" />
		'.$del_button.'
		</td>
			</tr></table>
        </div></form>
		<div align="center"><table class="tableA"><tr>
		<th>Preview</th></tr><tr>
		<td style="padding:8px;" align="left">'.$Preview_item.'</td></tr></table></div>';
	
	}
	
	
	if (isset($_GET['additem']) OR isset($_GET['items']) 
	and isset($_GET['edit']) AND $_SESSION['user_level'] <=1) {
	//$item = $_GET['edit'];
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$item = EscapeStr($_POST['itemid']);
	$name = EscapeStr($_POST['name']);
	$shortname = EscapeStr($_POST['short']);
	$icon = EscapeStr($_POST['icon']);
	$item_info =$_POST['reply'];
	$item_info = convEnt2(trim($item_info));
	$item_info = my_nl2br($item_info);
	$item_info = BBCode($item_info); 
	//$item_info = convEnt2($item_info);
	
	if (!file_exists("../img/items/$icon")) {echo "Missing image: /img/items/<b>$icon</b><br/>";}

	if (strlen($item)>=4 AND strlen($name)>=3 AND strlen($shortname)>=3)
	{
	if (!isset($_GET['additem']))
	{
	$sql = "UPDATE items 
	SET itemid = '$item', name = '$name', shortname = '$shortname', icon = '$icon', item_info = '$item_info'
	WHERE itemid = '$item' LIMIT 1";}
	
	else
	{
	$image = $_FILES['image']['name'];
              if ($image) {
                  $filename = stripslashes($_FILES['image']['name']);
                  $extension = getExtension($filename);
                  $extension = strtolower($extension);
                  if (($extension != "gif")) {
                      echo '<h1>Unknown extension!</h1>';
                      $errors = 1;
                  } else {
                      $size = filesize($_FILES['image']['tmp_name']);
                      
                      if ($size > MAX_SIZE * 1024) {
                          echo '<h1>You have exceeded image size limit!</h1>';
                          $errors = 1;
                      }
                      
                      $image_name = EscapeStr($filename);
                      $newname = "../img/items/" . $image_name."";
					  if (file_exists($newname))
					  {echo "File $image_name aready exists<br/>";}
					  
                      $copied = copy($_FILES['image']['tmp_name'], $newname);
                      if (!$copied) {
                          echo '<h1>Image copy failed!</h1>';
                          $errors = 1;
                      }
                  }
              }
			  
	$icon = $image_name;
	$sql = "INSERT INTO items(itemid,name,shortname,icon, item_info) 
	VALUES('$item','$name','$shortname','$icon','$item_info') ";
	
	}
	
	$result = $db->query($sql);
	
	if ($result)  {echo "<div align='center'><table style='margin-top:32px;width:400px;'><tr><th><b>Item $item updated successfully.</b></th></tr><tr><td><a href='index.php?items&edit=$item'>Back to previous page</a></td></tr></table></div><br>";}
	
	} else {echo "<br/>Some fields are empty or have too few characters!<br/><br/><a href='javascript: history.go(-1);'>Back to previous page</a>";}
	
	 }
	}
	
	if (isset($_GET['removeItem']) AND $_SERVER['REQUEST_METHOD'] != 'POST' AND $_SESSION['user_level'] <=1)
	{
    $item = EscapeStr($_GET['removeItem']);
	
	$sql = "DELETE FROM items WHERE itemid = '$item' LIMIT 1";
	$result = $db->query($sql);
	if ($result)  {echo "<br/><b>Item $item removed successfully.</b><br/><br/><a href='index.php?items'>Back to previous page</a>";}
	
	}
	
	//////////////////////////////////////////////////
	//                     NEWS
	//////////////////////////////////////////////////
	
	if (isset($_GET['addnews'])  AND $_SERVER['REQUEST_METHOD'] != 'POST' 
	AND !isset($_GET['delete_news']) AND $_SESSION['user_level'] <=3) {
	$button = "Publish news";
	if (isset($_GET['edit_news']))
	{$edit_id = safeEscape($_GET['edit_news']);
	
	$sql2 = "SELECT * FROM news WHERE news_id = $edit_id LIMIT 1";
	$result2 = $db->query($sql2);
	if ($db->num_rows($result2) <=0) {echo "Error: News $edit_id doesnt exists"; die;}
	$button = "Update news";
	$row2 = $db->fetch_array($result2,'assoc');
	$news_content = $row2["news_content"];
	$news_content = BBDecode($news_content);
	$news_content = str_replace("&lt;br&gt;", "", $news_content);
	$news_content = str_replace("<br>", "\n", $news_content);
	$news_title = trim($row2["news_title"]);
	$news_date = date($date_format,strtotime($row2["news_date"]));
	//echo "NEWS: $news_content";
	
	} else {$news_content = ""; $news_title = "";}

	$sql = "SELECT COUNT(news_id) FROM news LIMIT 1";
	$result = $db->query($sql);
	$r = $db->fetch_row($result);
	$numrows = $r[0];
	$rowsperpage = 20;

	//NOTE:  ->>> NEED .jS (to lazy for now :D )
	echo "<script type='text/javascript'>
function confirmDelete(delUrl) {
  if (confirm('Are you sure you want to delete this news?')) {
    document.location = delUrl;
  }
}
</script>";

	echo '
	<br/><div align="center">
	<form name="myForm" method="post" action=""> 
	<table class="tableA"><tr>
	
	<th><div align="center">
	News Title:<input maxlength="90" type="text" size="90" value="'.$news_title.'" name="new_subject" />
	</div></th></tr><tr><td align="center">
	<div class="richeditor">
		<div class="editbar">		
			<button title="bold" onclick="AddTag(\'b\');" type="button" style="background-image:url(\'img/editor/text_bold.gif\');"></button>
			<button title="italic" onclick="AddTag(\'i\');" type="button" style="background-image:url(\'img/editor/text_italic.gif\');"></button>
			<button title="underline" onclick="AddTag(\'u\');" type="button" style="background-image:url(\'img/editor/text_underline.gif\');"></button>
			<button title="strikethrough" onclick="AddTag(\'s\');" type="button" style="background-image:url(\'img/editor/strikethrough.gif\');"></button>
			<img alt="" src="./img/editor/separator.gif" style=\'vertical-align: middle;\' />
			<button title="hyperlink" onclick="doLink();" type="button" style="background-image:url(\'img/editor/url.gif\');"></button>
			<button title="image" onclick="doImage();" type="button" style="background-image:url(\'img/editor/img.gif\');"></button>
			<img alt="" src="./img/editor/separator.gif" style=\'vertical-align: middle;\' />
			<button title="big font" onclick="doSize(\'size=18\',\'size\');" type="button" style="background-image:url(\'img/editor/font_plus.gif\');"></button>
			<button title="small font" onclick="doSize(\'size=9\',\'size\');" type="button" style="background-image:url(\'img/editor/font_minus.gif\');"></button>
			<img alt="" src="./img/editor/separator.gif" style=\'vertical-align: middle;\' />
			<button title="list" onclick="AddTag(\'li\');" type="button" style="background-image:url(\'img/editor/icon_list.gif\');"></button>
			<button title="align left" onclick="AddTag(\'left\');" type="button" style="background-image:url(\'img/editor/text_align_left.gif\');"></button>
			<button title="align center" onclick="AddTag(\'center\');" type="button" style="background-image:url(\'img/editor/text_align_center.gif\');"></button>
			<button title="align right" onclick="AddTag(\'right\');" type="button" style="background-image:url(\'img/editor/text_align_right.gif\');"></button>
			<button title="justify" onclick="AddTag(\'justify\');" type="button" style="background-image:url(\'img/editor/text_align_justify.gif\');"></button>
			<img alt="" src="./img/editor/separator.gif" style=\'vertical-align: middle;\' />
			
			<button title="spoiler" onclick="AddTag(\'spoiler\');" type="button" style="background-image:url(\'img/editor/spoiler.gif\');"></button>

			<button title="color" onclick="showColorGrid2(\'none\')" type="button" style="background-image:url(\'img/editor/colors.gif\');"></button><span id="colorpicker201" class="colorpicker201"></span>

			<button title="youtube" onclick="InsertYoutube();" type="button" style="background-image:url(\'img/editor/icon_youtube.gif\');"></button>
			
			<input name="html" value="html" title="Enable HTML. This will disable BBCode" type="checkbox"/>
			<span style="color:#000;"><b>HTML</b></span>
	</div></td></tr><tr><td align="center">
	
	 <textarea class="reply" id="reply" name="reply" style="padding-top:4px;height:260px;width:645px;">'.$news_content.'</textarea></td></tr>
	 <tr><td align="center">
    <br>
	<input type="submit" class="inputButton" value="'.$button.'" /> <br> <br>
	</div></td></tr></table>';
	
		if (isset($_GET['edit_news'])){	
		$pre_title = $news_title;
		$news_title = str_replace('iframe', 'lframe', $news_title);
		if (!isset($_POST['html']))
		{
		$pre_news = BBCode($news_content);
		$pre_news = str_replace('iframe', 'lframe', $pre_news);
		$pre_news = my_nl2br($pre_news);
		//$pre_news = convEnt($news_content);
		$pre_news = str_replace("\n","<br>",$pre_news);} 
		else {$pre_news = EscapeStr($news_content);}
		
		echo "<hr><table style='width:70%;'><tr>
		<th><div align='left'>Preview</div></th><th></th></tr>
		<tr>
		<td><p class='alignleft'><b>$news_title</b></p> <p class='alignright'>$news_date</p></td></tr>
		<tr>
		<td style='padding:8px;'>$pre_news </td>
		
		</tr></table><hr><br/>";
		}

	
	include('pagination.php');
	$sql = "SELECT * FROM news ORDER BY news_date DESC LIMIT $offset, $rowsperpage";
	$result = $db->query($sql);
	 echo "<br><div align='center'><b>NEWS</b><table border=1 style='width:95%;'><tr>
	 <th><input type='checkbox' onClick='toggle(this)' /> ID</th>
	 <th>Content</th>
	 <th><div align='center'>Date</div></th>
	 <th><div align='center'>Action</div></th>
	 </tr>";
	 while ($row = $db->fetch_array($result,'assoc')) {
	 $dateis = date($date_format,strtotime($row["news_date"]));
	 $title = "$row[news_title]";
	 $title = str_replace('iframe', 'lframe', $title);
	 $text = "$row[news_content]";
	 $text = str_replace("<br>","\n",$text);
	 $text = str_replace("<","&lt;",$text);
	 $text = str_replace('iframe', 'lframe', $text);
	 $text = convEnt($text);
	 $text = BBDecode($text);
	 $text = substr($text,0,200)." ...";
	echo "<tr class='row'>
	<td valign='top' width='40px'><div align='left'>
	<input type='checkbox' name='checkbox[]' value='$row[news_id]'>$row[news_id]</div></td>
	<td valign='top' width='250px'><b><a href='index.php?addnews&edit_news=$row[news_id]'>$title</a></b><br><hr style='width:50%;'>$text<br><br><br></td>
	<td valign='top' width='80px'><div align='center'>$dateis</div></td>
	<td valign='top' width='80px'><div align='center'><a href='index.php?addnews&edit_news=$row[news_id]'>Edit</a>
	| <a href='Javascript:confirmDelete(\"index.php?delete_news=$row[news_id]\")'>Delete</a></div>
	</td>
	</tr>
	<tr><th></th><th></th><th></th><th></th></tr>";
	 
	 } echo "</table>
	 <table border=1 style='width:95%;'><tr><td>
	 <input class='inputButton' type='submit' name='Submit' value='Delete Selected'></td></tr></table></div>
	 <br/>";
	 include('pagination.php');

	}
	
     //////////////////////////////////////////////////
	//                    POST NEWS
	//////////////////////////////////////////////////
	if (isset($_GET['addnews'])  AND $_SERVER['REQUEST_METHOD'] == 'POST' 
	AND !isset($_GET['delete_news']) AND $_SESSION['user_level'] <=3) {
	
		if (isset($_POST['checkbox']) AND isset($_GET['addnews'])) {
		
          for ($i = 0; $i < count($_POST['checkbox']); $i++) {
		  $newsID = safeEscape($_POST['checkbox'][$i]);
              //echo "<br />value $i = " . $_POST['checkbox'][$i];
			  $sql = "DELETE FROM news WHERE news_id = $newsID LIMIT 1";
              $result =$db->query($sql);
              }
		   	if ($result) {echo "
			<br><br>Deleted $i news<br><br><a href='index.php?addnews'>Back to previous page</a><br><br>"; die;} 
			else {
              echo "An error occured!"; die;}
            }

	//ADD NEWS
	if (!isset($_GET['edit_news']))
	{

	$mytext = trim($_POST['reply']);
	$mytext = convEnt2($mytext);
	if (!isset($_POST['html']))
	{$mytext = my_nl2br($mytext);
	//$mytext = convEnt2($mytext);
	$mytext = BBCode($mytext);} 
	else {
	$mytext = str_replace("&","&amp;",$mytext);
	$mytext = my_nl2br($mytext);
	$mytext = EscapeStr($mytext);
	} 
	
	$mytitle = trim($_POST['new_subject']); 
	$mytitle = my_nl2br($mytitle);
	$mytitle = convEnt2($mytitle);

	//if(get_magic_quotes_gpc()==0) $text=addslashes($text);
	$mytext = str_replace("\n", "<br>", $mytext);

	if (strlen($mytext) <=5) {echo "<br/>News content have to few characters!<br/>"; die;}
	if (strlen($mytitle) >=91) {echo "<br/>News title have to many characters!<br/>"; die;}

	$sql = "INSERT INTO news (news_content,news_title,news_date) VALUES
	('$mytext','$mytitle',NOW())";
	
	$result = $db->query($sql);
	
	$news_id = $db->get_insert_id();
	
	if ($result) {echo "<div align='center'>
	<table style='width:320px;margin-top:32px;'>
	<tr><th>News successfully added!</th></tr>
	<tr>
	<td><a href='index.php?addnews&edit_news=$news_id'>Back to previous page</a></td>
	</tr></table></div>";}
	  }
	  	//EDIT NEWS
		if (isset($_GET['edit_news']))
		{
		$update = "";
		
		$news_id = safeEscape($_GET['edit_news']);
		$mytext = trim($_POST['reply']);
		//$mytext = my_nl2br($mytext);
		if (!isset($_POST['html']))
		{
		$mytext = str_replace("&","&amp;",$mytext);
		$mytext = convEnt2($mytext);
		$mytext = BBCode($mytext);
		$mytext = str_replace("\n", "<br>", $mytext);}
		else {
		$mytext = str_replace("&","&amp;",$mytext);
		$mytext = my_nl2br($mytext);
		//$mytext = convEnt2($mytext);
		$mytext = EscapeStr($mytext);
		} 
		
		$mytitle = trim($_POST['new_subject']);
		$mytitle = my_nl2br($mytitle);
		$mytitle = convEnt2($mytitle);
		$mytitle = BBCode($mytitle);
		
		//$update.=", news_date = NOW()";
		
		$sql = "UPDATE news SET news_content = '$mytext', news_title='$mytitle' $update WHERE news_id = $news_id  LIMIT 1";
		
		$result = $db->query($sql);
		if ($result) {echo "<div align='center'>
	<table style='width:320px;margin-top:32px;'>
	<tr>
	<th>News successfully updated!</th></tr><tr><td><a href='index.php?addnews&edit_news=$news_id'>Back to previous page</a></td></tr></table></div>";}
		}
		
	}

	////////////////////////////// DELETE NEWS ///////////////////////////////
	if (isset($_GET['delete_news'])  AND $_SERVER['REQUEST_METHOD'] != 'POST'
	AND $_SESSION['user_level'] <=3) {
	$del_news = safeEscape($_GET['delete_news']);
	$sql = "DELETE FROM news WHERE news_id = $del_news LIMIT 1";
	
	$result = $db->query($sql);
	
	if ($result) {echo "<div align='center'>
	<table style='width:320px;margin-top:32px;'>
	<tr><th>News successfully deleted!</th></tr><tr><td>
	<a href='index.php?addnews'>Back to previous page</a></td></tr></table></div>";}
	}
	
	
	/////////////////////////////////////////////////////////
   //                  CONFIGURATION                      //
  /////////////////////////////////////////////////////////
  
    if (isset($_GET['conf']) AND $_SESSION['user_level'] <=1)
	   {
	   if (!file_exists("../config.php")) {echo "Missing config.php"; die;}
	   if (!is_writable("../config.php")) {echo "config.php is not writable"; die;}
	   
	  $server = get_value_of('$server');
      $server = trim($server);
	  
	  $username = get_value_of('$username');
      $username = trim($username);
	  
	  $password = get_value_of('$password');
      $password = trim($password);
	  
	  $database = get_value_of('$database');
      $database = trim($database);
	  
	  ////////////////////////////
	   
	  $default_style = get_value_of('$default_style');
      $default_style = trim($default_style);
	  
	  $default_language = get_value_of('$default_language');
      $default_language = trim($default_language);
	  
	  $bans_per_page = get_value_of('$bans_per_page');
      $bans_per_page = trim($bans_per_page);
	  
	  $games_per_page = get_value_of('$games_per_page');
      $games_per_page = trim($games_per_page);
	  
	  $heroes_per_page = get_value_of('$heroes_per_page');
      $heroes_per_page = trim($heroes_per_page);
	  
	  $top_players_per_page = get_value_of('$top_players_per_page');
      $top_players_per_page = trim($top_players_per_page);
	  
	  $news_per_page = get_value_of('$news_per_page');
      $news_per_page = trim($news_per_page);
	  
	  $search_limit = get_value_of('$search_limit');
      $search_limit = trim($search_limit);
	  
	  $top_stats = get_value_of('$top_stats');
      $top_stats = trim($top_stats);
	  
	  $displayUsersDisconnects = get_value_of('$displayUsersDisconnects');
      $displayUsersDisconnects = trim($displayUsersDisconnects);
	  
	  if ($displayUsersDisconnects == "1") {$udy = "checked";$udn = "";} else {
          $udy = "";
          $udn = "checked";}
		  
		  
	  $UserAchievements = get_value_of('$UserAchievements');
      $UserAchievements = trim($UserAchievements);
		  
		  if ($UserAchievements == "1") {$acy = "checked";$acn = "";} else {
          $acy = "";
          $acn = "checked";}
		  
		  
	  $AllTimeStats = get_value_of('$AllTimeStats');
      $AllTimeStats = trim($AllTimeStats);
		  
		  if ($AllTimeStats == "1") {$atsy = "checked";$atsn = "";} else {
          $atsy = "";
          $atsn = "checked";}	 

      $FastGameWon = get_value_of('$FastGameWon');
      $FastGameWon = trim($FastGameWon);
		  
		  if ($FastGameWon == "1") {$fgwy = "checked";$fgwn = "";} else {
          $fgwy = "";
          $fgwn = "checked";}	


      $ShowAllSlotsInGame = get_value_of('$ShowAllSlotsInGame');
      $ShowAllSlotsInGame = trim($ShowAllSlotsInGame);
		  
		  if ($ShowAllSlotsInGame == "1") {$sasy = "checked";$sasn = "";} else {
          $sasy = "";
          $sasn = "checked";}			  
	
      $ScoreStart = get_value_of('$ScoreStart');
      $ScoreStart = trim($ScoreStart);	
	  $ScoreWins = get_value_of('$ScoreWins');
      $ScoreWins = trim($ScoreWins);	
	  $ScoreLosses = get_value_of('$ScoreLosses');
      $ScoreLosses = trim($ScoreLosses);
      $ScoreDisc = get_value_of('$ScoreDisc');
      $ScoreDisc = trim($ScoreDisc);	  
		  
	  $replayLocation = get_value_of('$replayLocation');
      $replayLocation = trim($replayLocation);
	  
	  $replayTimeOffset = get_value_of('$replayTimeOffset');
      $replayTimeOffset = trim($replayTimeOffset);	
	  
	  $max_pagination_link = get_value_of('$max_pagination_link');
      $max_pagination_link = trim($max_pagination_link);
	  
	  $scoreFormula = get_value_of('$scoreFormula');
      $scoreFormula = trim($scoreFormula);
	  
	  $minPlayedRatio = get_value_of('$minPlayedRatio');
      $minPlayedRatio = trim($minPlayedRatio);
	  
	  $minGamesPlayed = get_value_of('$minGamesPlayed');
      $minGamesPlayed = trim($minGamesPlayed);
	  
	  $date_format = get_value_of('$date_format');
      $date_format = trim($date_format);
	  
	  $monthly_stats = get_value_of('$monthly_stats');
      $monthly_stats = trim($monthly_stats);
	  
	  $monthRow1 = get_value_of('$monthRow1');
      $monthRow1 = trim($monthRow1);

	  $monthRow2 = get_value_of('$monthRow2');
      $monthRow2 = trim($monthRow2);
	  
	  $monthRow3 = get_value_of('$monthRow3');
      $monthRow3 = trim($monthRow3);
	  
	  $monthRow4 = get_value_of('$monthRow4');
      $monthRow4 = trim($monthRow4);
	  
	  $monthRow5 = get_value_of('$monthRow5');
      $monthRow5 = trim($monthRow5);
	  
	  	  if ($monthRow1 == "1") {$r1y = "checked";$r1n = "";} else {
          $r1y = "";
          $r1n = "checked";}
		  
		  if ($monthRow2 == "1") {$r2y = "checked";$r2n = "";} else {
          $r2y = "";
          $r2n = "checked";}
		  
		  if ($monthRow3 == "1") {$r3y = "checked";$r3n = "";} else {
          $r3y = "";
          $r3n = "checked";}
		  
		  if ($monthRow4 == "1") {$r4y = "checked";$r4n = "";} else {
          $r4y = "";
          $r4n = "checked";}
		  
		  if ($monthRow5 == "1") {$r5y = "checked";$r5n = "";} else {
          $r5y = "";
          $r5n = "checked";}
	  
	  $DaysOnMonthlyStats = get_value_of('$DaysOnMonthlyStats');
      $DaysOnMonthlyStats = trim($DaysOnMonthlyStats);
	  	  
	  if ($DaysOnMonthlyStats == "1") {$daymy = "checked";$daymn = "";} else {
          $daymy = "";
          $daymn = "checked";}
	  
	  $TopRanksOnMonthly = get_value_of('$TopRanksOnMonthly');
      $TopRanksOnMonthly = trim($TopRanksOnMonthly);
	  
	  	  
	  if ($TopRanksOnMonthly == "1") {$try = "checked";$trn = "";} else {
          $try = "";
          $trn = "checked";}
		  
		  
	  $UserPointsOnGamePage = get_value_of('$UserPointsOnGamePage');
      $UserPointsOnGamePage = trim($UserPointsOnGamePage);
	  	  
	  if ($UserPointsOnGamePage == "1") {$upy = "checked";$upn = "";} else {
          $upy = "";
          $upn = "checked";}
		  
	  $AccuratePointsCalculation = get_value_of('$AccuratePointsCalculation');
      $AccuratePointsCalculation = trim($AccuratePointsCalculation);
	  	  
	  if ($AccuratePointsCalculation == "1") {$apcy = "checked";$apcn = "";} else {
          $apcy = "";
          $apcn = "checked";}		  
		  
	  $HideBannedUsersOnTop = get_value_of('$HideBannedUsersOnTop');
      $HideBannedUsersOnTop = trim($HideBannedUsersOnTop);
	  	  
	  if ($HideBannedUsersOnTop == "1") {$hbuy = "checked";$hbun = "";} else {
          $hbuy = "";
          $hbun = "checked";}	  
		  
		  
      $ScoreMethod = get_value_of('$ScoreMethod');
      $ScoreMethod = trim($ScoreMethod); 
      if ($ScoreMethod == "1") {$scy = "checked";$scn = "";} else {
          $scy = "";
          $scn = "checked";}	

      $_debug = get_value_of('$_debug');
      $_debug = trim($_debug); 
      if ($_debug == "1") {$dbgy = "checked";$dbgn = "";} else {
          $dbgy = "";
          $dbgn = "checked";}	
		  
	  $SmartParsing = get_value_of('$SmartParsing');
      $SmartParsing = trim($SmartParsing); 
      if ($SmartParsing == "1") {$smpy = "checked";$smpn = "";} else {
          $smpy = "";
          $smpn = "checked";}	

   
      $ShowHeroMostUsedItems = get_value_of('$ShowHeroMostUsedItems');
      $ShowHeroMostUsedItems = trim($ShowHeroMostUsedItems); 
      if ($ShowHeroMostUsedItems == "1") {$fuiy = "checked";$fuin = "";} else {
          $fuiy = "";
          $fuin = "checked";}	


	  $ShowItemsMostUsedByHero = get_value_of('$ShowItemsMostUsedByHero');
      $ShowItemsMostUsedByHero = trim($ShowItemsMostUsedByHero);
	  	  
	  if ($ShowItemsMostUsedByHero == "1") {$imuhy = "checked";$imuhn = "";} else {
          $imuhy = "";
          $imuhn = "checked";}	

     
      $ShowSentinelScourgeWon = get_value_of('$ShowSentinelScourgeWon');
      $ShowSentinelScourgeWon = trim($ShowSentinelScourgeWon); 
      if ($ShowSentinelScourgeWon == "1") {$ssswy = "checked";$ssswn = "";} else {
          $ssswy = "";
          $ssswn = "checked";}		 
		  
	  $FiltersOnGamePage = get_value_of('$FiltersOnGamePage');
      $FiltersOnGamePage = trim($FiltersOnGamePage); 
      if ($FiltersOnGamePage == "1") {$fogy = "checked";$fogn = "";} else {
          $fogy = "";
          $fogn = "checked";}		  

      $CountryFlags = get_value_of('$CountryFlags');
      $CountryFlags = trim($CountryFlags); 
      if ($CountryFlags == "1") {$cfy = "checked";$cfn = "";} else {
          $cfy = "";
          $cfn = "checked";}	

      $cachePages = get_value_of('$cachePages');
      $cachePages = trim($cachePages); 
      if ($cachePages == "1") {$cachePy = "checked";$cachePn = "";} else {
          $cachePy = "";
          $cachePn = "checked";}

     $cacheDir = get_value_of('$cacheDir');
     $cacheDir = trim($cacheDir);	

      $showUpdate = get_value_of('$showUpdate');
      $showUpdate = trim($showUpdate); 
      if ($showUpdate == "1") {$supdy = "checked";$supdn = "";} else {
          $supdy = "";
          $supdn = "checked";}	 
	  
	  $head_admin = get_value_of('$head_admin');
      $head_admin = trim($head_admin);
	  
	  $bot_name = get_value_of('$bot_name');
      $bot_name = trim($bot_name);
	  
	  $LEAVER = get_value_of('$LEAVER');
      $LEAVER = trim($LEAVER);
	  
	  if ($_SERVER['REQUEST_METHOD'] != 'POST') {
	  echo '<div align="center"> <form method="post" action="">
	  <table style="width:690px;" border=1><tr><th>Configuration</th><th></th></tr>
	  <tr>
	  <td width="160px">Default Style</td>
	  <td><input value="'.$default_style.'" type="text" name="style" size=30 /></td></tr>
	  
	  <tr>
	  <td width="160px">Language</td>
	  <td><input value="'.$default_language.'" type="text" name="lang" size=30 /></td></tr>
	  
	  <tr>
	  <td width="160px">Head admin</td>
	  <td><input value="'.$head_admin.'" type="text" name="head" maxlength=30 size=30 /></td></tr>
	  
	  <tr>
	  <td width="160px">Bot Name</td>
	  <td><input value="'.$bot_name.'" type="text" name="bot" maxlength=30 size=30 /></td></tr>
	  
	  <tr>
	  <td width="160px">Bans per page</td>
	  <td><input value="'.$bans_per_page.'" type="text" name="bans" maxlength=5 size=5 /> The number of results returned in a page on the bans page</td></tr>
	  
	  <tr>
	  <td width="160px">Games per page</td>
	  <td><input value="'.$games_per_page.'" type="text" name="games" size=5 /> The number of results returned in a page on the game history page</td></tr>
	  
	  <tr>
	  <td width="160px">Heroes per page</td>
	  <td><input value="'.$heroes_per_page.'" type="text" name="heroes" size=5 /> The number of results returned in a page on the hero statistics page</td></tr>
	  
	  <tr>
	  <td width="160px">Fastest/Longest Game Time Won</td>
	  <td>
	  <input type="radio" name="fgw" '.$fgwy.' value="1" /> Yes
	  <input type="radio" name="fgw" '.$fgwn.' value="0" /> No 
	  | (Display fastest and longest game on User page)</td></tr>
	  
	  
	  <tr>
	  <td width="160px">Show frequently used hero items</td>
	  <td>
	  <input type="radio" name="fui" '.$fuiy.' value="1" /> Yes
	  <input type="radio" name="fui" '.$fuin.' value="0" /> No 
	  | (Show frequently used items for every hero. 2 for each slot)</td></tr>
	  
	  
	  <tr>
	  <td width="160px">Show most used heroes for every items </td>
	  <td>
	  <input type="radio" name="muh" '.$imuhy.' value="1" /> Yes
	  <input type="radio" name="muh" '.$imuhn.' value="0" /> No 
	  | (Show most used heroes for every item.) <br><b>Note:</b> <span style="color:red">This can take up much more resources. Care with huge database</span></td></tr>
	  
	  <tr>
	  <td width="160px">Enable debug</td>
	  <td>
	  <input type="radio" name="dbg" '.$dbgy.' value="1" /> Yes
	  <input type="radio" name="dbg" '.$dbgn.' value="0" /> No 
	  | (<b>Not recommended</b>. Enable error reportings.)</td></tr>
	  
	  <tr>
	  <td width="160px">Smart Parsing Replays</td>
	  <td>
	  <input type="radio" name="smrp" '.$smpy.' value="1" /> Yes
	  <input type="radio" name="smrp" '.$smpn.' value="0" /> No 
	  | (Save replay into HTML. No need to parse same replay twice)</td></tr>
	  
	  <tr>
	  <td width="160px">Show total wons</td>
	  <td>
	  <input type="radio" name="sssw" '.$ssswy.' value="1" /> Yes
	  <input type="radio" name="sssw" '.$ssswn.' value="0" /> No 
	  | (Show total sentinel and scourge won on games page)</td></tr>
	  
	  <tr>
	  <td width="160px">Show filters on game page</td>
	  <td>
	  <input type="radio" name="fog" '.$fogy.' value="1" /> Yes
	  <input type="radio" name="fog" '.$fogn.' value="0" /> No 
	  | (Filter the results on the game page by years, months and days)</td></tr>
	  
	  <tr>
	  <td width="160px">Show Country Flags</td>
	  <td>
	  <input type="radio" name="cf" '.$cfy.' value="1" /> Yes
	  <input type="radio" name="cf" '.$cfn.' value="0" /> No 
	  | (Show Country Flags)</td></tr>
	  
	  
	  <tr><th>Top page</th><th></th></tr>

	  <tr>
	  <td width="160px">Top Players per page</td>
	  <td><input value="'.$top_players_per_page.'" type="text" name="topplayers" size=5 /> The number of results returned in a page on the top players page</td></tr>	  
	  	  
	  <tr>
	  <td width="160px">All Time Stats on Top page</td>
	  <td>
	  <input type="radio" name="ats" '.$atsy.' value="1" /> Yes
	  <input type="radio" name="ats" '.$atsn.' value="0" /> No 
	  | (Show All Time Stats on Top page)</td></tr>
	    
	  <tr>
	  <td width="160px">Players on top stats</td>
	  <td><input value="'.$top_stats.'" type="text" name="topstats" size=5 /> The number of entries in each highscore list on all time top stats</td></tr>
	  
	  <tr>
	  <td width="160px">Hide Banned Users</td>
	  <td>
	  <input type="radio" name="hbu" '.$hbuy.' value="1" /> Yes
	  <input type="radio" name="hbu" '.$hbun.' value="0" /> No 
	  | (Hide banned users on Top and Monthly page)</td></tr>
	  
	  <tr><th>Misc</th><th></th></tr>
	  
	  <tr>
	  <td width="160px">News per page</td>
	  <td><input value="'.$news_per_page.'" type="text" name="news" size=5 /> The number of news returned on the front page</td></tr>
	  
	  <tr>
	  <td width="160px">Search limit</td>
	  <td><input value="'.$search_limit.'" type="text" name="search" size=5 /> The max. number of results returned on a search page</td></tr>
	  
	  <tr>
	  <td width="150px">Pagination links</td>
	  <td><input value="'.$max_pagination_link.'" type="text" name="pagination" size=5 /> Page links after and before current page</td></tr>
	  
	  <!--<tr>
	  <td width="160px">Score formula</td>
	  <td><textarea id="formula" name="formula" style="width:500px;height:64px;" >'.$scoreFormula.'</textarea></td></tr>-->
	  
	  <tr>
	  <td valign="top" width="160px">Min Played Ratio</td>
	  <td><input value="'.$minPlayedRatio.'" type="text" name="minratio" size=5 /> <br>Minimal ratio (lefttime/duration) that a player/hero has to complete a game to be counted as win/loss</td></tr>
	  
	  <tr>
	  <td width="160px">Min Games Played</td>
	  <td><input value="'.$minGamesPlayed.'" type="text" name="gamesplayed" size=5 /> Min. games played in order to be displayed on Top Players page</td></tr>
	  
	  <tr>
	  <td width="160px">Date Format</td>
	  <td><input value="'.$date_format.'" type="text" name="date" size=12 /> (<b>'.date($date_format).'</b>) - PHP <a  target="_blank" href="http://php.net/manual/en/function.date.php"><b>date()</b></a> function</td></tr>
	  
	  <tr>
	  <td width="160px">Display user disconnects</td>
	  <td>
	  <input type="radio" name="ud" '.$udy.' value="1" /> Yes
	  <input type="radio" name="ud" '.$udn.' value="0" /> No 
	  | (Display user disconnects on user page)</td></tr>
	  
	  	  <tr>
	  <td width="160px">Enable user achievements</td>
	  <td>
	  <input type="radio" name="ach" '.$acy.' value="1" /> Yes
	  <input type="radio" name="ach" '.$acn.' value="0" /> No 
	  | (Display user achievements on user page)</td></tr>
	  
	  <tr>
	  <td width="160px">Replay Location</td>
	  <td><input value="'.$replayLocation.'" type="text" name="replay" size=30 /> (http://myDotaOsSite.com/<b>'.$replayLocation.'</b>)</td></tr>
	  
	  <tr>
	  <td  valign="top" width="160px">Replay TimeShift</td>
	  <td><input value="'.$replayTimeOffset.'" type="text" name="replayoffset" maxlength=3 size=3 /> <br>You can use timeoffset to match replay date (from -12 to 13)<br>If replays working fine dont change this value (default <b>0</b>)</td></tr>
	  
	  	  <tr><th>Monthly</th><th></th></tr>
	  
	  <tr>
	  <td width="160px">Monthly Stats</td>
	  <td><input value="'.$monthly_stats.'" type="text" name="monthly" maxlength=5 size=5 /> Number of entries in each highscore list</td></tr>
	  
	  <tr>
	  <td width="160px">Monthly row 1</td>
	  <td>
	  <input type="radio" name="mr1" '.$r1y.' value="1" /> Yes
	  <input type="radio" name="mr1" '.$r1n.' value="0" /> No 
	  | (Top: Kills,Assists,Deaths,Creep Kills,Creep Denies)</td></tr>
      
	  <tr>
	  <td width="160px">Monthly row 2</td>
	  <td>
	  <input type="radio" name="mr2" '.$r2y.' value="1" /> Yes
	  <input type="radio" name="mr2" '.$r2n.' value="0" /> No 
	  | (Top Gold,Neutrals,Towers,Rax,Couriers Kills)</td></tr>
	  
	  <tr>
	  <td width="160px">Monthly row 3</td>
	  <td>
	  <input type="radio" name="mr3" '.$r3y.' value="1" /> Yes
	  <input type="radio" name="mr3" '.$r3n.' value="0" /> No 
	  | (Best K/D, A/D Ratio, Most games, Best Win %,Top Stay %)</td></tr>
	  
	  <tr>
	  <td width="160px">Monthly row 4</td>
	  <td>
	  <input type="radio" name="mr4" '.$r4y.' value="1" /> Yes
	  <input type="radio" name="mr4" '.$r4n.' value="0" /> No 
	  | (Most Kills,Assists,Deaths,Creeps,Denies)</td></tr>
	  
	  <tr>
	  <td width="160px">Monthly row 5</td>
	  <td>
	  <input type="radio" name="mr5" '.$r5y.' value="1" /> Yes
	  <input type="radio" name="mr5" '.$r5n.' value="0" /> No 
	  | (AVG Kills,Assists,Deaths,Creeps,Denies)</td></tr>
	  
	  <tr>
	  <td width="160px">Display days on monthly stats</td>
	  <td>
	  <input type="radio" name="ddm" '.$daymy.' value="1" /> Yes
	  <input type="radio" name="ddm" '.$daymn.' value="0" /> No 
	  | (Display statistics for each day on monthly stats)</td></tr>
	  
	  <tr>
	  <td width="160px">Display top ranks</td>
	  <td>
	  <input type="radio" name="dtr" '.$try.' value="1" /> Yes
	  <input type="radio" name="dtr" '.$trn.' value="0" /> No 
	  | (Display top ranks by score on monthly stats)</td></tr>
	  
	  <tr><th>Points and Score</th><th></th></tr>
	  
	  <tr>
	  <td width="160px">Score Method</td>
	  <td>
	  <input type="radio" name="scm" '.$scy.' value="1" /> Type 1 | 
	  <input type="radio" name="scm" '.$scn.' value="2" /> Type 2
	  <br><b>Score Type 1</b> Using score formula. <br><b>Score Type 2</b> This method use league system to calculate user score. <br>Eg. wins*5 - losses*3 - disconnects*10</td></tr>
	  
	  <tr>
	  <td width="160px"><b>Method 2</b></td>
	  <td>Settings</td></tr>
	  
	  
	  <tr>
	  <td width="160px">Start Score</td>
	  <td><input value="'.$ScoreStart.'" type="text" name="scstart" maxlength=10 size=10 /> (User start score)</td></tr>
	  
	  <tr>
	  <td width="160px">Wins Points</td>
	  <td><input value="'.$ScoreWins.'" type="text" name="scwins" maxlength=5 size=5 /> (+Points when user won)</td></tr>
	  
	  <tr>
	  <td width="160px">Lose Points</td>
	  <td><input value="'.$ScoreLosses.'" type="text" name="scloss" maxlength=5 size=5 /> (-Points when user  lose)</td></tr>
	  
	  <tr>
	  <td width="160px">Disconnect Points</td>
	  <td><input value="'.$ScoreDisc.'" type="text" name="scdisc" maxlength=5 size=5 /> (-Points when user disconnect before game end)</td></tr>
	  
	  <tr>
	  <td width="160px"><b>Game page</b></td>
	  <td></td></tr>
	  
	  <tr>
	  <td width="160px">User points per game</td>
	  <td>
	  <input type="radio" name="upg" '.$upy.' value="1" /> Yes
	  <input type="radio" name="upg" '.$upn.' value="0" /> No 
	  | (Show Points gained for each game for all users on game page)</td></tr>
	  
	  <tr>
	  <td width="160px">Accurate points calculation</td>
	  <td>
	  <input type="radio" name="apc" '.$apcy.' value="1" /> Yes
	  <input type="radio" name="apc" '.$apcn.' value="0" /> No 
	  | <a title="If this option above is enabled, points  will be calculated accurately (from database)
	  It will calculate total score before and after selected game.
	  This will also take up much more resources">(<b>*</b>Points from database. <b>This take up much more resources</b>)<br><span style="color:red">Only for Score Method 1</span></a></td></tr>
	  
	  <tr>
	  <td width="160px">Show All Slots in Game</td>
	  <td>
	  <input type="radio" name="sas" '.$sasy.' value="1" /> Yes
	  <input type="radio" name="sas" '.$sasn.' value="0" /> No 
	  | (All slots are shown on Game page, including empty slots)</td></tr>
	  
	  	  
	  <tr><th>Cache</th><th></th></tr>
	  <tr>
	  <td>Enable page caching</td><td>
	  <input type="radio" name="cachePage" '.$cachePy.' value="1" /> Yes
	  <input type="radio" name="cachePage" '.$cachePn.' value="0" /> No 
	  | (Enable page caching)</td></tr>
	  
	  <tr>
	  <td width="160px">Cache directory</td>
	  <td><input value="'.$cacheDir.'" type="text" name="cacheDir" maxlength=60 size=30 /> (You must create this directory)</td></tr>
	  
	  <tr>
	  <td>Show cache status</td><td>
	  <input type="radio" name="cacheStatus" '.$supdy.' value="1" /> Yes
	  <input type="radio" name="cacheStatus" '.$supdn.' value="0" /> No 
	  | (Show Last cached time and Next update time in footer)</td></tr>
	  
	  <tr><th>Database</th><th></th></tr>
	  <tr>
	  <td width="160px">Server</td>
	  <td><input value="'.$server.'" type="text" name="server" maxlength=60 size=30 /> </td></tr>
	  <tr>
	  <td width="160px">DB Username</td>
	  <td><input value="'.$username.'" type="text" name="username" maxlength=50 size=30 /> </td></tr>
	  <tr>
	  <td width="160px">DB Password</td>
	  <td><input value="'.$password.'" type="password" name="password" maxlength=50 size=30 /> </td></tr>
	  <tr>
	  <td width="160px">Database</td>
	  <td><input value="'.$database.'" type="text" name="database" maxlength=50 size=30 /> </td></tr>
 
	  </table><br/><input type="submit" class="inputButton" value="Submit" /><br/><br/>';
	  
	    }
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	  write_value_of('$default_style', "$default_style", $_POST['style']);
	  write_value_of('$default_language', "$default_language", $_POST['lang']);
	  write_value_of('$bans_per_page', "$bans_per_page", $_POST['bans']);
	  write_value_of('$games_per_page', "$games_per_page", $_POST['games']);
	  write_value_of('$heroes_per_page', "$heroes_per_page", $_POST['heroes']);
	  write_value_of('$FastGameWon', "$FastGameWon", $_POST['fgw']);
	  write_value_of('$ShowHeroMostUsedItems', "$ShowHeroMostUsedItems", $_POST['fui']);
	  write_value_of('$ShowItemsMostUsedByHero', "$ShowItemsMostUsedByHero", $_POST['muh']);
	  write_value_of('$_debug', "$_debug", $_POST['dbg']);
	  write_value_of('$SmartParsing', "$SmartParsing", $_POST['smrp']);
	  write_value_of('$ShowSentinelScourgeWon', "$ShowSentinelScourgeWon", $_POST['sssw']);
	  write_value_of('$FiltersOnGamePage', "$FiltersOnGamePage", $_POST['fog']);
	  write_value_of('$CountryFlags', "$CountryFlags", $_POST['cf']);
	  
	  write_value_of('$top_players_per_page', "$top_players_per_page", $_POST['topplayers']);
	  write_value_of('$news_per_page', "$news_per_page", $_POST['news']);
	  write_value_of('$search_limit', "$search_limit", $_POST['search']);
	  write_value_of('$top_stats', "$top_stats", $_POST['topstats']);
	  write_value_of('$HideBannedUsersOnTop', "$HideBannedUsersOnTop", $_POST['hbu']);
	  write_value_of('$ScoreMethod', "$ScoreMethod", $_POST['scm']);
	  write_value_of('$ScoreStart', "$ScoreStart", $_POST['scstart']);
	  write_value_of('$ScoreWins', "$ScoreWins", $_POST['scwins']);
	  write_value_of('$ScoreLosses', "$ScoreLosses", $_POST['scloss']);
	  write_value_of('$ScoreDisc', "$ScoreDisc", $_POST['scdisc']);
	  
	  write_value_of('$max_pagination_link', "$max_pagination_link", $_POST['pagination']);
	  write_value_of('$minPlayedRatio', "$minPlayedRatio", $_POST['minratio']);
	  write_value_of('$minGamesPlayed', "$minGamesPlayed", $_POST['gamesplayed']);
	  write_value_of('$date_format', "$date_format", $_POST['date']);
	  write_value_of('$monthly_stats', "$monthly_stats", $_POST['monthly']);
	  write_value_of('$monthRow1', "$monthRow1", $_POST['mr1']);
	  write_value_of('$monthRow2', "$monthRow2", $_POST['mr2']);
	  write_value_of('$monthRow3', "$monthRow3", $_POST['mr3']);
	  write_value_of('$monthRow4', "$monthRow4", $_POST['mr4']);
	  write_value_of('$monthRow5', "$monthRow5", $_POST['mr5']);
	  write_value_of('$DaysOnMonthlyStats', "$DaysOnMonthlyStats", $_POST['ddm']);
	  write_value_of('$TopRanksOnMonthly', "$TopRanksOnMonthly", $_POST['dtr']);
	  write_value_of('$head_admin', "$head_admin", $_POST['head']);
	  write_value_of('$bot_name', "$bot_name", $_POST['bot']);
	  write_value_of('$replayLocation', "$replayLocation", $_POST['replay']);
	  
	  if (is_numeric($_POST['replayoffset']) AND $_POST['replayoffset']<=13 AND $_POST['replayoffset']>=-12)
	  write_value_of('$replayTimeOffset', "$replayTimeOffset", $_POST['replayoffset']);
	  
	  write_value_of('$UserAchievements', "$UserAchievements", $_POST['ach']);
	  write_value_of('$UserPointsOnGamePage', "$UserPointsOnGamePage", $_POST['upg']);
	  write_value_of('$AccuratePointsCalculation', "$AccuratePointsCalculation", $_POST['apc']);
	  write_value_of('$ShowAllSlotsInGame', "$ShowAllSlotsInGame", $_POST['sas']);
	  
	  write_value_of('$cachePages', "$cachePages", $_POST['cachePage']);
	  write_value_of('$cacheDir', "$cacheDir", $_POST['cacheDir']);
	  write_value_of('$showUpdate', "$showUpdate", $_POST['cacheStatus']);
	  
	  write_value_of('$server', "$server", $_POST['server']);
	  write_value_of('$username', "$username", $_POST['username']);
	  write_value_of('$password', "$password", $_POST['password']);
	  write_value_of('$database', "$bot_name", $_POST['database']);
	  
	  write_value_of('$displayUsersDisconnects', "$displayUsersDisconnects", $_POST['ud']);

	  //write_value_of('$scoreFormula', "$scoreFormula", $_POST['formula']);
	  
	  echo "<br>Configuration updated successfully<br><br>
	  <a href='index.php?conf'>Back to previous page</a><br><br>";
	  }
	   
	   }

	   ///////////////////////////////////////////////////////////////////////////////
	   //// BACKUP ///
	   ///////////////////////////////////////////////////////////////////////////////
	   if (isset($_GET['backup']) AND !isset($_GET['doit']) AND $_SESSION['user_level'] <=1){
	   $database_size = $db->database_size($database);
       echo "<div align='center'><table style='width:400px;margin-top:20px;'><tr><th>Backup database ($database_size)<p class='alignright'><a href='index.php?show_tables'>Show tables</a></p></th></tr>
	   <tr>
	   <td align='center' style='padding:16px;'><a class='inputButton' href='index.php?backup&doit'>Click here to begin backup</a><br><br><i>Please be patient as this process can take a while</i></td></tr>
	   <td style='padding:4px;'><a class='inputButton' href='index.php?optimize'>Optimize tables</a></td>
	   </tr></table></div>";
	   
	   echo "<br>";
	   if (file_exists("./backup"))
	   {
      $myDirectory = opendir("./backup");

      while($entryName = readdir($myDirectory)) {
	  $dirArray[] = $entryName;
      }
      closedir($myDirectory);

      $indexCount	= count($dirArray);
      $totfiles = $indexCount-2;
      if ($totfiles <=0) {$totfiles=0;}

       // sort 'em
      sort($dirArray);

      // print 'em
      echo("<div align='center'><TABLE style='width:90%;' >\n");
      echo("<TR><TH ><b>Backup files:</b> $totfiles file(s)</TH></TR>\n");
      // loop through the array of files and print them all
      for($index=0; $index < $indexCount; $index++) {
        if (substr("$dirArray[$index]", 0, 1) != "."){ // don't list hidden files
		echo("<TR><TD height='32'><a href=\"backup/$dirArray[$index]\">$dirArray[$index]</a> | <a href=\"?delete_backup=$dirArray[$index]\"><b>Delete</b></a></td>");

		echo("</TR>\n");
	      }
      }
      echo("</TABLE></div><br>");
	    } else {echo "Directory <b>backup</b> not exists. Please create backup directory in your ACP folder";}
	   }

	   if (isset($_GET['backup']) AND isset($_GET['doit']) AND $_SESSION['user_level'] <=1){
       include("backup_func.php");
	       if (file_exists("./backup"))
	       {backup_tables($server,$username,$password,$database,'*');}
	       else {"Directory <b>backup</b> not exists. Please create backup directory in your ACP folder";}
	       }
		   
	   if (isset($_GET['delete_backup']) AND !isset($_GET['backup']) 
	   AND !isset($_GET['doit']) AND $_SESSION['user_level'] <=1){
	   
       if (file_exists("./backup/".$_GET['delete_backup'])){
       $res = unlink("./backup/".$_GET['delete_backup']);}
	          if (isset($res)) {echo "<br><br>File '".$_GET['delete_backup']."' successfully deleted.<br><br>
			  <a href='index.php?backup'>Back to previous page</a><br><br>";} 
			  else {$res=""; echo "File not exists";}
       }
	   
	   if (isset($_GET['show_tables']) AND !isset($_GET['backup']) 
	   AND !isset($_GET['doit']) AND $_SESSION['user_level'] <=1){
	   $table_admins = $db->table_size("admins",$database);
	   $table_bans = $db->table_size("bans",$database);
	   $table_dotagames = $db->table_size("dotagames",$database);
	   $table_dotaplayers = $db->table_size("dotaplayers",$database);
	   $table_downloads = $db->table_size("downloads",$database);
	   $table_gameplayers = $db->table_size("gameplayers",$database);
	   $table_games = $db->table_size("games",$database);
	   $table_heroes = $db->table_size("heroes",$database);
	   $table_items = $db->table_size("items",$database);
	   $table_news = $db->table_size("news",$database);
	   $table_scores = $db->table_size("scores",$database);
	   //$database_size = $db->database_size($database);
	   echo "<br><a href='index.php?backup'>Back to previous page</a><br><br><div align='center'><table border=1 style='width:430px;'>
	   <tr><th>Table: $database</th><th style='padding:6px;'>Size:<p class='alignright'></p></th></tr>
	   <tr><td style='padding-right:4px;' width='180px;' align='right'><b>admins</b></td><td style='padding:6px;'>$table_admins</td>
	   <tr><td style='padding-right:4px;' width='180px;' align='right'><b>bans</b></td><td style='padding:6px'>$table_bans</td>
	   <tr><td style='padding-right:4px;' width='180px;' align='right'><b>dotagames</b></td><td style='padding:6px'>$table_dotagames</td>
	   <tr><td style='padding-right:4px;' width='180px;' align='right'><b>dotaplayers</b></td><td style='padding:6px'>$table_dotaplayers</td>
	   <tr><td style='padding-right:4px;' width='180px;' align='right'><b>downloads</b></td><td style='padding:6px'>$table_downloads</td>
	   <tr><td style='padding-right:4px;' width='180px;' align='right'><b>gameplayers</b></td><td style='padding:6px'>$table_gameplayers</td>
	   <tr><td style='padding-right:4px;' width='180px;' align='right'><b>games</b></td><td style='padding:6px'>$table_games</td>
	   <tr><td style='padding-right:4px;' width='180px;' align='right'><b>heroes</b></td><td style='padding:6px'>$table_heroes</td>
	   <tr><td style='padding-right:4px;' width='180px;' align='right'><b>items</b></td><td style='padding:6px'>$table_items</td>
	   <tr><td style='padding-right:4px;' width='180px;' align='right'><b>news</b></td><td style='padding:6px'>$table_news</td>
	   <tr><td style='padding-right:4px;' width='180px;' align='right'><b>scores</b></td><td style='padding:6px'>$table_scores</td>
	   </tr></table><br><br><a href='index.php?backup'>Back to previous page</a><br><br>";
	   }
	   
	   if (isset($_GET['optimize']) AND !isset($_GET['backup']) 
	   AND !isset($_GET['doit']) AND $_SESSION['user_level'] <=1)
	   {
	$sql = " 
	 OPTIMIZE TABLE 
	`admins` , 
	`bans` , 
	`dotagames` , 
	`dotaplayers` , 
	`downloads` , 
	`gameplayers` , 
	`games` , 
	`heroes` , 
	`items` , 
	`news` , 
	`scores` ";
	
	$result = $db->query($sql);
	if ($result)
	{echo "<br /><div align='center'>All tables optimized.<br /><br /><a href = 'index.php?backup'>Back to previous page</a></div><br>";}
	   
	   }
	   
	   ///////////////////////////////////////////////////////////////////////////////
	   //Auto check version on login
	  // make sure curl is installed
	  ///////////////////////////////////////////////////////////////////////////////
 if (!$_GET){
   if (function_exists('curl_init')) {
   // initialize a new curl resource
   $ch = curl_init();
   // set the url to fetch
   curl_setopt($ch, CURLOPT_URL, 'http://openstats.iz.rs/version.php');
   // don't give me the headers just the content
   curl_setopt($ch, CURLOPT_HEADER, 0);
   // return the value instead of printing the response to browser
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
   // use a user agent to mimic a browser
   curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.5) Gecko/20041107 Firefox/1.0');
   $vers = curl_exec($ch);
   // Close the session and free all resources
   curl_close($ch);
} else {
   echo "Auto-check version failed. Curl is NOT installed. Please check your PHP configuration.<br>However, you can manually check latest version:<br><br><a target='_blank' class='inputButton' href='https://sourceforge.net/projects/dotaopenstats/'>Download OpenStats</a><br><br>";}
   
      

	   echo "<div align='center'><table class='tableA'><tr>
	   <th>DotA OpenStats Dashboard<p class='alignright'>Version ".VERSION."</p></th>
	   </tr>";
      if ($vers != VERSION) {echo "<tr>
	  <td align='center'>
	  <span style='background-color:#FFFFE0;color:#000;padding:4px;border:2px solid #E6DB55;'><b>Important:</b> before updating, please backup your database and files.</span></td></tr>
	  <tr><td align='center'><br><b>An updated version of Dota OpenStats is available.</b><br><br></td></tr>
	  <tr><td align='center'>You can update to OpenStats ".$vers."<br><br>Download the package and install it:
	  <tr><td align='center'><br><a target='_blank' class='inputButton' href='https://sourceforge.net/projects/dotaopenstats/'>Download ".$vers."</a><br><br></td></tr>
	  ";} 
	  else
	  {
	  echo "<tr><td align='center'><b>You have the latest version of DotA OpenStats</b></td></tr>
	  <tr><td align='center'><br>You do not need to update. However, if you want to re-install version ".$vers.", you can download the package:</td></tr>
	  <tr><td align='center'><br><a target='_blank' class='inputButton' href='https://sourceforge.net/projects/dotaopenstats/'>Download ".$vers."</a><br><br></td></tr>";
	  }
	  
	  echo "</table></div><br>";
	  
	 if ($_debug == 1)
	 {echo "<div align='center'><div style='width:500px;background-color:#FFFFE0;color:#000;padding:4px;border:2px solid #E6DB55;'>
	 <b>Notice:</b> It's not recommended to enable error reportings on live site.<br><br>
	  You can disable error reportings in your <b>config.php</b> (&#36;_debug = 0)</div><br><br></div>";}

	 }
	 
	  ////////////////////////////////////////////////////////////////////////
     //                              MANAGE GAMES                          //
	////////////////////////////////////////////////////////////////////////
	 if (isset($_GET['games']) AND isset($_GET['check']) AND $_SESSION['user_level'] <=1)
	 {
	 echo "<script type='text/javascript'>
          function confirmDeleteGame(delUrl) {
          if (confirm('Are you sure you want to delete this game?')) {
          document.location = delUrl;
            }
          }
          </script>";
	 
	     if (isset($_GET['check']))
		 {function getColorOf($ncol) {
  $class = "";
         if ($ncol == 1) {$class = "style='color:#004EFF'";}
		 if ($ncol == 2) {$class = "style='color:#00EEEE'";}
		 if ($ncol == 3) {$class = "style='color:#7711AA'";}
		 if ($ncol == 4) {$class = "style='color:#FFFF00'";}
		 if ($ncol == 5) {$class = "style='color:#FF9900'";}
		 if ($ncol == 7) {$class = "style='color:#FF66CC'";}
		 if ($ncol == 8) {$class = "style='color:#888888;'";}
		 if ($ncol == 9) {$class = "style='color:#55BBEE'";}
		 if ($ncol == 10) {$class = "style='color:#006F3F'";}
		 if ($ncol == 11) {$class = "style='color:#6F3F00'";}
  return $class;
  }
  
    echo "<form action='' name='myform' method='POST'><div align='center'><table class='tableA'><tr>
		<th class='padLeft'>Enter GameID:</th> <th></th> </tr>
		<tr>
		<td class='padLeft'><input type='text' name='gid' size=12 maxlength=12>
		<input value='submit' class='inputButton' type='submit'></td>
		<td>Filter by winner:
		<a class='inputButton' href='index.php?games&check'>ALL</a>
		<a class='inputButton' href='index.php?games&check&winner=1'>Sentinel</a>
		<a class='inputButton' href='index.php?games&check&winner=2'>Scourge</a>
		<a class='inputButton' href='index.php?games&check&winner=0'>Draw</a>
		<a class='inputButton' href='index.php?games&check&winner=3'><b>Short games</b></a>
		</td>
		</tr>
		</table></div></form>";
		$_shortGames = "";
  if (isset($_GET["winner"])) 
  {
  
  $getwinner = " AND winner = ".safeEscape(trim($_GET['winner'])); 
  $WhereWinner = " WHERE winner = ".safeEscape(trim($_GET['winner'])); 
      if ($_GET["winner"] == 3) 
      {$_shortGames = "AND g.duration <=800"; 
	  $WhereWinner = " WHERE min<=15";  $getwinner ="";}
  }
  
  else {$getwinner = ""; $WhereWinner = ""; $_shortGames = "";}

  if ($_SERVER['REQUEST_METHOD'] == 'POST' OR isset($_GET['id']))
  {
  if (isset($_POST['checkbox']) AND !isset($_POST["gid"]) AND $_SESSION['user_level'] <=3) {
          for ($i = 0; $i < count($_POST['checkbox']); $i++) {
		     $gid = safeEscape($_POST['checkbox'][$i]);
             // echo "<br />value $i = " . $_POST['checkbox'][$i];
              $sql_1 = "DELETE FROM games WHERE id = $gid LIMIT 1";
              $sql_2 = "DELETE FROM gameplayers WHERE gameid = $gid";
              $sql_3 = "DELETE FROM dotaplayers WHERE gameid = $gid";
              $sql_4 = "DELETE FROM dotagames WHERE gameid = $gid";
	  
              $result_1 = $db->query($sql_1);
              $result_2 = $db->query($sql_2);
              $result_3 = $db->query($sql_3);
              $result_4 = $db->query($sql_4);

              }
			  			  
              if ($result_1 AND $result_2 AND $result_3 AND $result_4) 
			  {echo "<br><br>$i Game(s) Deleted<br><br>
			  <a href='index.php?games&check'>Back to previous page</a>"; die;} else {
              echo "An error occured!";
           }
        }
  
    if (isset($_GET['gameid'])) 
	{
	$gid = safeEscape($_GET['gameid']);
	}
	
	if (isset($_GET["id"])) {$gid = safeEscape(trim($_GET['id']));}
	if (isset($_POST["gid"])) {$gid = safeEscape(trim($_POST['gid']));}
	
  if (!is_numeric($gid)) {echo "Try again!"; die;}
  
  $sql = "SELECT winner, dp.gameid, gp.colour, newcolour, original as hero, description, kills, deaths, assists, creepkills, creepdenies, neutralkills, towerkills, gold,  raxkills, courierkills, duration, datetime, 
	   item1, item2, item3, item4, item5, item6, g.gamename as gn,
	   it1.icon as itemicon1, 
	   it2.icon as itemicon2, 
	   it3.icon as itemicon3, 
	   it4.icon as itemicon4, 
	   it5.icon as itemicon5, 
	   it6.icon as itemicon6, 
	   it1.name as itemname1, 
	   it2.name as itemname2, 
	   it3.name as itemname3, 
	   it4.name as itemname4, 
	   it5.name as itemname5, 
	   it6.name as itemname6, 
	   leftreason, 
	   gp.left, 
	   gp.name as name, 
	   b.name as banname 
	   FROM dotaplayers AS dp 
	   LEFT JOIN gameplayers AS gp ON gp.gameid = dp.gameid and dp.colour = gp.colour 
	   LEFT JOIN dotagames AS dg ON dg.gameid = dp.gameid 
	   LEFT JOIN games AS g ON g.id = dp.gameid 
	   LEFT JOIN bans as b ON gp.name = b.name
	   LEFT JOIN heroes as f ON hero = heroid
	   LEFT JOIN items as it1 ON it1.itemid = item1
	   LEFT JOIN items as it2 ON it2.itemid = item2
	   LEFT JOIN items as it3 ON it3.itemid = item3
	   LEFT JOIN items as it4 ON it4.itemid = item4
	   LEFT JOIN items as it5 ON it5.itemid = item5
	   LEFT JOIN items as it6 ON it6.itemid = item6
	   WHERE dp.gameid=$gid $getwinner
	   ORDER BY newcolour";
	   
		  echo "<table><tr>
		  <td align='right' style='padding-right:22px;'>
		  <img alt='' style='vertical-align:middle;' border=0 width='18px' height='18px;'
		  src='../img/items/BTNCancel.gif'>
		  <a onclick='javascript:confirmDeleteGame(\"index.php?delete_game=$gid\");' href='javascript:;'>Delete game</a>
		  </td>
		  
		  </tr></table>";
		 $result = $db->query($sql);
		 echo "<div align='center'><table class='tableA'><tr>
		 <th><div align='center'>Slot</div></th>
		 <th>Switch</th>
		 <th class='padLeft'><div align='left'>Name</div></th>
		 <th>Hero</th>
		 <th title='Kills/Deaths/Assists'>K/D/A</th>
		 <th title='Creep Kills/Denies/Neutrals'>C/D/N</th>
		 <th>Items</th>
		 <th>GameID: $gid</th></tr>
		 ";
		 if ($db->num_rows($result)<=0) 
		 {echo "Game with given ID doesn't exists. </table>"; die;}
		 $err = "";$scourge = 0;

		 while ($row = $db->fetch_array($result,'assoc')) {
		 $ncol = $row["newcolour"];
		 if ($ncol == "") {$err .= "Field <b>'newcolour'</b> is empty.  Check <b>dotaplayers</b> table<br>";}
		 $col = $row["colour"];
		 if ($col == "") {$err .= "Field <b>'colour'</b> is empty.  Check <b>dotaplayers</b> table<br>";}
		 $gid = $row["gameid"];
		 if ($gid == "") {$err .= "Field <b>'gameid'</b> is unknown.<br>";}
		 $player = $row["name"];
		 if ($player == "") {$err .= "Field <b>'name'</b> is empty.<br>";}
		 $hero = $row["hero"];
		 if ($hero == "") {$err .= "Field <b>'hero'</b> is empty.<br>";}
		 $k = $row["kills"]; $d = $row["deaths"];  $a = $row["assists"];
		 if ($k == "") {$err .= "Field <b>'kills'</b> is empty.<br>";}
		 if ($d == "") {$err .= "Field <b>'deaths'</b> is empty.<br>";}
		 if ($a == "") {$err .= "Field <b>'assists'</b> is empty.<br>";}
		 
		 if ($err!="") {$err.="<hr>";}
		 
		 $c = $row["creepkills"]; $cd = $row["creepdenies"];  $n = $row["neutralkills"];
		 $i1 = $row["itemicon1"]; $i2 = $row["itemicon2"]; $i3 = $row["itemicon3"];
		 $i4 = $row["itemicon4"]; $i5 = $row["itemicon5"]; $i6 = $row["itemicon6"];
		 
		 if ($i1=="") {$i1 = "empty.gif";} 
		 if ($i2=="") {$i2 = "empty.gif";} 
		 if ($i3=="") {$i3 = "empty.gif";}
		 if ($i4=="") {$i4 = "empty.gif";} 
		 if ($i5=="") {$i5 = "empty.gif";} 
		 if ($i6=="") {$i6 = "empty.gif";}
		 
		 $gamename = $row["gn"];
		 $err2 = "";
		 if ($gamename=="") {$err2 .= "Field <b>'gamename'</b> is empty.<br>";}

		 $class = getColorOf($ncol);
		 $class2 = getColorOf($col);
		 
		 if ($col >6) {$col = $col-1;}
		 if ($ncol >6) {$ncol = $ncol-1;}
		 
		 if ($ncol >5 AND $scourge ==0) {echo "
		 <tr> 
		 <th></th> <th></th>
		 <th></th> <th></th>
		 <th></th> <th></th>
		 <th></th> <th></th>
		 </tr>";  
		 $scourge = 1;}
		 
		   $replayDate = $row["datetime"];
		   $gdate=date($date_format,strtotime($row["datetime"]));
		   $duration=secondsToTime($row["duration"]);
		   
		   $replayDate =  strtotime($replayDate)+$replayTimeOffset*3600;  //3*3600 = +3 HOURS
           $replayDate = date("Y-m-d H:i",$replayDate);
	   	   $gametimenew = substr(str_ireplace(":","-",date("Y-m-d H:i",strtotime($replayDate))),0,16);
		   $winner = $row["winner"];

		   if ($winner == 1) {$_win = "Sentinel";}
		   if ($winner == 2) {$_win = "Scourge";}
		   if ($winner != 1 AND $winner != 2) {$_win = "Draw";}
		 
		 echo "<tr class='row'>
		 <td align='right' width='42px'><span $class>$ncol</span> --></td>
		 <td align='center' width='24px'><span $class2>$col</span></td>
		 <td align='left' width='140px'>$player</td>
		 <td align='left' width='48px'><img width='24px' height='24px' alt='' src='../img/heroes/$hero.gif'></td>
		 <td title='Kills/Deaths/Assists' align='left' width='64px'>$k/$d/$a</td>
		 <td title='Creep Kills/Denies/Neutrals' align='left' width='64px'>$c/$cd/$n</td>
		 <td align='left' width='144px'>
		 <img width='24px' height='24px' alt='' src='../img/items/$i1'></img>
		 <img width='24px' height='24px' alt='' src='../img/items/$i2'></img>
		 <img width='24px' height='24px' alt='' src='../img/items/$i3'></img>
		 <img width='24px' height='24px' alt='' src='../img/items/$i4'></img>
		 <img width='24px' height='24px' alt='' src='../img/items/$i5'></img>
		 <img width='24px' height='24px' alt='' src='../img/items/$i6'></img>
		 </td>
		 <td align='left'><span style='background-color:#FAFCEE;color:#950001;'>$err</span></td>
		 </tr>";
		 if ($err!="") { $debug= "<tr><td></td><td></td><td><span style='background-color:#FAFCEE;color:#950001;'>$err</span></td><td></td><td></td><td></td><td></td><td></td></tr>";}
		 $err = "";
		 }
		 
        echo "<tr><td></td>
		<td></td><td></td><td></td><td></td><td></td><td width='220px'>Winner: <b>$_win </b>($duration)</td><td><span style='background-color:#FAFCEE;color:#950001;'>$err2</span> <b>$gamename </b></td>
		</tr><table></div>";
		
		       if (isset($_GET["id"])) {
               require_once('../includes/get_replay.php');
			   if(file_exists("../".$replayloc)) 
			   {$rep = "<span style='color:yellow'>Replay <b>available</b></span>";} 
			   else {$rep = "<span style='color:red'>Replay <b>NOT</b> available</span>";}
		       echo "<table><tr><td class='padLeft'>
			   Replay location: <input size='90' value='$replayloc'> $rep</td></tr></table><br>";}
		  }
	    }
		$sql = "SELECT COUNT(*) FROM dotagames $WhereWinner  LIMIT 1";
  
		$result = $db->query($sql);
		$r = $db->fetch_row($result);
		$numrows = $r[0];
		$rowsperpage = 50;
		include('pagination.php');
		if (!isset($_GET["order"]))
		{
		  if (isset($_GET["winner"]) AND $_GET["winner"] == 3) {$order = "g.duration";}
		  else {$order = "id"; }
		}
		   else
		   {
		   if ($_GET['order'] == 'date') 
		   {$order = ' datetime ';}
		   }

		$sql = "SELECT 
          g.id, map, datetime, gamename, ownername, duration, creatorname, dg.winner, 
		  CASE WHEN(gamestate = '17') THEN 'PRIV' ELSE 'PUB' end AS type 
		  FROM games as g 
		  LEFT JOIN dotagames as dg ON g.id = dg.gameid 
		  WHERE map LIKE '%dota%' $getwinner $_shortGames
		  ORDER BY $order $sortdb 
		  LIMIT $offset, $rowsperpage";
  
          $result = $db->query($sql);
		  echo "<form name ='myform' action='' method='post'>
		  <table class='tableA'><tr>
		  <th class='padLeft'><input type='checkbox' onClick='toggle(this)' />
		  <a href='index.php?games&check&order=id&sort=$sort'>ID</a></th>
		  <th><a href='index.php?games&check&order=game&sort=$sort'>Game Name</a></th>
		  <th><a href='index.php?games&check&order=duration&sort=$sort'>Duration</a></th>
		  <th><a href='index.php?games&check&order=date&sort=$sort'>Date</a></th>
		  <th><a href='index.php?games&check&order=creator&sort=$sort'>Creator</a></th><th></th></tr>";
		    while ($row = $db->fetch_array($result,'assoc')) {
		   $gn=$row["gamename"];
		   $gcreator=$row["creatorname"];
		   $replayDate = $row["datetime"];
		   $gdate=date($date_format,strtotime($row["datetime"]));
		   $duration=secondsToTime($row["duration"]);
		   if ($row["winner"] == 1) {$gn = "<span style='color:red'>$gn</span>";}
		   if ($row["winner"] == 2) {$gn = "<span style='color:green'>$gn</span>";}
		   
		   if (isset($_GET["id"]) AND $row["id"] == $_GET["id"] AND $_SERVER['REQUEST_METHOD'] != 'POST') 
		   {$gn = "<span style='background-color:yellow;'>$gn</span>";}
		   
		   if (isset($_GET["page"])	) {$_page = "&page=$_GET[page]";} else {$_page = "";}
		   if (isset($_GET["winner"])	) {$_winner = "&winner=$_GET[winner]&";} else {$_winner = "";}

		   echo "<tr class='row'>
		   <td align='left' class='padLeft' width='64px'>
		   <input type='checkbox' name='checkbox[]' value='$row[id]'>
		   <a href='index.php?games&check&id=$row[id]'>$row[id]</a></td>
		   <td align='left' width='250px'><a href='index.php?games&check&id=$row[id]$_winner$_page'>$gn</a></td>
		   <td align='left' width='120px'>$duration</td>
		   <td align='left' width='170px'>$gdate</td>
		   <td align='left'><a href='../user.php?u=$gcreator'>$gcreator</a>
		   <p class='alignright'>
		    <a href='javascript:confirmDeleteGame(\"index.php?delete_game=$row[id]\");'>
			<img border=0 alt='' width='16px' height='16px' 
			style='vertical-align:middle' src='../img/items/BTNCancel.gif'>
		   Delete Game</a></p>
		   </td>
		   <td></td>
		   </tr>";
		   } echo "</table><table><tr><td align='left'  class='padLeft'>
		   <input class='inputButton' type='submit' name='Submit' value='Delete Selected'>
		   </tr></td></table></form>"; 
		   
		   include('pagination.php'); echo "<br>";
	  }

	  if (isset($_GET["delete_game"]) AND is_numeric($_GET["delete_game"]) 
	  AND $_GET["delete_game"] !=""  AND $_SESSION['user_level'] <=1)
	  {
	  $gameID = safeEscape($_GET["delete_game"]);
	  $sql_1 = "DELETE FROM games WHERE id = $gameID LIMIT 1";
	  $sql_2 = "DELETE FROM gameplayers WHERE gameid = $gameID";
	  $sql_3 = "DELETE FROM dotaplayers WHERE gameid = $gameID";
	  $sql_4 = "DELETE FROM dotagames WHERE gameid = $gameID";
	  
	  $result_1 = $db->query($sql_1);
	  $result_2 = $db->query($sql_2);
	  $result_3 = $db->query($sql_3);
	  $result_4 = $db->query($sql_4);
	  	  
	  if ($result_1 AND $result_2 AND $result_3 AND $result_4)
	  {echo "<table class='tableA'><tr>
	  <td align='center'>Game: $gameID successfully deleted!</td></tr><tr>
	  <td align='center'><a href='javascript:history.go(-1);'>Back to previous page</a></td>
	  </tr></table>";}
	  }
	  
	  //ADMINISTRATORS
	  if (isset($_GET['admins']) AND $_SESSION['user_level'] <=1)
	  {
	  echo "<script type='text/javascript'>
       function confirmDelete(delUrl,mess) {
       if (confirm(mess)) {
       document.location = delUrl;
          }
       }
       </script>";
	  
	  $sql = "SELECT COUNT(*) FROM admins LIMIT 1";
	  $result = $db->query($sql);
	  $r = $db->fetch_row($result);
	  $numrows = $r[0];
	  $rowsperpage = 50;
	  
	  include("pagination.php");
	  
	  $sql = "SELECT * FROM admins ORDER BY LOWER(name) ASC LIMIT $offset, $rowsperpage";
	  $result = $db->query($sql);
	          echo "<div align='center'><table class='tableA'><tr>
			  <td align='right'><a href='index.php?edit_admin&add'>
			  <img  alt='' style='vertical-align: middle;' width='22px' height='22px' 
			  src='../img/heroes/H06S.gif' border=0/> <b>[+]Add admin</b></a>
			  </td></tr></table></div>";
			  
			  echo "<div align='center'><table class='tableA'><tr>
			  <th class='padLeft'><div align='center'>ID</div></th>
			  <th>Name</th>
			  <th>Edit</th>
			  <th>BotID</th>
			  <th>Server</th>
			  </tr>";
	          while ($row = $db->fetch_array($result,'assoc')) {
			  $Aname = $row["name"];
			  $Sname = $row["name"];
			  if (strtolower($Aname) == strtolower($head_admin))
			  { $Aname = "<span style='color:#00C412'>$Aname</span>";}
			  $AID = $row["id"];
			  $ABotID = $row["botid"];
			  $AServer = $row["server"];
			  echo "<tr class='row'>
			  <td width='48px' align='center' class='padLeft'>$AID</td>
			  <td width='200px' align='left'><a href='index.php?edit_admin=$AID'>$Aname</a></td>
			  <td width='180px'  align='left'><a href='index.php?edit_admin=$AID'>Edit</a>
			  | <a href='../user.php?u=$Sname'>View</a>
			  | <a onclick='javascript:confirmDelete(\"index.php?delete_admin=$AID&name=$Sname\",\"Are you sure you want to delete admin ($Sname)?\");' href='javascript:;' >
			  [x] Delete</a>
			  </td>
			  <td WIDTH = '65px' align='left'>$ABotID</td>
			  <td align='left'>$AServer</td>
			  </tr>";
			  }
			  echo "</table></div><br>";
			  include("pagination.php"); 
	  }
	  if (isset($_GET['delete_admin']) AND is_numeric($_GET['delete_admin']) <=1 AND $_SESSION['user_level'] <=1)
	  {
	  $del_admin = safeEscape($_GET["delete_admin"]);
	  $sql = "DELETE FROM admins WHERE id = $del_admin LIMIT 1";
	  $result = $db->query($sql);
	  if ($result) {echo "<br>Admin <b>$_GET[name]</b> successfully deleted<br><br>
	  <a href='index.php?admins'>Back to previous page</a><br><br>";}
	  }

	  if (isset($_GET['edit_admin']) AND $_SESSION['user_level'] <=1 AND $_SERVER['REQUEST_METHOD'] != 'POST')
	  {
	  $edit_admin = safeEscape($_GET["edit_admin"]);
	  
	  if (!isset($_GET["add"]))
	  {
	  $sql = "SELECT * FROM admins WHERE id = $edit_admin LIMIT 1";
	  $result = $db->query($sql);
	  if ($db->num_rows($result)<=0) {echo "Unable to find administrator"; die;}
	  $row = $db->fetch_array($result,'assoc');
	  $butt = "Edit administrator";
	  
	  $Aname = $row["name"];
	  $AID = $row["id"];
	  $ABotID = $row["botid"];
	  $AServer = $row["server"];}
	  else
	  {
	  $butt = "Add administrator";
	  $Aname = "";
	  $AID = "";
	  $ABotID = "0";
	  $AServer = "";}
	  echo '<form method="post" action="">
	  <br><div align="center">
	  <table style="width:500px;"><tr><th></th><th>'.$butt.'</th></tr>
	  <tr class="row">
	  <td width="70px;" align="right">Name:</td>
	  <td><input id="name" type="text" name="name" value="'.$Aname.'" size="40" maxlength="80"/></td></tr>
	  
	  <tr class="row">
	  <td width="70px;" align="right">BotID:</td>
	  <td><input id="name" type="text" name="bot" value="'.$ABotID.'" size="8" maxlength="8"/></td></tr>
	  <td width="70px;" align="right">Server:</td>
	  <td><input id="name" type="text" name="server" value="'.$AServer.'" size="25" maxlength="30"/></td></tr>
	  <td></td>
	  <td><input type="submit" class="inputButton" value="'.$butt.' '.$Aname.'" /></td></tr>
	</form></table></div><br>';
	  }
	  if (isset($_GET['edit_admin']) AND $_SESSION['user_level'] <=1 
	  AND $_SERVER['REQUEST_METHOD'] == 'POST' )
	  {
	  $edit_admin = safeEscape($_GET["edit_admin"]);
	  $Aname = convEnt2($_POST["name"]);
	  $AServer = strip_tags(convEnt2($_POST["server"]));
	  $ABotID = convEnt2($_POST["bot"]);
	  
	  if (!isset($_GET["add"]))
	 {$sql = "UPDATE admins SET botid = $ABotID, name = '$Aname', server = '$AServer'
	  WHERE id = $edit_admin LIMIT 1";}
	  else
	  {$sql = "INSERT INTO admins(botid,name,server)
	  VALUES($ABotID,'$Aname','$AServer')";}
	  
	  $result = $db->query($sql);
	  if ($result) 
	  {echo "<br>Admin updated successfully<br><br><a href='index.php?admins'>Back to previous page</a><br><br>";}
	  }
	  
	 if ($pageGen==1) {
	 echo "<table><tr><td align='center'>Total queries: ".$db->get_query_cout()."</td></tr></table><br>";}
	 ?>
	</body><div>
	 <table><tr>
	 <td style="padding-right:12px;text-align:right;" align="right">
	 &copy; '.date("Y").' <a href=\'http://openstats.iz.rs\'><b>DotA OpenStats</b></a></td>
	 </tr></table></div>
    <?php
	} //IS LOGGED
	?>