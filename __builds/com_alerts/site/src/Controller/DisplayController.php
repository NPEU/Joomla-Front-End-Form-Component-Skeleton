<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_alerts
 *
 * @copyright   Copyright (C) NPEU 2023.
 * @license     MIT License; see LICENSE.md
 */

namespace NPEU\Component\Alerts\Site\Controller;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Factory;


/**
 * _Frecom Component Controller
 */
class DisplayController extends BaseController {

    public function display($cachable = false, $urlparams = array()) {
        $viewName = $this->input->get('view', '');
		$cachable = true;
		if ($viewName == 'form' || Factory::getApplication()->getIdentity()->get('id'))
		{
			$cachable = false;
		}

		$safeurlparams = array(
			'id'               => 'ARRAY',
			'view'             => 'CMD',
			'lang'             => 'CMD',
		);

		parent::display($cachable, $safeurlparams);

        return $this;
    }

}