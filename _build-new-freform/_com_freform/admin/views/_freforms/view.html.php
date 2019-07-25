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
 * _Freform _Freform View
 */
class _FreformView_Freform extends JViewLegacy
{
    protected $items;

	protected $pagination;

	protected $state;
    
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
        $context = "_freform.list.admin._freform1";
        // Get data from the model
        $this->state         = $this->get('State');
		$this->items         = $this->get('Items');
		$this->pagination    = $this->get('Pagination');
		$this->filterForm    = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');
        #$this->filter_order     = $app->getUserStateFromRequest($context.'filter_order', 'filter_order', 'brand', 'cmd');
        #$this->filter_order_Dir = $app->getUserStateFromRequest($context.'filter_order_Dir', 'filter_order_Dir', 'asc', 'cmd');

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

        JToolBarHelper::title($title, '_freform1');
        /*
        JToolBarHelper::addNew('_freform1.add');
        if (!empty($this->items)) {
            JToolBarHelper::editList('_freform1.edit');
            JToolBarHelper::deleteList('', '_freform.delete');
        }
        */
        if ($canDo->get('core.create') || count($user->getAuthorisedCategories('com__freform', 'core.create')) > 0) {
            JToolbarHelper::addNew('_freform1.add');
        }

        if ($canDo->get('core.edit') || $canDo->get('core.edit.own'))
        {
            JToolbarHelper::editList('_freform1.edit');
        }
        
        if ($canDo->get('core.edit.state'))
        {
            JToolbarHelper::publish('_freform.publish', 'JTOOLBAR_PUBLISH', true);
            JToolbarHelper::unpublish('_freform.unpublish', 'JTOOLBAR_UNPUBLISH', true);
            //JToolbarHelper::custom('_freform1.featured', 'featured.png', 'featured_f2.png', 'JFEATURE', true);
            //JToolbarHelper::custom('_freform1.unfeatured', 'unfeatured.png', 'featured_f2.png', 'JUNFEATURE', true);
            //JToolbarHelper::archiveList('_freform1.archive');
            //JToolbarHelper::checkin('_freform1.checkin');
        }
        
        
        if ($this->state->get('filter.published') == -2 && $canDo->get('core.delete'))
        {
            JToolbarHelper::deleteList('JGLOBAL_CONFIRM_DELETE', '_freform.delete', 'JTOOLBAR_EMPTY_TRASH');
        }
        elseif ($canDo->get('core.edit.state'))
        {
            JToolbarHelper::trash('_freform.trash');
        }
        
        if ($user->authorise('core.admin', 'com__freform') || $user->authorise('core.options', 'com__freform'))
		{
			JToolbarHelper::preferences('com__freform');
		}
        
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
    
    /**
	 * Returns an array of fields the table can be sorted by
	 *
	 * @return  array  Array containing the field name to sort by as the key and display text as value
	 */
	protected function getSortFields()
	{
		return array(
			'a.state' => JText::_('COM_FREFORM_PUBLISHED'),
			'a.title' => JText::_('COM_FREFORM_RECORDS_NAME'),
			'a.id'    => JText::_('COM_FREFORM_ID')
		);
	}
}