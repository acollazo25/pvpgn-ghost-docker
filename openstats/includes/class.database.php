<?
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

      define("database","");    
class database {

    // mysql link identifier
    var $link_ident = NULL;
    
    // debug mode
    var $debug = true;
    
    // query counter
    var $query_count = 0;
    
    var $login = array();
    
    var $last_id = NULL;
    
    var $default_error_message = 'Sorry, a internal error occured. Please go back an try it again.<br/><br/><a href="javascript:history.back(-1)">Back</a>';
        
    /*
    ** database
    ** database constructor
    */ 
        
    function database(){        
        
        // parameter count:
        $args = func_num_args();
        
        if ($args == 4) {
            /*  
                set login data:
                host, username, password, database
            */
            $this->login = func_get_args();
        }
        
    }
    
    
    /*
    ** set_login
    ** set login data (host, username, password, database)
    */ 
        
    function set_login(){        
        
        // parameter count:
        $args = func_num_args();
        
        if ($args >= 3) {
            /*  
                set login data:
                host, username, password, database
            */
            $this->login = func_get_args();
        }
        
    }
    
    
    /*
    ** set_database
    ** 
    */ 
        
    function set_database($database){        
        $this->login[3] = $database;        
    }
    
    
    /*
    ** is_connected
    ** 
    */ 
    
    function is_connected(){        
        return ($this->link_ident !== NULL);        
    }
    
    
    /*
    ** error
    ** 
    */ 
    
    function error($msg){    
    
        $error = @mysql_error($this->link_ident);
        $errno = @mysql_errno($this->link_ident);
        
        if ($this->debug){
        
            print '<div style="border: 1px solid red; padding: 1em; font-family: Verdana;">';        
            print '<b>MySQL Database Error:</b><br/>';
            print $msg;
            print '<br/><br/><b>MySQL Error:</b> ' . $errno . ' ( ' . $error . ')<br/>\n';
            print '</div>';    

        }
        else {
            print $this->default_error_message;            
        }        
        exit;    
    }
    
    
    /*
    ** connect
    ** Connect to the database server.
    */ 
    
    function connect(){
    
        if ($this->is_connected()) {
            // Connection is alread etablished
            return true;
        }
            
        if (count($this->login) != 4) {
            $this->error('Did not found the login settings.');
            return false;
        }
    
        $this->link_ident = mysql_connect($this->login[0], $this->login[1], $this->login[2]); 
    
        if (!$this->link_ident) {
            $this->error('Could not connect to the database.');
            return false;
        }
    
        if (!@mysql_select_db($this->login[3], $this->link_ident)) {
            $this->error('Could not select the database.');
            return false;
        }
        
        return true;        
    }     


    /*
    ** get_query_cout
    ** 
    */ 
    
    function get_query_cout(){    
        return $this->query_count;    
    }
    
    
    /*
    ** simple_escape
    ** 
    */ 
    
    function simple_escape($str) {
        if (function_exists('mysql_real_escape_string')) {
            return @mysql_real_escape_string($str, $this->link_ident);
        } else {
            return @mysql_escape_string($str);
        }
    }
    
    
    /*
    ** insert_array
    ** 
    */ 
    
    function insert_array($table, $data_array){
        
        $row_keys = array();
        $row_vals = array();
        
        // go trough all keys:
        while (list($key, $val) = each($data_array)){
        
            // add key
            $row_keys[] = "`$key`";
            
            // prepare and add value
            if ($val == 'NOW()'){ $val = 'NOW()'; }
            else if (!is_numeric($val)) { $val = '"'.$this->simple_escape($val).'"'; }
            
            $row_vals[] = $val;
        
        }
        
        // generate query string
        $query_str = 'INSERT INTO ' . $table;
        $query_str .= ' ('.implode(',', $row_keys).') ';
        $query_str .= 'VALUES ('.implode(',', $row_vals).');';
        
        // exec. query:
        return $this->query($query_str);
    
    }


    /*
    ** prepare_query
    ** 
    */ 
        
    function prepare_query(){        
        
        // parameter count:
        $args = func_num_args();
        
        if ($args >= 1) {
  
            $args = func_get_args();
            
            $sql = $args[0];
            unset($args[0]);
            
            return vsprintf($sql, $args);
                        
        }
        
        return '';
        
    }
    
    
    /*
    ** close
    ** Close the database connection.
    */ 
    
