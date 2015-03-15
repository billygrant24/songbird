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
        return $this->createRepositoryForDirectory('content');
    }

    /**
     * @return \Songbird\File\Repository
     */
    public function createBlockRepository()
    {
        return $this->createRepositoryForDirectory('blocks');
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
        $repo = $this->resolve('Songbird\File\Repository');

        $source->setDirectory($directory);
        $source->setExtension($extension);
        $source->setParser($this->resolve('Songbird\File\Parser'));
        $source->setFilesystem($this->resolve('Filesystem'));

        $repo->addSource($source);

        return $repo;
    }
}