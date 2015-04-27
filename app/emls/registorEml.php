<?php


			
class registorEml{
	
	private $mailObj;
	private $fromData;
	
	public function __construct( $sendToEmails , $form  ){
		
		$config = new Config();
		$this->mailObj 			= new PHPMailer(true);
		$configArr = $config->getConfig();
		$this->fromData = $form;
		$debugEmls = $configArr['debugemails'];
		$fromEml   = $configArr['fromemails']['USEREMLS'];
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
			$this->mailObj->Subject = 'Registration Verification';
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
							<div style="float:left; font-family:Arial, Helvetica, sans-serif; font-size:18px; color:#ff9600; font-weight:bold; padding:55px 20px;">Dear User,</div>
						</div>
						<div style="float:left; width:700px;">
							<div style="font:normal 12px Arial, Helvetica, sans-serif; color:#747474; text-align:justify; padding-top:10px;">
									 <strong>&nbsp;&nbsp;Thank you for registering with us at <strong>[Project]</strong>. Check out the site daily for news & updates on the latest and greatest on the internet. </strong>
							</div>
							<div style="font:normal 12px Arial, Helvetica, sans-serif; color:#747474; text-align:justify; padding-top:10px; line-height:24px;">
								  <p>
								In order to complete your registration process, you must click the following link:: 
								</p>
								'.$this->drawActivationLink().'
								<p>
									If clicking the link does not work, copy and paste the link into your browser. If you are still having difficulty please forward this email to [Email], and our staff will help you shortly. 
								</p>
								<p>
								Thanks, 
								</p>	
							</div>
						</div>
						
						<div style="width:700px; height:7px;	clear:both;	background:#3c3b3b;	margin-top:24px;"></div>
					</div>
					<div style="width:700px; height:50px; clear:both; background:#ff8400; margin:0 auto;">	    	
					</div>
					<div style="padding:5px;">
						<p style="font-size:11px;font-style:italic;">
							*This email may contain confidential information. If you are not the adressee or authorized to receive this for the adressee, you must not use, copy, disclose, or take any action based on this message or any information herein. If you have recieved this email by accident please reply via email, and delete this message. Thank you for your cooperation.
						</p>	
					</div>
					</body>
					</html>
					';	
		return $html;	
	}
	
	
	private function drawActivationLink(){
		
		$username = Request::encode64($this->fromData['username']);
		$email    = Request::encode64($this->fromData['email']);
		$html = '<a href="http://localhost/project/verification-email.php?key='.$username.'::'.$email.'">
					http://localhost/project/verification-email.php?key='.$username.'::'.$email.' 
				 </a>';
		return $html;
	}
	

	
}//$


?>