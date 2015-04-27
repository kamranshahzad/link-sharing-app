<?php
	
	//date_default_timezone_set("America/New_York");
	//date_default_timezone_set("Asia/Karachi");

	class DBWrapper{
		
		
		private $host, $user, $pass, $db_name;
		
		private $link;
		
		private $result;
		
		private $errObj;
		
		const DATETIME = 'Y-m-d H:i:s';
		
		const DATE = 'Y-m-d';
		
		
		public function __construct($host, $user, $password, $db, $persistant = true, $connect_now = true){
			$this->errObj = new Error();
			
			$this->host = $host; 
			$this->user = $user;	
			$this->pass = $password;
			$this->db_name = $db;	
	
			if ($connect_now)
				$this->connect($persistant);
	
			return;
			
			
		}
		
		
		
		public function connect($persist = true){
			if ($persist)
				$link = mysql_pconnect($this->host, $this->user, $this->pass);
			else
				$link = mysql_connect($this->host, $this->user, $this->pass);
	
			if (!$link)
				$this->errObj->draw("Could not connect to the database.");
					
			if ($link)
			{
				$this->link = $link;
				if (mysql_select_db($this->db_name, $link))
					return true;
			}
	
			return false;
		}
		
		
		//  @ db Instance
		
		public function getInstance(){
			return $this->link;
		}
		
		
		// @ db schema 
		
		public function tableStructure($table='',$type='Field'){
			$rsVar = $this->query("DESCRIBE $table");
			while($i = $this->fetchAssoc($rsVar))
				$colArr[] =  $i[$type]; //Type ,Null ,Key ,Default, Extra          
			return $colArr;	
		}
		
		public function tableMetadata($table=''){
			$res = array(); 
			$id = @mysql_list_fields( $this->db_name , $table);
			$count = @mysql_num_fields($id);
			for ($i=0; $i < $count; $i++) {
				$column  = @mysql_field_name  ($id, $i);
				$res[$column]["type"]  = @mysql_field_type  ($id, $i);
				$res[$column]["len"]   = @mysql_field_len   ($id, $i);
				$res[$column]["flags"] = @mysql_field_flags ($id, $i);
			}
			@mysql_free_result($id);
			return $res; 
		}
		
		public function fieldName($rsVar = false, $offset){
			return mysql_field_name($rsVar, $offset);
		}
		
		public function fieldNameArray($rsVar = false){
			$names = array();
			$field = $this->countFields($rsVar);
			for ( $i = 0; $i < $field; $i++ )
				$names[] = $this->fieldName($rsVar, $i);
			return $names;
		}

		function optimize(){ //$this->query('OPTIMIZE TABLE `' . $table['Name'] . '`'); 
		}
		
		function showProcessList(){
			return $this->query("show processlist");	
		}
		
		
		
		// @ core mysql
		public function fetchAssoc($rsVar){
			return mysql_fetch_assoc($rsVar);
		}
		
		public function fetchObj($rsVar){
			return mysql_fetch_object($rsVar);
		}
		
		public function fetchArr($rsVar , $type = 'num'){
			if($type == 'num'){
				return mysql_fetch_array($result, MYSQL_NUM);
			}
			return mysql_fetch_array($result, MYSQL_ASSOC);
		}
		
		public function fetchRow($rsVar){
			return mysql_fetch_row($rsVar);
		}

		public function countRows($rsVar){
			return (int) mysql_num_rows($rsVar);
		}
		
		public function countFields($rsVar){
			return (int) mysql_num_fields($rsVar);
		}
		
		public function countColumnValues($rsVar){
			return mysql_fetch_lengths($rsVar); // print_r
		}
		
		public function insertId(){
			return (int) mysql_insert_id($this->link);
		}
		
		public function affectedRows(){
			return (int) mysql_affected_rows($this->link);
		}
		
		public function error(){
			return mysql_error($this->link);
		}
		
		public function dumpInfo(){
			echo mysql_info($this->link);
		}
		
		public function query($sql){
			
			list($usec, $sec) = explode(' ', microtime());
            $start_timer = (float)$usec + (float)$sec;
			
			$sql = preg_replace(array("/^\s+/m", "/\r\n/"), array('', ' '), $sql);
			$this->result = mysql_query($sql, $this->link);
			
			list($usec, $sec) = explode(' ', microtime());
            $stop_timer = (float)$usec + (float)$sec;
			$total_execution_time = $stop_timer - $start_timer;
			
			if ($this->result == false)
				$this->errObj->draw('Uncovered an error in your SQL query script: "' . $this->error() . '"');

			return $this->result;
		}
		
		public function bufferQuery($sqlQuery){
			return mysql_unbuffered_query($sqlQuery);  // can't use with this mysql_num_rows()
		}
		
		
		// @ disposing
		
		public function close(){
			//return mysql_close($this->link);
		}
		
		
		public function freeResult(){
			return mysql_free_result($this->result);
		}
		
		public function __destruct(){
			$this->close();
		}
		
		
		
		// @ utils 
		public function escapeString($string){
			if (get_magic_quotes_gpc()) {
				$string = stripslashes($string);
			}
			return mysql_real_escape_string($string, $this->link);
		}
		
		
		
		//  @  crud
		public function update(array $values, $table, $where = false, $limit = false){
			if (count($values) < 0)
				return false;
				
			$fields = array();
			foreach($values as $field => $val)
				$fields[] = "`" . $field . "` = '" . $this->escapeString($val) . "'";
	
			$where = ($where) ? " WHERE " . $where : '';
			$limit = ($limit) ? " LIMIT " . $limit : '';
	
			if ($this->query("UPDATE `" . $table . "` SET " . implode($fields, ", ") . $where . $limit))
				return true;
			else
				return false;
		}
		
		public function insert(array $values, $table){
			if (count($values) < 0)
				return false;
			
			foreach($values as $field => $val)
				$values[$field] = $this->escapeString($val);
	
			if ($this->query("INSERT INTO `" . $table . "`(`" . implode(array_keys($values), "`, `") . "`) VALUES ('" . implode($values, "', '") . "')"))
				return true;
			else
				return false;
		}
		
		public function delete($table, $where = false, $limit = 1){
			$where = ($where) ? " WHERE " . $where : '';
			$limit = ($limit) ? " LIMIT " . $limit : '';
	
			if ($this->query("DELETE FROM `" . $table . "`" . $where . $limit))
				return true;
			else
				return false;
		}
		
		public function select($fields, $table, $where = false, $orderby = false, $limit = false){
			if (is_array($fields))
				$fields = "`" . implode($fields, "`, `") . "`";
	
			$orderby = ($orderby) ? " ORDER BY " . $orderby : '';
			$where = ($where) ? " WHERE " . $where : '';
			$limit = ($limit) ? " LIMIT " . $limit : '';
	
			$this->query("SELECT " . $fields . " FROM `" . $table . "`" . $where . $orderby . $limit);
	
			if ($this->countRows() > 0)
			{
				$rows = array();
	
				while ($r = $this->fetchAssoc())
					$rows[] = $r;
	
				return $rows;
			} else
				return false;
		}
		
		public function selectOne($fields, $table, $where = false, $orderby = false){
			$result = $this->select($fields, $table, $where, $orderby, '1');
			return $result[0];
		}
		
		public function selectOneValue($field, $table, $where = false, $orderby = false){
			$result = $this->selectOne($field, $table, $where, $orderby);
			return $result[$field];
		}
		
		public function fetchOne($query = false){
			list($result) = $this->fetchRow($query);
			return $result;
		}
		
		
		
		// @ Handle Quries
		
		
		
		// @ Data Fetch
		public function fetchRecords(){
			$result = mysql_query('SELECT name FROM work.employee');
			echo mysql_result($result, 2); // outputs third employee's name	
		}
		
	
		
		
		
		
		
		
	} // $


?>