    function close(){        
        if ($this->is_connected()) {
            $r = @mysql_close($this->link_ident);
            $this->link_ident = NULL;
            return $r;
        }        
    }


    /*
    ** print_query
    ** Print query
    */       
    
    function print_query($query_string) {
        print '<div style="border: 1px solid red; padding: 0em 1em; font-family: Verdana;"><pre>';
        print_r($query_string);
        print '</pre></div>';
    }
    
    
    /*
    ** get_insert_id
    ** 
    */ 
    
    function get_insert_id(){        
        return mysql_insert_id($this->link_ident);        
    }
    
    
    /*
    ** debug_query
    ** 
    */ 
    
    function debug_query($query_string) {
        
        $r = $this->query($query_string);
        
        print '<table border="1" cellpadding="4" cellspacing="0" width="90%">';
        print '<tr style="background-color: #FDFAB6;"><td><b>SQL Query:</b><br/>'; 
        print htmlentities($query_string);        
        print '</td></tr>';        
        print '</table><br/>';        

        print '<table border="1" cellpadding="4" cellspacing="0" width="90%">';
        
        $first_row = $this->fetch_array($r);
        
        if ($first_row){
            
            $keys = array_keys($first_row);
            
            print '<tr style="background-color: #FDFAB6; text-align: left;">';
            for ($x=0; $x < count($keys); $x++){
                print '<th>'.$keys[$x].'</th>';
            }
            print '</tr>';
            
            $x = 0;
            while ($row = $this->fetch_array($r)){
                print '<tr style="background-color: '.(($x % 2) ? '#FFFFFF' : '#EEEEEE').';">';        
                while(list($name, $value) = each($row)){
                    print ($value != '') ? '<td>'.htmlentities($value).'</td>' : '<td>&nbsp;</td>';
                }
                print "</tr>\n";
                $x++;
            }        
            print '</table>';            
        }
        else {
        
            print '<tr><td>No rows found!</td></tr>';
        
        }
    }
    
    
    /*
    ** query
    ** 
    */ 
    
    function query($query_string) {
    
        // Connect to the database if we are 
        // not already connected
        $this->connect();
    
        $query_string = trim($query_string);
        if (empty($query_string) or $query_string == '') {
            return false;
        }
        
        // Check if we are connected to the database
        if (!$this->is_connected()) {        
            $this->error('No database connection found.');
            return false;
        }
    
        // Execute the query:
        $q_id = @mysql_query($query_string, $this->link_ident);
        // Update query count
        $this->query_count++;
    
        // If a empty result is given
        if (!$q_id) {
            $this->error('The database returned a invalid result.');
            return false;
        }
		
        return $q_id;
		 
    }
    

    /*
    ** query_fetch
    ** 
    */ 
    
    function &query_fetch($query_string){
    
        $query_handle = $this->query($query_string);
        
        if ($this->num_rows($query_handle) == 0){
            return array();
        }
        else {
            return $this->fetch_array($query_handle);
        }
    
    }
    
    
    /*
    ** query_single_result
    ** 
    */ 
    
    function query_single_result($query_string){
    
        $query_handle = $this->query($query_string);
        
        if ($this->num_rows($query_handle) == 0){
            return '';
        }
        else {
            $result = $this->fetch_array($query_handle, 'num');
            return isset($result[0]) ? $result[0] : '';
        }
    
    }
    
    
    /*
    ** count_rows
    ** 
    */ 
    
    function count_rows($query_string){
    
        $query_handle = $this->query($query_string);
        
        if (!$this->num_rows($query_handle)){            
            return 0; 
        }
        else {
            $row = $this->fetch_array($query_handle);
            return isset($row[0]) ? $row[0] : 0;
        }
    }
    
    
    /*
    ** num_rows
    ** 
    */ 
    
    function num_rows($r){
        return @mysql_num_rows($r);
    }
    
    
    /*
    ** num_cols
    ** 
    */ 
    
    function num_cols($r){
        return @mysql_num_fields($r);
    }


    /*
    ** table_size
    ** returns something like: 484.2 KB
    */ 
    
