<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_alerts
 *
 * @copyright   Copyright (C) NPEU 2023.
 * @license     MIT License; see LICENSE.md
 */

namespace NPEU\Component\Alerts\Site\Model;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\ItemModel;
use Joomla\CMS\Language\Text;
use Joomla\Registry\Registry;
#use NPEU\Component\Alert\Site\Helper\AlertHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\Cache\CacheControllerFactoryInterface;

/**
 * Alert Component Model
 */
class AlertModel extends ItemModel {

    /**
	 * @var object item
	 */
	protected $item;

	protected $published = 1;

	/**
	 * Method to auto-populate the model state.
	 *
	 * This method should only be called once per instantiation and is designed
	 * to be called on the first call to the getState() method unless the model
	 * configuration flag to ignore the request is set.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return	void
	 * @since	2.5
	 */
	protected function populateState()
	{
		// Get the message id
		$jinput = Factory::getApplication()->input;
		$id     = $jinput->get('id', 1, 'INT');
		$this->setState('message.id', $id);

		// Load the parameters.
		$this->setState('params', Factory::getApplication()->getParams());
		parent::populateState();

		/*
		$app = JFactory::getApplication();

        // Load state from the request.
        $pk = $app->input->getInt('id');
        $this->setState('alert.id', $pk);

        // Add compatibility variable for default naming conventions.
        $this->setState('form.id', $pk);

        $return = $app->input->get('return', null, 'base64');

        if (!JUri::isInternal(base64_decode($return)))
        {
            $return = null;
        }

        $this->setState('return_page', base64_decode($return));

        // Load the parameters.
        $params = $app->getParams();
        $this->setState('params', $params);

        $this->setState('layout', $app->input->getString('layout'));
		*/
	}

    /**
	 * Get the message
	 * @return object The message to be displayed to the user
	 */
	public function getItem($pk = NULL)
	{
		if (!isset($this->item) || !is_null($pk))
		{
			$id    = $pk ?: $this->getState('message.id');
			$db    = $this->getDatabase();
			$query = $db->getQuery(true);

			$query->select('*')
				->from($db->quoteName('#__alerts'))
				->where('id=' . (int) $id);

			if (is_numeric($this->published)) {
				$query->where('published = ' . (int) $this->published);
			} elseif ($this->published === '') {
				$query->where('(published IN (0, 1))');
			}

            /*if (Multilanguage::isEnabled())
			{
				$lang = Factory::getLanguage()->getTag();
				$query->where('h.language IN ("*","' . $lang . '")');
			}*/


            $db->setQuery((string) $query);

			if ($this->item = $db->loadObject())
			{
				// Load the JSON string
				$params = new Registry;
				$params->loadString($this->item->params, 'JSON');
				$this->item->params = $params;

				// Merge global params with item params
				$params = clone $this->getState('params');
				$params->merge($this->item->params);
				$this->item->params = $params;

                // Convert the JSON-encoded image info into an array
				/*$image = new Registry;
				$image->loadString($this->item->image, 'JSON');
				$this->item->imageDetails = $image;*/

                // Check if the user can access this record:
				$user = Factory::getApplication()->getIdentity();
				$userAccessLevels = $user->getAuthorisedViewLevels();
				if ($user->authorise('core.admin')) // ie superuser
				{
					$this->item->canAccess = true;
				}
				/*else
				{
					if ($this->item->catid == 0)
					{
						$this->item->canAccess = in_array($this->item->access, $userAccessLevels);
					}
					else
					{
						$this->item->canAccess = in_array($this->item->access, $userAccessLevels) && in_array($this->item->catAccess, $userAccessLevels);
					}
				}*/
			}
		}
		return $this->item;
	}
}