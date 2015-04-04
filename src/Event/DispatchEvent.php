<?php
namespace Songbird\Event;

use League\Event\AbstractEvent;

class DispatchEvent extends AbstractEvent
{
    public $dispatcher;
    public $response;
    public $request;

    public function __construct($dispatcher, $request, $response)
    {
        $this->response = $response;
        $this->request = $request;

        $path = rtrim($this->request->getPathInfo(), '/');
        $this->dispatcher = $dispatcher->dispatch($this->request->getMethod(), $path ? $path : '/');;
    }

    public function getName()
    {
        return 'DispatchEvent';
    }

    public function getModifiedResponse()
    {
        return $this->response;
    }
}