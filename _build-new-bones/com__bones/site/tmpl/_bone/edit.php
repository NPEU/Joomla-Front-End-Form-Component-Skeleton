<?php
/**
 * @package     Joomla.Site
 * @subpackage  com__bones
 *
 * @copyright   Copyright (C) {{OWNER}} {{YEAR}}.
 * @license     MIT License; see LICENSE.md
 */

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;

defined('_JEXEC') or die;

// These are for the Joomla way of doing things. You may not need these if you're doing things
// differently.
HTMLHelper::_('behavior.keepalive');
//HTMLHelper::_('behavior.formvalidator');

$form_id = '_bones_form';
$route   = Route::_(JUri::current());

$uri  = Uri::getInstance();
$item_id = '';
if (!empty($this->item) && !empty($this->item->id)) {
    $item_id = $this->item->id;
}

?>

<form action="<?php echo $uri; ?>" method="post" name="_bones_form" id="<?php echo $form_id; ?>" class="">
    <?php
    $fieldsets             = $this->form->getFieldsets();
    $inputs_fieldset       = $this->form->getFieldset('main');
    $inputs_fieldset_info  = $fieldsets['main'];
    $inputs_fieldset_class = isset($inputs_fieldset_info->class)
                        ? ' class="' . $inputs_fieldset_info->class . '"'
                        : '';
    $hidden_inputs = array();
    ?>
    <fieldset<?php echo $inputs_fieldset_class; ?>>
        <legend><?php echo Text::_($inputs_fieldset_info->label); ?></legend>
        <ol>
            <?php foreach($inputs_fieldset as $field): ?><?php if($field->type == 'Hidden'): ?>
            <?php $hidden_inputs[] = $field; ?>
            <?php elseif($field->type == 'Button'): ?>
            <li><?php echo $field->input;?></li>
            <?php elseif($field->type == 'Checkbox'): ?>
            <li><?php echo $field->input;echo Text::_($field->label); ?></li>
            <?php else: ?>
            <li><?php echo Text::_($field->label);echo $field->input; ?></li>
            <?php endif; ?><?php endforeach; ?>
        </ol>
        <?php foreach($hidden_inputs as $field): ?>
        <?php echo $field->input;?>
        <?php endforeach; ?>
        <?php echo HTMLHelper::_('form.token'); ?>
        <?php /* You may not need these if you're not using return value or Joomla data validation: */ ?>
        <?php /* <input type="hidden" name="layout" value="edit" />*/ ?>
        <input type="hidden" name="return" value="<?php echo $this->return_page;?>" />
        <input type="hidden" name="original_alias" value="<?php echo $this->item->alias;?>" />
        <input type="hidden" name="view" value="_bone" />
        <input type="hidden" name="id" value="<?php echo $item_id; ?>" />


    </fieldset>
    <?php
    $controls_fieldset = $this->form->getFieldset('controls');
    ?>
    <fieldset>
        <?php foreach($controls_fieldset as $field): ?><?php if($field->type == 'Button'): ?>
        <p><?php echo $field->input;?></p>
        <?php elseif($field->type == 'Checkbox'): ?>
        <p><?php echo $field->input;echo Text::_($field->label); ?></p>
        <?php else: ?>
        <p><?php echo Text::_($field->label);echo $field->input; ?></p>
        <?php endif; ?><?php endforeach; ?>
        <?php /*
        <button class="btn" type="submit"><?php echo Text::_('COM_BONES_SUBMIT_LABEL'); ?></button>
        <a class="btn" href="<?php echo Route::_('index.php?option=com__bones'); ?>"><?php echo Text::_('JCANCEL') ?></a>
        */ ?>
        <button type="submit" name="task" value="_bone.save"><strong><?php echo Text::_('COM_BONES_SUBMIT_LABEL'); ?></strong></button>
        <?php if (!empty($item_id)) : ?>
        <button type="submit" name="task" value="_bone.cancel"><?php echo Text::_('JCANCEL') ?></button>
        <?php else : ?>
        <a href="<?php echo Route::_('index.php?option=com__bones&task=_bone.abort'); ?>"><?php echo Text::_('JCANCEL') ?></a>
        <?php endif; ?>
    </fieldset>
</form>
