<?php
namespace Songbird\Emitter;

use League\Container\ContainerAwareInterface;
use League\Container\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AfterDocumentTransformEmitter implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * @var \Symfony\Component\HttpFoundation\Response
     */
    protected $response;

    /**
     * @return string
     */
    public function getName()
    {
        return 'AfterContentTransform';
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request  $request
     * @param \Symfony\Component\HttpFoundation\Response $response
     */
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    /*
     * @param $document
     */
    public function execute($document)
    {
        return $this->getContainer()->emit($this->getName(), [
            'body' => &$document->body
        ]);
    }
}