<?php
namespace Songbird;

class AppFactory
{
    /**
     * @param string $config
     *
     * @return \Songbird\App
     */
    public static function createApplication($config = '')
    {
        $app = new App();

        $app->add('App', $app);
        $app->add('Config', new Config($config));
        $app->add('Document.Transformer', $app->resolve('Songbird\Document\Transformer'));

        // Providers
        $app->addCoreServiceProviders();

        // Inflectors
        $app->inflector('League\Container\ContainerAwareInterface')->invokeMethod('setContainer', [$app->get('App')]);
        $app->inflector('Songbird\Logger\LoggerAwareInterface')->invokeMethod('setLogger', [$app->get('Logger')]);

        $app->registerMiddleware();

        return $app;
    }
}
