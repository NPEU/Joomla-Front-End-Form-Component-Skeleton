<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com__bones
 *
 * @copyright   Copyright (C) {{OWNER}} {{YEAR}}.
 * @license     MIT License; see LICENSE.md
 */

/**
 *
 */

namespace {{OWNER}}\Component\_Bones\Administrator\Service\Provider;

defined('_JEXEC') or die;

use Joomla\CMS\Categories\CategoryFactoryInterface;
use Joomla\CMS\Component\Router\RouterFactoryInterface;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\Database\DatabaseInterface;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;

use {{OWNER}}\Component\_Bones\Administrator\Service\RouterFactory;

class RouterFactoryProvider implements ServiceProviderInterface
{
    /**
     * The module namespace
     *
     * @since   4.0.0
     * @var  string
     *
     */
    private $namespace;

    /**
     * DispatcherFactory constructor.
     *
     * @param   string  $namespace  The namespace
     *
     * @since   4.0.0
     */
    public function __construct(string $namespace)
    {
        $this->namespace = $namespace;
    }

    /**
     * Registers the service provider with a DI container.
     *
     * @param   Container  $container  The DI container.
     *
     * @return  void
     *
     * @since   4.0.0
     */
    public function register(Container $container)
    {
        $container->set(
            RouterFactoryInterface::class,
            function (Container $container) {
                $categoryFactory = null;

                if ($container->has(CategoryFactoryInterface::class)) {
                    $categoryFactory = $container->get(CategoryFactoryInterface::class);
                }

                $routerFactory = new RouterFactory(
                    $this->namespace,
                    $categoryFactory,
                    $container->get(DatabaseInterface::class)
                );

                $routerFactory->setMVCFactory($container->get(MVCFactoryInterface::class));

                return $routerFactory;
            }
        );
    }
}