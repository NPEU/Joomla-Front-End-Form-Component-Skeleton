<?php
/**
 * @package     Joomla.Site
 * @subpackage  com__frecom
 *
 * @copyright   Copyright (C) {{OWNER}} {{YEAR}}.
 * @license     MIT License; see LICENSE.md
 */

#use Joomla\CMS\Language\Text;
#use Joomla\CMS\Factory;
#use Joomla\CMS\Router\Route;
#use Joomla\CMS\Layout\LayoutHelper;
#use Joomla\CMS\Layout\FileLayout;
#use Joomla\CMS\Language\Multilanguage;
#use Joomla\CMS\Session\Session;
#use Joomla\Component\Fields\Administrator\Helper\FieldsHelper;

defined('_JEXEC') or die;
$page_title = $this->item->title;

$skip = array(
    'id',
    'title'
);
?>
<h1><?php echo $page_title ?></h1>

<?php foreach ($this->form->getFieldsets() as $name => $fieldset): ?>
<?php /*<h2><?php echo JText::_($fieldset->label); ?></h2>*/ ?>
<dl>
    <?php foreach ($this->form->getFieldset($name) as $field): if(!in_array($field->fieldname, $skip)): ?>
    <dt><?php echo JText::_($field->getAttribute('label')); ?></dt>
    <dd><?php echo $field->value; ?></dd>
    <?php endif; endforeach; ?>
</dl>
<?php endforeach; ?>
<p>
    <a href="<?php echo JRoute::_('index.php?option=com__bones&task=_bone.edit&id=' . $this->item->id); ?>">
        <?php echo JText::_('COM_BONES_RECORDS_ACTION_EDIT'); ?>
    </a>
</p>
<p>
    <a href="<?php echo JRoute::_('index.php?option=com__bones'); ?>">Back</a>
</p>
