<?php
namespace Songbird;

use League\Flysystem\Filesystem;

interface FilesystemAwareInterface
{
    /**
     * @param \League\Flysystem\Filesystem $filesystem
     */
    public function setFilesystem(Filesystem $filesystem);

    /**
     * @return \League\Flysystem\Filesystem
     */
    public function getFilesystem();
}