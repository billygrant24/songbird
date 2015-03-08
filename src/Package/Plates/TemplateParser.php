<?php
namespace Songbird\Package\Plates;

use League\Event\EventInterface;
use Songbird\Package\Twig\Parser\AbstractParser;

class TemplateParser extends AbstractParser
{
    /**
     * @param \League\Event\EventInterface $event
     * @param array                        $params
     */
    public function handle(EventInterface $event, $params = [])
    {
//        $params['template'] = sprintf('theme::%s', $params['template']);
//
//        $params['data'] = [
//            'meta' => $this->parseMeta($params['document']),
//            'content' => $params['document']->body,
//        ];
    }
}