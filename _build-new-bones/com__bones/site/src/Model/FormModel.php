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
 */
class FormModel extends \{{OWNER}}\Component\_Bones\Administrator\Model\_BoneModel
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

    public function getTable($name = '_Bone', $prefix = 'Administrator', $options = array())
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
     */
    public function getForm($data = array(), $loadData = true)
    {
        // Get the form.
        $form = $this->loadForm(
            'com__bones.form',
            '_bone',
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