<?
/* 
	(c) 2009 ArTrix dEsiGn www.artrixdesign.com
	
	----------------------------------------------------------------
	Edited Note					Date
	----------------------------------------------------------------
	1. NORRAKITH S.				July 17, 2008
	2. NORRAKITH S.				Jan 29, 2009
	3. NORRAKITH S				Nov 05, 2011
	---------------------------------------------------------------
*/

class mysql_database{

	var $conn_id;								// connection id; use when select, insert, update, delete
	var $result_id;								// result id to be use when fetch data
	var $tablename		= "";					// Table Name
	var $use_fields		='*';					// List the field used in each time object created
	var $numrow		= 0;
	var $recordset;
	var $blobSize = 64; 	/// any varchar/char field this size or greater is treated as a blob
							/// in other words, we use a text area for editting.
	var $canSeek = false; 	/// indicates that seek is supported
	var $sql; 				/// sql text

	function mysql_database(){ // connect
		$this->conn_id = mysql_connect(BASS_HOST,BASS_USER,BASS_PASS) or trigger_error(mysql_error(),E_USER_ERROR);
		mysql_select_db(BASS_BASENAME, $this->conn_id);
		@mysql_query("SET NAMES utf8;", $this->conn_id);
	}
	
	// Manual Query
	function execute($sql){
		//echo $sql;
		$this->result_id = mysql_query($sql,$this->conn_id) or die(mysql_error());
	}

	// Get array
	function getrows($onerow = false) {
		$results = array();
		if($onerow) return mysql_fetch_assoc($this->result_id);
		while ($row = mysql_fetch_assoc($this->result_id)) {
			 is_array($row) ? $results[] = $row : NULL;
		}
		return $results;
	}
	
	// Get array
	function affectrow() {
		return @mysql_affected_rows();
	}
	
	// Get array
	function numrow() {
		$this->numrow = @mysql_num_rows($this->result_id);
		return $this->numrow;
	}
	
	// select database
	function select($sqlPortion="") {
		$sql = "SELECT " . $this->use_fields ." FROM $this->tablename " . $sqlPortion;
		//echo $sql."<br>";
		$this->execute($sql);
		$this->numrow = @mysql_num_rows($this->result_id);
		return @mysql_fetch_assoc($this->result_id);
	}

	// count database
	function countrow($sqlPortion=""){
		$sql = "SELECT count(*) FROM $this->tablename " . $sqlPortion;
		$this->execute($sql);
		$result_fetch = @mysql_fetch_assoc($this->result_id);
		//print_r( $result_fetch);
		return $result_fetch["count(*)"];
	}
	
	// get next id
	function nextid($key,$sqlPortion=""){
		$sql = "SELECT max(".$key.") as maxid FROM $this->tablename " . $sqlPortion;
		$result_fetch = $this->execute($sql);
		return $result_fetch["maxid"]+1;
	}
	
	// Manual Query -- no fetch
	function executen($sql){
		return mysql_query($sql,$this->conn_id) or die(mysql_error());
	}

	// view table
	function isNext() {return (mysql_fetch_assoc($this->result_id));}
		
	// add database
	function insert($field, $var){
		$sql = "INSERT INTO $this->tablename (" . $field . ") VALUES (" . $var . ")";
		return $this->executen($sql);
	}

	// update database
	function update($sqlPortion, $set){
		$sql = "UPDATE $this->tablename SET " . $set ." ". $sqlPortion;
		return $this->executen($sql);
	}

	// del database
	function deletes($wherePortion){
		$sql = "DELETE FROM $this->tablename " . $wherePortion;
		return $this->executen($sql);
	}

	// close database
	function close() {mysql_close($this->conn_id);}
}
?>