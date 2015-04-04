<?php
namespace Songbird\EventListener;

use League\Event\AbstractListener;
use League\Event\EventInterface;

class RouterListener extends AbstractListener
{
    /**
     * @param \League\Event\EventInterface $event
     *
     * @return void
     */
    public function handle(EventInterface $event)
    {
        foreach (['GET', 'POST', 'PUT', 'DELETE'] as $method) {
            $event->router->addRoute(
                $method,
                '/{fileId:[a-zA-Z0-9_\-\/]*}',
                'Songbird\Handler::__invoke'
            );
        }
    }
}
