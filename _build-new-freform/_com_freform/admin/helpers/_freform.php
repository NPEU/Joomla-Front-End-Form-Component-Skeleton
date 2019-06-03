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
 * _FreformHelper component helper.
 */
class _FreformHelper
{
    /**
     * Add style
     */
    public static function addStyle()
    {
        // Set some global property
        $document = JFactory::getDocument();

        // Update this with icon of choice from:
        // /administrator/templates/isis/css/template.css
        $document->addStyleDeclaration('.icon-record:before {content: "\e014";}');
    }
    
    /**
     * Configure the Submenu. Delete if component has only one view.
     *
     * @param   string  The name of the active view.
     */
    public static function addSubmenu($vName = 'records')
    {
        JHtmlSidebar::addEntry(
            JText::_('COM_FREFORM_MANAGER_SUBMENU_RECORDS'),
            'index.php?option=com__freform&view=records',
            $vName == 'records'
        );
        JHtmlSidebar::addEntry(
            JText::_('COM_FREFORM_MANAGER_SUBMENU_CATEGORIES'),
            'index.php?option=com_categories&view=categories&extension=com__freform',
            $vName == 'categories'
        );
    }

    /**
     * Get the actions
     */
    public static function getActions($itemId = 0, $model = null)
    {
        jimport('joomla.access.access');
        $user   = JFactory::getUser();
        $result = new JObject;

        if (empty($itemId)) {
            $assetName = 'com__freform';
        }
        else {
            $assetName = 'com__freform.record.'.(int) $itemId;
        }

        $actions = JAccess::getActions('com__freform', 'component');

        foreach ($actions as $action) {
            $result->set($action->name, $user->authorise($action->name, $assetName));
        }
        
        // Check if user belongs to assigned category and permit edit if so:
        if ($model) {
            $item  = $model->getItem($itemId);

            if (!!($user->authorise('core.edit', 'com__freform')
            || $user->authorise('core.edit', 'com_content.category.' . $item->catid))) {
                $result->set('core.edit', true);
            }
        }

        return $result;
    }
}