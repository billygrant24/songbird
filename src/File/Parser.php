<?php
namespace Songbird\File;

use League\Container\ContainerAwareInterface;
use League\Container\ContainerAwareTrait;
use Symfony\Component\Yaml\Parser as Yaml;

class Parser implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @var string
     */
    protected $contentFieldName;

    /**
     * @param string $contentFieldName
     */
    public function __construct($contentFieldName = 'body')
    {
        $this->contentFieldName = $contentFieldName;
    }

    /**
     * @param mixed $data
     *
     * @return mixed
     */
    public function decode($data)
    {
        $parts = preg_split('/[\n]*[-]{3}[\n]/', $data, 3);

        $parser = new Yaml;

        $yaml = $parser->parse($parts[1]);
        $yaml[$this->contentFieldName] = $parts[2];

        return $yaml;
    }
}
