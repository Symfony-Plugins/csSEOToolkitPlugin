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
    $this->name             = 'remove404';
    $this->briefDescription = 'removes all 404 pages being indexed in the SeoPage model';
    $this->detailedDescription = <<<EOF
This task removes 404 pages from the SeoPage Model.
It requires no arguments.
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
									
		foreach ($pages as $page) 
		{
			if(!$browser->isValidUrl($page['url']))
			{
				$page->delete();
				$final = $this->formatter->format('Removed one model: "'.$page->getTitle() . '"');
    		$this->dispatcher->notify(new sfEvent($this, 'command.log', array('', $final)));
			}
		}
  }
  protected function bootstrapSymfony($app, $env, $debug = true)
  {
    $configuration = ProjectConfiguration::getApplicationConfiguration($app, $env, $debug);

    sfContext::createInstance($configuration);
  }
}
