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
     * @param \Symfony\Component\HttpFoundation\Request $request A Request instance
     *
     * @return \Symfony\Component\HttpFoundation\Response A Response instance
     */
    public function run(Request $request)
    {
        $this->add('Symfony\Component\HttpFoundation\Response', new Response());
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

    public function registerPackages()
    {
        $packages = $this->get('Config')['packages'];

        if (is_array($packages)) {
            foreach ($packages as $package) {
                $this->resolve($package)->register();
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
