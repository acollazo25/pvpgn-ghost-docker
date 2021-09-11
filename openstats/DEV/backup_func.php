<?PHP

      	   function backup_tables($host,$user,$pass,$name,$tables = 'admins,bans,dotagames,dotaplayers,downloads,gameplayers,games,heroes,items,news,scores')
    {
	$link = mysql_connect($host,$user,$pass);
	mysql_select_db($name,$link);
	$return = "";
	//get all of the tables
	if($tables == '*')
	{
		$tables = array();
		$result = mysql_query('SHOW TABLES');
		while($row = mysql_fetch_row($result))
		{
			$tables[] = $row[0];
		}
	}
	else
	{
		$tables = is_array($tables) ? $tables : explode(',',$tables);
	}

	//cycle through
	foreach($tables as $table)
	{
	    
		$result = mysql_query('SELECT * FROM '.$table);
		$num_fields = mysql_num_fields($result);
		$return.= 'DROP TABLE IF EXISTS '.$table.';';
		$row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
		$return.= "\n\n".$row2[1].";\n\n";
		
		for ($i = 0; $i < $num_fields; $i++) 
		{
			while($row = mysql_fetch_row($result))
			{
				$return.= 'INSERT INTO '.$table.' VALUES(';
				for($j=0; $j<$num_fields; $j++) 
				{
					$row[$j] = addslashes($row[$j]);
					$row[$j] = ereg_replace("\n","\\n",$row[$j]);
					if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
					if ($j<($num_fields-1)) { $return.= ','; }
				}
				$return.= ");\n";
			}
		}
		$return.="\n\n\n";
	}
			
	//save file
	$backup = 'backup/dotaOS-'.date("d-M-Y__h-i-s").'.sql';
	$handle = fopen($backup,'w+');
	$filename = substr($backup,0,50);
	$filename = str_replace("backup/","",$backup);
	//$handle = fopen('db-backup-'.time().'-'.(md5(implode(',',$tables))).'.sql','w+');
	echo '<br/><div align="center">Backup successfully created.<br/><br/>
	<b>Download:</b><br><br/> <a href="'.$backup.'">'.$filename.'</a><br><br>
	<a href="index.php?backup">Back to previous page</a></div>';
	fwrite($handle,$return);
	fclose($handle);

    } //END BACKUP FUNC
	
	
	?>