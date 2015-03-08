<?php
namespace Songbird\Document\Pagination;

class Paginator implements PaginatorInterface
{
    /**
     * @param $results
     */
    public function __construct($results)
    {
        $this->result = $results;
    }

    /**
     * Check to see if we can navigate back a page.
     *
     * @return bool
     */
    public function hasPrevious()
    {
        return (bool) $this->getPreviousPage();
    }

    /**
     * Get the previous page, or return false if we are on page one.
     *
     * @return bool|int
     */
    public function getPreviousPage()
    {
        if ($this->getCurrentPage() > 1) {
            return $this->getCurrentPage() - 1;
        }

        return false;
    }

    /**
     * Get the current page.
     *
     * @return int
     */
    public function getCurrentPage()
    {
        return isset($_GET['page']) && $_GET['page'] > 0 ? $_GET['page'] : 1;
    }

    /**
     * Check if we can navigate forward by one page.
     *
     * @return bool
     */
    public function hasNext()
    {
        return (bool) $this->getNextPage();
    }

    /**
     * Get the next page, or return false if we are on the last page.
     *
     * @return bool|int
     */
    public function getNextPage()
    {
        if ($this->getCurrentPage() < $this->getLastPage()) {
            return $this->getCurrentPage() + 1;
        }

        return false;
    }

    /**
     * Get the last page.
     *
     * @return int
     */
    public function getLastPage()
    {
        return (int) ceil($this->getTotal() / $this->getPerPage());
    }

    /**
     * Get the total.
     *
     * @return int
     */
    public function getTotal()
    {
        return $this->result->total();
    }

    /**
     * Get the number per page.
     *
     * @return int
     */
    public function getPerPage()
    {
        return $this->result->limit();
    }

    /**
     * Get the count.
     *
     * @return int
     */
    public function getCount()
    {
        return $this->result->count();
    }

    /**
     * Get the url for the given page.
     *
     * @param int $page
     *
     * @return string
     */
    public function getUrl($page)
    {
        // TODO: Implement getUrl() method.
    }
}