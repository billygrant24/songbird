<?php
namespace Songbird\Event;


use League\Event\ListenerAcceptorInterface;

trait EventAwareTrait
{
    /**
     * @var \Songbird\Event\Event
     */
    protected $event;

    /**
     * @return \Songbird\Event\Event
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @param \Songbird\Event\Event $event
     */
    public function setEvent(Event $event)
    {
        $this->event = $event;
    }

    public function addListener($event, $listener, $priority = ListenerAcceptorInterface::P_NORMAL)
    {
        return $this->getEvent()->addListener($event, $listener, $priority);
    }

    public function emit($event, $args = [])
    {
        return $this->getEvent()->emit($event, $args);
    }
}