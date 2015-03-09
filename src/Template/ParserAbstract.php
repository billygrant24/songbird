<?php
namespace Songbird\Template;

use JamesMoss\Flywheel\DocumentInterface;
use League\Container\ContainerAwareInterface;
use League\Container\ContainerAwareTrait;
use League\Event\AbstractListener;
use League\Event\ListenerInterface;
use Songbird\ConfigAwareInterface;
use Songbird\ConfigAwareTrait;

abstract class ParserAbstract extends AbstractListener implements ListenerInterface, ContainerAwareInterface
{
    use ContainerAwareTrait;

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
