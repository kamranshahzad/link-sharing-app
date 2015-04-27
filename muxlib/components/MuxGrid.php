<?php


	abstract class MuxGrid extends Components {
		
		private $controller;
		private $dataSourceArr;
		private $columsArr = array();
		private $fieldArr  = array();
		private $isFilterWorks = false;
		private $sqlQuery = '';
		
		function __construct($controller='') {
			$this->controller = $controller;
		}
		
		public function dataSource($dataSourceArr = array()){
			$this->dataSourceArr = $dataSourceArr;
		}
		
		public function addColumn( $columnsArr = array() ){
			if(count($columnsArr) > 0){
				$this->columsArr[] = $columnsArr;
			}
		}
		
		
		private function buildQuery(){
			$this->buildQuery();
			if(array_key_exists('query',$this->dataSourceArr)){
				$this->sqlQuery = $this->dataSourceArr['query'];
			}else{
				
			}
		}
		
		public function drawGrid(){
			$this->fieldArr = array_map(array($this, 'getfileds'), $this->columsArr);
			Debug::drawArray($this->fieldArr);
			
			//return "Query:".$this->sqlQuery;
		}
		public function getfileds($arr) {
			$field = '';
			if(array_key_exists('lbl',$arr)){
				$field = $arr['lbl'];
			}
			return $field;
		}
		
		
		//  @ render
		private function renderGridHeader(){
				
		}
		
		private function renderGridFooter(){
			
		}
		
		private function renderGridInfo(){
			
		}
		
		
		
		
	
	} // $