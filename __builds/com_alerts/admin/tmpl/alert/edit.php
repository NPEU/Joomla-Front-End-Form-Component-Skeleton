<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_alerts
 *
 * @copyright   Copyright (C) NPEU 2023.
 * @license     MIT License; see LICENSE.md
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Associations;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;

HTMLHelper::_('behavior.formvalidator');

$global_edit_fields = array(
    'id',
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

$app = Factory::getApplication();
$input = $app->input;

#$this->ignore_fieldsets = array('details', 'images', 'item_associations', 'jmetadata');
$this->useCoreUI = true;

$fieldsets = $this->form->getFieldsets();
?>

<form action="<?php echo Route::_('index.php?option=com_alerts&layout=edit&id=' . $this->item->id); ?>"
    method="post"
    name="adminForm"
    id="alert-form"
    class="form-validate">

    <?php echo LayoutHelper::render('joomla.edit.title_alias', $this); ?>

    <div class="main-card">
        <?php echo HTMLHelper::_('uitab.startTabSet', 'myTab', array('active' => 'main')); ?>

        <?php $i=0; foreach ($fieldsets as $fieldset): $i++; ?>
        <?php $form_fieldset = $this->form->getFieldset($fieldset->name); ?>

		<?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'details', $fieldset->label); ?>

        <div class="row">
            <?php if ($fieldset->name == 'main'): ?>
            <div class="col-md-9"><?php else: ?><div class="col-12"><?php endif; ?>
                <?php $hidden_fields = array(); foreach($form_fieldset as $field): if(!in_array($field->fieldname, $global_edit_fields)): ?>
                <?php if($field->type == 'Hidden'){$hidden_fields[] = $field->input; continue;} ?>
                <?php if(!empty($field->getAttribute('hiddenLabel'))){ echo $field->input; continue; } ?>

                <div class="control-group">
                    <?php if ($field->type != 'Button'): ?>
                    <div class="control-label">
                        <?php echo JText::_($field->label); ?>
                    </div>
                    <?php endif; ?>
                    <div class="controls">
                        <?php echo $field->input; ?>
                    </div>
                </div>

                <?php endif; endforeach; ?>

            <?php if ($fieldset->name == 'main'): ?>
            </div>
            <div class="col-md-3">
                <?php echo LayoutHelper::render('joomla.edit.global', $this); ?>
            <?php endif; ?>
            </div>

        </div>
        <?php echo HTMLHelper::_('uitab.endTab'); ?>
        <?php endforeach; ?>

        <?php echo HTMLHelper::_('uitab.endTabSet'); ?>
    </div>
    <?php echo implode("\n", $hidden_fields); ?>
    <input type="hidden" name="task" value="alert.edit" />
	<?php echo HTMLHelper::_('form.token'); ?>
</form>