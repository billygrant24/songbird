<?php
namespace Songbird\File;

use Illuminate\Support\Collection as IlluminateCollection;

class Collection extends IlluminateCollection
{
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

        return $this->forPage($page, $this->perPage);
    }
}