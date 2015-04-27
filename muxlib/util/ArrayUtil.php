<?php



	class ArrayUtil{
		
		public static function value($key , $sourceArr ){
			if(array_key_exists($key ,$sourceArr) ){
				return $sourceArr[$key];
			}
			return '';
		}
		
		public static function isKeyExist($key , $sourceArr){
			if(array_key_exists($key ,$sourceArr) ){
				return true;
			}
			return false;
		}
		
		public static function disposeByKey($key , $sourceArr){
			if(array_key_exists($key ,$sourceArr) ){
				unset($sourceArr[$key]);
				return $sourceArr;
			}
			return $sourceArr;
		}
		
		
	}  //@ class

?>