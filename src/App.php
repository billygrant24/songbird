<?php
namespace Songbird;

use League\Container\Container;
use Songbird\Event\DispatchEvent;
use Songbird\Event\RouterEvent;
use Songbird\EventListener\RouterListener;
use Symfony\Component\Debug\Debug;

class App extends Container
{
    /**
     * Handles a Request to convert it to a Response.
     *
     * @return \Symfony\Component\HttpFoundation\Response A Response instance
     */
    public function run()
    {
        $this->registerPackages();

        $emitter = $this->get('Emitter');
        $emitter->addListener('RouterEvent', new RouterListener());

        $router = $emitter->emit(new RouterEvent(
            $this->get('Router'),
            $this->get('Symfony\Component\HttpFoundation\Request')
        ))->getModifiedRouter();

        return $emitter->emit(new DispatchEvent(
            $router->getDispatcher(),
            $this->get('Symfony\Component\HttpFoundation\Request'),
            $this->get('Symfony\Component\HttpFoundation\Response')
        ))->getModifiedResponse();
    }

    /**
     * Enable debugging if app.debug directive is set to true.
     */
    public function startDebugging()
    {
        if ($this->config('app.debug')) {
            Debug::enable();
        }
    }

    public function registerPackages()
    {
        $packages = $this->config('packages');

        if (is_array($packages)) {
            foreach ($packages as $package) {
                $this->get($package)->register();
            }
        }
    }

    /**
     * @param string     $key
     * @param mixed|null $defaultValue
     *
     * @return mixed
     */
    public function config($key, $defaultValue = null)
    {
        return $this->get('Config')->get($key, $defaultValue);
    }
}
