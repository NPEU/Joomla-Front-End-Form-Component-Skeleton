<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_alerts
 *
 * @copyright   Copyright (C) NPEU 2023.
 * @license     MIT License; see LICENSE.md
 */

namespace NPEU\Component\Alerts\Administrator\Controller;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\BaseController;


/**
 * _Frecom Component Controller
 */
class DisplayController extends BaseController {
    protected $default_view = 'alerts';

    public function display($cachable = false, $urlparams = array()) {
        return parent::display($cachable, $urlparams);
    }
}