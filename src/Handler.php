<?php
namespace Songbird;

use League\Container\ContainerAwareInterface;
use League\Container\ContainerAwareTrait;
use League\Container\Exception\ReflectionException;
use League\Event\EmitterAwareInterface;
use League\Event\EmitterTrait;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Songbird\Event\ContentEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Handler implements ContainerAwareInterface, LoggerAwareInterface, EmitterAwareInterface
{
    use ContainerAwareTrait, ContainerResolverTrait, LoggerAwareTrait, EmitterTrait;

    /**
     * @param \Symfony\Component\HttpFoundation\Request  $request
     * @param \Symfony\Component\HttpFoundation\Response $response
     * @param array                                      $args
     *
     * @return mixed
     *
     */
    public function __invoke(Request $request, Response $response, array $args)
    {
        $content = $this->getFileContent($args['fileId']);

        // Fire any events for the current file.
        $this->handleListeners($request, $response, $content);

        // We can assume that if the method is GET we want to set content body.
        if ($request->isMethod('get')) {
            try {
                $finalContent = $this->resolve('Template')->render($content['template'], $content);
            } catch (ReflectionException $e) {
                $finalContent = $content['body'];
            }

            $response->setContent($finalContent);
        }

        return $response;
    }

    /**
     * @param string $fileId
     *
     * @return mixed
     */
    protected function getFileContent($fileId)
    {
        $content = $this->resolve('Repository')->find($fileId ? $fileId : 'home');

        return $this->emit(new ContentEvent($content))->getModifiedContent();
    }

    /**
     * Register any listeners discovered for the current page and add them to the Emitter Emitter.
     *
     * @param \Symfony\Component\HttpFoundation\Request  $request
     * @param \Symfony\Component\HttpFoundation\Response $response
     * @param mixed                                      $document
     */
    protected function handleListeners(Request $request, Response $response, $document)
    {
        if (!isset($document['listen'])) {
            return;
        }

        $listeners = is_array($document['listen']) ? $document['listen'] : [$document['listen']];
        foreach ($listeners as $listener) {
            $this->addListener($listener,
                $this->resolve($listener, [$request, $response]));

            $this->emit($listener);
        }
    }
}
