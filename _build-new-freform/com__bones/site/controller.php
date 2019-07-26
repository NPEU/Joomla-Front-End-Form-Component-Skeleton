<?php
/**
 * @package     Joomla.Site
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
     * @param   boolean  $cachable   If true, the view output will be cached
     * @param   array    $urlparams  An array of safe url parameters and their variable types,
     *                               for valid values see {@link JFilterInput::clean()}.
     *
     * @return  _BonesController   This object to support chaining.
     *
     */
    public function display($cachable = false, $urlparams = false)
    {
        #$cachable  = true; // Huh? Why not just put that in the constructor?
        #$user      = JFactory::getUser();

        // Set the default view name and format from the Request.
        // Note we are using r_id to avoid collisions with the router and the return page.
        // Frontend is a bit messier than the backend.
        $id    = $this->input->getInt('r_id');
        $vName = $this->input->get('view', '_bones');
        
        // AK: I'm not keen on having the default view be '_bones'. This may make more sense in a
        // real scenario (e.g. 'weblinks' for where this is taken) but I find it less confusing this
        // way.
        if ($vName == '_bones') {
            $vName = '_bones';
        }
        $this->input->set('view', $vName);
        
        #echo '<pre>'; var_dump($this->input); echo '</pre>'; exit;

        /*if (JFactory::getUser()->id ||($this->input->getMethod() == 'POST' && $vName = 'categories'))
        {
            $cachable = false;
        }*/

        $safeurlparams = array(
            'id'                => 'INT',
            'limit'             => 'UINT',
            'limitstart'        => 'UINT',
            'filter_order'      => 'CMD',
            'filter_order_Dir'  => 'CMD',
            'lang'              => 'CMD'
        );

        // Check for edit form.
        if ($vName == 'form' && !$this->checkEditId('com__bones.edit._bone', $id))
        {
            // Somehow the person just went to the form - we don't allow that.
            return JError::raiseError(403, JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
        }

        return parent::display($cachable, $safeurlparams);
    }
}