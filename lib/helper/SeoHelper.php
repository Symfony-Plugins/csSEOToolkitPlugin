<?php


function keyword_encode($content, $selector = null)
{
	return SeoKeywordToolkit::getInstance()->parseBlock($content, $selector);
}

function include_seo_metas()
{
	echo get_component('csSEO', 'meta_data', array());
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
