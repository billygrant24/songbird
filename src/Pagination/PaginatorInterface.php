<?php
namespace Songbird\Pagination;

interface PaginatorInterface
{
    /**
     * Get the current page.
     *
     * @return int
     */
    public function getCurrentPage();

    /**
     * Get the last page.
     *
     * @return int
     */
    public function getLastPage();

    /**
     * Get the total.
     *
     * @return int
     */
    public function getTotal();

    /**
     * Get the count.
     *
     * @return int
     */
    public function getCount();

    /**
     * Get the number per page.
     *
     * @return int
     */
    public function getPerPage();

    /**
     * Get the url for the given page.
     *
     * @param int $page
     *
     * @return string
     */
    public function getUrl($page);
}