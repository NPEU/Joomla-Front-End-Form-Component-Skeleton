<?php
/**
 * @package     Joomla.Site
 * @subpackage  com__bones
 *
 * @copyright   Copyright (C) {{OWNER}} {{YEAR}}.
 * @license     MIT License; see LICENSE.md
 */

namespace {{OWNER}}\Component\_Bones\Site\Service;

// phpcs:disable PSR1.Files.SideEffects
\defined('JPATH_PLATFORM') or die;
// phpcs:enable PSR1.Files.SideEffects

use Joomla\CMS\Factory;

/**
 * RouterRules interface for Joomla
 *
 * @since  3.4
 */
class CustomRouterRules implements \Joomla\CMS\Component\Router\Rules\RulesInterface
{
    /**
     * Prepares a query set to be handed over to the build() method.
     * This should complete a partial query set to work as a complete non-SEFed
     * URL and in general make sure that all information is present and properly
     * formatted. For example, the Itemid should be retrieved and set here.
     *
     * @param   array  &$query  The query array to process
     *
     * @return  void
     *
     * @since   3.4
     */
    public function preprocess(&$query) {

        #echo 'preprocessquery<pre>'; var_dump($query); echo '</pre>'; #exit;
    }

    /**
     * Parses a URI to retrieve information for the right route through the component.
     * This method should retrieve all its input from its method arguments.
     *
     * @param   array  &$segments  The URL segments to parse
     * @param   array  &$vars      The vars that result from the segments
     *
     * @return  void
     *
     * @since   3.4
     */
    public function parse(&$segments, &$vars) {


        $app   = Factory::getApplication();
        $user_state_ids = $app->getUserState('com__bones.edit._bone.id');

        if (isset($vars['view'])) {
            if ($vars['view'] == 'edit') {
                if (!empty($user_state_ids) && !empty($vars['id']) && in_array($vars['id'], $user_state_ids)) {
                    $vars['view'] = '_bone';
                    $vars['layout'] = 'edit';
                    unset($vars['task']);
                    #echo 'parsevarsZ<pre>'; var_dump($vars); echo '</pre>'; exit;
                } else {
                    // Note the main router has already established there's an ID and that it's record
                    // exists so there's no need to check them here.
                    $vars['view'] = '_bone';
                    $vars['task'] = '_bone.edit';
                }
            } elseif ($vars['view'] == 'add') {
                $vars['view'] = '_bone';
                $vars['layout'] = 'edit';
            } elseif ($vars['view'] == 'other') {
                $vars['view'] = '_bones';
                $vars['layout'] = 'other';
            }
        }

        #echo 'parsevarsE<pre>'; var_dump($vars); echo '</pre>'; exit;
        return $vars;
    }

    /**
     * Builds URI segments from a query to encode the necessary information for a route in a human-readable URL.
     * This method should retrieve all its input from its method arguments.
     *
     * @param   array  &$query     The vars that should be converted
     * @param   array  &$segments  The URL segments to create
     *
     * @return  void
     *
     * @since   3.4
     */
    public function build(&$query, &$segments) {
        if (isset($query['task']) && $query['task'] == '_bone.edit') {
            $segments[] = 'edit';
            //$segments[] = $query['id'];
            unset ($query['task']);
            unset ($query['id']);
        }

        if (isset($query['layout']) && $query['layout'] == 'edit') {
            $segments[] = 'edit';
            //$segments[] = $query['id'];
            unset ($query['layout']);
        }

        if (isset($query['layout']) && $query['layout'] == 'other') {
            $segments[] = 'other';
            //$segments[] = $query['id'];
            unset ($query['layout']);
        }

        #echo 'buildsegments2<pre>'; var_dump($segments); echo '</pre><hr>';#exit;
    }
}
