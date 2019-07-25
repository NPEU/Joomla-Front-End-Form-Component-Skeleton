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
 * _Freform List Model
 */
class _FreformModel_Freform extends JModelList
{
    protected $published = 1;

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

        // Create the select statement.
        $query->select('*')
              ->from($db->quoteName('#___freform'));
              
        if (is_numeric($this->published))
        {
            $query->where('published = ' . (int) $this->published);
        }
        elseif ($this->published === '')
        {
            $query->where('(published IN (0, 1))');
        }

        #$query->order($db->escape($orderCol) . ' ' . $db->escape($orderDirn));

        return $query;
    }
    
    /**
     * Method to get an array of data items (published and unpublished).
     *
     * @return  mixed  An array of data items on success, false on failure.
     */
    public function getAllItems()
    {
        $this->published = '';
        return parent::getItems();
    }
    
    /**
     * Method to get an array of data items (unpublished only).
     *
     * @return  mixed  An array of data items on success, false on failure.
     */
    public function getUnpublishedItems()
    {
        $this->published = 0;
        return parent::getItems();
    }
}
