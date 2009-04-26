<?php

class seoGenerateMetasTask extends sfBaseTask
{
  protected function configure()
  {
    $this->addArguments(array(
      new sfCommandArgument('application', sfCommandArgument::REQUIRED, 'The application name'),
		));

	  $this->addOptions(array(
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('id', null, sfCommandOption::PARAMETER_REQUIRED, 'A specific id to rebuild', null),
      new sfCommandOption('where', null, sfCommandOption::PARAMETER_REQUIRED, 'A where clause (equals signs must be replaced with the word "is")', null),
    ));

    $this->namespace        = 'seo';
    $this->name             = 'rebuild-metas';
    $this->briefDescription = 'rebuilds metas across the site, using configurations set in app.yml';
    $this->detailedDescription = <<<EOF
This task rebuilds your SEO metadata across the entire site, using the urls available.
It requires an application argument.
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    $app     = $arguments['application'];
    $env     = $options['env'];

		$this->bootstrapSymfony($app, $env, true);
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase('doctrine')->getConnection();

		$q = 	Doctrine_Query::create()
								   ->select('*')
									 ->from('SeoPage');
									
		if (isset($options['id'])) 
		{
			$pages = $q->addWhere('id = ?', $options['id']);
		}
		if (isset($options['where'])) 
		{
			$pages = $q->addWhere(str_replace('is', '=', $options['where']));
		}
		$pages = $q->execute();

		if (!$pages->count() && isset($options['id'])) 
		{
			$this->logSection('seo', 'Page of id "'.$options['id'].'" not found.');
			return 1;
		}							
		
		$browser = new SeoTestFunctional(new sfBrowser());

		$updated = array();
		$missing = array();
		foreach ($pages as $page) 
		{
			$browser->loadUrl($page['url']);
			if($browser->isValidUrl($page['url']))
			{
				$updated[] = SeoToolkit::generateMetaData($browser->getContent(), $browser->getWebRequestObject(), $page);
			}
			else
			{
				$missing[] = $page;
			}
		}
		if (count($missing)) 
		{
			if(
				!$this->askConfirmation(array('You have '.count($missing).' pages in your database returning errors.  ', 'Would you like to remove these? (y/N)'), null, false)
	    )
	    {
	      $this->logSection('seo', 'task successful for '.count($updated). ' of '.count($updated)+count($missing). ' pages');
	      return 1;
	    }
			foreach ($missing as $page) 
			{
				$page->delete();
			}
			$this->logSection('seo', count($missing).' pages deleted successfully');
		}
		else
		{
			$this->logSection('seo', 'all '.count($updated).' of your meta pages were updated successfully');
		}
  }

  protected function bootstrapSymfony($app, $env, $debug = true)
  {
    $configuration = ProjectConfiguration::getApplicationConfiguration($app, $env, $debug);

    sfContext::createInstance($configuration);
  }
}
