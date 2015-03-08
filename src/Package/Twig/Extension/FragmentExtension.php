<?php
namespace Songbird\Package\Twig\Extension;

use League\Container\ContainerAwareInterface;
use League\Container\ContainerAwareTrait;
use League\Event\EmitterAwareInterface;
use League\Event\EmitterAwareTrait;
use Songbird\ConfigAwareInterface;
use Songbird\ConfigAwareTrait;
use Twig_Extension;
use Twig_SimpleFunction;

class FragmentExtension extends Twig_Extension implements ContainerAwareInterface, ConfigAwareInterface, EmitterAwareInterface
{
    use ContainerAwareTrait, ConfigAwareTrait, EmitterAwareTrait;

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array An array of functions
     */
    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('fragment', [$this, 'getFragment']),
        ];
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'fragment';
    }

    /**
     * Renders a partial document.
     *
     * @param string $id
     * @param array  $params
     *
     * @return \JamesMoss\Flywheel\Result
     */
    public function getFragment($id, array $params = [])
    {
        $fragment = $this->getContainer()->get('App.Repo.Fragments')->findById($id);
        $fragment = $this->getContainer()->get('App.Document.Transformer')->apply($fragment);

        return $this->getContainer()->get('Template')->render($fragment->body, $params);
    }
}