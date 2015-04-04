<?php
namespace Songbird\Event;

use League\Event\AbstractEvent;

class RouterEvent extends AbstractEvent
{
    public $router;
    public $request;

    public function __construct($router, $request)
    {
        $this->router = $router;
        $this->request = $request;
    }

    public function getName()
    {
        return 'RouterEvent';
    }

    public function getModifiedRouter()
    {
        return $this->router;
    }
}