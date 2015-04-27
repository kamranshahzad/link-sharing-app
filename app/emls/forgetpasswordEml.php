<?php


			
class forgetpasswordEml{
	
	private $mailObj;
	private $fromData;
	
	public function __construct( $sendToEmails , $form  ){
		
		$config = new Config();
		$this->mailObj 			= new PHPMailer(true);
		$configArr = $config->getConfig();
		$this->fromData = $form;
		$debugEmls = $configArr['debugemails'];
		$fromEml   = $configArr['fromemails']['FORGETPASS'];
		$this->mailObj->AddReplyTo($fromEml['email'], $fromEml['title']);
		$this->mailObj->SetFrom($fromEml['email'], $fromEml['title']);
		foreach($sendToEmails as $title=>$email){
			$this->mailObj->AddAddress( $email , $title );
		}		
		if(Config::EMAIL_DEBUG){
  			foreach($debugEmls as $key=>$val){
				$this->mailObj->AddCC( $val , $key );
			}
		}
		
  		
	}
	
	
	public function send(){
			$this->mailObj->Subject = 'Password Reset';
			try {
				$this->mailObj->MsgHTML($this->emailTemplate());
				$this->mailObj->Send();
				echo 'Mail Send';
			} catch (phpmailerException $e) {
			} catch (Exception $e){}
	}
	
	private function emailTemplate(){
		$html = '';
		$assetsLoc = 'http://localhost/project/public/siteimages/';
		
		$html .= '	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
					<html xmlns="http://www.w3.org/1999/xhtml">
					<head>
					<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
					<title>[Project]</title>
					</head>
					<body >
					<div style="width:700px; margin:0 auto;">
						<div style="width:700px; height:29px; background:#ff9600;">
						</div>
						
						<div style="width:700px;">
							<div style="width:170px; height:125px; float:left;"><img src="'.$assetsLoc.'logo.jpg" width="170" height="125" alt="" /></div>
							<div style="float:left; font-family:Arial, Helvetica, sans-serif; font-size:18px; color:#ff9600; font-weight:bold; padding:55px 20px;">Welcome to our site</div>
						</div>
						<div style="float:left; width:700px;">
							<div style="font:normal 12px Arial, Helvetica, sans-serif; color:#747474; text-align:justify; padding-top:10px; line-height:24px;">
								   <p>
									Here is you account information: 
								   </p>
								   '.$this->drawAccountInfo().'
								  <p>
								  <p>
								  	<a href="http://localhost/project/login.php">
								  	Click here to login
									</a>
								  </a>
								<p>
								</p>	
							</div>
						</div>
						
						<div style="width:700px; height:7px;clear:both;	background:#3c3b3b;	margin-top:24px;"></div>
					</div>
					<div style="width:700px; height:50px; clear:both; background:#ff8400; margin:0 auto;">	    	
								
					</div>
					</body>
					</html>
					';	
		return $html;	
	}
	
	private function drawAccountInfo(){
		$html = '';
		$html .= '<table><tr>
				  <td width="30%" height="25" align="right" ><b>Username</b>:&nbsp;</td>
				  <td width="70%">&nbsp;'.$this->fromData['username'].'</td>
				</tr>';
		$html .= '<tr>
				  <td width="30%" height="25" align="right" ><b>Password</b>:&nbsp;</td>
				  <td width="70%">&nbsp;'.$this->fromData['password'].'</td>
				</tr></table>';
		return $html;	
	}
	
	

	
}//$


?>