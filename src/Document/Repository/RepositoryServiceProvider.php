<?php
namespace Songbird\Document\Repository;

use JamesMoss\Flywheel\Config as FlywheelConfig;
use League\Container\ServiceProvider;
use Songbird\ConfigAwareInterface;
use Songbird\ConfigAwareTrait;
use Songbird\Document\Formatter\Universal;

class RepositoryServiceProvider extends ServiceProvider implements ConfigAwareInterface
{
    use ConfigAwareTrait;

    protected $provides = [
        'App.Repo.Documents',
        'App.Repo.Fragments',
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
        $app = $this->getContainer();

        foreach (['documents', 'fragments'] as $dir) {
            $repositoryConfig = new FlywheelConfig($this->getConfig()->get('runtime.paths.resources'), [
                'formatter' => new Universal(),
                'query_class' => $this->hasAPC() ? '\\Songbird\\Document\\CachedQuery' :
                    '\\Songbird\\Document\\Query',
                'document_class' => '\\JamesMoss\\Flywheel\\Document',
            ]);

            $alias = sprintf('App.Repo.%s', ucwords($dir));

            $repo = new NestedRepository($dir, $repositoryConfig);
            $repo->setFilesystem($this->getContainer()->get('Filesystem'));

            $app->add($alias, $repo);
        }
    }

    public function hasAPC()
    {
        return function_exists('apcu_fetch') || function_exists('apc_fetch');
    }
}