<?php
namespace Songbird\Listener;

use League\Event\AbstractListener;
use League\Event\EventInterface;
use League\Event\ListenerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class HttpEventListenerAbstract extends AbstractListener implements ListenerInterface
{
    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * @var \Symfony\Component\HttpFoundation\Response
     */
    protected $response;

    /**
     * @var array
     */
    protected $args;

    /**
     * @param \Symfony\Component\HttpFoundation\Request  $request
     * @param \Symfony\Component\HttpFoundation\Response $response
     * @param array                                      $args
     */
    public function __construct(Request $request, Response $response, array $args = [])
    {
        $this->request = $request;
        $this->response = $response;
        $this->args = $args;
    }

    /**
     * @param \League\Event\EventInterface $event
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(EventInterface $event)
    {
        // ...
    }

    /**
     * @return array
     */
    protected function getArgs()
    {
        return $this->args;
    }

    /**
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    protected function getArg($key, $default = null)
    {
        return isset($this->args[$key]) ? $this->args[$key] : $default;
    }
}
