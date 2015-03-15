<?php
namespace Songbird\File;

class Repository
{
    /**
     * @var \Songbird\File\Collection
     */
    protected $source;

    /**
     * Hydrate the repository with files.
     *
     * @param \Songbird\File\Source $source
     */
    public function addSource(Source $source)
    {
        $this->source = $source->getFiles();
    }

    /**
     * @param string $id
     *
     * @return mixed|null
     */
    public function find($id)
    {
        $file = $this->source->where('id', $id)->filter(function ($file) {
            return trim($file['body']) !== '...';
        });

        if ($file->isEmpty()) {
            $file = $this->source->where('id', '404');
        }

        return $file->first();
    }

    /**
     * @param mixed $name
     * @param null  $args
     *
     * @return \Songbird\File\Collection
     */
    public function __call($name, $args = null)
    {
        $this->source = call_user_func_array([$this->source, $name], $args);

        return $this->source;
    }
}