<?php
namespace Songbird\Event;

use League\Container\ServiceProvider;
use Songbird\ConfigAwareInterface;
use Songbird\ConfigAwareTrait;

class EventServiceProvider extends ServiceProvider
{
    protected $provides = [
        'Event',
        'Songbird\Event\EventAwareInterface',
    ];

    public function register()
    {
        $app = $this->getContainer();

        $app->singleton('Event', 'Songbird\Event\Event');
        $app->inflector('Songbird\Event\EventAwareInterface')->invokeMethod('setEvent', [$app->get('Event')]);
    }
}