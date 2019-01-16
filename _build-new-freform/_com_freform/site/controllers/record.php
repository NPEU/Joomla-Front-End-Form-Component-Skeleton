<?php
/**
 * @package     Joomla.Site
 * @subpackage  com__freform
 *
 * @copyright   Copyright (C) {{OWNER}} {{YEAR}}.
 * @license     MIT License; see LICENSE.md
 */

defined('_JEXEC') or die;

#use Joomla\Utilities\ArrayHelper;


/**
 * _Freform Controller
 */
class _FreformControllerRecord extends JControllerForm
{
    /**
     * The URL view item variable.
     *
     * @var    string
     * @since  1.6
     */
    #protected $view_item = 'form';

    /**
     * The URL view list variable.
     *
     * @var    string
     * @since  1.6
     */
    #protected $view_list = 'categories';

    /**
     * The URL edit variable.
     *
     * @var    string
     * @since  3.2
     */
    #protected $urlVar = 'a.id';

    /**
     * Method to add a new record.
     *
     * @return  boolean  True if the article can be added, false if not.
     */
    public function add()
    {
        if (!parent::add())
        {
            // Redirect to the return page.
            $this->setRedirect($this->getReturnPage());
        }
    }
    
    /**
     * Method override to check if you can add a new record.
     *
     * @param   array  $data  An array of input data.
     *
     * @return  boolean
     */
    protected function allowAdd($data = array())
    {
        /*$categoryId   = ArrayHelper::getValue($data, 'catid', $this->input->getInt('id'), 'int');
        $allow      = null;

        if ($categoryId)
        {
            // If the category has been passed in the URL check it.
            $allow = JFactory::getUser()->authorise('core.create', $this->option . '.category.' . $categoryId);
        }

        if ($allow !== null)
        {
            return $allow;
        }*/

        // In the absense of better information, revert to the component permissions.
        return parent::allowAdd($data);
    }

    /**
     * Method to check if you can add a new record.
     *
     * @param   array   $data  An array of input data.
     * @param   string  $key   The name of the key for the primary key.
     *
     * @return  boolean
     */
    protected function allowEdit($data = array(), $key = 'id')
    {
        /*$recordId   = (int) isset($data[$key]) ? $data[$key] : 0;
        $categoryId = 0;

        if ($recordId)
        {
            $categoryId = (int) $this->getModel()->getItem($recordId)->catid;
        }

        if ($categoryId)
        {
            // The category has been set. Check the category permissions.
            return JFactory::getUser()->authorise('core.edit', $this->option . '.category.' . $categoryId);
        }*/
        $user    = JFactory::getUser();
        $user_id = $user->id;
        
        $record_id = (int) isset($data[$key]) ? $data[$key] : 0;
         
        // If we have a record id, we also need the created_by id. If there isn't a form element for
        // this, we need to fetch it:
        if (!isset($data['created_by'])) {
            if ($record_id > 0) {
                $db = JFactory::getDbo();
                $db->setQuery('SELECT created_by FROM #___freformtbl WHERE ' . $key . ' = ' . $record_id);
                $data['created_by'] = $db->loadResult();
            } else {
                $data['created_by'] = 0;
            }
        }
        
        
        // This is based on what's found in the article admin controller but it returns null.
        // I'm not sure how it's SUPPOSED to work, so fudging it for now with the line below it.
        //$t = $user->authorise('core.edit.own', 'com__freform.' . $record_id);
        if ($user->authorise('core.edit.own', $this->option)) {
            
            // Now test the owner is the user.
            $owner_id = (int) $data['created_by'];
            if (empty($owner_id) && $record_id)
            {
                // Need to do a lookup from the model.
                $record = $this->getModel()->getItem($record_id);

                if (empty($record))
                {
                    return false;
                }

                $owner_id = $record->created_by;
            }

            // If the owner matches 'me' then do the test.
            if ($owner_id == $user_id)
            {
                return true;
            }
        }

        
        /*if ($user->id == $data['created_by']) {
            return $user->authorise('core.edit.own', $this->option);
        }*/
        
        
        // Since there is no asset tracking, revert to the component permissions.
        return parent::allowEdit($data, $key);
    }

