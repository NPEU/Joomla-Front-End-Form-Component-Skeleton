<?php
/**
 * @package     Joomla.Site
 * @subpackage  com__freform
 *
 * @copyright   Copyright (C) NPEU 2018.
 * @license     MIT License; see LICENSE.md
 */

defined('_JEXEC') or die;

JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select', null, array('disable_search_threshold' => 0 ));

$global_edit_fields = array(
	'parent',
    'parent_id',
	'published',
    'state',
    'enabled',
	'category',
    'catid',
	'featured',
	'sticky',
	'access',
	'language',
	'tags',
	'note',
	'version_note'
);
?>
<form action="<?php echo JRoute::_('index.php?option=com__freform&layout=edit&id=' . (int) $this->item->id); ?>"
    method="post" name="adminForm" id="adminForm" class="form-validate">
	<div class="form-horizontal">
		<?php foreach ($this->form->getFieldsets() as $name => $fieldset): ?>
			<fieldset class="adminform">
				<legend><?php echo JText::_($fieldset->label); ?></legend>
				<div class="row-fluid">
					<div class="span6">
						<?php foreach ($this->form->getFieldset($name) as $field): if(!in_array($field->fieldname, $global_edit_fields)): ?>
							<div class="control-group">
								<div class="control-label"><?php echo $field->label; ?></div>
								<div class="controls"><?php echo $field->input; ?></div>
							</div>
						<?php endif; endforeach; ?>
					</div>
                    <div class="span3">
                        <?php echo JLayoutHelper::render('joomla.edit.global', $this); ?>
                    </div>
				</div>
			</fieldset>
		<?php endforeach; ?>
	</div>
	<input type="hidden" name="task" value="record.edit" />
	<?php echo JHtml::_('form.token'); ?>
</form>
