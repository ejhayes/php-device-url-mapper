<?php
if( !defined("TEST_ROOT") ){
	define("TEST_ROOT","../");
}
require_once('simpletest/autorun.php');
require_once(TEST_ROOT . '../src/sharedUrl.php');

class TestSharedUrl extends UnitTestCase {
	function setUp(){
		$this->mobileAgents = array(
			"Mozilla/5.0 (iPhone; U; CPU iPhone OS 3_0 like Mac OS X; en-us) AppleWebKit/420.1 (KHTML, like Gecko) Version/3.0 Mobile/1A542a Safari/419.3",
			"Mozilla/5.0 (iPod; U; CPU iPhone OS 3_1_1 like Mac OS X; en-us) AppleWebKit/528.18 (KHTML, like Gecko) Mobile/7C145",
			"Mozilla/5.0 (Linux; U; Android 1.5; de-de; Galaxy Build/CUPCAKE) AppleWebkit/528.5+ (KHTML, like Gecko) Version/3.1.2 Mobile Safari/525.20.1  "
		);
		
		$this->desktopAgents = array(
			"Mozilla/5.0 (X11; U; Linux i586; de; rv:5.0) Gecko/20100101 Firefox/5.0"
		);
		
		$this->tabletAgents = array(
			"Mozilla/5.0 (iPad; U; CPU OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B367 Safari/531.21.10"
		);
		
		// tests may modify server behavior, make a backup of our agent
		$this->currentAgent = $_SERVER["HTTP_USER_AGENT"];
	}
	
	function tearDown(){
		// restore the user agent, since it may have been tampered with
		$_SERVER["HTTP_USER_AGENT"] = $this->currentAgent;
	}
	
	function test_returns_desktop(){
		$test_configuration = "http://www.google.com";
		
		foreach($this->desktopAgents as $agent){
			$_SERVER["HTTP_USER_AGENT"] = $agent;
			$sharedUrl = new sharedUrl($test_configuration);
			$this->assertEqual($sharedUrl->getBrowserType(), "desktop");
		}
	}
	
	function test_returns_mobile(){
		$test_configuration = "http://www.google.com";
		
		foreach($this->mobileAgents as $agent){
			$_SERVER["HTTP_USER_AGENT"] = $agent;
			$sharedUrl = new sharedUrl($test_configuration);
			$this->assertEqual($sharedUrl->getBrowserType(), "mobile");
		}
	}
	
	function test_returns_desktop_url(){
		$mobileUrl = 'http://m.somesite.com';
		$desktopUrl = 'http://somesite.com';
		
		$test_configuration_urls = array(
			"desktop" => $desktopUrl,
			"mobile" => $mobileUrl
		);
		
		foreach($this->desktopAgents as $agent){
			$_SERVER["HTTP_USER_AGENT"] = $agent;
			$sharedUrl = new sharedUrl($test_configuration_urls);
			$this->assertEqual($sharedUrl->getUrl(), $desktopUrl);
		}
	}
	
	function test_returns_mobile_url(){
		$mobileUrl = 'http://m.somesite.com';
		$desktopUrl = 'http://somesite.com';
		
		$test_configuration_urls = array(
			"desktop" => $desktopUrl,
			"mobile" => $mobileUrl
		);
		
		foreach($this->mobileAgents as $agent){
			$_SERVER["HTTP_USER_AGENT"] = $agent;
			$sharedUrl = new sharedUrl($test_configuration_urls);
			$this->assertEqual($sharedUrl->getUrl(), $mobileUrl);
		}
	}
	
	function test_expects_valid_url_single(){
		// incorrectly formatted urls (not comprehensive)
		$invalidUrls = array(
			"google.com",
			"google",
			"http://google",
			"http:/google.com",
			"htt://google.com"
		);
		
		// validate the incorrect urls
		foreach($invalidUrls as $invalidUrl){
			$this->expectException(new Exception("Invalid URL"));
			new sharedUrl($invalidUrl);
		}
		
		// validate a valid url
		$validUrl = 'http://www.google.com';
		new sharedUrl($validUrl);
	}
	
	function test_expected_input(){
		$validUrl = "http://www.google.com";
		$validUrlMobile = "http://m.google.com";
		
		// single urls
		$test_input_single_url = $validUrl;
		$sharedUrl = new sharedUrl($test_input_single_url);
		$this->assertEqual($sharedUrl->getUrl("desktop"), $test_input_single_url);
		$this->assertEqual($sharedUrl->getUrl("mobile"), $test_input_single_url);
		
		// array configuration
		$test_input_multiple_url = array(
			"desktop" => $validUrl,
			"mobile" => $validUrlMobile
		);
		$sharedUrl = new sharedUrl($test_input_multiple_url);
		$this->assertEqual($sharedUrl->getUrl("desktop"), $test_input_multiple_url["desktop"]);
		$this->assertEqual($sharedUrl->getUrl("mobile"), $test_input_multiple_url["mobile"]);
		
		$test_input_multiple_url_partial = array(
			"desktop" => $validUrl
		);
		$sharedUrl = new sharedUrl($test_input_multiple_url_partial);
		$this->assertEqual($sharedUrl->getUrl("desktop"), $test_input_multiple_url_partial["desktop"]);
		$this->assertNull($sharedUrl->getUrl("mobile"));
		
		$test_input_multiple_url_invalid = array(
			"desktop" => $validUrl,
			"applebanana" => $validUrl
		);
		$this->expectException(new Exception("Invalid Arguments"));
		$sharedUrl = new sharedUrl($test_input_multiple_url_invalid);
	}
	
	function test_redirect_works(){
		// not sure how to test this functionality???
		$validUrl = "http://www.google.com";
		$sharedUrl = new sharedUrl($validUrl);
		
		// NOTE: this way we can verify that redirect at least does something--without doing a header redirect
		$this->assertEqual($sharedUrl->redirect(false),$validUrl);
	}
	
}
?>