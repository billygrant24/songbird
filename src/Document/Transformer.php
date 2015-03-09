<?php
namespace Songbird\Document;

class Transformer
{
    /**
     * @param \Songbird\Document\DocumentInterface $document
     *
     * @return \Songbird\Document\DocumentInterface
     */
    public function apply(DocumentInterface $document)
    {
        return $document;
    }
}