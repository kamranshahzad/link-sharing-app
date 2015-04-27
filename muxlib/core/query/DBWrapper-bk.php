<?php

class DBWrapper{
	
	var $link_identifier;
	var $database;
	public $errObj;
	
	function __construct(){
        $this->database = $this->link_identifier = false;
		$this->errObj = new Error();
	}
	
	public function getDbInstance(){
		return $this->link_identifier;
	}
	
	public function dbConnect(){
		$configObj = new config();
		$appConfig = $configObj->getConfig();
		
        if (!$this->link_identifier) {
            if (!($this->link_identifier = @mysql_connect($appConfig['dsn']['host'], $appConfig['dsn']['username'] ,$appConfig['dsn']['password']))) {
				$this->errObj->draw("Database Connection Error.Please check you db information.");
				return false;
            }
            if (!($this->database = @mysql_select_db($appConfig['dsn']['dbname'], $this->link_identifier))) {
                $this->errObj->draw();
				trigger_error('Wrong Database name', E_USER_ERROR); 
				return false;
            }
        }
        return true;
    }
	
	public function _tblColumns($tbl=''){
			$colArr = array();
			$query ="DESCRIBE $tbl";
			$result = mysql_query($query);
			while($i = mysql_fetch_assoc($result))
				$colArr[] =  $i['Field'];
			return $colArr;	
	}
	
	public function metadata($table=''){
		$res = array(); 
		$id = @mysql_list_fields('testdb', $table);
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

	public function escape($string){
		if (get_magic_quotes_gpc()) {
			$string = stripslashes($string);
		}
		$result = mysql_real_escape_string($string, $this->link_identifier);
		return $result;
        return false;
    }
	
	
	function close(){
        @mysql_close($this->link_identifier);
    }
	
	
	function showProcessList(){
		mysql_query("show processlist");	
	}
	
	
	public function query($sql){
        $sql = preg_replace(array("/^\s+/m", "/\r\n/"), array('', ' '), $sql);
		$this->last_result = @mysql_query($sql, $this->link_identifier);
		return $this->last_result;
	}
	
	public function insert($table, $columns ){
		foreach ($columns as $field=>$value) {
			$fields[] = '`' . $field . '`';
			$values[] = "'" . $this->escape($value) . "'";
		}
		$field_list = join(',', $fields);
		$value_list = join(', ', $values);
	
		$query = "INSERT INTO `" . $table . "` (" . $field_list . ") VALUES (" . $value_list . ")";
		$this->query($query);	
    }
	
	

	public function update($table , $data , $key ){
		foreach ($data as $field=>$value) {
			$fieldValues[] = $field ."='". $this->escape($value)."'";
		}
		$data_list = join(',', $fieldValues);
	
		$query = "UPDATE $table SET $data_list WHERE $key";
		$this->query($query);	
	}
	
	
	public function lastid(){
		return mysql_insert_id();	
	}
	
	
	
	// @ Handle Resources
	
	
	
	
	
	// @ Handle Quries
	public function bufferQuery($sqlQuery){
		return mysql_unbuffered_query($sqlQuery);  // can't use with this mysql_num_rows()
	}
	
	
	// @ Data Fetch
	public function fetchRecords(){
		$result = mysql_query('SELECT name FROM work.employee');
		echo mysql_result($result, 2); // outputs third employee's name	
	}
	
	public function fetchRow(){
		$result = mysql_query("SELECT id,email FROM people WHERE id = '42'");
		$row = mysql_fetch_row($result);
		echo $row[0]; // 42
		echo $row[1]; // the email value
	}
	
	function mysql_fetch_all($result) {
	   while($row=mysql_fetch_array($result)) {
		   $return[] = $row;
	   }
	   return $return;
	}
	
	
	public function fetchRecordsArray(){
		$result = mysql_query("SELECT id, name FROM mytable");
		while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
			printf("ID: %s  Name: %s", $row[0], $row[1]);  
		}
		
		$result = mysql_query("SELECT id, name FROM mytable");

		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			printf("ID: %s  Name: %s", $row["id"], $row["name"]);
		}	
	}
	
	function mysql_fetch_full_result_array($result)
	{
		$table_result=array();
		$r=0;
		while($row = mysql_fetch_assoc($result)){
			$arr_row=array();
			$c=0;
			while ($c < mysql_num_fields($result)) {        
				$col = mysql_fetch_field($result, $c);    
				$arr_row[$col -> name] = $row[$col -> name];            
				$c++;
			}    
			$table_result[$r] = $arr_row;
			$r++;
		}    
		return $table_result;
		//echo $arr_table_result[2]['id'];
	}
	
	public function fetchRecordsAssocArray(){
		while ($row = mysql_fetch_assoc($result)) {
			echo $row["userid"];
			echo $row["fullname"];
			echo $row["userstatus"];
		}
	}
	
	
	public function fetchRecordsObj(){
		
		/*
		class Test {
    
			public $go ;
			private $id ;
			
			function show() {
				return "id: {$this->id} go: {$this->go}" ;
			}
			
			function __construct()
			{
				echo "in __construct() ". $this->show() ."\n" ;
				$this->go = uniqid()    ;
			}
			
		}
		$res = mysql_query('SELECT * FROM `test`', $db)
		
		while($obj = mysql_fetch_object($res, 'Test') ){
			echo "outside ________ ". $obj->show() ."\n\n";
		}
		*/
		
	}
	
	
	
	
	
	
	// @ Dispose all things
	
	public function exitDb(){
		mysql_close($this->link_identifier);
	}
	
	
	
} // $


?>