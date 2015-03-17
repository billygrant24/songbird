<?php
namespace Songbird;

use League\Container\Container;


use Symfony\Component\Debug\Debug;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class App extends Container
{
    /**
     * Handles a Request to convert it to a Response.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request A Request instance
     *
     * @return \Symfony\Component\HttpFoundation\Response A Response instance
     */
    public function run(Request $request)
    {
        $this->add('Symfony\Component\HttpFoundation\Response', new Response());
        $this->add('Symfony\Component\HttpFoundation\Request', $request);

        $this->get('Event')->emit('AddingRoutes', ['router' => $this->get('Router')]);
        $this->addRoutes();
        $this->get('Event')->emit('RoutesAdded', ['router' => $this->get('Router')]);

        $dispatcher = $this->get('Router')->getDispatcher();
        $path = rtrim($request->getPathInfo(), '/');

        $this->get('Event')->emit('BeforeDispatch', [$request]);
        $response = $dispatcher->dispatch($request->getMethod(), $path ? $path : '/');
        $this->get('Event')->emit('AfterDispatch', [$request, $response]);

        return $response;
    }

    /**
     * Enable debugging if app.debug directive is set to true.
     */
    public function startDebugging()
    {
        Debug::enable();
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

    /**
     * Add all routes required to handle document requests.
     */
    public function addRoutes()
    {
        foreach (['GET', 'POST', 'PUT', 'DELETE'] as $method) {
            $this->get('Router')->addRoute(
                $method,
                '/{documentId:[a-zA-Z0-9_\-\/]*}',
                sprintf('%s::handle', $this->config('app.handler', 'Songbird\Controller'))
            );
        }
    }
}
