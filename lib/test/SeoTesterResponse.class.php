<?php

/**
* 
*/
class SeoTesterResponse extends sfTesterResponse
{
	public function getStatusCode()
	{
		return $this->response->getStatusCode();
	}
	public function getWebResponseObject()
	{
		return $this->response;
	}
}