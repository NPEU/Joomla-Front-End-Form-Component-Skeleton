<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com__bones
 *
 * @copyright   Copyright (C) {{OWNER}} {{YEAR}}.
 * @license     MIT License; see LICENSE.md
 */

namespace {{OWNER}}\Component\_Bones\Administrator\View\_Bone;

defined('_JEXEC') or die;


use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Factory;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Component\ComponentHelper;


class HtmlView extends BaseHtmlView {

    protected $form;
	protected $item;
	protected $canDo;

    /**
     * Display the "Hello World" edit view
     */
    function display($tpl = null) {

        $this->form  = $this->get('Form');
        $this->item  = $this->get('Item');

        // What Access Permissions does this user have? What can (s)he do?
		$this->canDo = ContentHelper::getActions('com__bones', '_bone', $this->item->id);

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            throw new GenericDataException(implode("\n", $errors), 500);
        }

        $this->addToolBar();

        $this->setDocument();

        parent::display($tpl);
    }

    protected function addToolBar() {

        $input = Factory::getApplication()->input;

		// Hide Joomla Administrator Main menu
		$input->set('hidemainmenu', true);

		$isNew = ($this->item->id == 0);

		ToolBarHelper::title($isNew ? Text::_('COM_BONES_MANAGER_RECORD_ADD')
		                            : Text::_('COM_BONES_MANAGER_RECORD_EDIT'), '_bone');
		// Build the actions for new and existing records.
		if ($isNew)
		{
			// For new records, check the create permission.
			if ($this->canDo->get('core.create'))
			{
				ToolbarHelper::apply('_bone.apply', 'JTOOLBAR_APPLY');
				ToolbarHelper::save('_bone.save', 'JTOOLBAR_SAVE');
				ToolbarHelper::custom('_bone.save2new', 'save-new.png', 'save-new_f2.png',
				                       'JTOOLBAR_SAVE_AND_NEW', false);
			}
			ToolbarHelper::cancel('_bone.cancel', 'JTOOLBAR_CANCEL');
		}
		else
		{
			if ($this->canDo->get('core.edit'))
			{
				// We can save the new record
				ToolbarHelper::apply('_bone.apply', 'JTOOLBAR_APPLY');
				ToolbarHelper::save('_bone.save', 'JTOOLBAR_SAVE');

				// We can save this record, but check the create permission to see
				// if we can return to make a new one.
				if ($this->canDo->get('core.create'))
				{
					ToolbarHelper::custom('_bone.save2new', 'save-new.png', 'save-new_f2.png',
					                       'JTOOLBAR_SAVE_AND_NEW', false);
				}
                $save_history = Factory::getApplication()->get('save_history', true);
				if ($save_history)
				{
					ToolbarHelper::versions('com__bone._bone', $this->item->id);
				}
			}
			if ($this->canDo->get('core.create'))
			{
				ToolbarHelper::custom('_bone.save2copy', 'save-copy.png', 'save-copy_f2.png',
				                       'JTOOLBAR_SAVE_AS_COPY', false);
			}
			ToolbarHelper::cancel('_bone.cancel', 'JTOOLBAR_CLOSE');
		}
    }

    protected function setDocument() {
		//HtmlHelper::_('behavior.framework');
		//HtmlHelper::_('behavior.formvalidator');

		$isNew = ($this->item->id < 1);
		$this->document->setTitle($isNew ? Text::_('COM_BONES_BONE_CREATING') :
                Text::_('COM_BONES_BONE_EDITING'));
	}
}