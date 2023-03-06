<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_alerts
 *
 * @copyright   Copyright (C) NPEU 2023.
 * @license     MIT License; see LICENSE.md
 */

namespace NPEU\Component\Alerts\Administrator\View\Alerts;

defined('_JEXEC') or die;


use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Toolbar\ToolbarFactoryInterface;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Layout\FileLayout;

class HtmlView extends BaseHtmlView {

    /**
     * Display the main "Alerts" view
     *
     * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
     * @return  void
     */
    function display($tpl = null) {
        // Get application
		$app = Factory::getApplication();

        // Get data from the model
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
        $this->state			= $this->get('State');
		$this->filterForm    	= $this->get('FilterForm');
		$this->activeFilters 	= $this->get('ActiveFilters');

        // What Access Permissions does this user have? What can (s)he do?
		$this->canDo = ContentHelper::getActions('com_alerts');

        if (\count($errors = $this->get('Errors'))) {
            throw new GenericDataException(implode("\n", $errors), 500);
        }

        if ($this->getLayout() !== 'modal')
        {
            $this->addToolBar();
        } else
        {
            // If it's being displayed to select a record as an association, then forcedLanguage is set
			if ($forcedLanguage = $app->input->get('forcedLanguage', '', 'CMD'))
			{
				// Transform the language selector filter into an hidden field, so it can't be set
				$languageXml = new \SimpleXMLElement('<field name="language" type="hidden" default="' . $forcedLanguage . '" />');
				$this->filterForm->setField($languageXml, 'filter', true);

				// Also, unset the active language filter so the search tools is not open by default with this filter.
				unset($this->activeFilters['language']);
			}
        }

		// Prepare a mapping from parent id to the ids of its children
        $this->ordering = array();
        foreach ($this->items as $item)
        {
            $this->ordering[$item->parent_id][] = $item->id;
        }

        // Display the layout
		parent::display($tpl);

        $this->setDocument();
    }

    protected function addToolBar()
	{
		$title = Text::_('COM_ALERTS_MANAGER_ALERTSS', 'smiley-2');

        $bar = Toolbar::getInstance('toolbar');

		if ($this->pagination->total)
		{
			$title .= "<span style='font-size: 0.5em; vertical-align: middle;'>(" . $this->pagination->total . ")</span>";
		}
		ToolBarHelper::title($title, 'Messages');

        if ($this->canDo->get('core.create'))
		{
			ToolBarHelper::addNew('alert.add', 'JTOOLBAR_NEW');
		}
		if ($this->canDo->get('core.edit'))
		{
			ToolBarHelper::editList('alert.edit', 'JTOOLBAR_EDIT');
		}
		if ($this->canDo->get('core.delete'))
		{
			ToolBarHelper::deleteList('', 'alerts.delete', 'JTOOLBAR_DELETE');
		}
        if ($this->canDo->get('core.edit') || Factory::getApplication()->getIdentity()->authorise('core.manage', 'com_checkin'))
		{
			ToolBarHelper::checkin('alerts.checkin');
		}
        // Add a batch button
        if ($this->canDo->get('core.create') && $this->canDo->get('core.edit')
                && $this->canDo->get('core.edit.state'))
        {
            // we use a standard Joomla layout to get the html for the batch button
            $layout = new FileLayout('joomla.toolbar.batch');
            $batchButtonHtml = $layout->render(array('title' => Text::_('JTOOLBAR_BATCH')));
            $bar->appendButton('Custom', $batchButtonHtml, 'batch');
        }
		if ($this->canDo->get('core.admin'))
		{
			ToolBarHelper::divider();
			ToolBarHelper::preferences('comalert');
		}
	}

    protected function setDocument()
	{
		//$document = Factory::getApplication()->getDocument();
		$this->document->setTitle(Text::_('COM_ALERTS_ADMINISTRATION'));
	}

}