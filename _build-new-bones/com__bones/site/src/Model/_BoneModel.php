<?php
/**
 * @package     Joomla.Site
 * @subpackage  com__bones
 *
 * @copyright   Copyright (C) {{OWNER}} {{YEAR}}.
 * @license     MIT License; see LICENSE.md
 */

namespace {{OWNER}}\Component\_Bones\Site\Model;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
#use Joomla\CMS\MVC\Model\ItemModel;
use Joomla\CMS\Language\Text;
use Joomla\Registry\Registry;
#use {{OWNER}}\Component\_Bones\Site\Helper\_BoneHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\Cache\CacheControllerFactoryInterface;

/**
 * _Bone Component Model
 */
class _BoneModel extends \{{OWNER}}\Component\_Bones\Administrator\Model\_BoneModel {

    /**
     * @var object item
     */
    protected $item;

    protected $item_state;

    /**
     * Method to auto-populate the model state.
     *
     * This method should only be called once per instantiation and is designed
     * to be called on the first call to the getState() method unless the model
     * configuration flag to ignore the request is set.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @return    void
     * @since    2.5
     */
    protected function populateState()
    {
        $app = Factory::getApplication();

        // Get the _bone id
        $jinput = $app->input;
        $id     = $jinput->get('id', 1, 'INT');
        $this->setState('_bone.id', $id);

        // Load the parameters.
        $this->setState('params', Factory::getApplication()->getParams());
        parent::populateState();

        /*
        $app = JFactory::getApplication();

        // Load state from the request.
        $pk = $app->input->getInt('id');
        $this->setState('_bone.id', $pk);

        // Add compatibility variable for default naming conventions.
        $this->setState('form.id', $pk);

        $return = $app->input->get('return', null, 'base64');

        if (!JUri::isInternal(base64_decode($return))) {
            $return = null;
        }

        $this->setState('return_page', base64_decode($return));

        // Load the parameters.
        $params = $app->getParams();
        $this->setState('params', $params);

        $this->setState('layout', $app->input->getString('layout'));

        ---

        $app = Factory::getApplication();

        // Load the object state.
        $pk = $app->input->getInt('id');
        $this->setState('weblink.id', $pk);

        // Load the parameters.
        $params = $app->getParams();
        $this->setState('params', $params);

        $user = $app->getIdentity();

        if (!$user->authorise('core.edit.state', 'com_weblinks') && !$user->authorise('core.edit', 'com_weblinks')) {
            $this->setState('filter.state', 1);
            $this->setState('filter.archived', 2);
        }

        $this->setState('filter.language', Multilanguage::isEnabled());
        */
    }

    /**
     * Get the _bone
     * @return object The _bone to be displayed to the user
     */
    public function getItem($pk = NULL)
    {
        if (!isset($this->item) || !is_null($pk))
        {
            $id    = $pk ?: $this->getState('_bone.id');
            $db    = $this->getDatabase();
            $query = $db->getQuery(true);

            $query->select('*')
                ->from($db->quoteName('#___bones'))
                ->where('id=' . (int) $id);

            if (is_numeric($this->item_state)) {
                $query->where('state = ' . (int) $this->item_state);
            } elseif ($this->item_state === '') {
                $query->where('(state IN (0, 1))');
            }

            /*if (Multilanguage::isEnabled())
            {
                $lang = Factory::getLanguage()->getTag();
                $query->where('h.language IN ("*","' . $lang . '")');
            }*/


            $db->setQuery((string) $query);

            if ($this->item = $db->loadObject()) {
                // Load the JSON string
                $params = new Registry;
                $params->loadString($this->item->params, 'JSON');
                $this->item->params = $params;

                // Merge global params with item params
                $params = clone $this->getState('params');
                $params->merge($this->item->params);
                $this->item->params = $params;

                // Convert the JSON-encoded image info into an array
                /*$image = new Registry;
                $image->loadString($this->item->image, 'JSON');
                $this->item->imageDetails = $image;*/

                // Check if the user can access this record:
                $user = Factory::getApplication()->getIdentity();
                $userAccessLevels = $user->getAuthorisedViewLevels();
                if ($user->authorise('core.admin')) {
                    $this->item->canAccess = true;
                }
                /*else {
                    if ($this->item->catid == 0) {
                        $this->item->canAccess = in_array($this->item->access, $userAccessLevels);
                    } else {
                        $this->item->canAccess = in_array($this->item->access, $userAccessLevels) && in_array($this->item->catAccess, $userAccessLevels);
                    }
                }*/
            }
        }
        return $this->item;
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
        // To DRY use the admin form use:
        // JPATH_COMPONENT_ADMINISTRATOR . '/forms/_bone.xml',
        // or if you need a separate site form, use:
        // JPATH_COMPONENT_SITE . '/forms/_bone.xml',
        $form = $this->loadForm(
            'com__bones.form',
            JPATH_COMPONENT_SITE . '/forms/_bone.xml',
            array(
                'control' => 'jform',
                'load_data' => $loadData
            )
        );

        if (empty($form)) {
            $errors = $this->getErrors();
            throw new \Exception(implode("\n", $errors), 500);
        }

        return $form;
    }

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

    public function getTable($name = '_Bone', $prefix = 'administrator', $options = array())
    {
        return parent::getTable($name, $prefix, $options);
    }
}