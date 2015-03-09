<?php
namespace Songbird;

use Noodlehaus\Config as NoodleConfig;

class Config extends NoodleConfig
{
    public function __construct($path = null, $env = null)
    {
        $this->loadEnvironment();

        parent::__construct($path);
    }

    public function loadEnvironment()
    {
        $env = new Env(getcwd() . '/../.env');

        return $env->parse()->prefix('APP_')->define();
    }
}