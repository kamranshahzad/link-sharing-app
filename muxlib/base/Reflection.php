<?php


	class Reflection{
		
		public function call(){
			echo "I am here.";	
		}
		
		public function isMethodExist($targetCls , $method){
			return method_exists($targetCls , $method );		
		}
		
	} //$
	
	

?>