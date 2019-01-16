<?php
/**
 * @package     Joomla.Site
 * @subpackage  com__freform
 *
 * @copyright   Copyright (C) {{OWNER}} {{YEAR}}.
 * @license     MIT License; see LICENSE.md
 */

defined('_JEXEC') or die;

#require_once JPATH_COMPONENT . '/helpers/route.php';

// Get an instance of the controller prefixed by _Freform
$controller = JControllerLegacy::getInstance('_Freform');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();