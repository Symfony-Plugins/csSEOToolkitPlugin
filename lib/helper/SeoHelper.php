<?php


function keyword_encode($content, $selector = null)
{
	return SeoKeywordToolkit::getInstance()->parseBlock($content, $selector);
}

function include_seo_metas($content)
{
	echo get_component('csSEO', 'meta_data', array('sf_content' => $content));
}

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
