<?php
namespace Songbird;

class AppFactory
{
    /**
     * @param \Songbird\Config $config
     *
     * @return \Songbird\App
     */
    public static function createApplication(Config $config = null)
    {
        $app = new App();

        $app->add('App', $app);
        $app->add('Config', $config);
        $app->inflector('Songbird\ConfigAwareInterface')->invokeMethod('setConfig', [$app->get('Config')]);
        $app->add('App.Document.Transformer', $app->resolve('Songbird\Document\Transformer'));

        // Providers
        $app->addCoreServiceProviders();

        // Inflectors
        $app->inflector('League\Container\ContainerAwareInterface')->invokeMethod('setContainer', [$app->get('App')]);
        $app->inflector('Songbird\Logger\LoggerAwareInterface')->invokeMethod('setLogger', [$app->get('Logger')]);

        $app->registerMiddleware();

        return $app;
    }
}