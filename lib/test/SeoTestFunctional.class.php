<?php

class SeoTestFunctional extends sfTestFunctional
{
	protected $url;
	
	public function __construct(sfBrowserBase $browser, lime_test $lime = null, $testers = array())
	{
		parent::__construct($browser, $lime, array_merge(array('response' => 'SeoTesterResponse', 'request' => 'SeoTesterRequest'), $testers));
	}
	public function loadUrl($url)
	{
		$this->url = $url;
	}
	public function isValidUrl()
	{
		if ($page = $this->get($this->url)) 
		{
			$status = $page->with('response')->getStatusCode();
			return $status != 404 ? true : false;
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
	public function getWebRequestObject()
	{
		return $this->get($this->url)->with('request')->getWebRequestObject();
	}
	public function getWebResponseObject()
	{
		return $this->get($this->url)->with('response')->getWebResponseObject();
	}
	public function getContent()
	{
		return $this->getWebResponseObject()->getContent();
	}
}