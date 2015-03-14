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
        $app->add('Config', new Config($config));

        $app->inflector('League\Container\ContainerAwareInterface')->invokeMethod('setContainer', [$app->get('App')]);

        $app->add('Router', new RouteCollection($app));
        $app->get('Router')->setStrategy(new RequestResponseStrategy());

        $app->addCoreServiceProviders();

        $app->add('RepositoryFactory', 'Songbird\Document\RepositoryFactory');
        $app->add('Document.Repository', $app->get('RepositoryFactory')->createDocumentRepository());
        $app->add('Fragment.Repository', $app->get('RepositoryFactory')->createFragmentRepository());

        $app->registerPackages();

        $app->inflector('Songbird\Log\LoggerAwareInterface')->invokeMethod('setLogger', [$app->get('Logger')]);

        return $app;
    }
}
