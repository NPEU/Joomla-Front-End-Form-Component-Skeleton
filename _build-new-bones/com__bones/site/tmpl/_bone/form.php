<?php
/**
 * @package     Joomla.Site
 * @subpackage  com__bones
 *
 * @copyright   Copyright (C) {{OWNER}} {{YEAR}}.
 * @license     MIT License; see LICENSE.md
 */


use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;

defined('_JEXEC') or die;

// These are for the Joomla way of doing things. You may not need these if you're doing things
// differently.
HTMLHelper::_('behavior.keepalive');
HTMLHelper::_('behavior.formvalidator');

$form_id = '_bones_form';
$route   = JRoute::_(JUri::current());
?>

<form action="<?php echo $route; ?>" method="post" name="_bones_form" id="<?php echo $form_id; ?>" class="">
    <?php
    $fieldsets             = $this->form->getFieldsets();
    $inputs_fieldset       = $this->form->getFieldset('inputs');
    $inputs_fieldset_info  = $fieldsets['inputs'];
    $inputs_fieldset_class = isset($inputs_fieldset_info->class)
                        ? ' class="' . $inputs_fieldset_info->class . '"'
                        : '';
    $hidden_inputs = array();
    ?>
    <fieldset<?php echo $inputs_fieldset_class; ?>>
        <legend><?php echo JText::_($inputs_fieldset_info->label); ?></legend>
        <ol class="form-fields">
            <?php foreach($inputs_fieldset as $field): ?><?php if($field->type == 'Hidden'): ?>
            <?php $hidden_inputs[] = $field; ?>
            <?php elseif($field->type == 'Button'): ?>
            <li><?php echo $field->input;?></li>
            <?php elseif($field->type == 'Checkbox'): ?>
            <li class="three-quarters push--one-quarter"><?php echo $field->input;echo JText::_($field->label); ?></li>
            <?php else: ?>
            <li class="inline-fields"><?php echo JText::_($field->label);echo $field->input; ?></li>
            <?php endif; ?><?php endforeach; ?>
        </ol>
        <?php foreach($hidden_inputs as $field): ?>
        <?php echo $field->input;?>
        <?php endforeach; ?>
        <?php echo JHtml::_('form.token'); ?>
        <?php /* You may not need these if you're not using return value or Joomla data validation: */ ?>
        <input type="hidden" name="return" value="<?php echo $this->return_page;?>" />
        <input type="hidden" name="task" value="" />

    </fieldset>
    <?php
    $controls_fieldset = $this->form->getFieldset('controls');
    ?>
    <fieldset class="three-quarters push--one-quarter">
        <?php foreach($controls_fieldset as $field): ?><?php if($field->type == 'Button'): ?>
        <p><?php echo $field->input;?></p>
        <?php elseif($field->type == 'Checkbox'): ?>
        <p><?php echo $field->input;echo JText::_($field->label); ?></p>
        <?php else: ?>
        <p><?php echo JText::_($field->label);echo $field->input; ?></p>
        <?php endif; ?><?php endforeach; ?>
        <?php /*
        <button class="btn" type="submit"><?php echo JText::_('COM_BONES_SUBMIT_LABEL'); ?></button>
        <a class="btn" href="<?php echo JRoute::_('index.php?option=com__bones'); ?>"><?php echo JText::_('JCANCEL') ?></a>
        */ ?>
        <button class="btn" type="submit" onclick="return Joomla.submitbutton('_bone.save')"><?php echo JText::_('COM_BONES_SUBMIT_LABEL'); ?></button>
        <a class="btn" href="<?php echo JRoute::_('index.php?option=com__bones'); ?>" onclick="return Joomla.submitbutton('_bone.cancel')"><?php echo JText::_('JCANCEL') ?></a>
    </fieldset>
</form>
