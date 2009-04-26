<?php

class seoRemove404Task extends sfBaseTask
{
  protected function configure()
  {
    $this->addArguments(array(
      new sfCommandArgument('application', sfCommandArgument::REQUIRED, 'The application name'),
		));

	  $this->addOptions(array(
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
    ));

    $this->namespace        = 'seo';
    $this->name             = 'remove-404';
    $this->briefDescription = 'removes all 404 pages being indexed in the SeoPage model';
    $this->detailedDescription = <<<EOF
This task removes 404 pages from the SeoPage Model.
It requires an application argument.
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    $app     = $arguments['application'];
    $env     = $options['application'];

		$this->bootstrapSymfony($app, $env, true);
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase('doctrine')->getConnection();

		$pages = Doctrine_Query::create()
								   ->select('*')
									 ->from('SeoPage')
									 ->execute();		
									
		$browser = new SeoTestFunctional(new sfBrowser());
					
		$missingPages = array();
		foreach ($pages as $page) 
		{
			$browser->loadUrl($page['url']);
			if(!$browser->isValidUrl())
			{
				$missingPages[] = $page;
				$this->logSection('seo', 'invalid page: "'.($page->getTitle() ? "title: $page->title" : "id: $page->id").'"');
				// $final = $this->formatter->format('Removed one model: "'.$page->getTitle() . '"');
    		// $this->dispatcher->notify(new sfEvent($this, 'command.log', array('', $final)));
			}
		}
		if (count($missingPages)) 
		{
			if(
				!$this->askConfirmation(array('This command will remove '.count($missingPages).' pages from your database.', 'Are you sure you want to proceed? (y/N)'), null, false)
	    )
	    {
	      $this->logSection('seo', 'task aborted');
	      return 1;
	    }
			foreach ($missingPages as $page) 
			{
				$page->delete();
			}
			$this->logSection('seo', count($missingPages).' pages deleted successfully');
		}
		else
		{
			$this->logSection('seo', 'all your pages are valid');
		}
  }

  protected function bootstrapSymfony($app, $env, $debug = true)
  {
    $configuration = ProjectConfiguration::getApplicationConfiguration($app, $env, $debug);

    sfContext::createInstance($configuration);
  }
}
