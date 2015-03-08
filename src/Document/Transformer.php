<?php
namespace Songbird\Document;

use JamesMoss\Flywheel\DocumentInterface;

class Transformer
{
    /**
     * @param \JamesMoss\Flywheel\DocumentInterface $document
     *
     * @return \JamesMoss\Flywheel\DocumentInterface
     */
    public function apply(DocumentInterface $document)
    {
        return $document;
    }
}