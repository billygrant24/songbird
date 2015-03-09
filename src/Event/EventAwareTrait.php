<?php
namespace Songbird\Event;

use League\Event\EmitterTrait;

trait EventAwareTrait
{
    use EmitterTrait;

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
}