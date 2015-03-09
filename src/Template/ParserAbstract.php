<?php
namespace Songbird\Template;

use League\Container\ContainerAwareInterface;
use League\Container\ContainerAwareTrait;
use League\Event\AbstractListener;
use League\Event\ListenerInterface;
use Songbird\ConfigAwareInterface;
use Songbird\ConfigAwareTrait;
use Songbird\Document\DocumentInterface;

abstract class ParserAbstract extends AbstractListener implements ListenerInterface, ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @param \Songbird\Document\DocumentInterface $document
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
