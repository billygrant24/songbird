<?php
namespace Songbird\Document;

use League\Container\ContainerAwareInterface;
use League\Container\ContainerAwareTrait;
use Songbird\ContainerResolverTrait;

class RepositoryFactory implements ContainerAwareInterface
{
    use ContainerAwareTrait, ContainerResolverTrait;

    /**
     * @return \Songbird\Document\Repository
     */
    public function createDocumentRepository()
    {
        $documents = $this->getFilesForDirectory('documents');

        return new Repository($documents);
    }

    /**
     * @return \Songbird\Document\Repository
     */
    public function createFragmentRepository()
    {
        $fragments = $this->getFilesForDirectory('fragments');

        return new Repository($fragments);
    }

    /**
     * @param string $directory
     *
     * @return static
     */
    protected function getFilesForDirectory($directory)
    {
        $fs = $this->resolve('Filesystem');
        $files = Collection::make($fs->listContents($directory, true));

        return $files->map(function ($file) use ($fs, $directory) {
            if (isset($file['extension']) && $file['extension'] === 'md') {
                $parsed = new Formatter();
                $arr['id'] = str_replace([$directory . '/', '.md'], '', $file['path']);
                $arr = array_merge($arr, $parsed->decode($fs->read($file['path'])));

                return $arr;
            }
        });
    }
}