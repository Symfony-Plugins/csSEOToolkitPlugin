<?php

/**
 * PluginMetaData form.
 *
 * @package    form
 * @subpackage MetaData
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
abstract class PluginMetaDataForm extends BaseMetaDataForm
{
	public function setUp()
	{
		parent::setUp();
		$this->widgetSchema['title'] = new sfWidgetFormInput(array(), array('size' => 40));
		$this->widgetSchema['description'] = new sfWidgetFormTextarea(array(), array('cols' => 38));
		$this->widgetSchema['keywords'] = new sfWidgetFormInput(array(), array('size' => 40));

	}
}