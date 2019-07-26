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
     * Add style
     */
    public static function addStyle()
    {
        // Set some global property
        $document = JFactory::getDocument();
        // Update this with icon of choice from:
        // /administrator/templates/isis/css/template.css
        $document->addStyleDeclaration('.icon-_bone:before {content: "\e222";}');
    }
    
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
}