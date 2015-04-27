<?php


class EasyUploads {

  var $fileInfo;
  var $fileLocation;
  var $error;
  var $direct;
  var $targetLocation;
  var $newName;
  var $random;
  private $uploadPath = '';
  //var $direct = "";
  
  function __construct($dir){
	  
	  $this->uploadPath = $dir;
	  
	  /*
	  if(!is_dir($dir)){
		  die('Supplied directory is not valid: '.$$dir);	
	  }
	  */
	  
	  //$this->direct = $dir;
	  /*
	  //$this->direct = 'http://www.pinegrovebanquet.com'.$dir;
	  if(!is_dir($this->direct)){
		  die('Supplied directory is not valid: '.$this->direct);	
	  }*/
  }
  
  function upload($theFile){
	  
	  $this->fileInfo = $theFile;
	  $this->fileLocation = $this->uploadPath . $this->fileInfo['name'];
	  $this->random = rand(00000,99999);
	  $today = date("F j, Y, g:i:s");
	  $this->newName = $this->random.'_'.strtotime($today).'.'.$this->filename_extension($this->fileInfo['name']);
	  $this->targetLocation = $this->uploadPath . $this->newName;
	   if(!file_exists($this->fileLocation)){
		if(move_uploaded_file($this->fileInfo['tmp_name'], $this->targetLocation)){
			return 'File was successfully uploaded';
		} else {
			return 'File could not be uploaded';
			$this->error = "Error: File could not be uploaded.\n";
			$this->error .= 'Here is some more debugging info:';
			$this->error .= print_r($_FILES);	
		}
	  } else {
		  return 'File by this name already exists';	
	  }
  }
  
  function filename_extension($filename) {
		$pos = strrpos($filename, '.');
		if($pos===false) {
			return false;
		} else {
			return substr($filename, $pos+1);
		}
	}


  function overwrite($theFile){
	  $this->fileInfo = $theFile;
	  $this->fileLocation = $this->uploadPath . $this->fileInfo['name'];
	  if(file_exists($this->fileLocation)){
		  $this->delete($this->fileInfo['name']);
	  }
	  return $this->upload($this->fileInfo);
  }
  
  function location(){
	  return $this->fileLocation;	
  }
  
  function getFileName(){
	return  $this->newName;
  }
  function getFileLocation(){
	return  $this->targetLocation;  
  }
  
  function fileName(){
	  return $this->fileInfo['name'];
  }
  
  function delete($fileName){
	  $this->fileLocation = $this->direct.$fileName;
	  if(is_file($this->fileLocation)){
		unlink($this->fileLocation);
		return 'Your file was successfully deleted';
	  } else {
		return 'No such file exists: '.$this->fileLocation;	
	  }
  }
  function reportError(){
	  return $this->error;	
  }
  
  
}


?>