<?php
/**
 * @package     Joomla.Site
 * @subpackage  com__bones
 *
 * @copyright   Copyright (C) {{OWNER}} {{YEAR}}.
 * @license     MIT License; see LICENSE.md
 */

namespace {{OWNER}}\Component\_Bones\Site\View\_Bone;

defined('_JEXEC') or die;

#use Joomla\CMS\Helper\TagsHelper;
#use Joomla\CMS\Plugin\PluginHelper;
#use Joomla\Event\Event;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Log\Log;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;

/**
 * _Bone Component HTML View
 */
class HtmlView extends BaseHtmlView {

    /**
     * The _bone object
     *
     * @var    \JObject
     */
    protected $item;

    /**
     * The page parameters
     *
     * @var    \Joomla\Registry\Registry|null
     */
    protected $params;

    /**
     * The item model state
     *
     * @var    \Joomla\Registry\Registry
     */
    protected $state;


    protected function getTitle() {
        return  $this->title = $menu->title;
    }


    public function display($template = null)
    {
        $app          = Factory::getApplication();
        $input        = $app->input;

        $this->state  = $this->get('State');
        $this->params = $this->state->get('params');
        $this->items  = $this->get('Items');
        $this->item   = $this->get('Item');


        #echo '<pre>'; var_dump($this->state); echo '</pre>'; exit;
        #echo '<pre>'; var_dump($this->item); echo '</pre>'; exit;

        $user = $app->getIdentity();
        $user_is_root = $user->authorise('core.admin');
        $this->user   = $user;


        $this->state  = $this->get('State');
        $this->params = $this->state->get('params');

        $document     = Factory::getDocument();

        // We may not actually want to show the form at this point (though we could if we wanted to
        // include the form AND the record on the same page - especially if it's displayed via a
        // modal), but it's useful to have the form so we can retrieve language strings without
        // having to manually reclare them, along with any other properties of the form that may be
        // useful:
        $this->form = $this->get('Form');

        // Load admin lang file for use in the form:
        $app->getLanguage()->load('com__bones', JPATH_COMPONENT_ADMINISTRATOR);


        $uri    = Uri::getInstance();
        $menus  = $app->getMenu();
        $menu   = $menus->getActive();

        //$this->title = $menu->title;
        //$this->title = $this->item->title;

        $this->menu_params = $menu->getParams();

        $this->return_page = base64_encode($uri::base() . $menu->route);


        $is_new = empty($this->item->id);
        $is_own = false;
        if (!$is_new && ($user->id == $this->item->created_by)) {
            $is_own = true;
        }

        #echo '<pre>'; var_dump($user_is_root); echo '</pre>'; exit;
        if ($user_is_root) {
            $authorised = true;
        } elseif ($is_new) {
            $authorised = $user->authorise('core.create', 'com__bones');
        } elseif ($is_own) {
            $authorised = $user->authorise('core.edit.own', 'com__bones');
        }
        else {
            $authorised = $user->authorise('core.edit', 'com__bones');
        }

        if ($authorised !== true && $this->getLayout() == 'form') {
            throw new \Exception(Text::_('JERROR_ALERTNOAUTHOR'), 403);

            return false;
        }


        // Add to breadcrumbs:
        $breadcrumb_title = false;
        //if ((!$breadcrumb_title = $this->item->title) && $is_new) {
        if ($is_new) {
            $breadcrumb_title  = Text::_('COM_BONES_PAGE_TITLE_ADD_NEW');
        } else {
            if (!empty($this->item) && !empty($this->item->title)) {
                $breadcrumb_title = $this->item->title;
            }
        }

        $pathway = $app->getPathway();

        // Fix the pathway link:
        // I don't think this should be necessary - I thought the Router should handle this, but the
        // final URL is just 'index.php' (the link at this stage is:
        // 'index.php?option=com__bones&view=_bones&Itemid=xxx' )
        // I'm fudging things here...
        /*$pathway_items = $pathway->getPathway();
        $c = count($pathway_items) - 1;
        $link = $pathway_items[$c]->link;
        $pathway_items[$c]->link = $menu->route;
        #$pathway_items[$c]->link = preg_replace('/&Itemid=\d+$/', '', $link);

        $pathway->setPathway($pathway_items);
        */
        /* --- */

        $pathway->addItem($breadcrumb_title);
        #echo '<pre>'; var_dump($pathway); echo '</pre>'; exit;

        // Check for errors.
        $errors = $this->get('Errors', false);

        if (!empty($errors)) {
            Log::add(implode('<br />', $errors), Log::WARNING, 'jerror');

            return false;
        }

        $this->return_page = base64_encode($uri);

        if ($input->get('layout') == 'edit') {
            $document->page_heading_additional = ': ' . (
                $is_new
              ? Text::_('COM_BONES_RECORD_CREATING')
              : Text::_('COM_BONES_RECORD_EDITING') . ' ' . $this->item->title
            );
        } else {
            $document->page_heading_additional = ': ' . $this->item->title;
        }

        // Call the parent display to display the layout file
        parent::display($template);

        /*

        $user = JFactory::getUser();
        $user_is_root = $user->authorise('core.admin');

        $item = $this->get('Item');
        // We may not actually want to show the form at this point (though we could if we wanted to
        // include the form AND the record on the same page - especially if it's displayed via a
        // modal), but it's useful to have the form so we can retrieve language strings without
        // having to manually reclare them, along with any other properties of the form that may be
        // useful:
        $form = $this->get('Form');
        #echo '<pre>'; var_dump($item); echo '</pre>'; exit;
        #echo '<pre>'; var_dump($form); echo '</pre>'; exit;
        #echo '<pre>'; var_dump($this->getLayout()); echo '</pre>'; exit;

        $app    = JFactory::getApplication();
        $menus  = $app->getMenu();
        $menu   = $menus->getActive();
        #echo '<pre>'; var_dump($menu); echo '</pre>'; exit;
        #echo '<pre>'; var_dump(JRoute::_($menu->link)); echo '</pre>'; exit;
        #echo '<pre>'; var_dump(URI::base()); echo '</pre>'; exit;
        #echo '<pre>'; var_dump($item->id); echo '</pre>'; exit;
        #echo '<pre>'; var_dump($user, $item); echo '</pre>'; exit;
        #echo '<pre>'; var_dump($user->id, $item->created_by); echo '</pre>'; exit;

        $this->return_page = base64_encode(URI::base() . $menu->route);


        $is_new = empty($item->id);
        $is_own = false;
        if (!$is_new && ($user->id == $item->created_by)) {
            $is_own = true;
        }


        if ($user_is_root) {
            $authorised = true;
        } elseif ($is_new) {
            $authorised = $user->authorise('core.create', 'com__bones');
        } elseif ($is_own) {
            $authorised = $user->authorise('core.edit.own', 'com__bones');
        }
        else {
            $authorised = $user->authorise('core.edit', 'com__bones');
        }

        if ($authorised !== true && $this->getLayout() == 'form') {
            JError::raiseError(403, JText::_('JERROR_ALERTNOAUTHOR'));

            return false;
        }


        // Add to breadcrumbs:
        if ((!$breadcrumb_title = $item->title) && $is_new) {
            $breadcrumb_title  = JText::_('COM_BONES_PAGE_TITLE_ADD_NEW');
        }

        #echo '<pre>'; var_dump($breadcrumb_title); echo '</pre>'; exit;

        $app     = JFactory::getApplication();
        $pathway = $app->getPathway();
        $pathway->addItem($breadcrumb_title);

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JLog::add(implode('<br />', $errors), JLog::WARNING, 'jerror');

            return false;
        }


        // Assign data to the view
        $this->item = $item;
        // Although we're not actually showing the form, it's useful to use it to be able to show
        // the field names without having to explicitly state them (more DRY):
        $this->form = $form;

        */




        /*
        $app = Factory::getApplication();

        $this->item   = $this->get('Item');
        $this->state  = $this->get('State');
        $this->params = $this->state->get('params');

        // Create a shortcut for $item.
        $item = $this->item;

        $item->slug = $item->alias ? ($item->id . ':' . $item->alias) : $item->id;

        $temp         = $item->params;
        $item->params = clone $app->getParams();
        $item->params->merge($temp);

        $offset = $this->state->get('list.offset');

        $app->triggerEvent('onContentPrepare', array('com_weblinks.weblink', &$item, &$item->params, $offset));

        $item->event = new \stdClass;

        $results = $app->triggerEvent('onContentAfterTitle', array('com_weblinks.weblink', &$item, &$item->params, $offset));
        $item->event->afterDisplayTitle = trim(implode("\n", $results));

        $results = $app->triggerEvent('onContentBeforeDisplay', array('com_weblinks.weblink', &$item, &$item->params, $offset));
        $item->event->beforeDisplayContent = trim(implode("\n", $results));

        $results = $app->triggerEvent('onContentAfterDisplay', array('com_weblinks.weblink', &$item, &$item->params, $offset));
        $item->event->afterDisplayContent = trim(implode("\n", $results));

        parent::display($tpl);
        */


        /*// Assign data to the view
        $this->msg = 'Get from API';
        */

    }

}