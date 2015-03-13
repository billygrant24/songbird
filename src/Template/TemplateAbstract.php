<?php
namespace Songbird\Template;

use Songbird\Document\DocumentInterface;

abstract class TemplateAbstract implements TemplateInterface
{
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
            if ($key === 'repository') {
                continue;
            }

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
