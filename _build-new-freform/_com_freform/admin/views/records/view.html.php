<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com__freform
 *
 * @copyright   Copyright (C) {{OWNER}} {{YEAR}}.
 * @license     MIT License; see LICENSE.md
 */

defined('_JEXEC') or die;

/**
 * _Freform Records View
 */
class _FreformViewRecords extends JViewLegacy
{
    /**
     * Display the _Freform view
     *
     * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
     *
     * @return  void
     */
    function display($tpl = null)
    {
        
        // Get application
        $app = JFactory::getApplication();
        $context = "_freform.list.admin.record";
        // Get data from the model
        $this->items            = $this->get('Items');
        $this->pagination       = $this->get('Pagination');
        $this->state            = $this->get('State');
        $this->filter_order     = $app->getUserStateFromRequest($context.'filter_order', 'filter_order', 'record', 'cmd');
        $this->filter_order_Dir = $app->getUserStateFromRequest($context.'filter_order_Dir', 'filter_order_Dir', 'asc', 'cmd');
        $this->filterForm       = $this->get('FilterForm');
        $this->activeFilters    = $this->get('ActiveFilters');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode('<br />', $errors));

            return false;
        }

        // Set the toolbar and number of found items
        $this->addToolBar();

        // Display the template
        parent::display($tpl);

        // Set the document
        $this->setDocument();
    }

    /**
     * Add the page title and toolbar.
     *
     * @return  void
     */
    protected function addToolBar()
    {
        $canDo = _FreformHelper::getActions();
        $user  = JFactory::getUser();
        
        $title = JText::_('COM_FREFORM_MANAGER_RECORDS');

        if ($this->pagination->total) {
            $title .= "<span style='font-size: 0.5em; vertical-align: middle;'> (" . $this->pagination->total . ")</span>";
        }

        JToolBarHelper::title($title, 'record');
        /*
        JToolBarHelper::addNew('record.add');
        if (!empty($this->items)) {
            JToolBarHelper::editList('record.edit');
            JToolBarHelper::deleteList('', 'records.delete');
        }
        */
        if ($canDo->get('core.create') || count($user->getAuthorisedCategories('com__freform', 'core.create')) > 0) {
            JToolbarHelper::addNew('record.add');
        }

        if ($canDo->get('core.edit') || $canDo->get('core.edit.own'))
        {
            JToolbarHelper::editList('record.edit');
        }
        
        if ($canDo->get('core.edit.state'))
        {
            JToolbarHelper::publish('records.publish', 'JTOOLBAR_PUBLISH', true);
            JToolbarHelper::unpublish('records.unpublish', 'JTOOLBAR_UNPUBLISH', true);
            //JToolbarHelper::custom('record.featured', 'featured.png', 'featured_f2.png', 'JFEATURE', true);
            //JToolbarHelper::custom('record.unfeatured', 'unfeatured.png', 'featured_f2.png', 'JUNFEATURE', true);
            //JToolbarHelper::archiveList('record.archive');
            //JToolbarHelper::checkin('record.checkin');
        }
        
        
        if ($this->state->get('filter.published') == -2 && $canDo->get('core.delete'))
        {
            JToolbarHelper::deleteList('JGLOBAL_CONFIRM_DELETE', 'records.delete', 'JTOOLBAR_EMPTY_TRASH');
        }
        elseif ($canDo->get('core.edit.state'))
        {
            JToolbarHelper::trash('records.trash');
        }
        
        JToolBarHelper::preferences('com__freform');
        
        // Render side bar.
		$this->sidebar = JHtmlSidebar::render();
    }
    /**
     * Method to set up the document properties
     *
     * @return void
     */
    protected function setDocument() 
    {
        $document = JFactory::getDocument();
        $document->setTitle(JText::_('COM_FREFORM_ADMINISTRATION'));
    }
}