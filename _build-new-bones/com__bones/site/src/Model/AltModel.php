<?php
/**
 * @package     Joomla.Site
 * @subpackage  com__bones
 *
 * @copyright   Copyright (C) {{OWNER}} {{YEAR}}.
 * @license     MIT License; see LICENSE.md
 */

namespace {{OWNER}}\Component\_Bones\Site\Model;

defined('_JEXEC') or die;

#use {{OWNER}}\Component\_Bones\Site\Helper\_BoneHelper;
#use Joomla\CMS\MVC\Model\ItemModel;
use Joomla\CMS\Cache\CacheControllerFactoryInterface;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\Registry\Registry;

/**
 * Alt Component Model
 */
class AltModel extends \{{OWNER}}\Component\_Bones\Administrator\Model\_BonesModel {

    public function getTable($name = '', $prefix = '', $options = [])
    {
        return '_bones';
    }

}