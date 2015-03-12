<?php
namespace Songbird\Listener;

use League\Event\EventInterface;
use Songbird\Log\LoggerAwareInterface;
use Songbird\Log\LoggerAwareTrait;

class PageNotFound extends HttpEventListenerAbstract implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @param \League\Event\EventInterface $event
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(EventInterface $event)
    {
        $this->response->setStatusCode(404);

        $this->getLogger()->addError(sprintf('Page "%s" could not be found.', $this->request->getPathInfo()));

        $event->stopPropagation();
    }
}
