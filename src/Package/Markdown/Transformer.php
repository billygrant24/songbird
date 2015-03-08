<?php
namespace Songbird\Package\Markdown;

use JamesMoss\Flywheel\DocumentInterface;
use League\CommonMark\CommonMarkConverter;

class Transformer
{
    /**
     * @var \League\CommonMark\CommonMarkConverter
     */
    protected $markdown;

    /**
     * @param \League\CommonMark\CommonMarkConverter $markdown
     */
    public function __construct(CommonMarkConverter $markdown)
    {
        $this->markdown = $markdown;
    }

    /**
     * @param \JamesMoss\Flywheel\DocumentInterface $document
     *
     * @return \JamesMoss\Flywheel\DocumentInterface
     */
    public function apply(DocumentInterface $document)
    {
        $document->body = $this->markdown->convertToHtml($document->body);

        return $document;
    }
}