<?php
namespace Songbird\Emitter;

use League\Container\ContainerAwareInterface;
use League\Container\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BeforeDispatchEmitter implements ContainerAwareInterface
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
        return 'BeforeDispatch';
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
     *
     */
    public function execute()
    {
        return $this->getContainer()->emit($this->getName(), [
            'request' => $this->request,
            'response' => $this->response
        ]);
    }
}