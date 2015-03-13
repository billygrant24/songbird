<?php
namespace Songbird\Document;

use Illuminate\Support\Collection as IlluminateCollection;

class Collection extends IlluminateCollection
{
    public $perPage;

    /**
     * "Paginate" the collection by slicing it into a smaller collection.
     *
     * @param  int $page
     * @param  int $perPage
     *
     * @return static
     */
    public function forPage($page, $perPage)
    {
        $this->perPage = $perPage;

        return parent::forPage($page, $perPage);
    }
}