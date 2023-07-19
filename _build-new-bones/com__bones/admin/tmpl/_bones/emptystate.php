<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com__bones
 *
 * @copyright   Copyright (C) NPEU 2023.
 * @license     MIT License; see LICENSE.md
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Layout\LayoutHelper;

$displayData = [
    'textPrefix' => 'COM_BONES',
    'formURL'    => 'index.php?option=com__bones',
];

/*
$displayData = [
    'textPrefix' => 'COM_BONES',
    'formURL'    => 'index.php?option=com__bones',
    'helpURL'    => '',
    'icon'       => 'icon-globe _bones',
];
*/

$user = Factory::getApplication()->getIdentity();

if ($user->authorise('core.create', 'com__bones') || count($user->getAuthorisedCategories('com__bones', 'core.create')) > 0) {
    $displayData['createURL'] = 'index.php?option=com__bones&task=_bone.add';
}

echo LayoutHelper::render('joomla.content.emptystate', $displayData);