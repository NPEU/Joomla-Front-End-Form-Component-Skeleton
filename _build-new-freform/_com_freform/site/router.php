<?php
/**
 * @package     Joomla.Site
 * @subpackage  com__freform
 *
 * @copyright   Copyright (C) {{OWNER}} {{YEAR}}.
 * @license     MIT License; see LICENSE.md
 */

defined('_JEXEC') or die;

/**
 * _Freform Router
 */
class _FreformRouter extends JComponentRouterBase
{
    /**
     * Build the route for the com__freform component
     *
     * @param   array  &$query  An array of URL arguments
     *
     * @return  array  The URL arguments to use to assemble the subsequent URL.
     */
    public function build(&$query)
    {
        #echo '<pre>'; var_dump($query); echo '</pre>'; exit;
        $segments = array();

        // I need to understand a lot more about Joomla's routing here, but this works for now:

        /*if (isset($query['return'])) {
            unset ($query['return']);
        }*/

        // Fix the route for the breadcrumb:
        if (
            !empty($query['view']) && $query['view'] == 'records'
         && !empty($query['Itemid'])
        ) {
            unset ($query['view']);
            return $segments;
        }

        if (
            isset($query['view']) && $query['view'] == 'record'
         && isset($query['layout']) && $query['layout'] == 'form'
         && isset($query['id']) && $query['id'] > 0
        ) {
            $segments[] = 'edit';
            $segments[] = $query['id'];

            unset ($query['view']);
            unset ($query['layout']);
            unset ($query['id']);

            return $segments;
        }

        if (isset($query['task']) && $query['task'] == 'record.add') {
            $segments[] = 'add';
            unset ($query['task']);
        }

        if (isset($query['task']) && isset($query['id']) && $query['task'] == 'record.edit') {
            $segments[] = 'edit';
            $segments[] = $query['id'];
            unset ($query['task']);
            unset ($query['id']);
        }

        if (isset($query['task']) && isset($query['id']) && $query['task'] == 'record.view') {
            $segments[] = 'view';
            $segments[] = $query['id'];
            unset ($query['task']);
            unset ($query['id']);
        }

        if (isset($query['view'])) {
            $segments[] = $query['view'];
            #unset ($query['view']);
        }

        return $segments;

        /*
        // Declare static variables.
        static $items;
        static $default;
        static $registration;
        static $profile;
        static $login;
        static $remind;
        static $resend;
        static $reset;

        $segments = array();

        // Get the relevant menu items if not loaded.
        if (empty($items))
        {
            // Get all relevant menu items.
            $items = $this->menu->getItems('component', 'com_users');

            // Build an array of serialized query strings to menu item id mappings.
            for ($i = 0, $n = count($items); $i < $n; $i++)
            {
                // Check to see if we have found the resend menu item.
                if (empty($resend) && !empty($items[$i]->query['view']) && ($items[$i]->query['view'] == 'resend'))
                {
                    $resend = $items[$i]->id;
                }

                // Check to see if we have found the reset menu item.
                if (empty($reset) && !empty($items[$i]->query['view']) && ($items[$i]->query['view'] == 'reset'))
                {
                    $reset = $items[$i]->id;
                }

                // Check to see if we have found the remind menu item.
                if (empty($remind) && !empty($items[$i]->query['view']) && ($items[$i]->query['view'] == 'remind'))
                {
                    $remind = $items[$i]->id;
                }

                // Check to see if we have found the login menu item.
                if (empty($login) && !empty($items[$i]->query['view']) && ($items[$i]->query['view'] == 'login'))
                {
                    $login = $items[$i]->id;
                }

                // Check to see if we have found the registration menu item.
                if (empty($registration) && !empty($items[$i]->query['view']) && ($items[$i]->query['view'] == 'registration'))
                {
                    $registration = $items[$i]->id;
                }

                // Check to see if we have found the profile menu item.
                if (empty($profile) && !empty($items[$i]->query['view']) && ($items[$i]->query['view'] == 'profile'))
                {
                    $profile = $items[$i]->id;
                }
            }

            // Set the default menu item to use for com_users if possible.
            if ($profile)
            {
                $default = $profile;
            }
            elseif ($registration)
            {
                $default = $registration;
            }
            elseif ($login)
            {
                $default = $login;
            }
        }

        if (!empty($query['view']))
        {
            switch ($query['view'])
            {
                case 'reset':
                    if ($query['Itemid'] = $reset)
                    {
                        unset ($query['view']);
                    }
                    else
                    {
                        $query['Itemid'] = $default;
                    }
                    break;

                case 'resend':
                    if ($query['Itemid'] = $resend)
                    {
                        unset ($query['view']);
                    }
                    else
                    {
                        $query['Itemid'] = $default;
                    }
                    break;

                case 'remind':
                    if ($query['Itemid'] = $remind)
                    {
                        unset ($query['view']);
                    }
                    else
                    {
                        $query['Itemid'] = $default;
                    }
                    break;

                case 'login':
                    if ($query['Itemid'] = $login)
                    {
                        unset ($query['view']);
                    }
                    else
                    {
                        $query['Itemid'] = $default;
                    }
                    break;

                case 'registration':
                    if ($query['Itemid'] = $registration)
                    {
                        unset ($query['view']);
                    }
                    else
                    {
                        $query['Itemid'] = $default;
                    }
                    break;

                default:
                case 'profile':
                    if (!empty($query['view']))
                    {
                        $segments[] = $query['view'];
                    }

                    unset ($query['view']);

                    if ($query['Itemid'] = $profile)
                    {
                        unset ($query['view']);
                    }
                    else
                    {
                        $query['Itemid'] = $default;
                    }

                    // Only append the user id if not "me".
                    $user = JFactory::getUser();

                    if (!empty($query['user_id']) && ($query['user_id'] != $user->id))
                    {
                        $segments[] = $query['user_id'];
                    }

                    unset ($query['user_id']);

                    break;
            }
        }

        $total = count($segments);

        for ($i = 0; $i < $total; $i++)
        {
            $segments[$i] = str_replace(':', '-', $segments[$i]);
        }

        return $segments;*/
    }

