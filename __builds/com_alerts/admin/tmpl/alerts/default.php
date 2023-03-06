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
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Uri\Uri;
use Joomla\Registry\Registry;
use Joomla\CMS\Language\Associations;


$user    = Factory::getApplication()->getIdentity();
$user_id = $user->get('id');
#$this->document->getWebAssetManager()->useScript('com_alerts.enable-tooltips');

$listOrder     = $this->escape($this->state->get('list.ordering'));
$listDirn      = $this->escape($this->state->get('list.direction'));

?>
<form action="<?php echo Route::_('index.php?option=com_alerts&view=alerts'); ?>" method="post" id="adminForm" name="adminForm">
    <div class="row">
		<div class="col-md-12">
			<div id="j-main-container" class="j-main-container">
				<?php
				// Search tools bar
				echo LayoutHelper::render('joomla.searchtools.default', ['view' => $this]);
				?>
				<?php if (empty($this->items)) : ?>
                <div class="alert alert-info">
                    <span class="icon-info-circle" aria-hidden="true"></span><span class="visually-hidden"><?php echo Text::_('INFO'); ?></span>
                    <?php echo Text::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
                </div>
				<?php else : ?>
                <table class="table" id="alertsList">
                    <caption class="visually-hidden">
                        <?php echo Text::_('COM_ALERTS_TABLE_CAPTION'); ?>,
                        <span id="orderedBy"><?php echo Text::_('JGLOBAL_SORTED_BY'); ?> </span>,
                        <span id="filteredBy"><?php echo Text::_('JGLOBAL_FILTERED_BY'); ?></span>
                    </caption>
                    <thead>
						<tr>
							<td class="w-1 text-center">
								<?php echo HTMLHelper::_('grid.checkall'); ?>
							</td>
                            <th class="w-1 text-center">
                                <?php echo Text::_('COM_ALERTS_NUM'); ?>
                            </th>
							<th scope="col" style="min-width:85px" class="w-1 text-center">
								<?php echo HTMLHelper::_('searchtools.sort', 'JSTATUS', 'a.state', $listDirn, $listOrder); ?>
							</th>
							<th scope="col">
								<?php echo HTMLHelper::_('searchtools.sort', 'COM_ALERTS_RECORDS_TITLE', 'a.title', $listDirn, $listOrder); ?>
							</th>
							<th scope="col" class="w-10 d-none d-md-table-cell">
								<?php echo HTMLHelper::_('searchtools.sort', 'COM_ALERTS_PUBLISHED', 'a.state', $listDirn, $listOrder); ?>
							</th>
							<th scope="col" class="w-5 d-none d-md-table-cell">
								<?php echo HTMLHelper::_('searchtools.sort', 'COM_ALERTS_ID', 'a.id', $listDirn, $listOrder); ?>
							</th>
						</tr>
                    </thead>
                    <tbody>
                        <?php foreach ($this->items as $i => $item) : ?>
                        <?php $canEdit        = $user->authorise('core.edit',       'com_alerts.' . $item->id); ?>
                        <?php $canCheckin     = $user->authorise('core.manage',     'com_checkin') || $item->checked_out == $user->id || !$item->checked_out; ?>
                        <?php $canEditOwn     = $user->authorise('core.edit.own',   'com_alerts.' . $item->id) && $item->created_by == $user->id; ?>
                        <?php $canChange      = $user->authorise('core.edit.state', 'com_alerts.' . $item->id) && $canCheckin; ?>

                        <tr class="row<?php echo $i % 2; ?>">
                            <td class="text-center">
                                <?php echo HTMLHelper::_('grid.id', $i, $item->id); ?>
                            </td>
                            <td>
                                <?php echo $this->pagination->getRowOffset($i); ?>
                            </td>
                            <th scope="row" class="has-context">
                                <div>
                                    <?php if ($item->checked_out) : ?>
                                    <?php echo HTMLHelper::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'weblinks.', $canCheckin); ?>
                                    <?php endif; ?>
                                    <?php if ($canEdit || $canEditOwn) : ?>
                                    <a href="<?php echo Route::_('index.php?option=com_alerts&task=comalert.edit&id=' . $item->id); ?>" title="<?php echo Text::_('JACTION_EDIT'); ?> <?php echo $this->escape($item->title); ?>">
                                        <?php echo $this->escape($item->title); ?>
                                    </a>
                                    <?php else : ?>
                                        <?php echo $this->escape($item->title); ?>
                                    <?php endif; ?>
                                    <span class="small">
                                        <?php echo Text::sprintf('JGLOBAL_LIST_ALIAS', $this->escape($item->alias)); ?>
                                    </span>
                                </div>
                            </th>
                            <td class="text-center">
                                <?php echo HTMLHelper::_('jgrid.published', $item->state, $i, 'alerts.', $canChange, 'cb'); ?>
                            </td>
                            <td class="d-none d-md-table-cell">
                                <?php echo $item->id; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php endif;?>

                <?php // Load the pagination. ?>
                <?php echo $this->pagination->getListFooter(); ?>

                <input type="hidden" name="task" value="">
				<input type="hidden" name="boxchecked" value="0">
				<?php echo HTMLHelper::_('form.token'); ?>
			</div>
		</div>
	</div>
</form>