<?php
/**
 * @package     Joomla.Site
 * @subpackage  com__bones
 *
 * @copyright   Copyright (C) {{OWNER}} {{YEAR}}.
 * @license     MIT License; see LICENSE.md
 */

namespace {{OWNER}}\Component\_Bones\Site\Controller;

defined('_JEXEC') or die;


use Joomla\CMS\Factory;
use Joomla\CMS\User\UserFactoryInterface;
use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\User\User;
use Joomla\CMS\Session\Session;
use Joomla\CMS\Log\Log;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Filesystem\Path;
use Joomla\CMS\Helper\MediaHelper;
use Joomla\CMS\Component\ComponentHelper;


/**
 * _Frecom Component Controller
 *
 * Used to handle the http POST from the front-end form which allows
 * users to enter a new _bones message
 */
class _BoneController extends FormController
{
    protected $view_item;  // default view within JControllerForm for reload function

	public function __construct($config = array())
	{
		$input = Factory::getApplication()->input;
		$this->view_item = $input->get("view", "_bone", "string");
		parent::__construct($config);
	}

    public function cancel($key = null)
    {
        parent::cancel($key);

        // set up the redirect back to the same form
        $this->setRedirect(
            (string) Uri::getInstance(),
            Text::_('COM_BONES_ADD_CANCELLED')
		);
    }