    /**
     * Parse the segments of a URL.
     *
     * @param   array  &$segments  The segments of the URL to parse.
     *
     * @return  array  The URL attributes to be used by the application.
     *
     * @since   3.3
     */
    public function parse(&$segments)
    {

        #$session = JFactory::getSession();
        #echo '<pre>'; var_dump($session); echo '</pre>'; exit;
        #echo '<pre>'; var_dump($segments); echo '</pre>'; #exit;
        $app   = JFactory::getApplication();
        $state_ids = $app->getUserState('com__freform.edit.record.id');
        #echo '<pre>'; var_dump($state); echo '</pre>'; exit;

        if (empty($segments)) {
            return;
        }
        $vars = array();

        if ($segments[0] == 'add') {

            $vars['view']   = 'record';
            $vars['layout'] = 'form';

            return $vars;
        }

        if ($segments[0] == 'alt') {

            $vars['view']   = 'records';
            $vars['layout'] = 'alt';

            return $vars;


            /* Ref:
                if (!(isset($query['id'] && $this->record_exists($query['id']))) {
                    JError::raiseError(404, JText::_('JGLOBAL_RESOURCE_NOT_FOUND'));
                }
            */
        }

        if ($segments[0] == 'view') {
            if (!isset($segments[1])) {
                // ID not supplied:
                JError::raiseError(404, JText::_('JGLOBAL_RESOURCE_NOT_FOUND'));
                return $vars;
            }

            if (!$this->record_exists($segments[1])) {
                // Invalid ID:
                JError::raiseError(404, JText::_('JGLOBAL_RESOURCE_NOT_FOUND'));
                return $vars;
            }

            $vars['view'] = 'record';
            $vars['id']   = $segments[1];
            //$vars['layout'] = 'form';

            return $vars;
        }

        if ($segments[0] == 'edit') {
            if (!isset($segments[1])) {
                // ID not supplied:
                JError::raiseError(404, JText::_('JGLOBAL_RESOURCE_NOT_FOUND'));
                return $vars;
            }

            if (!$this->record_exists($segments[1])) {
                // Invalid ID:
                JError::raiseError(404, JText::_('JGLOBAL_RESOURCE_NOT_FOUND'));
                return $vars;
            }

            if ($state_ids == null || (is_array($state_ids) && !in_array($segments[1], $state_ids))) {
                $vars['task'] = 'record.edit';
            }

            // 'edit' task will invoke 'edit' controller method which in turn calls the 'check-out'
            // model method. It will then redirect.
            // The relevent part of build route above will reconstruct the URL

            if (isset($segments[2]) && $segments[2] == 'save') {
                $vars['task'] = 'record.save';
            }

            $vars['view']   = 'record';
            $vars['layout'] = 'form';
            $vars['id']     = $segments[1];

            return $vars;
        }

        return $vars;

        /*$vars['alias'] = str_replace(':', '-', $segments[0]);
        $person_id     = preg_replace('#.*-(\d+)$#', '$1', $vars['alias']);

        $db = JFactory::getDBO();
        $query = 'SELECT id FROM #__users WHERE id = '. (int) $person_id . ' AND block = 0';
        $db->setQuery($query);
        $result = $db->loadObject();
        if (!$result) {
            JError::raiseError(404, JText::_("Page Not Found"));
        }

        $vars['id']   = $person_id;
        $vars['view'] = 'person';
        #echo "<pre>\n"; var_dump($vars); echo "</pre>\n"; exit;
        return $vars;*/

        /*
        $total = count($segments);
        $vars = array();

        for ($i = 0; $i < $total; $i++)
        {
            $segments[$i] = preg_replace('/-/', ':', $segments[$i], 1);
        }

        // Only run routine if there are segments to parse.
        if (count($segments) < 1)
        {
            return;
        }

        // Get the package from the route segments.
        $userId = array_pop($segments);

        if (!is_numeric($userId))
        {
            $vars['view'] = 'profile';

            return $vars;
        }

        if (is_numeric($userId))
        {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true)
                ->select($db->quoteName('id'))
                ->from($db->quoteName('#__users'))
                ->where($db->quoteName('id') . ' = ' . (int) $userId);
            $db->setQuery($query);
            $userId = $db->loadResult();
        }

        // Set the package id if present.
        if ($userId)
        {
            // Set the package id.
            $vars['user_id'] = (int) $userId;

            // Set the view to package if not already set.
            if (empty($vars['view']))
            {
                $vars['view'] = 'profile';
            }
        }
        else
        {
            JError::raiseError(404, JText::_('JGLOBAL_RESOURCE_NOT_FOUND'));
        }

        return $vars;
        */
    }

    /**
     * Method to check a record exists.
     *
     * @param   int     $id   The record ID
     *
     * @return  bool    Record does/does not exist.
     */
    protected function record_exists($id)
    {
        if (is_numeric($id)) {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true)
                ->select($db->quoteName('id'))
                ->from($db->quoteName('#___freform'))
                ->where($db->quoteName('id') . ' = ' . (int) $id);
            $db->setQuery($query);

            $record_id = $db->loadResult();
            return (bool) $record_id;
        }
        return false;
    }
}

/**
 * _Freform router functions
 *
 * These functions are proxys for the new router interface
 * for old SEF extensions.
 *
 * @param   array  &$query  REQUEST query
 *
 * @return  array  Segments of the SEF url
 *
 * @deprecated  4.0  Use Class based routers instead
 */
function _freformBuildRoute(&$query)
{
    $router = new _FreformRouter;

    return $router->build($query);
}

/**
 * Convert SEF URL segments into query variables
 *
 * @param   array  $segments  Segments in the current URL
 *
 * @return  array  Query variables
 *
 * @deprecated  4.0  Use Class based routers instead
 */
function _freformParseRoute($segments)
{
    $router = new _FreformRouter;

    return $router->parse($segments);
}
