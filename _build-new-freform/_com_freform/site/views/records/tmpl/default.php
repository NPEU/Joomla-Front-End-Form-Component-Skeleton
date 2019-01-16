<?php
/**
 * @package     Joomla.Site
 * @subpackage  com__freform
 *
 * @copyright   Copyright (C) {{OWNER}} {{YEAR}}.
 * @license     MIT License; see LICENSE.md
 */

defined('_JEXEC') or die;

$table_id = 'recordsTable';
// If you need specific JS/CSS for this view, add them here.
// Example included for DataTables (https://datatables.net/) delete if you don't want this.
// Make sure jQuery is loaded first:
JHtml::_('jquery.framework');
JHtml::_('bootstrap.framework');
// Get the doc object:
$doc = JFactory::getDocument();
// Add a script tag with a src:
$doc->addScript("//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js");
#$doc->addScript("//cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js");
// Add a CSS link tag:
$doc->addStyleSheet('//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css');
#$doc->addStyleSheet('//cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css');
// Add a script tag with content:
$js = '
jQuery(document).ready(function(){
    jQuery("#' . $table_id . '").DataTable();
});
';
$doc->addScriptDeclaration($js);

?>
<p>
<a href="<?php echo JRoute::_('index.php?option=com__freform&task=record.add'); ?>">Add new</a>
</p>
<table class="table table-striped table-hover" id="<?php echo $table_id; ?>">
    <thead>
        <tr>
            <?php /*<th width="2%"><?php echo JText::_('COM_FREFORM_NUM'); ?></th>
            <th width="4%">
                <?php echo JHtml::_('grid.checkall'); ?>
            </th>*/ ?>
            <th width="50%">
                <?php echo JText::_('COM_FREFORM_RECORDS_TITLE'); ?>
            </th>
            <th width="40%">
                <?php echo JText::_('COM_FREFORM_RECORDS_SLUG'); ?>
            </th>
            <th width="10%">
                <?php echo JText::_('COM_FREFORM_RECORDS_ACTIONS'); ?>
            </th>
            <?php /*<th width="10%">
                <?php echo JHtml::_('grid.sort', 'COM_FREFORM_PUBLISHED', 'published', $listDirn, $listOrder); ?>
            </th>
            <th width="4%">
                <?php echo JHtml::_('grid.sort', 'COM_FREFORM_ID', 'id', $listDirn, $listOrder); ?>
            </th>*/ ?>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($this->items)) : ?>
            <?php foreach ($this->items as $i => $row) :
                $view_link = JRoute::_('index.php?option=com__freform&task=record.view&id=' . $row->id);
                $edit_link = JRoute::_('index.php?option=com__freform&task=record.edit&id=' . $row->id);
                $is_own = false;
                if ($this->user->authorise('core.edit.own', 'com__freform') && ($this->user->id == $row->created_by)) {
                    $is_own = true;
                }
                $authorised = $this->user->authorise('core.edit', 'com__freform');
            ?>
                <tr>
                    <?php /*<td><?php echo $this->pagination->getRowOffset($i); ?></td>
                    <td>
                        <?php echo JHtml::_('grid.id', $i, $row->id); ?>
                    </td>*/ ?>
                    <td>
                        <a href="<?php echo $view_link; ?>" title="<?php echo JText::_('COM_FREFORM_EDIT_RECORD'); ?>">
                            <?php echo $row->title; ?>
                        </a>
                    </td>
                    <td>
                        <?php echo $row->slug; ?>
                    </td>
                    <td>
                        <?php if($is_own || $authorised): ?>
                        <a href="<?php echo $edit_link; ?>">
                            <?php echo JText::_('COM_FREFORM_RECORDS_ACTION_EDIT'); ?>
                        </a>
                        <?php else: ?>
                        -
                        <?php endif; ?>
                    </td>
                    <?php /*<td align="center">
                        <?php echo JHtml::_('jgrid.published', $row->published, $i, 'records.', true, 'cb'); ?>
                    </td>
                    <td align="center">
                        <?php echo $row->id; ?>
                    </td>*/ ?>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<p>
    <a href="<?php $altview = 'alt'; echo JRoute::_('index.php?option=com__freform&view=' . $altview); ?>">Sample alternative view</a>
</p>
<?php /* You can include the form  here if you want like this: 
include JPATH_SITE . '/components/com__freform/views/record/tmpl/form.php';
*/?>