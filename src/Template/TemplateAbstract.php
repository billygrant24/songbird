<?php
namespace Songbird\Template;

use League\Container\ContainerAwareInterface;
use League\Container\ContainerAwareTrait;
use Songbird\Document\DocumentInterface;

abstract class TemplateAbstract implements ContainerAwareInterface, TemplateInterface
{
    use ContainerAwareTrait;

    /**
     * @var object
     */
    protected $engine;

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
    abstract public function render($content, $data = null);

    /**
     * @param \Songbird\Document\DocumentInterface $document
     *
     * @return array
     */
    protected function parseMeta($document)
    {
        $meta = [];
        foreach ($document as $key => $value) {
            if (strpos($key, '_', 0) || $key === 'settings') {
                continue;
            }

            $meta[$key] = $value;
        }

        return $meta;
    }

    protected function replacePlaceholders($body, $data = [], $prefix = '')
    {
        if (!$data) {
            $data = array_merge($this->getEngine()->getData(), $this->getData()['meta']);
        }

        foreach ($data as $key => $meta) {
            if (is_array($meta)) {
                $body = $this->replacePlaceholders($body, $meta, $key . '.');
                continue;
            }

            $body = str_replace('{{ ' . $prefix . $key . ' }}', $meta, $body);
        }

        return $body;
    }

    /**
     * @return object
     */
    public function getEngine()
    {
        return $this->engine;
    }

    /**
     * @param object $engine
     */
    public function setEngine($engine)
    {
        $this->engine = $engine;
    }

    /**
     * @return array
     */
    public function getData()
    {
        $app = $this->getContainer();
        $config = $this->getContainer()->get('Config');

        $this->getEngine()->addData([
            'siteTitle' => $config->get('vars.siteTitle'),
            'baseUrl' => $config->get('vars.baseUrl'),
            'themeDir' => $config->get('vars.baseUrl') . '/themes/' . $config->get('app.theme'),
            'dateFormat' => $config->get('vars.dateFormat'),
            'excerptLength' => $config->get('vars.excerptLength'),
        ]);

        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData(array $data)
    {
        $this->data = array_merge($this->getData(), $data);
    }
}
