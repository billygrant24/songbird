<?php
namespace Songbird;

use League\Container\ContainerInterface;
use League\Container\ServiceProvider;

abstract class PackageProviderAbstract extends ServiceProvider
{
    /**
     * Use the register method to register items with the container via the
     * protected $this->container property or the `getContainer` method
     * from the ContainerAwareTrait.
     *
     * @return void
     */
    final public function register()
    {
        $app = $this->getContainer();

        $this->registerPackage($app);
        $this->registerEventListeners($app, $app->get('Event'));
        $this->registerRoutes($app, $app->get('Router'));
    }

    /**
     * @param \League\Container\ContainerInterface $app
     */
    protected function registerEventListeners(ContainerInterface $app, $event = null)
    {
        // ...
    }

    /**
     * Add all routes required to handle document requests.
     */
    protected function registerRoutes($app, $router)
    {
        // ...
    }
}