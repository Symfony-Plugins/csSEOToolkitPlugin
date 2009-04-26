<?php

class csSEOComponents extends sfComponents
{
	public function executeMetas(sfWebRequest $request)
	{
		$this->include_admin_bar = false;
		$this->page = $this->getCurrentSeoPage($request);
		$this->metas = $this->getMetas($this->page, sfContext::getInstance());

		if ($this->page && $this->validatedUser()) 
		{
			$this->include_admin_bar = true;
		}
	}
	public function getMetas($page, $context)
	{
 		$i18n = sfConfig::get('sf_i18n') ? $context->getI18N() : null;
		$metatags = array();
		$metanames = array_merge($context->getResponse()->getMetas(), array('description' => 'description', 'keywords' => 'keywords'));
		foreach ($metanames as $name => $content)
 		{	
			if(isset($page[$name]))
			{
				$content = $page[$name];
			}
   		$metatags[] = tag('meta', array('name' => $name, 'content' => is_null($i18n) ? $content : $i18n->__($content)))."\n";
 		}
		return $metatags;
	}
	public function executeMeta_data(sfWebRequest $request)
	{
		$this->sf_request = $request;
		$this->sf_response = sfContext::getInstance()->getResponse();
		if (!isset($this->sf_content))
		{
			throw new sfException("Must include \$sf_content when generating meta data");
		} 
	}
	/**
	 * executeMeta_data_admin_bar
	 *
	 * Be careful calling this component individually, you may add an unnecessary query to every page
	 *
	 * @param sfWebRequest $request 
	 * @return void
	 * @author Brent Shaffer
	 */
	public function executeSeo_admin_bar(sfWebRequest $request)
	{
		if (sfConfig::get('app_csSEOToolkitPlugins_IncludeAssets', true)) 
		{
			$this->includeAdminBarAssets($request);
		}

		if (!isset($this->page)) 
		{
			$this->page = $this->getCurrentSeoPage($request);
		}
		$this->metaform = new MetaDataForm($this->page);
		$this->sitemapform = new SitemapItemForm($this->page);
	}
	public function getCurrentSeoPage(sfWebRequest $request)
	{
		$url = $request->getUri();
		return Doctrine::getTable('SeoPage')->findOneByUrl($url);
	}
	public function validatedUser()
	{
		$user = sfContext::getInstance()->getUser();
		$authmethod = sfConfig::get('app_csSEOToolkitPlugin_AuthMethod', 'isAuthenticated');
		return $user->$authmethod();
	}
	public function includeAdminBarAssets(sfWebRequest $request)
	{
		sfContext::getInstance()->getResponse()->addStylesheet('/csSEOToolkitPlugin/css/seo.css');
		sfContext::getInstance()->getResponse()->addStylesheet('/csSEOToolkitPlugin/css/slider_default.css');		
		sfContext::getInstance()->getResponse()->addJavascript('/csSEOToolkitPlugin/js/slider.js');		
	}
}