<?php

/*
class value_parser {   
public $strings = array();
public $numbers = array();
public $arrays = array();
public $objects = array();

public function add($value, $index){

if (is_object($value)){    
$this->objects[$index] = $value;
} elseif (is_array($value)) {
$this->arrays[$index] = $value;
} elseif (is_scalar($value)){
  if (is_numeric($value)){  
$this->numbers[$index] = $value;
} else {
   $this->strings[$index] = $value;  
}
}
*/

class value_parser{
	private $arr = array();
	
	public function add($value, $index){	
		$arr[$value] = 	$index;
		return $arr;
	}
}



$vp = new value_parser();

$data = array( 'a'=>'Apple' , 'b'=>'Ball','c'=>'Cat');

array_walk( $data, array($vp, 'add') , 'New one' );
	
print_r($data);	
	
