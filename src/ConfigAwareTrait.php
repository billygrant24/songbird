<?php
namespace Songbird;

trait ConfigAwareTrait
{
    /**
     * @var \Songbird\Config
     */
    protected $config;

    /**
     * @return \Songbird\Config
     */
    public function getConfig()
    {
        $configFiles = [getcwd() . '/../etc'];

        return new Config($configFiles);
    }
}