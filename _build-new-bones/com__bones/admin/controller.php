<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com__bones
 *
 * @copyright   Copyright (C) {{OWNER}} {{YEAR}}.
 * @license     MIT License; see LICENSE.md
 */

defined('_JEXEC') or die;

/**
 * _Bones Component Controller
 */
class _BonesController extends JControllerLegacy
{
    /**
     * Method to display a view.
     *
     * @param   boolean  $cacheable  If true, the view output will be cached
     * @param   array    $urlparams  An array of safe url parameters and their variable types,
     *                               for valid values see {@link JFilterInput::clean()}.
     *
     * @return  JControllerLegacy  This object to support chaining.
     */
    public function display($cacheable = false, $urlparams = false)
    {
        require_once JPATH_COMPONENT . '/helpers/_bones.php';

        $view   = $this->input->get('view', '_bones');
        $layout = $this->input->get('layout', 'default');
        $id     = $this->input->getInt('id');

        // Check for edit form.
        if ($view == '_bone' && $layout == 'edit' && !$this->checkEditId('com__bones.edit._bone', $id))
        {
            // Somehow the person just went to the form - we don't allow that.
            $this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
            $this->setMessage($this->getError(), 'error');
            $this->setRedirect(JRoute::_('index.php?option=com__bones&view=_bones', false));

            return false;
        }

        // Add style
        _BonesHelper::addStyle();

        return parent::display();
    }
}
