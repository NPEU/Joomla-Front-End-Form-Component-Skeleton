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
 * _BonesHelper component helper.
 */
class _BonesHelper extends JHelperContent
{
    /**
     * Configure the Submenu. Delete if component has only one view.
     *
     * @param   string  The name of the active view.
     */
    public static function addSubmenu($vName = '_bones')
    {
        JHtmlSidebar::addEntry(
            JText::_('COM_BONES_MANAGER_SUBMENU_RECORDS'),
            'index.php?option=com__bones&view=_bones',
            $vName == '_bones'
        );

        JHtmlSidebar::addEntry(
            JText::_('COM_BONES_MANAGER_SUBMENU_CATEGORIES'),
            'index.php?option=com_categories&view=categories&extension=com__bones',
            $vName == 'categories'
        );
    }

    /**
     * Get the actions
     */
     /*
    public static function getActions($itemId = 0, $model = null)
    {
        jimport('joomla.access.access');
        $user   = JFactory::getUser();
        $result = new JObject;

        if (empty($itemId)) {
            $assetName = 'com_bones';
        }
        else {
            $assetName = 'com__bones._bone.'.(int) $itemId;
        }

        $actions = JAccess::getActions('com__bones', 'component');

        foreach ($actions as $action) {
            $result->set($action->name, $user->authorise($action->name, $assetName));
        }

        // Check if user belongs to assigned category and permit edit if so:
        if ($model) {
            $item  = $model->getItem($itemId);

            if (!!($user->authorise('core.edit', 'com__bones')
            || $user->authorise('core.edit', 'com_content.category.' . $item->catid))) {
                $result->set('core.edit', true);
            }
        }

        return $result;
    }*/

}
