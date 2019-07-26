<?php
/**
 * @package     Joomla.Site
 * @subpackage  com__bones
 *
 * @copyright   Copyright (C) {{OWNER}} {{YEAR}}.
 * @license     MIT License; see LICENSE.md
 */

defined('_JEXEC') or die;

#require_once JPATH_COMPONENT . '/helpers/route.php';

// Get an instance of the controller prefixed by _Bones
$controller = JControllerLegacy::getInstance('_Bones');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();