<?php

class SeoTestFunctional extends sfTestFunctional
{
	public function __construct(sfBrowserBase $browser, lime_test $lime = null, $testers = array())
	{
		parent::__construct($browser, $lime, array_merge(array('response' => 'SeoTesterResponse'), $testers));
	}
	public function isValidUrl($url)
	{
		if ($page = $this->get($url)) 
		{
			$status = $page->with('response')->getStatusCode();
			return $status == 200 ? true : false;
		}
		return false;
	}
	public function get($uri, $parameters = array(), $changeStack = true)
	{
		try 
		{
			return parent::get($uri, $parameters, $changeStack);	
		} 
		catch (Exception $e) 
		{
			//If the URI cannot be found, the page is a 404 and should be removed
		}	
		return false;
	}
}