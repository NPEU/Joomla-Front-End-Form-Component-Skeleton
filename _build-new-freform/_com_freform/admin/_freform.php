<?php
/**
 * @package     Joomla.Site
 * @subpackage  com__freform
 *
 * @copyright   Copyright (C) NPEU 2019.
 * @license     MIT License; see LICENSE.md
 */

defined('_JEXEC') or die;

// Set some global property
#$document = JFactory::getDocument();
#$document->addStyleDeclaration('.icon-helloworld {background-image: url(../media/com_helloworld/images/tux-16x16.png);}');

// Require helper file
#JLoader::register('HelloWorldHelper', JPATH_COMPONENT . '/helpers/helloworld.php');

// Get an instance of the controller prefixed by _Freform
$controller = JControllerLegacy::getInstance('_Freform');

// Perform the Request task
$input = JFactory::getApplication()->input;
$controller->execute($input->getCmd('task'));

// Redirect if set by the controller
$controller->redirect();
