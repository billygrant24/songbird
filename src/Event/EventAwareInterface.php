<?php
namespace Songbird\Event;

interface EventAwareInterface
{
    /**
     * @return \Songbird\Event\Event
     */
    public function getEvent();

    /**
     * @param \Songbird\Event\Event $event
     */
    public function setEvent(Event $event);
}