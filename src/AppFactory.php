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

        $app->singleton('App', $app);
        $app->singleton('App.Event', 'League\Event\Emitter');

        $app->add('Config', $config);
        $app->add('App.Document.Transformer', $app->resolve('Songbird\Document\Transformer'));

        // Providers
        $app->addCoreServiceProviders();

        // Inflectors
        $app->inflector('League\Container\ContainerAwareInterface')->invokeMethod('setContainer', [$app->get('App')]);
        $app->inflector('League\Event\EmitterAwareInterface')->invokeMethod('setEmitter', [$app->get('App.Event')]);
        $app->inflector('Songbird\Logger\LoggerAwareInterface')->invokeMethod('setLogger', [$app->get('App.Logger')]);

        $app->registerMiddleware();

        return $app;
    }
}