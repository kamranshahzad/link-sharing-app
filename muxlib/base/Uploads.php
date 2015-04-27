<?php


	abstract class Uploads{
		
	} //$Uploads
	
	
	class FileUpload{
		
		private $fileInfo;
	  	private $fileLocation;
	  	private $error;
	  	private $direct;
	  	private $targetLocation;
	  	private $newName;
	  	private $random;
		
		
		
		
		
	}//$FileUpload
	
	
	


class fileDir {

  
  function __construct($dir){
	  $this->direct = $_SERVER['DOCUMENT_ROOT'].$dir;
	  
	  if(!is_dir($this->direct)){
		  die('Supplied directory is not valid: '.$this->direct);	
	  }
  }
  function upload($theFile , $filename =''){
	  $this->fileInfo = $theFile;
	  $this->fileLocation = $this->direct . $this->fileInfo['name'];
	  $this->random = rand(00000,99999);
	  $this->newName = $this->random.'-'.$this->fileInfo['name'];
	  $this->targetLocation = $this->direct . $this->newName;
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
  function overwrite($theFile){
	  $this->fileInfo = $theFile;
	  $this->fileLocation = $this->direct . $this->fileInfo['name'];
	  if(file_exists($this->fileLocation)){
		  $this->delete($this->fileInfo['name']);
	  }
	  return $this->upload($this->fileInfo);
  }
  function location(){
	  return $this->fileLocation;	
  }
  
  function getExtension($str) {
         $i = strrpos($str,".");
         if (!$i) { return ""; }
         $l = strlen($str) - $i;
         $ext = substr($str,$i+1,$l);
         return $ext;
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