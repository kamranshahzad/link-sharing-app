<?php


abstract class Mapper extends DBWrapper{
	
	
	public static $DB_INFO  = array();
	public static $FRM_INFO = array();
	private $TABLE;
	private $PK;
	private $FK;
	private $MODEL;
	public static $GETTER	= array();
	public static $FIELDS = array();
	
	public function __construct() {
		$this->init();	
	}
	
	
	private function init(){
		$this->MODEL = StringUtil::className(get_class($this));
		parent::__construct();
		$this->dbConnect();
		
		$this->TABLE 	= $this->getInfo('TABLE');
		$this->PK		= $this->getInfo('PK');
		$this->FK		= $this->getInfo('FK');
		

		foreach($this->_tblColumns($this->TABLE) as $fields){
			self::$GETTER[] = $this->getterString($fields);
			self::$FIELDS[] = $fields;
		}
	}
	
	public function fetchAll(){
		
		$Critera = array('col'=>array());
		
		$data = array();
		$sql = "SELECT * FROM ".$this->TABLE;
		$result = mysql_query($sql);
		while($row = mysql_fetch_object($result)){
			$tmp = array();
			foreach($this->_tblColumns($this->TABLE) as $fields){
				$tmp[$this->getCleanField($fields)] = $row->$fields;
			}
			$obj = new $this->MODEL($tmp);
			$data[] = $obj;
		}
		return $data; 
	}
	
	
	public function save($table , $data , $criteria = ''){
		$colums = array();
		foreach($data as $key=>$value){
			if(in_array( $key ,self::$FIELDS)){
				$colums[$key] =  $value;
			}
		}
		if(!empty($criteria)){
			unset($colums[$this->PK]);
			$this->update($table,$colums , $criteria );
		}else{
			$this->insert($table,$colums);
		}
	}
	
	
	
	
	public function fetchAllById($id){
		$rS = $this->query("SELECT * FROM $this->TABLE WHERE $this->PK='$id' ");
		return mysql_fetch_assoc($rS);
	}
	public function fetchAllByKey( $filterKeys =array()){}
	public function fetchRow(){}
	
	
	public function isExist( $criteria = array() ){
		foreach ($criteria as $field => $value) {
			$fieldValues[] = $field ."='". stripslashes($value)."'";
		}
		$filterString = join(' AND ', $fieldValues);
		if(mysql_num_rows(mysql_query("SELECT $this->PK FROM $this->TABLE WHERE $filterString "))){
			return true;
		}	
		return false;
	}
	
	
	
	
	
	public function countRows(){}
	public function delete($id){
		return $this->query("DELETE FROM $this->TABLE WHERE $this->PK='$id' ");
	}
	
	public function find(){}
	
	
	public function getInfo($key){
		if(array_key_exists( $key , self::$DB_INFO )){
			return self::$DB_INFO[$key];	
		}
		//put in debug (Please define you Mapper info)
	}
	
	
	
	
	// # Resource functions
	public function burnResource($sqlQuery){
		$result = mysql_query('SELECT * WHERE 1=1');
		if (!$result) {
    		die('Invalid query: ' . mysql_error());
		}
		
		$query = sprintf("SELECT firstname, lastname, address, age FROM friends 
    WHERE firstname='%s' AND lastname='%s'",
    mysql_real_escape_string($firstname),
    mysql_real_escape_string($lastname));

// Perform Query
$result = mysql_query($query);
		
		$result = mysql_query("set names 'utf8'"); 
		
		
	}
	
	
	public function countRows_r($resource){
		if(is_resource($resource)){
			return mysql_num_rows($resource);
		}
	}
	
	
	
	
	
	// # Helper functions
	
	private function getCleanField($field){
		if($field != ''){
			return strtolower(str_replace('_','',str_replace('-','',$field)));
		}
	}
	 
	private function getterString($str){
		if($str != ''){
			return 'get'.ucwords(strtolower(str_replace('_','',str_replace('-','',$str)))).'()';
		}
	}
	
	
	
	
	
	
} //$


