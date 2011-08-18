Simply Device URL Mapping in PHP
================================

__IMPORTANT: This is only setup for Android and iPhone devices right now.  It can be expanded or fitted for any number of cases!__

To use, just include the php file in your script.  That's it.

	require('sharedUrl.php');

Link Generation Cases
=====================
These cases are useful if you want to create device specific links within a page.

Simple case: Always forward to the same url, regardless of device
-----------------------------------------------------------------
No matter what device it is, always redirect to the same url.  Kinda pointless, but the functionality is here if you want it.

	$sharedUrl = new sharedUrl("http://www.google.com");
	
	$url = $sharedUrl->getUrl(); // will always return the same url

Basic Case: Use a different mobile and desktop url
--------------------------------------------------
If you want to differentiate between a mobile and a desktop device, you can use this code.

	$sharedUrl = new sharedUrl(array(
		"desktop" => "http://www.google.com",
		"mobile" => "http://m.google.com"
	));
	
	$url = $sharedUrl->getUrl(); // returns the desktop or mobile, depending on who is looking

Basic Case: Force a specific URL to be used
-------------------------------------------
If you'd like to force the URL being used, then simply tell getUrl what you'd like.  Again kinda pointless, but the functionality is included in the rare case that you actually need it!

	$sharedUrl = new sharedUrl(array(
		"desktop" => "http://www.google.com",
		"mobile" => "http://m.google.com"
	));
	
	$url = $sharedUrl->getUrl("desktop"); // returns the desktop or mobile, depending on who is looking
	
Page Forwarding Cases
=====================
These cases are useful if you want to navigate to a page right away (think url forwarding services, but device specific).

Simple Case (also, the only one I have for now): Redirect a page based on the device
------------------------------------------------------------------------------------

	$sharedUrl = new sharedUrl(array(
		"desktop" => "http://www.google.com",
		"mobile" => "http://m.google.com"
	));
	
	$sharedUrl->redirect();
	
Key forwarding Case: Redirect to a page and preserve the keys (currently only GET scoped)
-----------------------------------------------------------------------------------------

	$sharedUrl = new sharedUrl(array(
		"desktop" => "http://www.google.com",
		"mobile" => "http://m.google.com"
	));

	$sharedUrl->redirectKeys(array("item1", "item2")); // items 1 and 2 will be forwarded if they exist

What am I using case
====================
If you just want to know what type of browser is viewing, then do this:

	$sharedUrl = new sharedUrl("http://www.google.com");
	$sharedUrl->getBrowserType(); // returns desktop or mobile