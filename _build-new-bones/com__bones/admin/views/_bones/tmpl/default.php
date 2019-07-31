<?php
/**
 * @package     Joomla.Site
 * @subpackage  com__bones
 *
 * @copyright   Copyright (C) {{OWNER}} {{YEAR}}.
 * @license     MIT License; see LICENSE.md
 */

defined('_JEXEC') or die;
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

$user      = JFactory::getUser();
$userId    = $user->get('id');
#$listOrder = $this->escape($this->filter_order);
#$listDirn  = $this->escape($this->filter_order_Dir);
$listOrder    = $this->escape($this->state->get('list.ordering'));
$listDirn    = $this->escape($this->state->get('list.direction'));

?>
<form action="<?php echo JRoute::_('index.php?option=com__bones&view=_bones'); ?>" method="post" id="adminForm" name="adminForm">

    <?php if (!empty( $this->sidebar)) : ?>
    <div id="j-sidebar-container" class="span2">
        <?php echo $this->sidebar; ?>
    </div>
    <div id="j-main-container" class="span10">
    <?php else : ?>
    <div id="j-main-container">
    <?php endif;?>
        <?php echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this)); ?>
        <div class="clearfix"> </div>
        <?php if (empty($this->items)) : ?>
        <div class="_bone _bone-no-items">
            <?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); #COM_BONES_NO_RECORDS ?>
        </div>
        <?php else : ?>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th width="2%"><?php echo JText::_('COM_BONES_NUM'); ?></th>
                    <th width="4%">
                        <?php echo JHtml::_('grid.checkall'); ?>
                    </th>
                    <th width="40%">
                        <?php echo JHtml::_('searchtools.sort', 'COM_BONES_RECORDS_TITLE', 'title', $listDirn, $listOrder); ?>
                    </th>
                    <th width="34%">
                        <?php echo JHtml::_('searchtools.sort', 'COM_BONES_RECORDS_OWNER', 'owner_name', $listDirn, $listOrder); ?>
                    </th>
                    <th width="10%">
                        <?php echo JHtml::_('searchtools.sort', 'COM_BONES_PUBLISHED', 'state', $listDirn, $listOrder); ?>
                    </th>
                    <th width="10%">
                        <?php echo JHtml::_('searchtools.sort', 'COM_BONES_ID', 'id', $listDirn, $listOrder); ?>
                    </th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <td colspan="6">
                        <?php echo $this->pagination->getListFooter(); ?>
                    </td>
                </tr>
            </tfoot>
            <tbody>
            <?php foreach ($this->items as $i => $item) : ?>
                <?php $item->cat_link = JRoute::_('index.php?option=com_categories&extension=com__boness&task=category.edit&id=' . $item->catid); ?>
                <?php $canCreate      = $user->authorise('core.create',     'com__bones.category.' . $item->catid); ?>
                <?php $canEdit        = $user->authorise('core.edit',       'com__bones.category.' . $item->catid); ?>
                <?php $canCheckin     = $user->authorise('core.manage',     'com_checkin') || $item->checked_out == $user->id || $item->checked_out == 0; ?>
                <?php $canEditOwn     = $user->authorise('core.edit.own',   'com__bones.category.' . $item->catid) && $item->created_by == $user->id; ?>
                <?php $canChange      = $user->authorise('core.edit.state', 'com__bones.category.' . $item->catid) && $canCheckin; ?>

                <tr>
                    <td><?php echo $this->pagination->getRowOffset($i); ?></td>
                    <td>
                        <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                    </td>
                    <td class="nowrap has-context">
                        <?php if ($item->checked_out) : ?>
                            <?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, '_bones.', $canCheckin); ?>
                        <?php endif; ?>
                        <?php if ($canEdit || $canEditOwn) : ?>
                            <a href="<?php echo JRoute::_('index.php?option=com__bones&task=_bone.edit&id=' . (int) $item->id); ?>" title="<?php echo JText::_('COM_BONES_EDIT_RECORD'); ?>">
                                <?php echo $this->escape($item->title); ?></a>
                        <?php else : ?>
                                <?php echo $this->escape($item->title); ?>
                        <?php endif; ?>
                        <span class="small">
                            <?php echo JText::sprintf('JGLOBAL_LIST_ALIAS', $this->escape($item->alias)); ?>
                        </span>
                        <div class="small">
                            <?php echo JText::_('JCATEGORY') . ': ' . (empty($item->category_title) ? 'none' : '<a href="' . $item->cat_link . '" target="_blank">' . $this->escape($item->category_title) . '</a>'); ?>
                        </div>
                    </td>
                    <td align="center">
                        <a href="mailto:<?php echo $item->owner_email; ?>"><?php echo $item->owner_name; ?></a>
                    </td>
                    <td align="center">
                        <?php echo JHtml::_('jgrid.published', $item->state, $i, '_bones.', true, 'cb'); ?>
                    </td>
                    <td align="center">
                        <?php echo $item->id; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>

        <input type="hidden" name="task" value="" />
        <input type="hidden" name="boxchecked" value="0" />
        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>
