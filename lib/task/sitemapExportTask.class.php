<?php

class sitemapExportTask extends sfBaseTask
{
  protected function configure()
  {

    $this->namespace        = 'sitemap';
    $this->name             = 'export';
    $this->briefDescription = 'Exports the sitemap to XML for Google Search';
    $this->detailedDescription = <<<EOF
This task exports the sitemap as XML for Google Search indexing.
It requires no arguments.
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase('doctrine')->getConnection();

    // this is an ultra-simple task. we're simply taking all the 'Page' records for the sitemap
    // and exporting it as XML format. 
    // initialize an output accumulator for this purpose:
    $output = '';
    //PHP uses the same tags as XML, so in order to make this easy, print a var with the tag, so 
		//so it won't get eval'd
		$xmlTag = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
		//add the xml tag to start with...
		$output .= $xmlTag;
		//defines the sitemap schema format... similar to XHTML DOCTYPE declaration...
		$output .= "<urlset xmlns='http://www.sitemaps.org/schemas/sitemap/0.9'>\n";
		
		$locations = Doctrine_Query::create()
								   ->select('*')
									 ->from('Page')
									 //never actually made use of this boolean. update later...
									 #->where("in_sitemap IS NOT NULL AND in_sitemap != '0'")
									 ->execute();
	  $projectSettings = sfYaml::load(sfConfig::get('sf_root_dir').'/config/app.yml');
	  $baseURL = $projectSettings['all']['sitemap']['baseURL'];
	
		foreach ($locations as $location)
		{
			print '.';
			$url = $baseURL.$location['slug'];
			$modDate = date('Y-m-d', time());
			$output .=  "\t<url>\n";
			$output .=  "\t\t<loc>{$url}</loc>\n";
			$output .=  "\t\t<lastmod>{$modDate}</lastmod>\n";
			//well say that custom URLS update always (on every request) and that cms pages change roughly monthly
			//this is opposed to an archived URL which never udpates. this could be a LOT better, but gets the job done.
			if ($location['custom'])
			{
				$output .= "\t\t<changefreq>always</changefreq>\n";
			}
			else
			{
				$output .= "\t\t<changefreq>monthly</changefreq>\n";				
			}
   		$output .= "\t</url>\n";
  	}
    $output .= "</urlset>\n";
		//put this in a place where google can find it...
		$sitemapXMLfileSpec = sfConfig::get('sf_web_dir') .'/sitemap.xml';
		$smFile=fopen($sitemapXMLfileSpec,'w');
		fwrite($smFile, $output);
		fclose($smFile);
		//all done...
		

  }
}