    /*
     * Function handing the save for adding a new _bones record
     * Based on the save() function in the JControllerForm class
     */
    public function save($key = null, $urlVar = null)
    {

		// Get the application
		$app = $this->app;

		$context = "$this->option.edit.$this->context";

		// Get the data from POST
		$data = $this->input->post->get('jform', array(), 'array');

		// Save the data in the session.
		$app->setUserState($context . '.data', $data);
		$result = parent::save($key, $urlVar);

		// If ok, redirect to the return page.
		if ($result)
		{
			// Flush the data from the session
			$app->setUserState($context . '.data', null);
			$this->setRedirect($this->getReturnPage());
		}

		return $result;
		/*
		// Check for request forgeries.
		Session::checkToken();

		$app = Factory::getApplication();
		$input = $app->input;
		$model = $this->getModel('form');

		// Get the current URI to set in redirects. As we're handling a POST,
		// this URI comes from the <form action="..."> attribute in the layout file above
		$currentUri = (string)Uri::getInstance();
        $currentUser = $app->getIdentity();

		// Check that this user is allowed to add a new record
		if (!$currentUser->authorise( "core.create", "com__bones"))
		{
			$app->enqueueMessage(Text::_('JERROR_ALERTNOAUTHOR'), 'error');
			$app->setHeader('status', 403, true);

			return;
		}

		// get the data from the HTTP POST request
		$data  = $input->get('jform', array(), 'array');

		// set up context for saving form data
		$context = "$this->option.edit.$this->context";

		// Validate the posted data.
		// First we need to set up an instance of the form ...
		$form = $model->getForm($data, false);

		if (!$form)
		{
			$app->enqueueMessage($model->getError(), 'error');
			return false;
		}

		// ... and then we validate the data against it
		// The validate function called below results in the running of the validate="..." routines
		// specified against the fields in the form xml file, and also filters the data
		// according to the filter="..." specified in the same place (removing html tags by default in strings)
		$validData = $model->validate($form, $data);

		// Handle the case where there are validation errors
		if ($validData === false)
		{
			// Get the validation messages.
			$errors = $model->getErrors();

			// Display up to three validation messages to the user.
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++)
			{
				if ($errors[$i] instanceof \Exception)
				{
					$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
				}
				else
				{
					$app->enqueueMessage($errors[$i], 'warning');
				}
			}

			// Save the form data in the session.
			$app->setUserState($context . '.data', $data);

			// Redirect back to the same screen.
			$this->setRedirect($currentUri);

			return false;
		}

        // Handle the uploaded file - get it from the PHP $_FILES structure
		$fileinfo = $this->input->files->get('jform', array(), 'array');
		$file = $fileinfo['imageinfo']['image'];
		/* The $file variable above should contain an array of 5 elements as follows:
		 *   name: the name of the file (on the system from which it was uploaded), without directory info
		 *   type: should be something like image/jpeg
		 *   tmp_name: pathname of the file where PHP has stored the uploaded data
		 *   error: 0 if no error
		 *   size: size of the file in bytes
		 * /

		// Check if any files have been uploaded
		if ($file['error'] == 4)   // no file uploaded (see PHP file upload error conditions)
		{
			$validData['imageinfo'] = null;
		}
		else
		{
			if ($file['error'] > 0)
			{
				$app->enqueueMessage(Text::sprintf('COM_BONES_ERROR_FILEUPLOAD', $file['error']), 'warning');
				return false;
			}

			// make sure filename is clean
			//jimport('joomla.filesystem.file');
			$file['name'] = File::makeSafe($file['name']);
			if (!isset($file['name']))
			{
				// No filename (after the name was cleaned by File::makeSafe)
				$app->enqueueMessage(Text::_('COM_BONES_ERROR_BADFILENAME'), 'warning');
				return false;
			}

			// files from Microsoft Windows can have spaces in the filenames
			$file['name'] = str_replace(' ', '-', $file['name']);

			// do checks against Media configuration parameters
			$mediaHelper = new MediaHelper;
			if (!$mediaHelper->canUpload($file))
			{
				// The file can't be uploaded - the helper class will have enqueued the error message
				return false;
			}

			// prepare the uploaded file's destination pathnames
			$mediaparams = ComponentHelper::getParams('com_media');
			$relativePathname = Path::clean($mediaparams->get('image_path', 'images') . '/' . $file['name']);
            //$relativePathname = $mediaparams->get('image_path', 'images') . '/' . $file['name'];
			$absolutePathname = JPATH_ROOT . '/' . $relativePathname;
			if (File::exists($absolutePathname))
			{
				// A file with this name already exists
				$app->enqueueMessage(Text::_('COM_BONES_ERROR_FILE_EXISTS'), 'warning');
				return false;
			}

			// check file contents are clean, and copy it to destination pathname
			if (!File::upload($file['tmp_name'], $absolutePathname))
			{
				// Error in upload
				$app->enqueueMessage(Text::_('COM_BONES_ERROR_UNABLE_TO_UPLOAD_FILE'));
				return false;
			}

			// Upload succeeded, so update the relative filename for storing in database
			$validData['imageinfo']['image'] = $relativePathname;
		}

		// Attempt to save the data.
		if (!$model->save($validData))
		{
		// Handle the case where the save failed

			// Save the data in the session.
			$app->setUserState($context . '.data', $validData);

			// Redirect back to the edit screen.
			$this->setError(Text::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $model->getError()));
			$this->setMessage($this->getError(), 'error');

			$this->setRedirect($currentUri);

			return false;
		}

		// clear the data in the form
		$app->setUserState($context . '.data', null);

		// notify the administrator that a new _bones message has been added on the front end

		// get the id of the person to notify from global config
		$params   = $app->getParams();
		$userid_to_email = (int) $params->get('user_to_email');

        if (isset($userid_to_email) && ($userid_to_email > 0)) {
            $user_to_email = Factory::getContainer()->get(UserFactoryInterface::class)->loadUserById($userid_to_email);
            $to_address = $user_to_email->get("email");

            if ($currentUser->get("id") > 0)
            {
                $current_username = $currentUser->get("username");
            }
            else
            {
                $current_username = "a visitor to the site";
            }

            // get the Mailer object, set up the email to be sent, and send it
            $mailer = Factory::getMailer();
            $mailer->addRecipient($to_address);
            $mailer->setSubject("New _bones message added by " . $current_username);
            $mailer->setBody("New greeting is " . $validData['greeting']);
            try
            {
                $mailer->send();
            }
            catch (\Exception $e)
            {
                Log::add('Caught exception: ' . $e->getMessage(), Log::ERROR, 'jerror');
            }
        }

		$this->setRedirect(
				$currentUri,
				Text::_('COM_BONES_ADD_SUCCESSFUL')
				);

		return true;
        */
    }

    public function getModel($name = '', $prefix = '', $config = array('ignore_request' => true))
	{
		if (empty($name))
		{
			$input = Factory::getApplication()->input;
			$modelname = $input->get("modelname", "_bones", "string");
			return parent::getModel($modelname, $prefix, $config);
		}

		return parent::getModel($name, $prefix, $config);
	}

}