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
 * _Freform Records List Model
 */
class _FreformModelRecords extends JModelList
{
    /**
     * Constructor.
     *
     * @param   array  $config  An optional associative array of configuration settings.
     *
     * @see     JController
     */
    public function __construct($config = array())
    {
        if (empty($config['filter_fields']))
        {
            $config['filter_fields'] = array(
                'id',
                'users_name',
                'message',
                'state'
            );
        }

        parent::__construct($config);
    }

    /**
     * Method to build an SQL query to load the list data.
     *
     * @return      string  An SQL query
     */
    protected function getListQuery()
    {
        // Initialize variables.
        $db    = JFactory::getDbo();
        $query = $db->getQuery(true);

        // Create the base select statement.
        $query->select('a.*')
              ->from($db->quoteName('#___freform') . ' AS a');
              
        // Join the categories table again for the project group (delete if not using categories):
        $query->select('c.title AS category')
            ->join('LEFT', '#__categories AS c ON c.id = a.catid');
              
        // Join over the users for the checked out user.
        $query->select('uc.name AS editor')
            ->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');

        // Filter by a single or group of categories.
        $categoryId = $this->getState('filter.category_id');

        if (is_numeric($categoryId))
        {
            $query->where($db->quoteName('a.catid') . ' = ' . (int) $categoryId);
        }
        elseif (is_array($categoryId))
        {
            $query->where($db->quoteName('a.catid') . ' IN (' . implode(',', ArrayHelper::toInteger($categoryId)) . ')');
        }

        // Filter: like / search
        $search = $this->getState('filter.search');

        if (!empty($search))
        {
            $like = $db->quote('%' . $search . '%');
            $query->where('a.title LIKE ' . $like);
            $query->where('a.alias LIKE ' . $like);
        }

        // Filter by state
        $state = $this->getState('filter.published');

        if (is_numeric($state))
        {
            $query->where('a.state = ' . (int) $state);
        }
        elseif ($state === '')
        {
            $query->where('(a.state IN (0, 1))');
        }

        // Add the list ordering clause.
        $orderCol   = $this->state->get('list.ordering', 'a.title');
        $orderDirn  = $this->state->get('list.direction', 'asc');

        $query->order($db->escape($orderCol) . ' ' . $db->escape($orderDirn));

        return $query;
    }
}
