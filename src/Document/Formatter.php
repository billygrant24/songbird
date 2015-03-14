<?php

namespace Songbird\Document;

use Symfony\Component\Yaml\Parser;

class Formatter
{
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

        $parser = new Parser;

        $yaml = $parser->parse($parts[1]);
        $yaml[$this->contentFieldName] = $parts[2];

        return $yaml;
    }
}
