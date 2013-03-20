<?php
require_once("mashape/MashapeClient.php");


class Way2SMS {
  
	private $authHandlers;

	function __construct($mashapeKey) {
		$this->authHandlers = array();
		$this->authHandlers[] = new MashapeAuthentication($mashapeKey);
		
	}
	public function sendSms($msg,$phone) {
		$parameters = array(
			 
			
				"msg" => $msg,
				"phone" => $phone,
				"pwd" => your_password,
				"uid" => your_way2SMS_userid );

		$response = HttpClient::doRequest(
				HttpMethod::GET,
				"https://way2sms.p.mashape.com/index.php",
				$parameters,
				$this->authHandlers,
				ContentType::FORM,
				true);
		return $response;
	}
	

	
}
?>
