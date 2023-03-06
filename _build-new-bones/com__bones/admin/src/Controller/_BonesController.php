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

use Joomla\CMS\MVC\Controller\AdminController;


class _BonessController extends AdminController
{

    public function getModel($name = '_Bone', $prefix = 'Administrator', $config = array('ignore_request' => true))
    {
        return parent::getModel($name, $prefix, $config);
    }
}
