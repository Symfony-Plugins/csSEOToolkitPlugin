<?php

class csSEOComponents extends sfComponents
{
	public function executeMetas(sfWebRequest $request)
	{
		$this->meta = $this->getCurrentMetadata();
		$this->metas = array();
	  $context = sfContext::getInstance();
 		$i18n = sfConfig::get('sf_i18n') ? $context->getI18N() : null;
 		foreach ($context->getResponse()->getMetas() as $name => $content)
 		{	
			if(isset($this->meta[$name]))
			{
				$content = $this->meta[$name];
			}
   		$this->metas[] = tag('meta', array('name' => $name, 'content' => is_null($i18n) ? $content : $i18n->__($content)))."\n";
 		}
		if ($this->meta && sfContext::getInstance()->getUser()->isSuperAdmin()) 
		{
			$this->form = new MetaDataForm($this->meta);
		}
	}
	public function executeMeta_data(sfWebRequest $request)
	{
		$this->sf_request = $request;
		$this->sf_content = sfContext::getInstance()->getResponse()->getContent();
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
	public function executeMeta_data_admin_bar(sfWebRequest $request)
	{
		if (!isset($this->form)) 
		{
			$this->form = new MetaDataForm($this->getCurrentMetadata());
		}
	}
	public function getCurrentMetadata()
	{
		$url = $request->getUri();
		return Doctrine::getTable('MetaData')->findOneByUrl($url);
	}
}