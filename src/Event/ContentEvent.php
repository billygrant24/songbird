<?php
namespace Songbird\Event;

use League\Event\AbstractEvent;

class ContentEvent extends AbstractEvent
{
    public $content;

    public function __construct($content)
    {
        $this->content = $content;
    }

    public function getName()
    {
        return 'ContentEvent';
    }

    public function getModifiedContent()
    {
        return $this->content;
    }
}