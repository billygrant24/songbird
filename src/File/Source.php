<?php
namespace Songbird\File;

class Source
{
    /**
     * @var string
     */
    protected $directory;

    /**
     * @var string $extension
     */
    protected $extension;

    /**
     * @var \Songbird\Filesystem\Filesystem
     */
    protected $filesystem;

    /**
     * @var \Songbird\File\Parser
     */
    protected $parser;

    /**
     * Hydrate the repository with files.
     */
    public function getFiles()
    {
        $files = Collection::make($this->getFilesystem()->listContents($this->getDirectory(), true));

        return $files->map(function ($file) {
            if ($this->shouldBeParsed($file)) {
                return $this->parseFile($file);
            }
        });
    }

    /**
     * @return \Songbird\Filesystem\Filesystem
     */
    public function getFilesystem()
    {
        return $this->filesystem;
    }

    /**
     * @param \Songbird\Filesystem\Filesystem $filesystem
     */
    public function setFilesystem($filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * @return string
     */
    public function getDirectory()
    {
        return $this->directory;
    }

    /**
     * @param string $directory
     */
    public function setDirectory($directory)
    {
        $this->directory = $directory;
    }

    /**
     * @param mixed $file
     *
     * @return bool
     */
    protected function shouldBeParsed($file)
    {
        return isset($file['extension']) && $file['extension'] === $this->getExtension();
    }

    /**
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * @param string $extension
     */
    public function setExtension($extension = 'md')
    {
        $this->extension = $extension;
    }

    /**
     * @param mixed $file
     *
     * @return array
     */
    protected function parseFile($file)
    {
        $arr['id'] = $this->generateIdForFile($file['path']);
        $arr = array_merge($arr, $this->getParser()->decode($this->getFilesystem()->read($file['path'])));

        if ($this->hasParent($arr)) {
            $arr = array_merge($this->parseParent($arr), $arr);
        }

        if ($this->hasIncludes($arr)) {
            $arr = array_merge($arr, $this->parseIncludes($arr));
        }

        return $arr;
    }

    /**
     * @param mixed $path
     *
     * @return mixed
     */
    protected function generateIdForFile($path)
    {
        return str_replace([$this->getDirectory() . '/', '.' . $this->getExtension()], '', $path);
    }

    /**
     * @return \Songbird\File\Parser
     */
    public function getParser()
    {
        return $this->parser;
    }

    /**
     * @param \Songbird\File\Parser $parser
     */
    public function setParser($parser)
    {
        $this->parser = $parser;
    }

    /**
     * @param mixed $file
     *
     * @return bool
     */
    protected function hasParent($file)
    {
        return isset($file['extends']);
    }

    /**
     * @param mixed $file
     *
     * @return mixed
     */
    protected function parseParent($file)
    {
        $parentPath = $file['extends'];

        return $this->getParser()->decode(
            $this->getFilesystem()->read($this->generateFullPathToFile($parentPath))
        );
    }

    /**
     * @param mixed $path
     *
     * @return mixed
     */
    protected function generateFullPathToFile($path)
    {
        return vsprintf('%s/%s.%s', [$this->getDirectory(), $path, $this->getExtension()]);
    }

    /**
     * @param mixed $file
     *
     * @return bool
     */
    protected function hasIncludes($file)
    {
        return isset($file['includes']);
    }

    /**
     * @param mixed $file
     *
     * @return array
     */
    protected function parseIncludes($file)
    {
        $newFile = [];
        foreach ($file['includes'] as $key => $includePath) {
            $includedFile = $this->getParser()->decode(
                $this->getFilesystem()->read($this->generateFullPathToFile($includePath))
            );

            $newFile[$key] = $includedFile;
        }

        return $newFile;
    }
}