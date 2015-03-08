<?php

namespace Songbird\Document\Formatter;

use Symfony\Component\Yaml\Dumper;
use Symfony\Component\Yaml\Parser;

class Universal
{
    protected $fileExtension = 'md';

    protected $contentFieldName;

    public function __construct($contentFieldName = 'body')
    {
        $this->contentFieldName = $contentFieldName;
    }

    public function getFileExtension()
    {
        return $this->fileExtension;
    }

    public function setFileExtension($fileExtension)
    {
        $this->fileExtension = $fileExtension;
    }

    public function encode(array $data)
    {
        $body = $data[$this->contentFieldName];
        unset($data[$this->contentFieldName]);

        $parser = new Dumper;

        $str = "---\n";
        $str .= $parser->dump($data, true);
        $str .= "---\n\n";
        $str .= $body;

        return $str;
    }

    public function decode($data)
    {
        $parts = preg_split('/[\n]*[-]{3}[\n]/', $data, 3);

        $parser = new Parser;

        $yaml = $parser->parse($parts[1]);
        $yaml[$this->contentFieldName] = $parts[2];

        return $yaml;
    }
}
