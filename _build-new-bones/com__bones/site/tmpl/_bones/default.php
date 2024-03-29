<?php
/**
 * @package     Joomla.Site
 * @subpackage  com__bones
 *
 * @copyright   Copyright (C) {{OWNER}} {{YEAR}}.
 * @license     MIT License; see LICENSE.md
 */

#use Joomla\CMS\HTML\HTMLHelper;
#use Joomla\CMS\Language\Multilanguage;
#use Joomla\CMS\Layout\FileLayout;
#use Joomla\CMS\Layout\LayoutHelper;
#use Joomla\CMS\Session\Session;
#use Joomla\Component\Fields\Administrator\Helper\FieldsHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

defined('_JEXEC') or die;

$language = JFactory::getLanguage();
$language->load('com__bones', JPATH_ADMINISTRATOR . '/components/com__bones');

$table_id = '_bonesTable';

// Get the user object.
$user = Factory::getUser();

$uri  = JUri::getInstance();
#echo '<pre>'; echo $uri; echo '</pre>'; exit;
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

<?php if ($can_create) : ?>
<p>
    <a href="<?php echo Route::_('index.php?option=com__bones&view=add'); ?>"><?php echo Text::_('COM_BONES_RECORD_CREATING'); ?></a>
</p>
<?php endif; ?>

<table class="" id="<?php echo $table_id; ?>">
    <thead>
        <tr>
            <?php /*<th width="2%"><?php echo Text::_('COM_BONES_NUM'); ?></th>
            <th width="4%">
                <?php echo HTMLHelper::_('grid.checkall'); ?>
            </th>*/ ?>
            <th width="50%">
                <?php echo Text::_('COM_BONES_RECORDS_TITLE'); ?>
            </th>
            <th width="40%">
                <?php echo Text::_('COM_BONES_RECORDS_ALIAS'); ?>
            </th>
            <th width="10%">
                <?php echo Text::_('COM_BONES_RECORDS_ACTIONS'); ?>
            </th>
            <?php /*<th width="10%">
                <?php echo HTMLHelper::_('grid.sort', 'COM_BONES_PUBLISHED', 'published', $listDirn, $listOrder); ?>
            </th>
            <th width="4%">
                <?php echo HTMLHelper::_('grid.sort', 'COM_BONES_ID', 'id', $listDirn, $listOrder); ?>
            </th>*/ ?>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($this->items)) : ?>
            <?php foreach ($this->items as $i => $item) : ?>
                <tr>
                    <?php /*<td><?php echo $this->pagination->getitemOffset($i); ?></td>
                    <td>
                        <?php echo HTMLHelper::_('grid.id', $i, $item->id); ?>
                    </td>*/ ?>
                    <td>
                        <?php
                        //$view_link ='test';
                        $view_link = Route::_('index.php?option=com__bones&view=_bone&id=' . $item->id);
                        //$edit_link = $uri . '?task=_bone.edit&view=_bone&id=' . $item->id;
                        //$edit_link = 'index.php?option=com__bones&task=_bone.edit&view=_bone&id=' . $item->id;
                        $edit_link = Route::_('index.php?option=com__bones&view=_bone&task=_bone.edit&id=' . $item->id);
                        $is_own = false;
                        if ($this->user->authorise('core.edit.own', 'com__bones') && ($this->user->id == $item->created_by)) {
                            $is_own = true;
                        }
                        ?>
                        <?php echo $view_link; ?><br>
                        <a href="<?php echo $view_link; ?>" title="<?php echo Text::_('COM_BONES_VIEW_RECORD'); ?>">
                            <?php echo $item->title; ?>
                        </a>
                    </td>
                    <td>
                        <?php echo $item->alias; ?>
                    </td>
                    <td>
                        <?php if($is_own || $can_edit): ?>
                        <?php echo $edit_link; ?><br>
                        <a href="<?php echo $edit_link; ?>" title="<?php echo Text::_('COM_BONES_EDIT_RECORD'); ?>">
                            <?php echo Text::_('COM_BONES_RECORDS_ACTION_EDIT'); ?>
                        </a>
                        <?php else: ?>
                        -
                        <?php endif; ?>
                    </td>
                    <?php /*<td align="center">
                        <?php echo HTMLHelper::_('jgrid.published', $item->state, $i, '_bones.', true, 'cb'); ?>
                    </td>
                    <td align="center">
                        <?php echo $item->id; ?>
                    </td>*/ ?>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<p>
    <?php //$alt = 'alt'; ?>
    <?php $alt = Route::_('index.php?option=com__bones&view=alt'); ?>
    <?php echo $alt; ?> <a href="<?php echo $alt; ?>">Sample alternative view</a>
</p>
<p>
    <?php $alt = Route::_('index.php?option=com__bones&layout=other'); ?>
    <?php echo $alt; ?> <a href="<?php echo $alt; ?>">Sample alternative (other) template</a>
</p>