<?php
namespace Songbird\Package\Twig;

use JamesMoss\Flywheel\DocumentInterface;
use Songbird\Template\TemplateInterface;
use Twig_Environment;

class Template implements TemplateInterface
{
    /**
     * @var \Twig_Environment
     */
    protected $twig;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * Render a template.
     *
     * @param string $content
     * @param array  $data
     *
     * @return mixed
     */
    public function render($content, array $data = [])
    {
        $this->setData($data);

        return $this->getTwig()->render($content, $this->getData());
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData(array $data)
    {
        $this->data = array_merge($this->getData(), $data);
    }

    /**
     * @return \Twig_Environment $twig
     */
    public function getTwig()
    {
        return $this->twig;
    }

    /**
     * @param \Twig_Environment $twig
     */
    public function setTwig(Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @param \JamesMoss\Flywheel\DocumentInterface $document
     *
     * @return array
     */
    protected function parseMeta(DocumentInterface $document)
    {
        $meta = [];
        foreach ($document as $key => $value) {
            if (strpos($key, '_', 0)) {
                continue;
            }

            $meta[$key] = $value;
        }

        return $meta;
    }
}