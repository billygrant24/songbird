<?php
namespace Songbird;

use League\Container\ContainerInterface;
use League\Container\ServiceProvider;
use League\Route\RouteCollection;
use League\Event\Emitter;

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
        $this->registerEventListeners($app->get('Emitter'));
        $this->registerRoutes($app->get('Router'));
    }

    /**
     * @param \League\Container\ContainerInterface $app
     *
     * @return mixed
     */
    protected function registerPackage(ContainerInterface $app)
    {
        // ...
    }

    /**
     * @param \League\Event\Emitter $event
     */
    protected function registerEventListeners(Emitter $event)
    {
        // ...
    }

    /**
     * Add all routes required to handle document requests.
     *
     * @param \League\Route\RouteCollection $router
     */
    protected function registerRoutes(RouteCollection $router)
    {
        // ...
    }
}