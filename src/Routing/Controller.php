<?php
namespace Songbird\Routing;

use League\Container\ContainerAwareInterface;
use League\Container\ContainerAwareTrait;
use League\Container\Exception\ReflectionException;
use Songbird\Document\DocumentInterface;
use Songbird\Event\EventAwareTrait;
use Songbird\Logger\LoggerAwareInterface;
use Songbird\Logger\LoggerAwareTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Controller implements ContainerAwareInterface, LoggerAwareInterface
{
    use ContainerAwareTrait, LoggerAwareTrait, EventAwareTrait;

    /**
     * @param \Symfony\Component\HttpFoundation\Request  $request
     * @param \Symfony\Component\HttpFoundation\Response $response
     * @param array                                      $args
     *
     * @return mixed
     *
     */
    public function handle(Request $request, Response $response, array $args)
    {
        $slug = $args['slug'] ? $args['slug'] : 'home';

        $repo = $this->getContainer()->get('Repo.Documents');
        if (!$document = $repo->findById($slug)) {
            $document = $repo->findById('404');
        }

        // Fire any events for the current document.
        $this->handleListeners($request, $response, $args, $document);

        // We can assume that if the method is GET we want to set content body.
        if ($request->isMethod('get')) {
            // Run middleware for content transforms (the Markdown parser, for example).
            $this->getContainer()->resolve('Songbird\Emitter\BeforeDocumentTransformEmitter')->execute($document);
            $document = $this->getContainer()->get('Document.Transformer')->apply($document);
            $this->getContainer()->resolve('Songbird\Emitter\AfterDocumentTransformEmitter')->execute($document);

            try {
                $template = $document->_template;
                $content = $this->getContainer()->get('Template')->render($template, $document);
            } catch (ReflectionException $e) {
                $content = $document->body;
            }

            $response->setContent($content);
        }

        return $response;
    }

    /**
     * Register any listeners discovered for the current page and add them to the Event Emitter.
     *
     * @param \Symfony\Component\HttpFoundation\Request  $request
     * @param \Symfony\Component\HttpFoundation\Response $response
     * @param array                                      $args
     * @param \Songbird\Document\DocumentInterface       $document
     */
    protected function handleListeners(Request $request, Response $response, $args, DocumentInterface $document)
    {
        $verb = strtolower($request->getMethod());

        if (isset($document->_listen[$verb])) {
            $listeners = $document->_listen[$verb];
        } else {
            if (isset($document->_listen['all'])) {
                $listeners = $document->_listen['all'];
            } else {
                $listeners = [];
            }
        }

        if (!is_array($listeners)) {
            $listeners = [$listeners];
        }

        foreach ($listeners as $listener) {
            $this->addListener($listener,
                $this->getContainer()->resolve($listener, [$request, $response, $args]));

            $this->emit($listener);
        }
    }
}