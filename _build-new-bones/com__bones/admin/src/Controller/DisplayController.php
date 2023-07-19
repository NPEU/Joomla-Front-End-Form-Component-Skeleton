<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com__bones
 *
 * @copyright   Copyright (C) {{OWNER}} {{YEAR}}.
 * @license     MIT License; see LICENSE.md
 */

namespace {{OWNER}}\Component\_Bones\Administrator\Controller;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\BaseController;


/**
 * _Bones Component Controller
 */
class DisplayController extends BaseController {
    protected $default_view = '_bones';

    public function display($cachable = false, $urlparams = []) {
        return parent::display($cachable, $urlparams);
    }
}