    /**
     * Method to cancel an edit.
     *
     * @param   string  $key  The name of the primary key of the URL variable.
     *
     * @return  boolean  True if access level checks pass, false otherwise.
     */
    public function cancel($key = 'id')
    {
        $return = parent::cancel($key);

        // Redirect to the return page.
        $this->setRedirect($this->getReturnPage());

        return $return;
    }
    
    /**
     * Method to edit an existing record.
     *
     * @param   string  $key     The name of the primary key of the URL variable.
     * @param   string  $urlVar  The name of the URL variable if different from the primary key (sometimes required to avoid router collisions).
     *
     * @return  boolean  True if access level check and checkout passes, false otherwise.
     */
    public function edit($key = null, $urlVar = 'id')
    {
        return parent::edit($key, $urlVar);
    }

    /**
     * Method to get a model object, loading it if required.
     *
     * @param   string  $name    The model name. Optional.
     * @param   string  $prefix  The class prefix. Optional.
     * @param   array   $config  Configuration array for model. Optional.
     *
     * @return  object  The model.
     */
    public function getModel($name = 'record', $prefix = '', $config = array('ignore_request' => true))
    {
        return parent::getModel($name, $prefix, $config);
    }

    /**
     * Gets the URL arguments to append to an item redirect.
     *
     * @param   integer  $recordId  The primary key id for the item.
     * @param   string   $urlVar    The name of the URL variable for the id.
     *
     * @return  string  The arguments to append to the redirect URL.
     */
    protected function getRedirectToItemAppend($recordId = null, $urlVar = null)
    {
        $append = parent::getRedirectToItemAppend($recordId, $urlVar);
        $itemId = $this->input->getInt('Itemid');
        $return = $this->getReturnPage();

        if ($itemId)
        {
            $append .= '&Itemid=' . $itemId;
        }

        if ($return)
        {
            $append .= '&return=' . base64_encode($return);
        }

        return $append;
    }

    /**
     * Get the return URL if a "return" variable has been passed in the request
     *
     * @return  string  The return URL.
     */
    protected function getReturnPage()
    {
        $return = $this->input->get('return', null, 'base64');

        if (empty($return) || !JUri::isInternal(base64_decode($return)))
        {
            return JUri::base();
        }

        return base64_decode($return);
    }

    /**
     * Method to save a record.
     *
     * @param   string  $key     The name of the primary key of the URL variable.
     * @param   string  $urlVar  The name of the URL variable if different from the primary key (sometimes required to avoid router collisions).
     *
     * @return  boolean  True if successful, false otherwise.
     */
    public function save($key = null, $urlVar = 'id')
    {
        $is_ajax =  JFactory::getApplication()->input->get('ajax', '', 'bool');

        $result = parent::save($key, $urlVar);

        if ($is_ajax) {
            $app = JFactory::getApplication();
            try {
                $record_id = false;

                if ($result) {
                    $is_new = (bool) !$this->input->getInt($urlVar, false);
                    $message_type = 'success';
                    $message = $is_new ? JText::_('JLIB_APPLICATION_SUBMIT_SAVE_SUCCESS')
                                       : JText::_('JLIB_APPLICATION_SAVE_SUCCESS');
                    // Get the latest id:
                    $db = JFactory::getDbo();
                    $record_id = $db->insertid();
                } else {
                    $message_type = 'error';
                    $message = JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', 'Undetermined error.');
                }
                // Pass the new id back - may be useful:
                $data = array('id'=>$record_id);

                $app->enqueueMessage($message, $message_type);

                echo new JResponseJson($data, $message, $result);

            } catch(Exception $e) {
                echo new JResponseJson($e);
            }
            $app->close();
            exit;
        }

        // If ok, redirect to the return page.
        /*if ($result)
        {
            $this->setRedirect($this->getReturnPage());
        }*/
        return $result;
    }
}