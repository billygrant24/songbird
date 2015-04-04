<?php
namespace Songbird;

use League\Flysystem\Adapter\Local as Adapter;
use League\Flysystem\Cached\CachedAdapter;
use League\Flysystem\Cached\Storage\Memory as CacheStore;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AppFactory
{
    /**
     * @param string $config
     *
     * @return \Songbird\App
     */
    public static function createApplication($config = '')
    {
        $app = new App(require __DIR__ . '/../config/di.php');

        $app->add('Config', new Config($config));
        $app->add('Symfony\Component\HttpFoundation\Response', new Response());
        $app->add('Symfony\Component\HttpFoundation\Request', Request::createFromGlobals());
        $app->add('Filesystem', 'League\Flysystem\Filesystem')->withArgument(
            new CachedAdapter(new Adapter($app->config('app.paths.resources')), new CacheStore())
        );
        $app->get('Logger')->pushHandler(new StreamHandler(vsprintf('%s/songbird-%s.log', [
            $app->config('app.paths.log'),
            date('Y-d-m')
        ]), Logger::INFO));

        $app->inflector('League\Container\ContainerAwareInterface')->invokeMethod('setContainer', [$app]);
        $app->inflector('League\Event\EmitterAwareInterface')->invokeMethod('setEmitter', [$app->get('Emitter')]);
        $app->inflector('Psr\Log\LoggerAwareInterface')->invokeMethod('setLogger', [$app->get('Logger')]);
        $app->inflector('Songbird\FilesystemAwareInterface')->invokeMethod('setFilesystem', ['Filesystem']);

        $app->add('Repository', $app->get('RepositoryFactory')->createContentRepository());

        return $app;
    }
}
