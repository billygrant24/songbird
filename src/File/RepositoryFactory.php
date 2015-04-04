<?php
namespace Songbird\File;

use League\Container\ContainerAwareInterface;
use League\Container\ContainerAwareTrait;
use Songbird\ContainerResolverTrait;

class RepositoryFactory implements ContainerAwareInterface
{
    use ContainerAwareTrait, ContainerResolverTrait;

    /**
     * @return \Songbird\File\Repository
     */
    public function createContentRepository()
    {
        return $this->createRepositoryForDirectory('/');
    }

    /**
     * @param string $directory
     * @param string $extension
     *
     * @return \Songbird\File\Repository
     */
    public function createRepositoryForDirectory($directory, $extension = 'md')
    {
        $source = $this->resolve('Songbird\File\Source');
        $repository = $this->resolve('Songbird\File\Repository');

        $source->setFilesystem($this->resolve('Filesystem'));
        $source->setDirectory($directory);
        $source->setExtension($extension);

        $repository->addDataSource($source);

        return $repository;
    }
}