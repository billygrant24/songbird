<?php
namespace Songbird;

use League\Route\RouteCollection;
use League\Route\Strategy\RequestResponseStrategy;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

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

        $app->add('RepositoryFactory', 'Songbird\File\RepositoryFactory');
        $app->add('Repository.Content', $app->get('RepositoryFactory')->createContentRepository());
        $app->add('Repository.Block', $app->get('RepositoryFactory')->createBlockRepository());

        $app->registerPackages();

        $app->singleton('Logger', 'Monolog\Logger')->withArgument('songbird');
        $app->inflector('Psr\Log\LoggerAwareInterface')->invokeMethod('setLogger', [$app->get('Logger')]);

        $fileName = vsprintf('%s/songbird-%s.log', [$app->config('app.paths.log'), date('Y-d-m')]);
        $app->get('Logger')->pushHandler(new StreamHandler($fileName, Logger::INFO));

        return $app;
    }
}
