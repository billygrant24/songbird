<?php
namespace Songbird\File;

class Repository
{
    /**
     * @var \Songbird\Collection
     */
    protected $source;

    public $perPage;

    /**
     * "Paginate" the collection by slicing it into a smaller collection.
     *
     * @param  int $perPage
     *
     * @return static
     */
    public function paginate($perPage)
    {
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $this->perPage = $perPage;

        return $this->source->forPage($page, $this->perPage);
    }

    /**
     * Hydrate the repository with files.
     *
     * @param \Songbird\File\Source $source
     */
    public function addDataSource(Source $source)
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
     * @return \Songbird\Collection
     */
    public function __call($name, $args = null)
    {
        $this->source = call_user_func_array([$this->source, $name], $args);

        return $this;
    }
}