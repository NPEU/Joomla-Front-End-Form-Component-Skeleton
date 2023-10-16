<?php
/**
 * @package     Joomla.Site
 * @subpackage  com__bones
 *
 * @copyright   Copyright (C) {{OWNER}} {{YEAR}}.
 * @license     MIT License; see LICENSE.md
 */

namespace {{OWNER}}\Component\_Bones\Site\Controller;

defined('_JEXEC') or die;


use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Filesystem\Path;
use Joomla\CMS\Helper\MediaHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Log\Log;
use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Session\Session;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\User\User;
use Joomla\CMS\User\UserFactoryInterface;

/**
 * _Bone Component Controller
 *
 * Used to handle the http POST from the front-end form which allows users to enter a new _bone
 */
class _BoneController extends FormController
{
    protected $view_item;  // default view within JControllerForm for reload function

    public function __construct($config = array())
    {
        $input = Factory::getApplication()->input;
        $this->view_item = $input->get("view", "_bone", "string");
        parent::__construct($config);
    }

    public function cancel($key = null)
    {
        parent::cancel($key);

        // set up the redirect back to the same form
        /*$this->setRedirect(
            (string) Uri::getInstance(),
            Text::_('COM_BONES_ADD_CANCELLED')
        );*/
        $recordId = $this->input->getInt('id', false);

        if ($recordId) {
            $url = 'index.php?option=' . $this->option . '&view=' . $this->view_item . '&id=' . $recordId . $this->getRedirectToListAppend();
            $message = Text::_('COM_BONES_EDIT_CANCELLED');
        } else {
            $url = 'index.php?option=' . $this->option . '&view=' . $this->view_list . $this->getRedirectToListAppend();
            $message = Text::_('COM_BONES_ADD_CANCELLED');
        }

        $route = Route::_($url);

        // Redirect to the list screen.
        $this->setRedirect($route, $message);
    }


    public function abort()
    {
        // Using unconventional 'abort' task so that we can show the message. Using 'cancel'
        // results in an invalid token error so we can't use that (unless I figure out how)
        // Plus we don't need to 'check-in' an item so this may be better anyway?
        $url = 'index.php?option=' . $this->option . '&view=' . $this->view_list . $this->getRedirectToListAppend();
        $message = Text::_('COM_BONES_ADD_CANCELLED');

        $route = Route::_($url);
        // Redirect to the list screen.
        $this->setRedirect($route, $message);
    }


    /*
     * Function handing the save for adding a new _bones record
     * Based on the save() function in the JControllerForm class
     */
    public function save($key = null, $urlVar = null)
    {

        // Get the application
        $app = $this->app;

        $context = "$this->option.edit.$this->context";

        // Get the data from POST
        $data = $this->input->post->get('jform', array(), 'array');

        // Save the data in the session.
        $app->setUserState($context . '.data', $data);
        $result = parent::save($key, $urlVar);

        // If ok, redirect to the return page.
        if ($result) {
            // Flush the data from the session
            $app->setUserState($context . '.data', null);
            $this->setRedirect($this->getReturnPage());
        }

        return $result;
    }


    /**
     * Method to get a model object, loading it if required.
     *
     * @param   string  $name    The model name. Optional.
     * @param   string  $prefix  The class prefix. Optional.
     * @param   array   $config  Configuration array for model. Optional.z
     *
     * @return  object  The model.
     *
     * @since   1.5
     */
    public function getModel($name = 'Form', $prefix = '_Bones', $config = ['ignore_request' => true])
    {
        return parent::getModel($name, $prefix, $config);
    }


    /**
     * Get the return URL if a "return" variable has been passed in the request
     *
     * @return  string  The return URL.
     *
     * @since   1.6
     */
    protected function getReturnPage()
    {
        $return = $this->input->get('return', null, 'base64');

        if (empty($return) || !Uri::isInternal(base64_decode($return))) {
            return Uri::base();
        }

        // We need to check if the alias is being used in the return URL and update it if so
        // (it may have changed and would then result in a 404). This seems unreliable - if form
        // input names change this will break. I guess thats' why it's usual to redirect to the
        // listing page to avoud this issue. If it becomes a problem, do that.
        $r = base64_decode($return);
        $alias = $this->input->post->get('jform', array(), 'array')['alias'];
        $original_alias = $this->input->post->get('original_alias');

        $r = str_replace('/' . $original_alias . '/', '/' . $alias . '/', $r);
        return $r;
    }

}