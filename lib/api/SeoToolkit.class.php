<?php

/**
 * SeoToolkit
 * Create / Manage Metadata and Sitemap
 *
 * @package default
 * @author Brent Shaffer
 */
class SeoToolkit
{
	public static $_split_tags = array('description' => array('</p>', '<br>', '<br />', '</blockquote>', '</div>'),
																		 'title' => array('<h1>' => '</h1>'));
	public static $_maxlen  = 255;
	public static $_wordlen  = 4;
	public static $_numkeywords  = 7;
	
	/**
	 * generateMetaData
	 * uses the page HTML to generate metadata
	 * 
	 * @param string $content - HTML page content 
	 * @param string $request - sfWebRequest object
	 * @return SeoPage object
	 * @author Brent Shaffer
	 */
	public static function generateMetaData($content, $request)
	{
		$meta = new SeoPage();
		$meta->setUrl($request->getUri());
		$meta->setTitle(self::parseTitle($content));
		$meta->setDescription(self::parseDescription($content));
		$meta->setKeywords(self::parseKeywords($content));
		$meta->save();
		return $meta;
	}
	/**
	 * parseTitle
	 * retrieves a default page title from the HTML content
	 *
	 * @param string $content - HTML content
	 * @return string page title
	 * @author Brent Shaffer
	 */
	private static function parseTitle($content, $request)
	{
		$title = '';
		foreach (self::$_split_tags['title'] as $open => $close) 
		{
			if($i = strpos($content, $close))
			{
				$h = strpos($content, $open);
				$title = $h && $h < $i ? substr($content, $h, $i-$h) : substr($content, 0, $i);
				break;
			}
		}
		$title = trim(strip_tags($title));
		return substr($title ? self::getSitePrefix() . $title : self::getDefaultTitle(), 0, 255);
	}
	
	//This can be extended / overriden on a per-site basis
	public static function getSitePrefix()
	{
		return sfConfig::get('app_csSEOToolkitPlugin_SitePrefix');
	}
	
	//This can be extended / overriden on a per-site basis
	public static function getDefaultTitle()
	{
		return 'Home';
	}
	/**
	 * creates a meta description from a block of HTML
	 *
	 * @param string $content - HTML content
	 * @return string for meta description
	 * @author Brent Shaffer
	 */
	private static function parseDescription($content)
	{
		$summary = '';
		foreach (self::$_split_tags['description'] as $tag) 
		{
			if($i = strpos($content, $tag))
			{
				$summary = substr($content, 0, $i);
				break;
			}
		}
		$summary = $summary ? $summary : ($content ? trim(substr($content, 0, self::$_maxlen)) : '');
		$summary = preg_replace('/\s+/', ' ', strip_tags($summary));	
		return $summary;
	}
	/**
	 * parseKeywords
	 * creates metadata keywords from HTML content
	 *
	 * @param string $content - the HTML to create a keyword string from
	 * @return string of keywords for metadata
	 * @author Brent Shaffer
	 */
	private static function parseKeywords($content)
	{
		$words = self::prepForKeywords($content);
		$words = array_filter($words, 'SeoToolkit::filterLength');
		$weight = array();

		foreach ($words as $word) 
		{
			$weight[$word] = isset($weight[$word]) ? $weight[$word] + 1 : 0;
		}
		asort($weight, SORT_NUMERIC);

		return self::convertToKeywordString($weight);
	}
	/**
	 * prepForKeywords
	 * converts an HTML string into a word array
	 *
	 * @param string $text - the text to pull keywords from 
	 * @return array of words found in the text
	 * @author Brent Shaffer
	 */
	private static function prepForKeywords($text)
	{
		//keywords are not case sensitive, we don't want to include HTML tags
		$text = strtolower(strip_tags($text);
		
		//We want to strip all punctuation
		$text = preg_replace('/\W/', ' ', $text));
		
		//create the array
		$words = explode(' ', $text);
		
		//We want to remove all beginning and ending whitespace
		array_walk($words, 'trim');
		
		return $words;
	}
	/**
	 * convertToKeywordString
	 *
	 * @param array $keywords - a unique array of keywords and their weight 
	 * @return string of keywords for metadata
	 * @author Brent Shaffer
	 */
	private static function convertToKeywordString($keywords)
	{
		//rank by most frequent
		$keywords = array_reverse($keywords);
		//Take the top matches, limitted by the max number of keywords
		$keywords = array_slice($keywords, 0, self::$_numkeywords);
		
		//Take only the keys from the array, implode to string
		$string = implode(',', array_keys($keywords));
		
		//limit to 255 characters (database field max)
		return trim(substr($string, 0, 255));
	}
	private static function stripPunctuation($text)
	{
		return preg_replace('/\W/', ' ', $text);
	}
	private static function filterLength($text)
	{
		return strlen($text) >= self::$_wordlen;
	}
	
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
