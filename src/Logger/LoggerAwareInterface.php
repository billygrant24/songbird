<?php
namespace Songbird\Logger;

use Monolog\Logger;

interface LoggerAwareInterface
{
    /**
     * @return \Monolog\Logger
     */
    public function getLogger();

    /**
     * @param \Monolog\Logger $logger
     */
    public function setLogger(Logger $logger);
}