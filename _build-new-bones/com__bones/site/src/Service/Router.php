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
use Joomla\CMS\MVC\Factory\MVCFactoryAwareTrait;
use Joomla\Database\DatabaseInterface;
use Joomla\Database\ParameterType;

use {{OWNER}}\Component\_Bones\Site\Service\CustomRouterRules;



class Router extends RouterView
{
    use MVCFactoryAwareTrait;

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
    public function __construct(SiteApplication $app, AbstractMenu $menu)
    {
        //$this->categoryFactory = $categoryFactory;
        //$this->db              = $db;
        $this->db = \Joomla\CMS\Factory::getContainer()->get('DatabaseDriver');

        //$this->attachRule(new CustomRouterRules($this));

        #$category = new RouterViewConfiguration('category');
        #$category->setKey('id')->setNestable();
        #$this->registerView($category);
        $_bones = new RouterViewConfiguration('_bones');
        $_bones->addLayout('other');
        $this->registerView($_bones);

        $_bone = new RouterViewConfiguration('_bone');
        $_bone->setKey('id')->setParent($_bones);
        $this->registerView($_bone);

        $edit = new RouterViewConfiguration('edit');
        $edit->setParent($_bone);
        $this->registerView($edit);

        $alt = new RouterViewConfiguration('alt');
        $alt->setParent($_bones);
        $this->registerView($alt);

        $other = new RouterViewConfiguration('other');
        $other->setParent($_bones);
        $this->registerView($other);

        $add = new RouterViewConfiguration('add');
        $add->setParent($_bones);
        $this->registerView($add);

        //$this->attachRule(new CustomRouterRules($this));

        parent::__construct($app, $menu);

        $this->attachRule(new MenuRules($this));
        $this->attachRule(new StandardRules($this));
        $this->attachRule(new NomenuRules($this));

        $this->attachRule(new CustomRouterRules($this));
    }

    /**
     * Method to get the id for an _bones item from the segment
     *
     * @param   string  $segment  Segment of the _bones to retrieve the ID for
     * @param   array   $query    The request that is parsed right now
     *
     * @return  mixed   The id of this item or false
     */
    public function get_BoneId(string $segment, array $query): bool|int
    {
        // If the alias (segment) has been constructed to include the id as a
        // prefixed part of it, (e.g. 123-thing-name) then we can use this:
        //return (int) $segment;
        // Otherwise we'll need to query the database:
        $db = $this->db;
        $dbQuery = $db->getQuery(true)
            ->select($db->quoteName('id'))
            ->from($db->quoteName('#___bones'))
            ->where($db->quoteName('alias') . ' = :alias')
            ->bind(':alias', $segment);

        return  $db->setQuery($dbQuery)->loadResult() ?: false;
    }

    /**
     * Method to get the segment(s) for a _bones item
     *
     * @param   string  $id     ID of the _bones to retrieve the segments for
     * @param   array   $query  The request that is built right now
     *
     * @return  array|string  The segments of this item
     */
    public function get_BoneSegment(int $id, array $query): array
    {
        #echo 'get_BoneSegment<pre>'; var_dump($query); echo '</pre>';# exit;

        $db = $this->db;

        $dbQuery = $db->getQuery(true)
            ->select($db->quoteName('alias'))
            ->from($db->quoteName('#___bones'))
            ->where($db->quoteName('id') . ' = :id')
            ->bind(':id', $id);

            $segment = $db->setQuery($dbQuery)->loadResult() ?: null;

        if ($segment === null) {
            return [];
        }
        return [$id => $segment];
    }



    /**
     * Method to get the id for edit view
     *
     * @param   string  $segment  Segment of the _bones to retrieve the ID for
     * @param   array   $query    The request that is parsed right now
     *
     * @return  mixed   The id of this item or false
     */
    public function getEditId(string $segment, array $query): bool|int
    {
        #echo 'getEditIdsegemnt<pre>'; var_dump($query); echo '</pre>';# exit;
        return true;
    }

    /**
     * Method to get the segment(s) for edit view
     *
     * @param   string  $id     ID of the _bone to retrieve the segments for
     * @param   array   $query  The request that is built right now
     *
     * @return  array|string  The segments of this item
     *
     * @since   4.0.0
     */
    public function getEditSegment($id, $query)
    {
        return 'edit';
    }

    /**
     * Method to get the id for add view
     *
     * @param   string  $segment  Segment of the _bones to retrieve the ID for
     * @param   array   $query    The request that is parsed right now
     *
     * @return  mixed   The id of this item or false
     */
    public function getAddId(string $segment, array $query): bool|int
    {
        return true;
    }

    /**
     * Method to get the segment(s) for add view
     *
     * @param   string  $id     ID of the _bone to retrieve the segments for
     * @param   array   $query  The request that is built right now
     *
     * @return  array|string  The segments of this item
     *
     * @since   4.0.0
     */
    public function getAddSegment($id, $query)
    {
        return 'add';
    }

    /**
     * Method to get the id for alt view
     *
     * @param   string  $segment  Segment of the _bones to retrieve the ID for
     * @param   array   $query    The request that is parsed right now
     *
     * @return  mixed   The id of this item or false
     */
    public function getAltId(string $segment, array $query): bool|int
    {
        return true;
    }

    /**
     * Method to get the segment(s) for alt view
     *
     * @param   string  $id     ID of the _bone to retrieve the segments for
     * @param   array   $query  The request that is built right now
     *
     * @return  array|string  The segments of this item
     *
     * @since   4.0.0
     */
    public function getAltSegment($id, $query)
    {
        return 'alt';
    }

    /**
     * Method to get the id for alt view
     *
     * @param   string  $segment  Segment of the _bones to retrieve the ID for
     * @param   array   $query    The request that is parsed right now
     *
     * @return  mixed   The id of this item or false
     */
    public function geOtherId(string $segment, array $query): bool|int
    {
        return true;
    }

    /**
     * Method to get the segment(s) for alt view
     *
     * @param   string  $id     ID of the _bone to retrieve the segments for
     * @param   array   $query  The request that is built right now
     *
     * @return  array|string  The segments of this item
     *
     * @since   4.0.0
     */
    public function getOtherSegment($id, $query)
    {
        return 'other';
    }
}