    function table_size($table_name, $database=NULL) {
    
        if ($database === NULL){
            $database = isset($this->login[3]) ? $this->login[3] : '';
        }
        
        if ($database == ''){
            $this->error('No database given.');
        }
    
        $this->connect();
                
        $r = $this->query("SHOW TABLE STATUS FROM ".$database." LIKE '".$table_name."'", $this->link_ident);                        
        $size = intval($this->result($r, 0, 'Index_length')) + intval($this->result($r, 0, 'Data_length'));        

        return $this->nice_date($size);
    }
    
    
    /*
    ** nice_date
    ** returns something like: 484.2 KB
    */ 
    
    function nice_date($size){
        $units = array(' B', ' KB', ' MB', ' GB', ' TB');
        for ($i = 0; $size > 1024; $i++) { $size /= 1024; }
        return round($size, 2).$units[$i];
    }


    /*
    ** database_size
    ** returns something like: 484.2 KB
    */ 
    
    function database_size($database) {
    
        $this->connect();
        
        $tables = mysql_list_tables($database, $this->link_ident);        
        if (!$tables) { return -1; }
        
        $table_count = mysql_num_rows($tables);
        $size = 0;
        
        for ($i=0; $i < $table_count; $i++) {        
            $table_name = mysql_tablename($tables, $i);            
            $r = $this->query("SHOW TABLE STATUS FROM ".$database." LIKE '".$table_name."'", $this->link_ident);                        
            $size += intval($this->result($r, 0, 'Index_length')) + intval($this->result($r, 0, 'Data_length'));        
        }        
        
        return $this->nice_date($size);
    }
    
    
    /*
    ** affected_rows
    ** 
    */ 
    
    function &affected_rows(){
        return @mysql_affected_rows($this->link_ident);
    }
    
    
    /*
    ** fetch_object
    ** 
    */ 
    
    function &fetch_object($handle) {            
        return @mysql_fetch_object($handle);   
    }
    
    
    /*
    ** fetch_array
    ** 
    */ 
    
    function &fetch_array($handle, $type = 'assoc') {

        if ($type == 'assoc'){ $result_type = MYSQL_ASSOC; }
        else if ($type == 'num'){ $result_type = MYSQL_NUM; }
		else if ($type == 'row'){ $result_type = MYSQL_ROW; }
        else { $result_type = MYSQL_BOTH; }
		//I hate this :)
        $return = @mysql_fetch_array($handle, $result_type);  
        return $return;   
    }
     
	    function &fetch_row($handle) {
        $return = @mysql_fetch_row($handle); 
        return $return;   
    } 
    
    /*
    ** free
    ** Deletes the results and frees the memory
    */ 
    
    function free($result){
        return @mysql_free_result($result);
    }
    
    /*
    ** result
    ** 
    */ 
    
    function &result($handle, $row, $col) { 
    $return = @mysql_result($handle, $row, $col);
        return $return;
    }
	
   function getUserWins($username) {
	$sql = "SELECT COUNT(*) FROM gameplayers LEFT JOIN games ON games.id=gameplayers.gameid LEFT JOIN dotaplayers ON dotaplayers.gameid=games.id AND dotaplayers.colour=gameplayers.colour LEFT JOIN dotagames ON games.id=dotagames.gameid WHERE LOWER(name) = LOWER('$username') AND ((winner=1 AND dotaplayers.newcolour>=1 AND dotaplayers.newcolour<=5) OR (winner=2 AND dotaplayers.newcolour>=7 AND dotaplayers.newcolour<=11)) AND gameplayers.`left`/games.duration >= 0.8 LIMIT 1";

		$result = mysql_query($sql);
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		$inwins=$row["COUNT(*)"];
		//mysql_free_result($result);
	return $inwins;
}

     function getUserLosses($username) {
	$sql = "SELECT COUNT(*) FROM gameplayers LEFT JOIN games ON games.id=gameplayers.gameid LEFT JOIN dotaplayers ON dotaplayers.gameid=games.id AND dotaplayers.colour=gameplayers.colour LEFT JOIN dotagames ON games.id=dotagames.gameid WHERE name='$username' AND ((winner=2 AND dotaplayers.newcolour>=1 AND dotaplayers.newcolour<=5) OR (winner=1 AND dotaplayers.newcolour>=7 AND dotaplayers.newcolour<=11)) AND gameplayers.`left`/games.duration >= 0.8  LIMIT 1";

	
		$result = mysql_query($sql);
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		$inlosses=$row["COUNT(*)"];
		//mysql_free_result($result);
	return $inlosses;
}
	
	

    // end of class

}

?>