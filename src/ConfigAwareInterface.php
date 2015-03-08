<?php
namespace Songbird;

interface ConfigAwareInterface
{
    /**
     * @return \Songbird\Config
     */
    public function getConfig();
}