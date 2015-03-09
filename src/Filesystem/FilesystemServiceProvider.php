<?php
namespace Songbird\Filesystem;

use League\Container\ServiceProvider;
use League\Flysystem\Adapter\Local as Adapter;
use League\Flysystem\Cached\CachedAdapter;
use League\Flysystem\Cached\Storage\Memory as CacheStore;
use Songbird\ConfigAwareInterface;
use Songbird\ConfigAwareTrait;

class FilesystemServiceProvider extends ServiceProvider
{
    /**
     * This array allows the container to be aware of
     * what your service provider actually provides,
     * this should contain all alias names that
     * you plan to register with the container
     *
     * @var array
     */
    protected $provides = [
        'Filesystem',
        'Songbird\Filesystem\FilesystemAwareInterface',
    ];

    /**
     * This is where the magic happens, within the method you can
     * access the container and register or retrieve anything
     * that you need to
     */
    public function register()
    {
        $app = $this->getContainer();
        $config = $this->getContainer()->get('Config');

        $localAdapter = new Adapter($config->get('app.paths.resources'));
        $cacheStore = new CacheStore();
        $adapter = new CachedAdapter($localAdapter, $cacheStore);

        $app->add('Filesystem', 'Songbird\Filesystem\Filesystem')->withArgument($adapter);

        $app->inflector('Songbird\Filesystem\FilesystemAwareInterface')->invokeMethod('setFilesystem', ['Filesystem']);

    }
}