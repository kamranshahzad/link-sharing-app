<?php

	abstract class Mapper extends DBWrapper{
		
		
		public static $DB_INFO  = array();
		public static $FRM_INFO = array();
		private $TABLE;
		private $PK;
		private $FK = array();
		private $ReferenceMap = array();
		private $MODEL;
		public static $FIELDS = array();
		
		public function __construct() {
			$this->init();	
		}
		
		
		
		
		// @ system functions
		private function init(){
			
			$this->MODEL = StringUtil::className(get_class($this));
			
			$configObj = new config();
			$appConfig = $configObj->getConfig();
			
			parent::__construct($appConfig['dsn']['host'], $appConfig['dsn']['username'] ,$appConfig['dsn']['password'],$appConfig['dsn']['dbname']);
			$this->initTableInfo();
			
		}
		
		private function initTableInfo(){
			$obj = new $this->MODEL();
			$this->TABLE = $obj->Table;
			$this->PK	 = $obj->Pk;
			$tableFields = $this->tableStructure($this->TABLE);
			foreach($tableFields as $fields){
				self::$FIELDS[] = $fields;
			}
		}
		
		public function getTable(){
			return $this->TABLE;
		}
		
		public function getPk(){
			return $this->PK;
		}
		
		
		// @ select actions
		
		public function fetchById( $id , $fieldsArr = '' ){
			$fields = "*";
			$dataArr = array();
			if (is_array($fieldsArr)){
				$fields = "`" . implode($fieldsArr, "`, `") . "`";
			}
			$rsVar = $this->query("SELECT " . $fields . " FROM `" . $this->TABLE . "` WHERE {$this->PK}='$id'");
			$row = $this->fetchAssoc($rsVar);
			//$this->freeResult();	
			if (is_array($fieldsArr)){
				foreach($fieldsArr as $field){
					$dataArr[$field] = $row[$field];
				}
			}else{
				foreach(self::$FIELDS as $field){
						$dataArr[$field] = $row[$field];
				}
			}
			return $dataArr;
		}
		
		
		
		//@ save
		public function save( $data , $criteria = ''){
			$colums = array();
			foreach($data as $key=>$value){
				if(in_array( $key ,self::$FIELDS)){
					$colums[$key] =  $value;
				}
			}
			if(!empty($criteria)){
				unset($colums[$this->PK]);
				$this->update( $colums , $this->TABLE , $criteria );
			}else{
				$this->insert( $colums , $this->TABLE);
			}
		}
		
		
		public function updateSelf($dataArr , $criteria = ''){
			return $this->update( $dataArr , $this->TABLE ,  $criteria );
		}
		
		public function remove($criteria){
			return $this->delete($this->TABLE , $criteria );
		}
		
		
	
		
	} //$


