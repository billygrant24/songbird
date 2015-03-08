<?php
namespace Songbird\Routing;

use League\Container\ServiceProvider;
use League\Route\RouteCollection;
use League\Route\Strategy\RequestResponseStrategy;
use Songbird\ConfigAwareInterface;
use Songbird\ConfigAwareTrait;

class RouterServiceProvider extends ServiceProvider implements ConfigAwareInterface
{
    use ConfigAwareTrait;

    protected $provides = [
        'App.Router'
    ];

    /**
     * Use the register method to register items with the container via the
     * protected $this->container property or the `getContainer` method
     * from the ContainerAwareTrait.
     *
     * @return void
     */
    public function register()
    {
        $this->getContainer()->singleton('App.Router', new RouteCollection($this->getContainer()));
        $this->getContainer()->get('App.Router')->setStrategy(new RequestResponseStrategy());
        $this->addRoutes();
    }

    /**
     * Add all routes required to handle document requests.
     */
    protected function addRoutes()
    {
        foreach (['GET', 'POST', 'PUT', 'DELETE'] as $method) {
            $this->getContainer()->get('App.Router')->addRoute(
                $method,
                '/{slug:.*}',
                sprintf('%s::handle', $this->getConfig()->get('app.handler', 'Songbird\Controller'))
            );
        }
    }
}