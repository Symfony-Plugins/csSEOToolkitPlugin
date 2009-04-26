<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PluginSeoPageTable extends Doctrine_Table
{
	public function getSearchQuery($keywords)
	{
		$q = $this->createQuery('p');
		foreach ($keywords as $keyword) 
		{
		   $q->orWhere('p.title like ?', "%$keyword%")
				 ->orWhere('p.description like ?', "%$keyword%")
				 ->orWhere('p.keywords like ?', "%$keyword%");
		}
		return $q;
	}
}