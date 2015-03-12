<?php
namespace Songbird;

use League\Route\RouteCollection;
use League\Route\Strategy\RequestResponseStrategy;

class AppFactory
{
    /**
     * @param string $config
     *
     * @return \Songbird\App
     */
    public static function createApplication($config = '')
    {
        $app = new App();

        $app->add('App', $app);
        $app->inflector('League\Container\ContainerAwareInterface')->invokeMethod('setContainer', [$app->get('App')]);

        $app->add('Config', new Config($config));
        $app->add('Document.Transformer', $app->resolve('Songbird\Document\Transformer'));

        $app->add('Router', new RouteCollection($app));
        $app->get('Router')->setStrategy(new RequestResponseStrategy());

        $app->addCoreServiceProviders();
        $app->registerPackages();
        $app->addRoutes();

        $app->inflector('Songbird\Log\LoggerAwareInterface')->invokeMethod('setLogger', [$app->get('Logger')]);

        return $app;
    }
}
