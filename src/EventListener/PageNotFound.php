<?php
namespace Songbird\EventListener;

use League\Event\EventInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

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

        $this->logger->addError(sprintf('Page "%s" could not be found.', $this->request->getPathInfo()));

        $event->stopPropagation();
    }
}
