<?php

/**
 * keyword_encode
 * Adds keyword decorators to specified block
 * 
 * @param string $content - the HTML or text block to encode
 * @param string $selector - optional - limit HTML encoding to specified css selector
 * @return encoded HTML or text block
 * @author Brent Shaffer
 */
function keyword_encode($content, $selector = null)
{
	return SeoKeywordToolkit::getInstance()->parseBlock($content, $selector);
}

/**
 * include_seo_metas
 *
 * @param string $content - HTML block used to generate metas if none are available
 * @return meta tags
 * @author Brent Shaffer
 */
function include_seo_metas($content)
{
	echo get_component('csSEO', 'meta_data', array('sf_content' => $content));
}

/**
 * seo_admin_bar
 *
 * @return includes an SEO menu bar in your project to edit meta and sitemap data
 * @author Brent Shaffer
 */
function seo_admin_bar()
{
	if(has_slot('seo_admin_bar'))
	{
		include_slot('seo_admin_bar');
	}
	else
	{
		include_component('csSEO', 'seo_admin_bar');
	}
}
