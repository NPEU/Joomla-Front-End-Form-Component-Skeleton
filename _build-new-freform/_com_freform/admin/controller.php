<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com__freform
 *
 * @copyright   Copyright (C) {{OWNER}} {{YEAR}}.
 * @license     MIT License; see LICENSE.md
 */

defined('_JEXEC') or die;

/**
 * _Freform Component Controller
 */
class _FreformController extends JControllerLegacy
{
    /**
     * The default view for the display method.
     *
     * @var string
     */
    #protected $default_view = '_freform';
    
    /**
     * Constructor
     *
     * @param   array  $config  Optional configuration array
     *
     */
    public function __construct($config = array())
    {
        parent::__construct($config);
        // Submenu, delete if component has only one view:
        JLoader::register('MenusHelper', JPATH_ADMINISTRATOR . '/components/com_menus/helpers/menus.php');
        $this->addModelPath(JPATH_ADMINISTRATOR . '/components/com_menus/models');
    }
    
    /**
     * display task
     *
     * @return void
     */
    public function display($cachable = false, $urlparams = false)
    {
        // Get the document object.
        $document = JFactory::getDocument();

        // Set the default view name and format from the Request.
        $vName   = $this->input->get('view', '_freform');
        $vFormat = $document->getType();
        $lName   = $this->input->get('layout', 'default', 'string');

        // Get and render the view.
        if ($view = $this->getView($vName, $vFormat))
        {
            // Get the model for the view.
            $model = $this->getModel($vName);

            // Push the model into the view (as default).
            $view->setModel($model, true);
            $view->setLayout($lName);

            // Push document object into the view.
            $view->document = $document;
            
            // Add style
            _FreformHelper::addStyle();

            // Load the submenu. Delete if component has only one view:
			_FreformHelper::addSubmenu($vName);
            
			$view->display();
        }

        return $this;
    }
}
