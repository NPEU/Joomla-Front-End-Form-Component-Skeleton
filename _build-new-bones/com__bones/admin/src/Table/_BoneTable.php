<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com__bones
 *
 * @copyright   Copyright (C) {{OWNER}} {{YEAR}}.
 * @license     MIT License; see LICENSE.md
 */

namespace {{OWNER}}\Component\_Bones\Administrator\Table;

defined('_JEXEC') or die;

use Joomla\CMS\Table\Nested;
use Joomla\CMS\Table\Table;
use Joomla\Database\DatabaseDriver;
use Joomla\Registry\Registry;
use Joomla\CMS\Factory;
use Joomla\CMS\Access\Rules;
use Joomla\CMS\Filter\OutputFilter;
#use Joomla\CMS\Versioning\VersionableTableInterface;
#use Joomla\CMS\Tag\TaggableTableInterface;
#use Joomla\CMS\Tag\TaggableTableTrait;


/**
 * _Bone Table class.
 *
 * @since  1.0
 */
#class _BoneTable extends Nested implements VersionableTableInterface, TaggableTableInterface
class _BoneTable extends Table
{
    #use TaggableTableTrait;

    public function __construct(DatabaseDriver $db) {
        $this->typeAlias = 'com__bones._bone';

        parent::__construct('#___bones', 'id', $db);

        // In functions such as generateTitle() Joomla looks for the 'title' field ...
        #$this->setColumnAlias('title', 'greeting');
    }

    public function bind($array, $ignore = '') {
		/*if (isset($array['params']) && is_array($array['params'])) {
			// Convert the params field to a string.
			$parameter = new Registry;
			$parameter->loadArray($array['params']);
			$array['params'] = (string)$parameter;
		}*/

        // Bind the rules.
        if (isset($array['rules']) && \is_array($array['rules'])) {
            $rules = new Rules($array['rules']);
            $this->setRules($rules);
        }

        /*if (isset($array['parent_id'])) {
			if (!isset($array['id']) || $array['id'] == 0)
			{   // new record
				$this->setLocation($array['parent_id'], 'last-child');
			}
			elseif (isset($array['_bonesordering']))
			{
				// when saving a record load() is called before bind() so the table instance will have properties which are the existing field values
				if ($this->parent_id == $array['parent_id'])
				{
					// If first is chosen make the item the first child of the selected parent.
					if ($array['_bonesordering'] == -1)
					{
						$this->setLocation($array['parent_id'], 'first-child');
					}
					// If last is chosen make it the last child of the selected parent.
					elseif ($array['_bonesordering'] == -2)
					{
						$this->setLocation($array['parent_id'], 'last-child');
					}
					// Don't try to put an item after itself. All other ones put after the selected item.
					elseif ($array['_bonesordering'] && $this->id != $array['_bonesordering'])
					{
						$this->setLocation($array['_bonesordering'], 'after');
					}
					// Just leave it where it is if no change is made.
					elseif ($array['_bonesordering'] && $this->id == $array['_bonesordering'])
					{
						unset($array['_bonesordering']);
					}
				}
				// Set the new parent id if parent id not matched and put in last position
				else
				{
					$this->setLocation($array['parent_id'], 'last-child');
				}
			}*/
		}

		return parent::bind($array, $ignore);
	}

    public function store($updateNulls = true) {
        // add the 'created by' and 'created' date fields if it's a new record
        // and these fields aren't already set
        $date = date('Y-m-d h:i:s');
        $user_id = Factory::getApplication()->getIdentity()->get('id');
        if (!$this->id) {
            // new record
            if (empty($this->created_by)) {
                $this->created_by = $user_id;
                $this->created    = $date;
            }
        }

        return parent::store();
    }

    /**
	 * Method to compute the default name of the asset.
	 * The default name is in the form `table_name.id`
	 * where id is the value of the primary key of the table.
	 *
	 * @return	string
	 * @since	2.5
	 */
	protected function _getAssetName() {
		$k = $this->_tbl_key;
		return 'com__bones._bone.'.(int) $this->$k;
	}
	/**
	 * Method to return the title to use for the asset table.
	 *
	 * @return	string
	 * @since	2.5
	 */
	protected function _getAssetTitle() {
		return $this->title;
	}

    public function check() {
		$this->alias = trim($this->alias);
		if (empty($this->alias))
		{
			$this->alias = $this->greeting;
		}
		$this->alias = OutputFilter::stringURLSafe($this->alias);
		return true;
	}

    public function delete($pk = null, $children = false) {
		return parent::delete($pk, $children);
	}

    /**
     * typeAlias is the key used to find the content_types record
     * needed for creating the history record
     */
    public function getTypeAlias() {
        return $this->typeAlias;
    }
}
