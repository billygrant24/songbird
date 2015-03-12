<?php
namespace Songbird\Log;

use League\Container\ServiceProvider;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Songbird\ConfigAwareInterface;
use Songbird\ConfigAwareTrait;

class LoggerServiceProvider extends ServiceProvider
{
    protected $provides = [
        'Logger'
    ];

    /**
     * Use the register method to register items with the container via the
     * protected $this->container property or the `getContainer` method
     * from the ContainerAwareTrait.
     *
     * @return void
     */
    public function register()
    {
        $app = $this->getContainer();
        $config = $this->getContainer()->get('Config');

        $app->singleton('Logger', 'Monolog\Logger')->withArgument('songbird');

        $fileName = vsprintf('%s/songbird-%s.log', [$config->get('app.paths.log'), date('Y-d-m')]);
        $app->get('Logger')->pushHandler(new StreamHandler($fileName, Logger::INFO));
    }
}