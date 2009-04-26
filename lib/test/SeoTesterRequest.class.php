<?php

/**
* 
*/
class SeoTesterRequest extends sfTesterRequest
{
	public function getWebRequestObject()
	{
		return $this->request;
	}
}