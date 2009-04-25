<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PluginMetaDataTable extends Doctrine_Table
{
	public function findOneByUrl($url)
	{
		$q = $this->createQuery('m')
							->innerJoin('m.SeoPage p')
							->addWhere('p.url = ?', $url);
							
		return $q->fetchOne();
	}
}