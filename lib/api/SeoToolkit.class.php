<?php

/**
* 
*/
class SeoToolkit
{
	static public function xmlencode($tag)
	{
	 $tag = str_replace("&", "&amp;", $tag);
	 $tag = str_replace("<", "&lt;", $tag);
	 $tag = str_replace(">", "&gt;", $tag);
	 $tag = str_replace("'", "&apos;", $tag);
	 $tag = str_replace("\"", "&quot;", $tag); 
	 return $tag;
	}
	static public function xmldecode($tag)
	{
	 $tag = str_replace("&amp;", "&", $tag);
	 $tag = str_replace("&lt", "<", $tag);
	 $tag = str_replace("&gt", ">", $tag);
	 $tag = str_replace("&apos;", "'", $tag);
	 $tag = str_replace("&quot", "\"", $tag); 
	 return $tag;
	}
}
