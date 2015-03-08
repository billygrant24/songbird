<?php
namespace Songbird\Document;

use JamesMoss\Flywheel\Result as FlywheelResult;

class Result extends FlywheelResult
{
    /**
     * @var int
     */
    protected $limit;

    /**
     * @param array $documents
     * @param int   $total
     * @param int   $limit
     */
    public function __construct($documents, $total, $limit)
    {
        parent::__construct($documents, $total);

        $this->limit = $limit;
    }

    public function limit()
    {
        return $this->limit;
    }
}
