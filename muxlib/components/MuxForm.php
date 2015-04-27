<?php


class MuxForm{
	
	
	private $controller  	= '';
	private $formMethod  	= '';
	private $formAction 	= '';
	private $formname       = '';
	
	function __construct($formname = '') {
		$this->formname = $formname;
	}
	
	public function setController($controller){
		 $this->controller = $controller;
	}
	
	public function setMethod($method){
        $this->formMethod = $method;
    }
	
	public function setAction($action){
		$this->formAction = '<input type="hidden" name="action" value="'.$action.'" />';
	}
	
	public function init($whereCall='admin'){
		$htmlTag = '';
		if($whereCall == 'admin'){
			$htmlTag .= '<form action="../muxlib/core/ControllerLoader.php" method="Post"  enctype="multipart/form-data" name="'.$this->formname.'" id="'.$this->formname.'">';
		}else{
			$htmlTag .= '<form action="muxlib/core/ControllerLoader.php" method="Post"  enctype="multipart/form-data" name="'.$this->formname.'" id="'.$this->formname.'">';
		}
		$htmlTag .= '<input type="hidden" name="view_state_controller" value="'.Request::encode64($this->controller).'" >';
		$htmlTag .= $this->formAction;
		return $htmlTag;
    }
	
	public function close(){
		return '</form>';	
	}
	
	
	public function createElement($type , $name , $value ){
		return "<input type='hidden' name='$name' value='$value' > \n";	
	}
	
	
	
	
}//$


?>