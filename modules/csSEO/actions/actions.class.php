<?php

class csSEOActions extends sfActions
{
 // ===========
  // = Sitemap =
  // ===========
	public function executeSitemap(sfWebRequest $request)
	{
		$this->items = Doctrine::getTable('SitemapItem')->findAll();
		$this->setLayout(false);
    $this->getResponse()->setHttpHeader('Content-type','text/xml');
		return 'XML';
	}
	public function executeError404($value='')
	{
		$this->search = preg_split ("/\/|-/", $request->getPathInfo());
		$query = Doctrine::getTable('SEOPage')
								->getSearchQuery($this->search)->limit(10);
								
		$this->results = $query->execute();
		
    $this->getResponse()->setStatusCode(404, 'This page does not exist');
	}
}