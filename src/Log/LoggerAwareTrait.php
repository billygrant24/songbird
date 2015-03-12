<?php
namespace Songbird\Log;

use Monolog\Logger;

trait LoggerAwareTrait
{
    /**
     * @var \Monolog\Logger
     */
    protected $logger;

    /**
     * @return \Monolog\Logger
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @param \Monolog\Logger $logger
     */
    public function setLogger(Logger $logger)
    {
        $this->logger = $logger;
    }
}