<?php
namespace Songbird;

use League\Container\Container;
use League\Event\EmitterTrait;
use League\Url\Url;
use Symfony\Component\Debug\Debug;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class App extends Container
{
    use EmitterTrait;

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

        $dispatcher = $this->get('App.Router')->getDispatcher();
        $path = rtrim($request->getPathInfo(), '/');

        $this->addListener('BeforeDispatch', function ($event, $args) {
            $url = Url::createFromUrl($args['request']->getUri());
            if (count($url->getPath()->keys('blog')) > 0) {
                $args['response']->setContent('you are reading my blog. bugger off.');
//                $args['response']->send();
            }
        });

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
        foreach ($this->get('Config')->get('middleware') as $middleware) {
            $this->get($middleware)->register();
        }
    }

    public function addCoreServiceProviders()
    {
        $this->addServiceProvider('Songbird\Filesystem\FilesystemServiceProvider');
        $this->addServiceProvider('Songbird\Logger\LoggerServiceProvider');
        $this->addServiceProvider('Songbird\Document\Repository\RepositoryServiceProvider');
        $this->addServiceProvider('Songbird\Routing\RouterServiceProvider');
    }
}