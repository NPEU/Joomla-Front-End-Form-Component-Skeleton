<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com__bones
 *
 * @copyright   Copyright (C) {{OWNER}} {{YEAR}}.
 * @license     MIT License; see LICENSE.md
 */

namespace {{OWNER}}\Component\_Bones\Administrator\Extension;

defined('JPATH_PLATFORM') or die;


#use Joomla\CMS\Association\AssociationServiceInterface;
#use Joomla\CMS\Association\AssociationServiceTrait;
#use Joomla\CMS\Categories\CategoryServiceInterface;
#use Joomla\CMS\Categories\CategoryServiceTrait;
use Joomla\CMS\Extension\BootableExtensionInterface;
use Joomla\CMS\Extension\MVCComponent;
use Joomla\CMS\HTML\HTMLRegistryAwareTrait;
use Psr\Container\ContainerInterface;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Component\Router\RouterServiceTrait;
use Joomla\CMS\Component\Router\RouterServiceInterface;
#use {{OWNER}}\Component\_Bones\Site\Service\TraditionalRouter;
use Joomla\CMS\Component\Router\RouterInterface;
use Joomla\CMS\Application\CMSApplicationInterface;
use Joomla\CMS\Menu\AbstractMenu;
use {{OWNER}}\Component\_Bones\Administrator\Service\HTML\AdministratorService;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
#use Joomla\CMS\Fields\FieldsServiceInterface;
use Joomla\Database\DatabaseAwareTrait;

class _BonesComponent extends MVCComponent implements
    RouterServiceInterface, BootableExtensionInterface
{
    use RouterServiceTrait;
    use HTMLRegistryAwareTrait;
    #use AssociationServiceTrait;
    use DatabaseAwareTrait;

    /**
     * Booting the extension. This is the function to set up the environment of the extension like
     * registering new class loaders, etc.
     *
     * We use this to register the helper file class which contains the html for displaying associations
     */
    public function boot(ContainerInterface $container)
    {
        $this->getRegistry()->register('_bonesadministrator', new AdministratorService);
    }


    /**
     * Returns the name of the published state column in the table
     * for use by the count items function
     *
     */
    protected function getStateColumnForSection(string $section = null)
    {
        return 'state';
    }

}
