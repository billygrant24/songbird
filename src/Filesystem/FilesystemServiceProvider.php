<?php
namespace Songbird\Filesystem;

use League\Container\ServiceProvider;
use League\Flysystem\Adapter\Local as Adapter;
use League\Flysystem\Cached\CachedAdapter;
use League\Flysystem\Cached\Storage\Memory as CacheStore;
use Songbird\ConfigAwareInterface;
use Songbird\ConfigAwareTrait;
use Songbird\Document\Collection;
use Songbird\Document\Formatter\Universal;
use Songbird\Document\Repository;

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
        'Document.Repository',
        'Fragment.Repository',
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

        $localAdapter = new Adapter($app->config('app.paths.resources'));
        $cacheStore = new CacheStore();
        $adapter = new CachedAdapter($localAdapter, $cacheStore);

        $app->add('Filesystem', 'Songbird\Filesystem\Filesystem')->withArgument($adapter);

        $app->inflector('Songbird\Filesystem\FilesystemAwareInterface')->invokeMethod('setFilesystem', ['Filesystem']);

        $fs = $app->resolve('Filesystem');

        $docs = Collection::make($fs->listContents('documents', true));
        $docs = $docs->map(function ($doc) use ($fs) {
            if (isset($doc['extension']) && $doc['extension'] === 'md') {
                $parsed = new Universal();
                $arr['id'] = str_replace(['documents/', '.md'], '', $doc['path']);
                $arr = array_merge($arr, $parsed->decode($fs->read($doc['path'])));

                return $arr;
            }
        });

        $app->add('Document.Repository', new Repository($docs));

        $docs = Collection::make($fs->listContents('fragments', true));
        $docs = $docs->map(function ($doc) use ($fs) {
            if (isset($doc['extension']) && $doc['extension'] === 'md') {
                $parsed = new Universal();
                $arr['id'] = str_replace(['fragments/', '.md'], '', $doc['path']);
                $arr = array_merge($arr, $parsed->decode($fs->read($doc['path'])));

                return $arr;
            }
        });

        $app->add('Fragment.Repository', new Repository($docs));
    }
}