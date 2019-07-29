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
 * _Bones _Bones View
 */
class _BonesView_Bones extends JViewLegacy
{
    protected $items;

	protected $pagination;

	protected $state;
    
    /**
     * Display the _Bones view
     *
     * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
     *
     * @return  void
     */
    function display($tpl = null)
    {
        $this->state         = $this->get('State');
		$this->items         = $this->get('Items');
		$this->pagination    = $this->get('Pagination');
		$this->filterForm    = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');

		BonesHelper::addSubmenu('_bones');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->addToolbar();
		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
    }

    /**
     * Add the page title and toolbar.
     *
     * @return  void
     */
    protected function addToolBar()
    {
        //$canDo = _BonesHelper::getActions();
        $canDo = JHelperContent::getActions('com__bones');
        $user  = JFactory::getUser();
        
        $title = JText::_('COM_BONES_MANAGER_RECORDS');

        if ($this->pagination->total) {
            $title .= "<span style='font-size: 0.5em; vertical-align: middle;'> (" . $this->pagination->total . ")</span>";
        }

        JToolBarHelper::title($title, '_bone');
        /*
        JToolBarHelper::addNew('_bone.add');
        if (!empty($this->items)) {
            JToolBarHelper::editList('_bone.edit');
            JToolBarHelper::deleteList('', '_bones.delete');
        }
        */
        if ($canDo->get('core.create') || count($user->getAuthorisedCategories('com__bones', 'core.create')) > 0) {
            JToolbarHelper::addNew('_bone.add');
        }

        if ($canDo->get('core.edit') || $canDo->get('core.edit.own'))
        {
            JToolbarHelper::editList('_bone.edit');
        }
        
        if ($canDo->get('core.edit.state'))
        {
            JToolbarHelper::publish('_bones.publish', 'JTOOLBAR_PUBLISH', true);
            JToolbarHelper::unpublish('_bones.unpublish', 'JTOOLBAR_UNPUBLISH', true);
            //JToolbarHelper::custom('_bone.featured', 'featured.png', 'featured_f2.png', 'JFEATURE', true);
            //JToolbarHelper::custom('_bone.unfeatured', 'unfeatured.png', 'featured_f2.png', 'JUNFEATURE', true);
            //JToolbarHelper::archiveList('_bone.archive');
            //JToolbarHelper::checkin('_bone.checkin');
        }
        
        
        if ($this->state->get('filter.published') == -2 && $canDo->get('core.delete'))
        {
            JToolbarHelper::deleteList('JGLOBAL_CONFIRM_DELETE', '_bones.delete', 'JTOOLBAR_EMPTY_TRASH');
        }
        elseif ($canDo->get('core.edit.state'))
        {
            JToolbarHelper::trash('_bones.trash');
        }
        
        if ($user->authorise('core.admin', 'com__bones') || $user->authorise('core.options', 'com__bones'))
		{
			JToolbarHelper::preferences('com__bones');
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
        $document->setTitle(JText::_('COM_BONES_ADMINISTRATION'));
    }

    /**
	 * Returns an array of fields the table can be sorted by
	 *
	 * @return  array  Array containing the field name to sort by as the key and display text as value
	 */
	protected function getSortFields()
	{
		return array(
			'a.state' => JText::_('COM_BONES_PUBLISHED'),
			'a.title' => JText::_('COM_BONES_RECORDS_NAME'),
			'a.id'    => JText::_('COM_BONES_ID')
		);
	}
}