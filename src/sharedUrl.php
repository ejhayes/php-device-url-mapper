<?php
class sharedUrl {
	const DESKTOP = "desktop";
	const MOBILE = "mobile";
	
	function __construct($url){
		// our default type
		$this->type = null;
		
		$this->config = array(
			sharedURL::DESKTOP => null,
			sharedURL::MOBILE => null
		);
		
		if( is_array($url)){
			foreach($url as $key => $value){
				if(($key == sharedURL::DESKTOP || $key == sharedURL::MOBILE) && $this->validUrl($value)){
					$this->config[$key] = $value;
				} else {
					throw new Exception("Invalid Arguments");
				}
			}
		} else {
			if( !$this->validUrl($url) ) throw new Exception("Invalid URL");
			
			$this->config = array(
				sharedUrl::DESKTOP => $url,
				sharedUrl::MOBILE => $url
			);
		}		
		
	}
	
	function getUrl($key=""){
		if($key == ""){
			return $this->config[$this->getBrowserType()];
		} else {
			return $this->config[$key];
		}
	}
	
	function redirect($action=true){
		if ($action){
			header('Location: ' . $this->getUrl());
		} else {
			// return the string representation
			return $this->getUrl();
		}
	}
	
	private function getBrowserType(){
		$user_agent = $_SERVER["HTTP_USER_AGENT"];
		
		if( is_null($this->type) ){
			switch(true){
				case (preg_match('/ipod/i',$user_agent)||preg_match('/iphone/i',$user_agent)):
			    case (preg_match('/android/i',$user_agent)):
					$this->type = sharedURL::MOBILE;
			    	break;
				default:
					$this->type = sharedURL::DESKTOP; // render as a desktop by default
			}
		}
		
		return $this->type;
	}
	
	private function validUrl($url){
		return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url);
	}
}
?>