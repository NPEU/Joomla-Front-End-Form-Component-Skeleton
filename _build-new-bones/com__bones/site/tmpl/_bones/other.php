<?php
/**
 * @package     Joomla.Site
 * @subpackage  com__bones
 *
 * @copyright   Copyright (C) {{OWNER}} {{YEAR}}.
 * @license     MIT License; see LICENSE.md
 */

use Joomla\CMS\Language\Text;
use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;
#use Joomla\CMS\Layout\LayoutHelper;
#use Joomla\CMS\Layout\FileLayout;
#use Joomla\CMS\Language\Multilanguage;
#use Joomla\CMS\Session\Session;
#use Joomla\Component\Fields\Administrator\Helper\FieldsHelper;

#use Joomla\CMS\HTML\HTMLHelper;

defined('_JEXEC') or die;

$language = JFactory::getLanguage();
$language->load('com__bones', JPATH_ADMINISTRATOR . '/components/com__bones');

$table_id = '_bonesTable';

// Get the user object.
$user = Factory::getUser();

// Check if user is allowed to add/edit based on tags permissions.
$can_edit       = $user->authorise('core.edit', 'com__bones');
$can_create     = $user->authorise('core.create', 'com__bones');
$can_edit_state = $user->authorise('core.edit.state', 'com__bones');

?>
<?php if ($this->params->get('show_page_heading')) : ?>
<h1>
    <?php echo $this->escape($this->params->get('page_heading')); ?>
</h1>
<?php endif; ?>
<p>This content is coming from a template in the '_bones' folder, and doesn't need it's own View to stuff.</p>