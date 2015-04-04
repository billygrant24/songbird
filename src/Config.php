<?php
namespace Songbird;

use josegonzalez\Dotenv\Loader;
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
        $env = new Loader(getcwd() . '/../.env');

        return $env->parse()->prefix('APP_')->define();
    }
}