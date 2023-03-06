<?php
/**
 * @package     Joomla.Site
 * @subpackage  com__bones
 *
 * @copyright   Copyright (C) {{OWNER}} {{YEAR}}.
 * @license     MIT License; see LICENSE.md
 */

namespace {{OWNER}}\Component\_Bones\Site\Model;


// No direct access to this file
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\CMS\Factory;


/**
 * _Bones Model
 *
 * @since  0.0.1
 */
class FormModel extends AdminModel
{

	/**
	 * Method to get a table object, load it if necessary.
	 *
	 * @param   string  $type    The table name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  Table  A Table object
     *
     * We need to override this - otherwise it would take 'Form' as the $name
	 */

    public function getTable($name = '_Bones', $prefix = 'administrator', $options = array())
    {
        return parent::getTable($name, $prefix, $options);
    }

    /**
	 * Method to get the record form.
	 *
	 * @param   array    $data      Data for the form.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  mixed    A JForm object on success, false on failure
	 *
	 * @since   1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm(
			'com__bones.form',
			'add-form',
			array(
				'control' => 'jform',
				'load_data' => $loadData
			)
		);

		if (empty($form))
		{
			$errors = $this->getErrors();
			throw new \Exception(implode("\n", $errors), 500);
		}

		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 * As this form is for add, we're not prefilling the form with an existing record
	 * But if the user has previously hit submit and the validation has found an error,
	 *   then we inject what was previously entered.
	 *
	 * @return  mixed  The data for the form.
	 *
	 * @since   1.6
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = Factory::getApplication()->getUserState(
			'com__bones.edit._bone.data',
			array()
		);

		return $data;
	}

    /**
	 * Prepare a _bones record for saving in the database
	 */
	protected function prepareTable($table)
	{
	}

    protected function cleanCache($group = null, $client_id = 0)
	{
		parent::cleanCache('com__bones');
	}
}