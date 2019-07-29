<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com__bones
 *
 * @copyright   Copyright (C) {{OWNER}} {{YEAR}}.
 * @license     MIT License; see LICENSE.md
 */

defined('_JEXEC') or die;

/**
 * _Bones _Bone Controller
 */
class _BonesController_Bone extends JControllerForm
{
    /**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @see     \JControllerLegacy
	 * @throws  \Exception
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);
        $this->view_list = '_bones';
    }
}
