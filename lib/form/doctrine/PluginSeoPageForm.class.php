<?php

/**
 * PluginSeoPage form.
 *
 * @package    form
 * @subpackage SeoPage
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
abstract class PluginSeoPageForm extends BaseSeoPageForm
{
	public function setUp()
	{
		parent::setUp();
		$this->widgetSchema['url'] = new sfWidgetFormInputHidden();
		
		$metadata = new MetaDataForm($this->getObject()->getMetaData());
		$sitemap = new SitemapItemForm($this->getObject()->getSitemapItem());
		
		unset($this['url'], $sitemap['id'], $sitemap['seo_page_id'], $metadata['id'], $metadata['seo_page_id']);
		
		$this->embedForm('SitemapItem', $sitemap);
		$this->embedForm('MetaData', $metadata);
	}
}