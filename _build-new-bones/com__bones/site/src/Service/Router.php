<?php
/**
 * @package     Joomla.Site
 * @subpackage  com__bones
 *
 * @copyright   Copyright (C) {{OWNER}} {{YEAR}}.
 * @license     MIT License; see LICENSE.md
 */

namespace {{OWNER}}\Component\_Bones\Site\Service;

defined('_JEXEC') or die;

use Joomla\CMS\Application\SiteApplication;
use Joomla\CMS\Categories\CategoryFactoryInterface;
use Joomla\CMS\Categories\CategoryInterface;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Component\Router\RouterView;
use Joomla\CMS\Component\Router\RouterViewConfiguration;
use Joomla\CMS\Component\Router\Rules\MenuRules;
use Joomla\CMS\Component\Router\Rules\NomenuRules;
use Joomla\CMS\Component\Router\Rules\StandardRules;
use Joomla\CMS\Menu\AbstractMenu;
use Joomla\Database\DatabaseInterface;
use Joomla\Database\ParameterType;


class Router extends RouterView
{

    private $categoryFactory;

    private $categoryCache = [];

    private $db;

    /**
     * Component router constructor
     *
     * @param   SiteApplication           $app              The application object
     * @param   AbstractMenu              $menu             The menu object to work with
     * @param   CategoryFactoryInterface  $categoryFactory  The category object
     * @param   DatabaseInterface         $db               The database object
     */
    public function __construct(SiteApplication $app, AbstractMenu $menu, CategoryFactoryInterface $categoryFactory, DatabaseInterface $db)
    {
        $this->categoryFactory = $categoryFactory;
        $this->db              = $db;

        #$category = new RouterViewConfiguration('category');
        #$category->setKey('id')->setNestable();
        #$this->registerView($category);

        $_bones = new RouterViewConfiguration('_bones');
        #$_bones->setKey('id')->setParent($category, 'catid');
        $this->registerView($_bones);

        $form = new RouterViewConfiguration('form');
        $this->registerView($form);

        parent::__construct($app, $menu);

        $this->attachRule(new MenuRules($this));
        $this->attachRule(new StandardRules($this));
        $this->attachRule(new NomenuRules($this));
    }

    /**
     * Method to get the id for a _bones item from the segment
     *
     * @param   string  $segment  Segment of the _bones to retrieve the ID for
     * @param   array   $query    The request that is parsed right now
     *
     * @return  mixed   The id of this item or false
     */
    public function get_BonesId($segment, $query)
    {
        return (int) $segment;
    }

    /**
     * Method to get the segment(s) for a _bones item
     *
     * @param   string  $id     ID of the _bones to retrieve the segments for
     * @param   array   $query  The request that is built right now
     *
     * @return  array|string  The segments of this item
     */
    public function get_BonesSegment($id, $query)
    {
        if (!strpos($id, ':')) {
            $id      = (int) $id;
            $dbquery = $this->db->getQuery(true);
            $dbquery->select($this->db->quoteName('alias'))
                ->from($this->db->quoteName('#___bones'))
                ->where($this->db->quoteName('id') . ' = :id')
                ->bind(':id', $id, ParameterType::INTEGER);
            $this->db->setQuery($dbquery);

            $id .= ':' . $this->db->loadResult();
        }

        return array((int) $id => $id);
    }

    /**
     * Method to get the id for a category
     *
     * @param   string  $segment  Segment to retrieve the ID for
     * @param   array   $query    The request that is parsed right now
     *
     * @return  mixed   The id of this item or false
     */
    /*public function getCategoryId($segment, $query)
    {
        if (isset($query['id'])) {
            $category = $this->getCategories(['access' => false])->get($query['id']);

            if ($category) {
                foreach ($category->getChildren() as $child) {
                    if ($child->id == (int) $segment) {
                        return $child->id;
                    }
                }
            }
        }

        return false;
    }*/

    /**
     * Method to get the segment(s) for a category
     *
     * @param   string  $id     ID of the category to retrieve the segments for
     * @param   array   $query  The request that is built right now
     *
     * @return  array|string  The segments of this item
     */
    /*public function getCategorySegment($id, $query)
    {
        $category = $this->getCategories(['access' => true])->get($id);

        if ($category) {
            $path = array_reverse($category->getPath(), true);
            $path[0] = '1:root';

            return $path;
        }

        return array();
    }*/

    /**
     * Method to get categories instance
     * The instance is stored in a cache to speed up subsequent invocations, keyed by the
     *   option of whether or not to take Access into consideration when analysing the categories
     *
     * @param   array  $options   The options for retrieving categories
     *
     * @return  CategoryInterface  The object containing categories
     *
     * @since   4.0.0
     */
    /*private function getCategories(array $options = []): CategoryInterface
    {
        $key = serialize($options);

        if (!isset($this->categoryCache[$key])) {
            $this->categoryCache[$key] = $this->categoryFactory->createCategory($options);
        }

        return $this->categoryCache[$key];
    }*/
}
