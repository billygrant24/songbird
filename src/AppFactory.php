<?php
namespace Songbird;

use League\Flysystem\Adapter\Local as Adapter;
use League\Flysystem\Cached\CachedAdapter;
use League\Flysystem\Cached\Storage\Memory as CacheStore;
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
        $app = new App(require __DIR__ . '/Resources/etc/di.php');

        $app->add('App', $app);
        $app->add('Config', new Config($config));

        $app->inflector('League\Container\ContainerAwareInterface')->invokeMethod('setContainer', [$app->get('App')]);
        $app->inflector('Songbird\Event\EventAwareInterface')->invokeMethod('setEvent', [$app->get('Event')]);

        $app->add('Filesystem', 'Songbird\Filesystem\Filesystem')->withArgument(
            new CachedAdapter(new Adapter($app->config('app.paths.resources')), new CacheStore())
        );

        $app->inflector('Songbird\Filesystem\FilesystemAwareInterface')->invokeMethod('setFilesystem', ['Filesystem']);

        $app->add('Repository.Content', $app->get('RepositoryFactory')->createContentRepository());
        $app->add('Repository.Block', $app->get('RepositoryFactory')->createBlockRepository());

        $app->registerPackages();

        $app->inflector('Psr\Log\LoggerAwareInterface')->invokeMethod('setLogger', [$app->get('Logger')]);

        $fileName = vsprintf('%s/songbird-%s.log', [$app->config('app.paths.log'), date('Y-d-m')]);
        $app->get('Logger')->pushHandler(new StreamHandler($fileName, Logger::INFO));

        return $app;
    }
}
