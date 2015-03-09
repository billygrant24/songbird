<?php
namespace Songbird;

use League\Container\Container;
use Songbird\Event\EventAwareTrait;
use Symfony\Component\Debug\Debug;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class App extends Container
{
    use EventAwareTrait;

    /**
     * Handles a Request to convert it to a Response.
     *
     * @param \Symfony\Component\HttpFoundation\Request  $request A Request instance
     * @param \Symfony\Component\HttpFoundation\Response $response
     *
     * @return \Symfony\Component\HttpFoundation\Response A Response instance
     */
    public function run(Request $request, Response $response)
    {
        $this->add('Symfony\Component\HttpFoundation\Response', $response);
        $this->add('Symfony\Component\HttpFoundation\Request', $request);

        $dispatcher = $this->get('Router')->getDispatcher();
        $path = rtrim($request->getPathInfo(), '/');

        $this->resolve('Songbird\Emitter\BeforeDispatchEmitter')->execute();
        $response = $dispatcher->dispatch($request->getMethod(), $path ? $path : '/');
        $this->resolve('Songbird\Emitter\AfterDispatchEmitter')->execute();

        return $response;
    }

    /**
     * This is an alias for get(). I prefer to use this to resolve fully qualified class names, reserving get()
     * for alias and configuration keys. Mixed style I find to be an eye sore.
     *
     * @param string $className
     * @param array  $args
     *
     * @return mixed|object
     */
    public function resolve($className, array $args = [])
    {
        return $this->get($className, $args);
    }

    /**
     * Enable debugging if app.debug directive is set to true.
     */
    public function startDebugging()
    {
        return Debug::enable();
    }

    public function registerMiddleware()
    {
        $middleware = $this->get('Config')['middleware'];

        if (is_array($middleware)) {
            foreach ($middleware as $service) {
                $this->get($service)->register();
            }
        }
    }

    public function addCoreServiceProviders()
    {
        $this->addServiceProvider('Songbird\Event\EventServiceProvider');
        $this->addServiceProvider('Songbird\Filesystem\FilesystemServiceProvider');
        $this->addServiceProvider('Songbird\Logger\LoggerServiceProvider');
        $this->addServiceProvider('Songbird\Document\Repository\RepositoryServiceProvider');
        $this->addServiceProvider('Songbird\Routing\RouterServiceProvider');
    }
}
