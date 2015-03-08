<?php
namespace Songbird\Package\Twig\Parser;

use League\Event\EventInterface;

class TemplateParser extends AbstractParser
{
    /**
     * @param \League\Event\EventInterface $event
     * @param array                        $params
     */
    public function handle(EventInterface $event, $params = [])
    {
        $params['template'] = sprintf('@theme/%s', $params['template']);

        $params['data'] = [
            'meta' => $this->parseMeta($params['document']),
            'content' => $params['document']->body,
        ];
    }
}