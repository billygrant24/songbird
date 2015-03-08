<?php
namespace Songbird\Package\Plates\Extension;

use League\Container\ContainerAwareInterface;
use League\Container\ContainerAwareTrait;
use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;

class FragmentExtension implements ExtensionInterface, ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function register(Engine $engine)
    {
        $engine->registerFunction('fragment', [$this, 'fragment']);
    }

    /**
     * Renders a partial document.
     *
     * @param string $id
     * @param array  $params
     *
     * @return \JamesMoss\Flywheel\Result
     */
    public function fragment($id, array $params = [])
    {
        $fragment = $this->getContainer()->get('App.Repo.Fragments')->findById($id);
        $fragment = $this->getContainer()->get('App.Document.Transformer')->apply($fragment);

        return $fragment->body;
    }
}