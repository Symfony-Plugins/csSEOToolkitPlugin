<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PluginSeoPageTable extends Doctrine_Table
{
	public function createQuery($alias = '')
	{
		return parent::createQuery($alias)
									->innerJoin($alias.'.MetaData m')
									->innerJoin($alias.'.SitemapItem s');
	}
}